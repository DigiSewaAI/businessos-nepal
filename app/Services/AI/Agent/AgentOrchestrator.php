<?php
namespace App\Services\AI\Agent;

use App\Services\AI\Agent\Contracts\ToolInterface;
use App\Services\AI\Agent\Tools\StockTool;
use App\Services\AI\Agent\Tools\SalesTool;
use App\Services\AI\Agent\Tools\CreateInvoiceTool;
use App\Services\AI\OllamaService;
use App\Services\AI\Agent\PermissionGate; // <-- Added for Phase 5

class AgentOrchestrator
{
    protected $planner;
    protected $ollama;
    protected $tools = [];
    protected $permissionGate; // <-- New property

    public function __construct(
        Planner $planner,
        OllamaService $ollama,
        PermissionGate $permissionGate // <-- Injected
    ) {
        $this->planner = $planner;
        $this->ollama = $ollama;
        $this->permissionGate = $permissionGate; // <-- Assign

        // Register all tools
        $this->registerTool(new StockTool());
        $this->registerTool(new SalesTool());
        $this->registerTool(new CreateInvoiceTool());
    }

    public function registerTool(ToolInterface $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
        $this->planner->register($tool);
    }

    public function execute(string $message): ?string
    {
        $plan = $this->planner->plan($message);

        if ($plan['intent'] === 'unknown') {
            return null; // Fallback to regular AI
        }

        // ✅ Permission check for actions
        if ($plan['intent'] === 'action') {
            $action = $plan['tool'];
            if (!$this->permissionGate->canExecute($action)) {
                return "⚠️ You don't have permission to perform this action. Please contact your manager.";
            }
        }

        // Single tool execution
        if ($plan['intent'] === 'query' || $plan['intent'] === 'action') {
            $tool = $this->tools[$plan['tool']] ?? null;
            if ($tool) {
                $result = $tool->execute($plan['params']);
                return $this->formatResponse($plan['intent'], $result, $message);
            }
        }

        // Multi-tool (complex queries)
        if ($plan['intent'] === 'multi_tool') {
            $results = [];
            foreach ($plan['tools'] as $toolName) {
                $tool = $this->tools[$toolName] ?? null;
                if ($tool) {
                    $results[$toolName] = $tool->execute($plan['params']);
                }
            }
            return $this->formatMultiToolResponse($results, $message);
        }

        return null;
    }

    protected function formatResponse($intent, $result, $originalMessage)
    {
        if ($intent === 'action') {
            return $result['message'] ?? "Action completed successfully.";
        }

        // For queries, enhance with AI explanation
        $prompt = "You are BusinessOS AI. Based on this data: " . json_encode($result) . 
                  ", explain it to the user clearly. User asked: {$originalMessage}";
        
        return $this->ollama->generate($prompt);
    }

    protected function formatMultiToolResponse($results, $originalMessage)
    {
        $prompt = "You are BusinessOS AI. Based on multiple data sources: " . json_encode($results) . 
                  ", provide a comprehensive answer. User asked: {$originalMessage}";
        
        return $this->ollama->generate($prompt);
    }
}