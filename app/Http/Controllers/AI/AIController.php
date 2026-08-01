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
use App\Services\AI\Context\IndustryContextFactory;
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
    protected $industryFactory;

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
        DemoDataService $demoDataService,
        IndustryContextFactory $industryFactory
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
        $this->industryFactory = $industryFactory;
    }

    /**
     * Display the AI chat interface.
     */
    public function chat()
    {
        $isGuest = !auth()->check();
        $conversations = [];
        $messages = [];

        if (auth()->check()) {
            $conversations = Conversation::where('user_id', auth()->id())
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();
        }

        return view('ai.chat', compact('conversations', 'messages', 'isGuest'));
    }

    /**
     * Handle incoming user messages.
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

        // Guest / Demo mode
        if ($isGuest) {
            $response = $this->demoDataService->getDemoAnswer($message);
            $response = $this->markdownToHtml($response);
            
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

        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;
        $industry = auth()->user()->organization->industry ?? 'retail';

        // ---- GREETINGS ----
        $greetings = ['hello', 'hi', 'hey', 'hola', 'namaste', 'नमस्ते', 'namaskar', 'नमस्कार'];
        if (in_array($lower, $greetings) || strlen($message) <= 3) {
            return $this->handleGreeting($request, $message, $startTime, $industry);
        }

        // ============================================================
        // 🟢 STEP 1: INDUSTRY-SPECIFIC CONTEXT (using IndustryContextFactory)
        // ============================================================
        $keywords = $this->industryFactory->getKeywords($industry);
        $isIndustryQuery = false;
        foreach ($keywords as $kw) {
            if (stripos($lower, $kw) !== false) {
                $isIndustryQuery = true;
                break;
            }
        }

        if ($isIndustryQuery) {
            $contextData = $this->industryFactory->getContext($industry, $orgId);
            $builders = $this->industryFactory->getBuilders();
            $builderMethod = $builders[$industry] ?? 'buildRetailResponse';
            
            if (method_exists($this, $builderMethod)) {
                $response = $this->$builderMethod($message, $contextData);
                if ($response) {
                    $this->logAnalytics($message, 'industry_data', $industry, $startTime, true);
                    return $this->saveAndRespond($request, $message, $response, 'industry_data');
                }
            }
        }

        // ============================================================
        // 🟢 STEP 2: AGENT ORCHESTRATOR
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
        // 🟢 STEP 3: FAQ SERVICE
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
        // 🟢 STEP 4: LEGACY RETAIL DATA (for backward compatibility)
        // ============================================================
        if ($industry === 'retail') {
            try {
                $dataAnswer = $this->dataService->getAnswer($message);
                if ($dataAnswer) {
                    $this->logAnalytics($message, 'data', $this->detectIntentFromMessage($lower), $startTime, true);
                    return $this->saveAndRespond($request, $message, $dataAnswer, 'data');
                }
            } catch (\Exception $e) {
                Log::warning('Data error: ' . $e->getMessage());
            }
        }

        // ============================================================
        // 🟢 STEP 5: FORECASTING
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
        // 🟢 STEP 6: ANOMALY DETECTION
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
        // 🟢 STEP 7: OLLAMA (with heavy processing queue)
        // ============================================================
        $conversation = $this->getOrCreateConversation($request, $userId, $orgId);
        $history = $this->getConversationHistory($conversation->id);

        // Heavy query: dispatch to queue
        if (strlen($message) > 100 || stripos($lower, 'analyze') !== false) {
            dispatch(new AIProcessJob($conversation->id, $message, $userId));
            $this->logAnalytics($message, 'queue', 'heavy', $startTime, true);
            return response()->json([
                'conversation_id' => $conversation->id,
                'status' => 'processing',
                'message' => 'Your question is being processed. I\'ll respond shortly.',
            ]);
        }

        // Light query: synchronous Ollama call
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
            $context['industry'] = $industry;
            $context['org_id'] = $orgId;

        } catch (\Exception $e) {
            Log::warning('Intent scoring failed: ' . $e->getMessage());
            $intent = $this->intentParser->parse($message);
            $bestIntent = $intent['category'] ?? 'general';
            $context = [
                'organization' => auth()->user()->organization->name ?? 'Your Business',
                'industry' => $industry,
                'org_id' => $orgId,
                'metrics' => []
            ];
        }

        try {
            $systemPrompt = $this->promptManager->getSystemPrompt($bestIntent, $context);
            $historyText = '';
            foreach ($history as $msg) {
                $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
                $historyText .= "{$role}: {$msg['content']}\n";
            }

            $fullPrompt = $systemPrompt . "\n\n" . $historyText . "User: {$message}\nAssistant:";

            $aiResponse = $this->ollama->generate($fullPrompt, ['industry' => $industry]);

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
            'metadata' => ['intent' => $intent, 'context' => $context, 'source' => 'ollama', 'industry' => $industry],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'response' => $aiResponse,
        ]);
    }

    // ============================================================
    // 🟢 INDUSTRY RESPONSE BUILDERS (called from sendMessage)
    // ============================================================

    private function buildSchoolResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'attendance') !== false) {
            $rate = $data['total_students'] > 0 ? round(($data['present_today'] / $data['total_students']) * 100, 1) : 0;
            return "📊 **Attendance Summary**\n\n" .
                   "👨‍🎓 Total Students: {$data['total_students']}\n" .
                   "✅ Present Today: {$data['present_today']}\n" .
                   "❌ Absent Today: {$data['absent_today']}\n" .
                   "📈 Attendance Rate: {$rate}%";
        }
        
        if (stripos($lower, 'student') !== false || stripos($lower, 'विद्यार्थी') !== false) {
            return "👨‍🎓 **Student Summary**\n\n" .
                   "Total Students: {$data['total_students']}\n" .
                   "👨‍🏫 Total Teachers: {$data['total_teachers']}\n" .
                   "💰 Pending Fees: Rs. " . number_format($data['pending_fees'] ?? 0, 2) . "\n" .
                   "📝 Upcoming Exams: {$data['upcoming_exams']}";
        }
        
        if (stripos($lower, 'fee') !== false || stripos($lower, 'payment') !== false) {
            return "💰 **Fee Summary**\n\n" .
                   "Pending Fees: Rs. " . number_format($data['pending_fees'] ?? 0, 2) . "\n\n" .
                   "💡 Tip: You can generate fee invoices from the Fees section.";
        }
        
        if (stripos($lower, 'exam') !== false || stripos($lower, 'परीक्षा') !== false) {
            return "📝 **Exam Summary**\n\n" .
                   "Upcoming Exams: {$data['upcoming_exams']}\n\n" .
                   "💡 Tip: You can manage exams from the Exams section.";
        }
        
        return null;
    }

    private function buildRetailResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'sales') !== false || stripos($lower, 'बिक्री') !== false) {
            return "📊 **Sales Summary**\n\n" .
                   "Today's Sales: Rs. " . number_format($data['today_sales'] ?? 0, 2) . "\n" .
                   "Total Sales: Rs. " . number_format($data['total_sales'] ?? 0, 2) . "\n" .
                   "Total Products: {$data['total_products']}";
        }
        
        if (stripos($lower, 'stock') !== false || stripos($lower, 'inventory') !== false) {
            return "📦 **Stock Summary**\n\n" .
                   "Total Products: {$data['total_products']}\n" .
                   "Low Stock Items: {$data['low_stock_count']}\n" .
                   "💡 Tip: Low stock items need immediate attention!";
        }
        
        if (stripos($lower, 'profit') !== false || stripos($lower, 'loss') !== false) {
            $profit = ($data['today_sales'] ?? 0) - ($data['today_expenses'] ?? 0);
            return "💰 **Profit Summary**\n\n" .
                   "Today's Revenue: Rs. " . number_format($data['today_sales'] ?? 0, 2) . "\n" .
                   "Today's Expenses: Rs. " . number_format($data['today_expenses'] ?? 0, 2) . "\n" .
                   "Today's Profit: Rs. " . number_format($profit, 2);
        }
        
        return null;
    }

    private function buildRestaurantResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'order') !== false || stripos($lower, 'आदेश') !== false) {
            return "🍽️ **Order Summary**\n\n" .
                   "Active Orders: {$data['active_orders']}\n" .
                   "Today's Orders: {$data['today_orders']}\n" .
                   "💡 Tip: Check the Kitchen section for pending orders.";
        }
        
        if (stripos($lower, 'table') !== false || stripos($lower, 'टेबल') !== false) {
            return "🪑 **Table Summary**\n\n" .
                   "Available Tables: {$data['available_tables']}\n" .
                   "💡 Tip: You can manage tables from the Tables section.";
        }
        
        if (stripos($lower, 'revenue') !== false || stripos($lower, 'income') !== false) {
            return "💰 **Revenue Summary**\n\n" .
                   "Active Orders: {$data['active_orders']}\n" .
                   "💡 Tip: Complete pending orders to increase revenue.";
        }
        
        return null;
    }

    private function buildTravelResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'booking') !== false || stripos($lower, 'बुकिङ') !== false) {
            return "📅 **Booking Summary**\n\n" .
                   "Total Bookings: {$data['total_bookings']}\n" .
                   "Today's Bookings: {$data['today_bookings']}\n" .
                   "💡 Tip: Check the Bookings section for details.";
        }
        
        if (stripos($lower, 'package') !== false || stripos($lower, 'tour') !== false) {
            return "✈️ **Package Summary**\n\n" .
                   "Active Packages: {$data['active_packages']}\n" .
                   "Upcoming Tours: {$data['upcoming_tours']}\n" .
                   "💡 Tip: New packages can be added from the Packages section.";
        }
        
        return null;
    }

    private function buildNGOResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'project') !== false || stripos($lower, 'योजना') !== false) {
            return "📊 **Project Summary**\n\n" .
                   "Total Projects: {$data['total_projects']}\n" .
                   "Active Projects: {$data['active_projects']}\n" .
                   "💡 Tip: Manage projects from the Projects section.";
        }
        
        if (stripos($lower, 'donation') !== false || stripos($lower, 'दान') !== false) {
            return "💰 **Donation Summary**\n\n" .
                   "Total Donations: Rs. " . number_format($data['total_donations'] ?? 0, 2) . "\n" .
                   "💡 Tip: Donations can be tracked from the Donations section.";
        }
        
        return null;
    }

    private function buildManufacturingResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'production') !== false || stripos($lower, 'उत्पादन') !== false) {
            return "🏭 **Production Summary**\n\n" .
                   "Today's Production: {$data['production_today']}\n" .
                   "Total Products: {$data['total_products']}\n" .
                   "💡 Tip: Check the Production section for details.";
        }
        
        return null;
    }

    private function buildHospitalResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'patient') !== false || stripos($lower, 'बिरामी') !== false) {
            return "🏥 **Patient Summary**\n\n" .
                   "Total Patients: {$data['total_patients']}\n" .
                   "Today's Appointments: {$data['today_appointments']}\n" .
                   "💡 Tip: Manage patients from the Patients section.";
        }
        
        return null;
    }

    private function buildServiceResponse(string $message, array $data): ?string
    {
        $lower = strtolower($message);
        
        if (stripos($lower, 'client') !== false || stripos($lower, 'service') !== false) {
            return "💼 **Service Summary**\n\n" .
                   "Total Clients: {$data['total_clients']}\n" .
                   "Today's Bookings: {$data['today_bookings']}\n" .
                   "💡 Tip: Manage services from the Services section.";
        }
        
        return null;
    }

    // ============================================================
    // 🟢 HELPER METHODS (greeting, intent detection, conversation, etc.)
    // ============================================================

    private function handleGreeting($request, $message, $startTime, $industry = 'retail')
    {
        $greetingResponses = [
            'hello' => "Hello! 👋 How can I help you today?",
            'hi' => "Hi there! 👋 What can I do for you today?",
            'hey' => "Hey! 👋 Ready to help with your business.",
            'namaste' => "Namaste! 🙏 How can I assist you today?",
            'default' => "Hello! 👋 Ask me anything about your business.",
        ];

        $lower = strtolower(trim($message));
        $response = $greetingResponses[$lower] ?? $greetingResponses['default'];

        $this->logAnalytics($message, 'greeting', 'greeting', $startTime, true);
        return $this->saveAndRespond($request, $message, $response, 'greeting');
    }

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

    private function markdownToHtml(string $text): string
    {
        $pattern = '/\[([^\]]+)\]\(([^)]+)\)/';
        $replacement = '<a href="$2" class="text-blue-600 font-semibold hover:underline" target="_blank">$1</a>';
        return preg_replace($pattern, $replacement, $text);
    }

    /**
     * Fetch messages of a conversation (for loading history).
     */
    public function conversation($id)
    {
        $conversation = Conversation::with('messages')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($conversation->messages);
    }

    /**
     * Delete a conversation.
     */
    public function deleteConversation($id)
    {
        $conversation = Conversation::where('user_id', auth()->id())->findOrFail($id);
        $conversation->delete();
        return redirect()->back()->with('success', 'Conversation deleted.');
    }

    /**
     * AI Dashboard.
     */
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

    /**
     * Export conversations to Excel.
     */
    public function exportConversations(Request $request)
    {
        if (!class_exists(ConversationExport::class)) {
            return redirect()->back()->with('error', 'Export feature not available.');
        }
        $export = new ConversationExport($request->user_id);
        return Excel::download($export, 'ai_conversations_' . date('Y-m-d') . '.xlsx');
    }
}