<?php
namespace App\Services\AI\Agent;

use App\Services\AI\Agent\Contracts\ToolInterface;

class Planner
{
    protected $tools = [];

    public function register(ToolInterface $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }

    public function plan(string $message): array
    {
        $lower = strtolower($message);

        // Detect action/query type
        if (stripos($lower, 'create invoice') !== false || stripos($lower, 'invoice banau') !== false) {
            return [
                'intent' => 'action',
                'tool' => 'create_invoice',
                'params' => $this->extractInvoiceParams($message),
            ];
        }

        if (stripos($lower, 'stock') !== false || stripos($lower, 'inventory') !== false) {
            return [
                'intent' => 'query',
                'tool' => 'stock_lookup',
                'params' => $this->extractProductName($message),
            ];
        }

        if (stripos($lower, 'sales') !== false || stripos($lower, 'today') !== false) {
            return [
                'intent' => 'query',
                'tool' => 'sales_summary',
                'params' => ['date' => now()->toDateString()],
            ];
        }

        // Complex query: multiple tools
        if (stripos($lower, 'compare') !== false && stripos($lower, 'stock') !== false) {
            return [
                'intent' => 'multi_tool',
                'tools' => ['stock_lookup', 'sales_summary'],
                'params' => $this->extractProductName($message),
            ];
        }

        return [
            'intent' => 'unknown',
            'tool' => null,
            'params' => [],
        ];
    }

    protected function extractInvoiceParams($message)
    {
        // Simple extraction (can be improved with regex/NLP)
        return [
            'registration_id' => 1, // Default fallback
            'amount' => 1000,
            'invoice_type' => 'manual',
        ];
    }

    protected function extractProductName($message)
    {
        $remove = ['stock', 'of', 'in', 'my', 'store', 'inventory', 'how', 'much', 'quantity'];
        $words = explode(' ', strtolower($message));
        $filtered = array_diff($words, $remove);
        return ['product_name' => trim(implode(' ', $filtered))];
    }
}