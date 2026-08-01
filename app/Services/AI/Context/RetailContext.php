<?php
namespace App\Services\AI\Context;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class RetailContext
{
    public function getData($orgId): array
    {
        $today = now()->toDateString();

        $todaySales = Sale::where('organization_id', $orgId)
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $todayExpenses = Expense::where('organization_id', $orgId)
            ->whereDate('created_at', $today)
            ->sum('amount');

        // Low stock calculation
        $lowStockCount = Product::where('organization_id', $orgId)
            ->whereColumn('stock', '<=', 'alert_quantity')
            ->where('stock', '>', 0)
            ->count();

        return [
            'type' => 'retail',
            'today_sales' => $todaySales,
            'today_expenses' => $todayExpenses,
            'today_profit' => $todaySales - $todayExpenses,
            'low_stock_count' => $lowStockCount,
            'total_products' => Product::where('organization_id', $orgId)->count(),
            'total_sales' => Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->sum('total'),
        ];
    }
}