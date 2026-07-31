<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Organization;
use App\Models\User;
use App\Models\Branch;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\IndustryService; // ✅ NEW: Import for industry detection

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ===== SUPER ADMIN DASHBOARD =====
        if ($user->hasRole('Super Admin')) {
            $data = [
                'total_organizations' => Organization::count(),
                'total_users' => User::count(),
                'total_branches' => Branch::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_revenue' => Sale::sum('total'),
                'today_sales' => Sale::whereDate('created_at', today())->sum('total'),
                'recent_organizations' => Organization::latest()->limit(5)->get(),
                'recent_users' => User::latest()->limit(5)->get(),
            ];

            return view('dashboard.super_admin', $data);
        }

        // ===== REGULAR USER DASHBOARD =====
        $organizationId = $user->organization_id;
        $today = Carbon::today();

        // ✅ Phase B readiness: we can get industry but not using it yet
        // $industry = $this->getOrganizationIndustry();

        // 1. Today's Total Sales
        $todaySales = Sale::where('organization_id', $organizationId)
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        // 2. Today's Total Expenses
        $todayExpenses = Expense::where('organization_id', $organizationId)
            ->whereDate('created_at', $today)
            ->sum('amount');

        // 3. Today's Profit
        $profitData = DB::table('sales')
            ->join('sale_lines', 'sales.id', '=', 'sale_lines.sale_id')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->where('sales.organization_id', $organizationId)
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'completed')
            ->select(DB::raw('SUM(sale_lines.total) as total_sales, SUM(products.purchase_price * sale_lines.quantity) as total_cost'))
            ->first();

        $todayProfit = 0;
        if ($profitData && $profitData->total_sales) {
            $todayProfit = $profitData->total_sales - $profitData->total_cost - $todayExpenses;
        }

        // 4. Low Stock Count
        $productIds = Product::where('organization_id', $organizationId)->pluck('id');
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
            ->where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->value('closing_balance') ?? 0;

        // 6. Top Selling Products
        $topProducts = DB::table('sale_lines')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->join('sales', 'sale_lines.sale_id', '=', 'sales.id')
            ->where('sales.organization_id', $organizationId)
            ->where('sales.status', 'completed')
            ->select('products.name', DB::raw('SUM(sale_lines.quantity) as total_qty'), DB::raw('SUM(sale_lines.total) as total_amount'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // 7. Monthly Sales
        $monthlySales = Sale::where('organization_id', $organizationId)
            ->where('status', 'completed')
            ->whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('dashboard', compact(
            'todaySales',
            'todayExpenses',
            'todayProfit',
            'lowStockCount',
            'cashBalance',
            'topProducts',
            'monthlySales'
        ));
    }

    /**
     * ✅ NEW: Get the industry of the current organization.
     * Used in Phase B for dynamic dashboard & widgets.
     */
    private function getOrganizationIndustry(): string
    {
        $org = auth()->user()->organization;
        return $org->industry ?? 'retail';
    }
}