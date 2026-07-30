<?php
namespace App\Services\AI\Prompts;

class PromptManager
{
    /**
     * Get only the system prompt (without user message)
     * Used by AIController to build full prompt with history
     */
    public function getSystemPrompt(string $module, array $context): string
    {
        return match($module) {
            'inventory' => $this->inventoryPrompt($context),
            'sales' => $this->salesPrompt($context),
            'financial' => $this->financialPrompt($context),
            'school' => $this->schoolPrompt($context),
            'restaurant' => $this->restaurantPrompt($context),
            default => $this->generalPrompt($context),
        };
    }

    /**
     * Build full prompt with user message (without history)
     * Used as fallback or for simple queries
     */
    public function buildPrompt(string $module, array $context, string $message): string
    {
        $systemPrompt = $this->getSystemPrompt($module, $context);
        return $systemPrompt . "\n\nUser: {$message}\nAssistant:";
    }

    protected function inventoryPrompt(array $context): string
    {
        return "You are BusinessOS Inventory Assistant. You help with product stock, inventory management, and low stock alerts.
Current Inventory Data:
- Total Products: {$context['total_products']}
- Low Stock Items: {$context['low_stock_items']}
- Top Stocked Products: " . implode(', ', $context['top_stock_products'] ?? []);
    }

    protected function salesPrompt(array $context): string
    {
        return "You are BusinessOS Sales Assistant. You help with sales data, revenue, and order management.
Current Sales Data:
- Today's Sales: Rs. {$context['today_sales']}
- This Month's Sales: Rs. {$context['this_month_sales']}
- Total Orders: {$context['total_orders']}";
    }

    protected function financialPrompt(array $context): string
    {
        return "You are BusinessOS Finance Assistant. You help with profit, expenses, and cash flow.
Current Financial Data:
- Today's Sales: Rs. {$context['today_sales']}
- Today's Expenses: Rs. {$context['today_expenses']}
- Today's Profit: Rs. {$context['today_profit']}";
    }

    protected function schoolPrompt(array $context): string
    {
        return "You are BusinessOS School Assistant. You help with student management, attendance, and fees.
Current School Data:
- Total Students: {$context['total_students']}
- Present Today: {$context['present_today']}
- Absent Today: {$context['absent_today']}";
    }

    protected function restaurantPrompt(array $context): string
    {
        return "You are BusinessOS Restaurant Assistant. You help with orders, table management, and kitchen.
Current Restaurant Data:
- Active Orders: {$context['active_orders']}
- Available Tables: {$context['available_tables']}
- Today's Orders: {$context['today_orders']}";
    }

    protected function generalPrompt(array $context): string
    {
        return "You are BusinessOS AI Assistant. You help with overall business management.
Current Business Data:
- Today's Sales: Rs. {$context['total_sales_today']}
- Total Products: {$context['total_products']}
- Active Orders: {$context['active_orders']}";
    }
}