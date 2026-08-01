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
use App\Models\Plan;
use App\Services\Dashboard\DashboardFactory;
use App\Services\Dashboard\Dashboards\SchoolDashboard;
use App\Services\Dashboard\Dashboards\RetailDashboard;
use App\Services\Dashboard\Dashboards\RestaurantDashboard;
use App\Services\Dashboard\Dashboards\TravelDashboard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // ✅ यो import गर्नुहोस्

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

        // ===== SUPER ADMIN DASHBOARD =====
        if ($user->hasRole('Super Admin')) {
            // Get all organization IDs
            $orgIds = Organization::pluck('id');

            // Platform-level metrics
            $totalOrgs = Organization::count();
            
            // If plan_id column exists and Plan model exists, use it; else fallback
            $paidOrgs = 0;
            $trialOrgs = 0;
            if (Schema::hasColumn('organizations', 'plan_id') && class_exists('App\Models\Plan')) {
                $paidOrgs = Organization::where('plan_id', '>', 1)->count();
                $trialOrgs = Organization::where('plan_id', 1)->count();
            }

            $todaySignups = Organization::whereDate('created_at', today())->count();

            // Revenue metrics
            $mrr = Sale::whereIn('organization_id', $orgIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('total') ?? 0;

            $todayRevenue = Sale::whereIn('organization_id', $orgIds)
                ->whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total') ?? 0;

            $monthRevenue = Sale::whereIn('organization_id', $orgIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('total') ?? 0;

            $totalRevenue = Sale::whereIn('organization_id', $orgIds)
                ->where('status', 'completed')
                ->sum('total') ?? 0;

            // Expiring plans (next 7 days) – if subscription relationship exists
            $expiringPlans = 0;
            if (method_exists(new Organization(), 'subscription')) {
                $expiringPlans = Organization::whereHas('subscription', function($q) {
                    $q->whereDate('ends_at', '>=', now())
                        ->whereDate('ends_at', '<=', now()->addDays(7));
                })->count();
            }

            // System health (placeholders)
            $systemHealth = [
                'ai_usage' => 85,
                'storage' => 62,
                'queue' => 0,
                'failed_jobs' => 0,
            ];

            // Recent organizations (without eager loading of plan)
            $recentOrgs = Organization::latest()
                ->limit(5)
                ->get()
                ->map(function($org) {
                    // Get plan name safely
                    $planName = 'Free';
                    if (isset($org->plan_id) && class_exists('App\Models\Plan')) {
                        $plan = Plan::find($org->plan_id);
                        $planName = $plan->name ?? 'Free';
                    }
                    return (object) [
                        'id' => $org->id,
                        'name' => $org->name,
                        'plan' => $planName,
                        'status' => $org->status ?? 'active',
                        'joined' => $org->created_at->diffForHumans(),
                    ];
                });

            // Recent payments (using sales as proxy)
            $recentPayments = Sale::whereIn('organization_id', $orgIds)
                ->where('status', 'completed')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($sale) {
                    return (object) [
                        'amount' => $sale->total,
                        'plan' => 'Standard', // placeholder
                        'status' => 'Completed',
                        'date' => $sale->created_at->diffForHumans(),
                    ];
                });

            // Recent users with role and organization
            $recentUsers = User::with('organization')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return (object) [
                        'name' => $user->name,
                        'role' => $user->roles->first()->name ?? 'User',
                        'organization' => $user->organization->name ?? 'N/A',
                        'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never',
                    ];
                });

            $data = [
                'total_organizations' => $totalOrgs,
                'paid_organizations' => $paidOrgs,
                'trial_organizations' => $trialOrgs,
                'today_signups' => $todaySignups,
                'mrr' => $mrr,
                'today_revenue' => $todayRevenue,
                'month_revenue' => $monthRevenue,
                'total_revenue' => $totalRevenue,
                'expiring_plans' => $expiringPlans,
                'system_health' => $systemHealth,
                'recent_organizations' => $recentOrgs,
                'recent_payments' => $recentPayments,
                'recent_users' => $recentUsers,
                'org_count' => $totalOrgs,
                'admin_name' => $user->name,
            ];

            return view('dashboard.super_admin', $data);
        }

        // ===== REGULAR USER =====
        $organization = $user->organization;
        $organizationId = $organization->id;
        $industry = $organization->industry ?? 'retail';

        if (!config('businessos.features.dynamic_dashboard', false)) {
            return $this->renderLegacyDashboard($organizationId);
        }

        $dashboard = null;
        $view = null;
        $data = [];

        if ($industry === 'school') {
            $dashboard = new SchoolDashboard($organizationId, $industry, $organization->business_category);
            $data = $dashboard->getData();
            $view = 'dashboard.school';
        }

        if (!$dashboard) {
            $dashboard = $this->dashboardFactory->create(
                $organizationId,
                $industry,
                $organization->business_category
            );
            $data = $dashboard->getData();
            $view = $dashboard->getView();
        }

        if (!view()->exists($view)) {
            $view = 'dashboard.retail';
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