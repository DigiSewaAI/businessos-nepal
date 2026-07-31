<?php

namespace App\Services\Dashboard\Dashboards;

use App\Services\Dashboard\BaseDashboard;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RetailDashboard extends BaseDashboard
{
    public function getData(): array
    {
        $today = Carbon::today();

        // 1. Today's Sales
        $todaySales = Sale::where('organization_id', $this->organizationId)
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        // 2. Today's Expenses
        $todayExpenses = Expense::where('organization_id', $this->organizationId)
            ->whereDate('created_at', $today)
            ->sum('amount');

        // 3. Today's Profit
        $profitData = DB::table('sales')
            ->join('sale_lines', 'sales.id', '=', 'sale_lines.sale_id')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->where('sales.organization_id', $this->organizationId)
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'completed')
            ->select(DB::raw('SUM(sale_lines.total) as total_sales, SUM(products.purchase_price * sale_lines.quantity) as total_cost'))
            ->first();

        $todayProfit = 0;
        if ($profitData && $profitData->total_sales) {
            $todayProfit = $profitData->total_sales - $profitData->total_cost - $todayExpenses;
        }

        // 4. Low Stock Count
        $productIds = Product::where('organization_id', $this->organizationId)->pluck('id');
        $lowStockCount = 0;
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            $currentStock = StockMovement::where('product_id', $productId)
                ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock'))
                ->first()->stock ?? 0;
            if ($currentStock <= $product->alert_quantity && $currentStock > 0) {
                $lowStockCount++;
            }
        }

        // 5. Cash Balance
        $cashBalance = DB::table('cashbook')
            ->where('organization_id', $this->organizationId)
            ->orderBy('created_at', 'desc')
            ->value('closing_balance') ?? 0;

        // 6. Top Selling Products
        $topProducts = DB::table('sale_lines')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->join('sales', 'sale_lines.sale_id', '=', 'sales.id')
            ->where('sales.organization_id', $this->organizationId)
            ->where('sales.status', 'completed')
            ->select('products.name', DB::raw('SUM(sale_lines.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // 7. Monthly Sales
        $monthlySales = Sale::where('organization_id', $this->organizationId)
            ->where('status', 'completed')
            ->whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'industry' => $this->industry,
            'business_category' => $this->businessCategory,
            'todaySales' => $todaySales,
            'todayExpenses' => $todayExpenses,
            'todayProfit' => $todayProfit,
            'lowStockCount' => $lowStockCount,
            'cashBalance' => $cashBalance,
            'topProducts' => $topProducts,
            'monthlySales' => $monthlySales,
        ];
    }

    public function getBusinessCategories(): array
    {
        return ['pharmacy', 'electronics', 'bakery', 'supermarket', 'hardware', 'furniture', 'mobile_shop', 'cosmetics'];
    }
}