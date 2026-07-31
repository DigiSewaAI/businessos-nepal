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
use App\Services\Dashboard\DashboardFactory;
use App\Services\Dashboard\Dashboards\SchoolDashboard;
use App\Services\Dashboard\Dashboards\RetailDashboard;
use App\Services\Dashboard\Dashboards\RestaurantDashboard;
use App\Services\Dashboard\Dashboards\TravelDashboard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboardFactory;

    public function __construct(DashboardFactory $dashboardFactory)
    {
        $this->dashboardFactory = $dashboardFactory;
    }

    public function index()
    {
        $user = auth()->user();

        // ===== SUPER ADMIN =====
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

        // ===== REGULAR USER =====
        $organization = $user->organization;
        $organizationId = $organization->id;
        $industry = $organization->industry ?? 'retail';

        // ✅ If dynamic dashboard feature is OFF, use legacy dashboard
        if (!config('businessos.features.dynamic_dashboard', false)) {
            return $this->renderLegacyDashboard($organizationId);
        }

        // ✅ Temporary bypass: directly instantiate dashboard for specific industries
        // This avoids the factory issue where 'school' falls back to 'retail'
        $dashboard = null;
        $view = null;
        $data = [];

        if ($industry === 'school') {
            $dashboard = new SchoolDashboard($organizationId, $industry, $organization->business_category);
            $data = $dashboard->getData();
            $view = 'dashboard.school';
        }
        // Add other specific industries here if needed
        // elseif ($industry === 'restaurant') { ... }

        // If no specific industry matched, fallback to factory
        if (!$dashboard) {
            $dashboard = $this->dashboardFactory->create(
                $organizationId,
                $industry,
                $organization->business_category
            );
            $data = $dashboard->getData();
            $view = $dashboard->getView();
        }

        // ✅ If view doesn't exist, fallback to retail
        if (!view()->exists($view)) {
            $view = 'dashboard.retail';
            // If we don't have data for retail, get it from factory
            if ($industry !== 'retail') {
                $retailDashboard = new RetailDashboard($organizationId, 'retail');
                $data = $retailDashboard->getData();
            }
        }

        return view($view, $data);
    }

    /**
     * Legacy dashboard (exactly as before Phase B)
     */
    private function renderLegacyDashboard(int $organizationId)
    {
        $today = Carbon::today();

        $todaySales = Sale::where('organization_id', $organizationId)
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $todayExpenses = Expense::where('organization_id', $organizationId)
            ->whereDate('created_at', $today)
            ->sum('amount');

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

        $cashBalance = DB::table('cashbook')
            ->where('organization_id', $organizationId)
            ->orderBy('created_at', 'desc')
            ->value('closing_balance') ?? 0;

        $topProducts = DB::table('sale_lines')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->join('sales', 'sale_lines.sale_id', '=', 'sales.id')
            ->where('sales.organization_id', $organizationId)
            ->where('sales.status', 'completed')
            ->select('products.name', DB::raw('SUM(sale_lines.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

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
}