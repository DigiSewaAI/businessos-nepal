<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AI\Conversation;
use App\Models\AI\Message;
use App\Models\AI\Insight;
use App\Services\AI\OllamaService;
use App\Services\AI\IntentParser;
use App\Services\AI\ContextBuilder;
use App\Services\AI\Context\ContextManager;
use App\Services\AI\Prompts\PromptManager;
use App\Services\AI\IntentScorer;
use App\Services\AI\Agent\AgentOrchestrator; // <-- Phase 4
use App\Services\AI\FAQService;
use App\Services\AI\DataService;
use App\Services\AI\ForecastingService;
use App\Services\AI\AnomalyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIController extends Controller
{
    protected $ollama;
    protected $intentParser;
    protected $contextBuilder;
    protected $faqService;
    protected $dataService;
    protected $forecastService;
    protected $anomalyService;
    protected $contextManager;
    protected $promptManager;
    protected $intentScorer;
    protected $agentOrchestrator; // <-- Phase 4

    public function __construct(
        OllamaService $ollama,
        IntentParser $intentParser,
        ContextBuilder $contextBuilder,
        FAQService $faqService,
        DataService $dataService,
        ForecastingService $forecastService,
        AnomalyService $anomalyService,
        ContextManager $contextManager,
        PromptManager $promptManager,
        IntentScorer $intentScorer,
        AgentOrchestrator $agentOrchestrator // <-- Injected
    ) {
        $this->ollama = $ollama;
        $this->intentParser = $intentParser;
        $this->contextBuilder = $contextBuilder;
        $this->faqService = $faqService;
        $this->dataService = $dataService;
        $this->forecastService = $forecastService;
        $this->anomalyService = $anomalyService;
        $this->contextManager = $contextManager;
        $this->promptManager = $promptManager;
        $this->intentScorer = $intentScorer;
        $this->agentOrchestrator = $agentOrchestrator; // <-- Assign
    }

    public function chat()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('ai.chat', compact('conversations'));
    }

    /**
     * Hybrid AI message handler:
     * Agent → FAQ → Data → Forecast → Anomaly → Ollama
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'conversation_id' => 'nullable|exists:ai_conversations,id',
        ]);

        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;
        $message = $request->message;

        // ========== STEP 0: AGENT (Phase 4) ==========
        // Try Agent first – handles actions (create invoice, add product, etc.)
        // and complex multi-tool queries.
        try {
            $agentResponse = $this->agentOrchestrator->execute($message);
            if ($agentResponse) {
                return $this->saveAndRespond($request, $message, $agentResponse, 'agent');
            }
        } catch (\Exception $e) {
            Log::warning('Agent error: ' . $e->getMessage());
            // Fallback to normal pipeline
        }

        // --- Step 1: Check FAQ (Knowledge Base) ---
        try {
            $faqAnswer = $this->faqService->findAnswer($message);
            if ($faqAnswer) {
                return $this->saveAndRespond($request, $message, $faqAnswer, 'faq');
            }
        } catch (\Exception $e) {
            Log::warning('FAQ error: ' . $e->getMessage());
        }

        // --- Step 2: Check Business Data (Stock, Sales, Profit) ---
        try {
            $dataAnswer = $this->dataService->getAnswer($message);
            if ($dataAnswer) {
                return $this->saveAndRespond($request, $message, $dataAnswer, 'data');
            }
        } catch (\Exception $e) {
            Log::warning('Data error: ' . $e->getMessage());
        }

        // --- Step 3: Forecasting ---
        if (stripos($message, 'forecast') !== false || stripos($message, 'predict') !== false) {
            try {
                $forecast = $this->forecastService->forecast($orgId);
                if ($forecast) {
                    return $this->saveAndRespond($request, $message, $forecast, 'forecast');
                }
            } catch (\Exception $e) {
                Log::warning('Forecast error: ' . $e->getMessage());
            }
        }

        // --- Step 4: Anomaly Detection ---
        if (stripos($message, 'anomaly') !== false || stripos($message, 'unusual') !== false) {
            try {
                $anomalies = $this->anomalyService->check($orgId);
                $response = "🔍 **Detected Anomalies:**\n" . ($anomalies ? implode("\n", $anomalies) : "✅ No anomalies found.");
                return $this->saveAndRespond($request, $message, $response, 'anomaly');
            } catch (\Exception $e) {
                Log::warning('Anomaly error: ' . $e->getMessage());
            }
        }

        // --- Step 5: Fallback to Ollama ---
        $conversation = $this->getOrCreateConversation($request, $userId, $orgId);
        $history = $this->getConversationHistory($conversation->id);

        try {
            // 🔥 Phase 3: Intent Scoring System
            $cacheKey = 'ai_intent_scores_' . md5($message);
            $scores = Cache::remember($cacheKey, 300, function() use ($message) {
                return $this->intentScorer->score($message);
            });
            $bestIntent = $this->intentScorer->getBestIntent($scores);

            $intent = [
                'category' => $bestIntent,
                'action' => 'query',
                'timeframe' => null,
                'scores' => $scores,
            ];

            $context = $this->contextManager->getContext($bestIntent);
        } catch (\Exception $e) {
            Log::warning('Intent scoring failed: ' . $e->getMessage());
            $intent = $this->intentParser->parse($message);
            $bestIntent = $intent['category'] ?? 'general';
            $context = ['organization' => auth()->user()->organization->name ?? 'Your Business', 'metrics' => []];
        }

        // --- Phase 2: Build Module-Specific Prompt ---
        try {
            $systemPrompt = $this->promptManager->getSystemPrompt($bestIntent, $context);

            $historyText = '';
            foreach ($history as $msg) {
                $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
                $historyText .= "{$role}: {$msg['content']}\n";
            }

            $fullPrompt = $systemPrompt . "\n\n" . $historyText . "User: {$message}\nAssistant:";

            if (method_exists($this->ollama, 'generate')) {
                $aiResponse = $this->ollama->generate($fullPrompt);
            } else {
                $aiResponse = $this->ollama->generateWithContext($history, $message);
            }

            if (empty($aiResponse) || strpos($aiResponse, 'Error') !== false) {
                throw new \Exception('Empty or error response from Ollama');
            }
        } catch (\Exception $e) {
            Log::error('Ollama error: ' . $e->getMessage());
            $aiResponse = 'Sorry, I encountered an error. Please try again.';
        }

        // Save assistant message
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $aiResponse,
            'metadata' => ['intent' => $intent, 'context' => $context, 'source' => 'ollama'],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'response' => $aiResponse,
        ]);
    }

    /**
     * Save user message and assistant response, then return JSON.
     */
    private function saveAndRespond($request, $message, $response, $source)
    {
        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;

        $conversation = $this->getOrCreateConversation($request, $userId, $orgId);

        // Save user message
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

        // Save assistant message with source metadata
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $response,
            'metadata' => ['source' => $source],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'response' => $response,
        ]);
    }

    /**
     * Get or create a conversation.
     */
    private function getOrCreateConversation($request, $userId, $orgId)
    {
        if ($request->conversation_id) {
            return Conversation::where('user_id', $userId)
                ->where('id', $request->conversation_id)
                ->firstOrFail();
        }

        return Conversation::create([
            'organization_id' => $orgId,
            'user_id' => $userId,
            'title' => Str::limit($request->message, 30),
            'session_id' => Str::uuid(),
        ]);
    }

    /**
     * Get recent conversation history (last 5 messages) for context.
     */
    private function getConversationHistory($conversationId)
    {
        return Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->toArray();
    }

    // ========== EXISTING METHODS (unchanged) ==========

    public function conversation($id)
    {
        $conversation = Conversation::with('messages')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($conversation->messages);
    }

    public function deleteConversation($id)
    {
        $conversation = Conversation::where('user_id', auth()->id())->findOrFail($id);
        $conversation->delete();
        return redirect()->back()->with('success', 'Conversation deleted.');
    }

    public function dashboard()
    {
        $orgId = auth()->user()->organization_id;

        $insights = Insight::where('organization_id', $orgId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $totalChats = Conversation::where('user_id', auth()->id())->count();
        $totalMessages = Message::whereHas('conversation', function($q) {
            $q->where('user_id', auth()->id());
        })->count();

        return view('ai.dashboard', compact('insights', 'conversations', 'totalChats', 'totalMessages'));
    }
}