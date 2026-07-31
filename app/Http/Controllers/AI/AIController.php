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
use App\Services\AI\Agent\AgentOrchestrator;
use App\Services\AI\FAQService;
use App\Services\AI\DataService;
use App\Services\AI\ForecastingService;
use App\Services\AI\AnomalyService;
use App\Services\AI\AnalyticsService;
use App\Services\AI\DemoDataService;
use App\Jobs\AIProcessJob;
use App\Exports\ConversationExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

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
    protected $agentOrchestrator;
    protected $analyticsService;
    protected $demoDataService;

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
        AgentOrchestrator $agentOrchestrator,
        AnalyticsService $analyticsService,
        DemoDataService $demoDataService
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
        $this->agentOrchestrator = $agentOrchestrator;
        $this->analyticsService = $analyticsService;
        $this->demoDataService = $demoDataService;
    }

    /**
     * Show the chat interface (Public accessible)
     */
    public function chat()
    {
        $isGuest = !auth()->check();
        $conversations = [];
        $messages = [];

        if (auth()->check()) {
            // Logged in: get user conversations
            $conversations = Conversation::where('user_id', auth()->id())
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();
        }

        // For guest, we don't load messages (just show empty with demo banner)
        return view('ai.chat', compact('conversations', 'messages', 'isGuest'));
    }

    /**
     * Send message - PUBLIC ACCESS allowed (with demo mode for guests)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'conversation_id' => 'nullable|exists:ai_conversations,id',
        ]);

        $startTime = microtime(true);
        $message = trim($request->message);
        $lower = strtolower($message);
        $isGuest = !auth()->check();

        // ─── GUEST / DEMO MODE ──────────────────────────────────────
        if ($isGuest) {
            // Use DemoDataService for public responses
            $response = $this->demoDataService->getDemoAnswer($message);

            // ✅ Convert markdown [text](url) to HTML <a> tags
            $response = $this->markdownToHtml($response);
            
            // Check if user asked for personal data
            $personalKeywords = ['my', 'mero', 'our', 'hamro', 'my business', 'mero business'];
            $needsLogin = false;
            foreach ($personalKeywords as $kw) {
                if (stripos($message, $kw) !== false) {
                    $needsLogin = true;
                    break;
                }
            }

            $this->logAnalytics($message, 'demo', 'demo', $startTime, true);

            return response()->json([
                'response' => $response,
                'is_demo' => true,
                'needs_login' => $needsLogin,
                'conversation_id' => null,
            ]);
        }

        // ─── LOGGED IN USER ──────────────────────────────────────────
        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;
        $source = 'unknown';
        $bestIntent = 'general';

        // ============================================================
        // 🟢 STEP 0: GREETINGS / SMALL TALK
        // ============================================================
        $greetings = ['hello', 'hi', 'hey', 'hola', 'namaste', 'नमस्ते', 'namaskar', 'नमस्कार'];
        if (in_array($lower, $greetings) || strlen($message) <= 3) {
            return $this->handleGreeting($request, $message, $startTime);
        }

        // ============================================================
        // 🟢 STEP 1: AGENT (Phase 4 - Actions)
        // ============================================================
        try {
            $agentResponse = $this->agentOrchestrator->execute($message);
            if ($agentResponse) {
                $this->logAnalytics($message, 'agent', 'action', $startTime, true);
                return $this->saveAndRespond($request, $message, $agentResponse, 'agent');
            }
        } catch (\Exception $e) {
            Log::warning('Agent error: ' . $e->getMessage());
        }

        // ============================================================
        // 🟢 STEP 2: FAQ (Static Knowledge)
        // ============================================================
        try {
            $faqAnswer = $this->faqService->findAnswer($message);
            if ($faqAnswer) {
                $this->logAnalytics($message, 'faq', 'faq', $startTime, true);
                return $this->saveAndRespond($request, $message, $faqAnswer, 'faq');
            }
        } catch (\Exception $e) {
            Log::warning('FAQ error: ' . $e->getMessage());
        }

        // ============================================================
        // 🟢 STEP 3: BUSINESS DATA (Stock, Sales, Profit, Attendance)
        // ============================================================
        try {
            $dataAnswer = $this->dataService->getAnswer($message);
            if ($dataAnswer) {
                $this->logAnalytics($message, 'data', $this->detectIntentFromMessage($lower), $startTime, true);
                return $this->saveAndRespond($request, $message, $dataAnswer, 'data');
            }
        } catch (\Exception $e) {
            Log::warning('Data error: ' . $e->getMessage());
        }

        // ============================================================
        // 🟢 STEP 4: FORECASTING
        // ============================================================
        if (stripos($lower, 'forecast') !== false || stripos($lower, 'predict') !== false) {
            try {
                $forecast = $this->forecastService->forecast($orgId);
                if ($forecast) {
                    $this->logAnalytics($message, 'forecast', 'forecast', $startTime, true);
                    return $this->saveAndRespond($request, $message, $forecast, 'forecast');
                }
            } catch (\Exception $e) {
                Log::warning('Forecast error: ' . $e->getMessage());
            }
        }

        // ============================================================
        // 🟢 STEP 5: ANOMALY DETECTION
        // ============================================================
        if (stripos($lower, 'anomaly') !== false || stripos($lower, 'unusual') !== false) {
            try {
                $anomalies = $this->anomalyService->check($orgId);
                $response = "🔍 **Detected Anomalies:**\n" . ($anomalies ? implode("\n", $anomalies) : "✅ No anomalies found.");
                $this->logAnalytics($message, 'anomaly', 'anomaly', $startTime, true);
                return $this->saveAndRespond($request, $message, $response, 'anomaly');
            } catch (\Exception $e) {
                Log::warning('Anomaly error: ' . $e->getMessage());
            }
        }

        // ============================================================
        // 🟢 STEP 6: OLLAMA (Fallback)
        // ============================================================
        $conversation = $this->getOrCreateConversation($request, $userId, $orgId);
        $history = $this->getConversationHistory($conversation->id);

        // Heavy query → Queue
        if (strlen($message) > 100 || stripos($lower, 'analyze') !== false) {
            dispatch(new AIProcessJob($conversation->id, $message, $userId));
            $this->logAnalytics($message, 'queue', 'heavy', $startTime, true);
            return response()->json([
                'conversation_id' => $conversation->id,
                'status' => 'processing',
                'message' => 'Your question is being processed. I\'ll respond shortly.',
            ]);
        }

        try {
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
                throw new \Exception('Empty or error response');
            }
        } catch (\Exception $e) {
            Log::error('Ollama error: ' . $e->getMessage());
            $aiResponse = 'Sorry, I encountered an error. Please try again.';
            $this->logAnalytics($message, 'error', $bestIntent, $startTime, false, $e->getMessage());
            return $this->saveAndRespond($request, $message, $aiResponse, 'error');
        }

        $this->logAnalytics($message, 'ollama', $bestIntent, $startTime, true);

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
     * Handle greetings separately
     */
    private function handleGreeting($request, $message, $startTime)
    {
        $greetingResponses = [
            'hello' => "Hello! 👋 How can I help you today?\n\nTry asking me:\n- 📊 'Today ko sales kati cha?'\n- 📦 'Low stock items haru dekhau'\n- 💰 'Profit kati cha?'\n- 🎓 'Attendance summary dekhau'",
            'hi' => "Hi there! 👋 What can I do for you?\n\nI can help with:\n- 📊 Sales reports\n- 📦 Stock management\n- 💰 Profit & expenses\n- 🎓 School attendance\n- 🍽️ Restaurant orders",
            'hey' => "Hey! 👋 Ready to help with your business.\n\nQuick commands:\n- 'stock' → see all products\n- 'sales today' → today's sales\n- 'profit' → profit breakdown",
            'namaste' => "Namaste! 🙏 How can I assist you with your business today?",
            'default' => "Hello! 👋 Ask me anything about your business — sales, stock, profit, attendance, or restaurant orders.",
        ];

        $lower = strtolower(trim($message));
        $response = $greetingResponses[$lower] ?? $greetingResponses['default'];

        $this->logAnalytics($message, 'greeting', 'greeting', $startTime, true);
        return $this->saveAndRespond($request, $message, $response, 'greeting');
    }

    /**
     * Detect intent from message for analytics
     */
    private function detectIntentFromMessage($lower)
    {
        if (stripos($lower, 'stock') !== false || stripos($lower, 'inventory') !== false) return 'inventory';
        if (stripos($lower, 'sales') !== false || stripos($lower, 'sale') !== false) return 'sales';
        if (stripos($lower, 'profit') !== false || stripos($lower, 'loss') !== false) return 'financial';
        if (stripos($lower, 'attendance') !== false || stripos($lower, 'student') !== false) return 'school';
        if (stripos($lower, 'order') !== false || stripos($lower, 'table') !== false) return 'restaurant';
        if (stripos($lower, 'forecast') !== false || stripos($lower, 'predict') !== false) return 'forecast';
        return 'general';
    }

    /**
     * Save user message and assistant response
     */
    private function saveAndRespond($request, $message, $response, $source)
    {
        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;
        $conversation = $this->getOrCreateConversation($request, $userId, $orgId);

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

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
     * Get or create conversation
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
     * Get conversation history (last 5 messages)
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

    /**
     * Log analytics
     */
    private function logAnalytics($message, $source, $intent, $startTime, $success, $error = null)
    {
        try {
            if ($this->analyticsService) {
                $this->analyticsService->log([
                    'source' => $source,
                    'intent' => $intent,
                    'query' => $message,
                    'response_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                    'success' => $success,
                    'error_message' => $error,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Analytics logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Convert markdown [text](url) to HTML <a> tags
     * (Used for demo responses to make links clickable)
     */
    private function markdownToHtml(string $text): string
    {
        $pattern = '/\[([^\]]+)\]\(([^)]+)\)/';
        $replacement = '<a href="$2" class="text-blue-600 font-semibold hover:underline" target="_blank">$1</a>';
        return preg_replace($pattern, $replacement, $text);
    }

    // ========== EXISTING METHODS ==========

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

    public function exportConversations(Request $request)
    {
        if (!class_exists(ConversationExport::class)) {
            return redirect()->back()->with('error', 'Export feature not available.');
        }
        $export = new ConversationExport($request->user_id);
        return Excel::download($export, 'ai_conversations_' . date('Y-m-d') . '.xlsx');
    }
}