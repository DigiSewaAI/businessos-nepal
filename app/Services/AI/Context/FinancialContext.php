<?php
namespace App\Services\AI\Context;

use App\Models\Expense;
use App\Models\Sale;

class FinancialContext
{
    public function getData($orgId): array
    {
        $todaySales = Sale::where('organization_id', $orgId)
            ->whereDate('created_at', now())
            ->where('status', 'completed')
            ->sum('total');
            
        $todayExpenses = Expense::where('organization_id', $orgId)
            ->whereDate('created_at', now())
            ->sum('amount');
            
        return [
            'type' => 'financial',
            'today_sales' => $todaySales,
            'today_expenses' => $todayExpenses,
            'today_profit' => $todaySales - $todayExpenses,
        ];
    }
}