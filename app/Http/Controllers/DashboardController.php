<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check if user is Super Admin
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

        // Regular User Dashboard
        $orgId = $user->organization_id;

        $data = [
            'total_products' => Product::where('organization_id', $orgId)->count(),
            'today_sales' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', today())
                ->sum('total'),
            'low_stock' => Product::where('organization_id', $orgId)
                ->whereColumn('alert_quantity', '>=', 'quantity')
                ->count(),
            'recent_sales' => Sale::where('organization_id', $orgId)
                ->latest()
                ->limit(5)
                ->get(),
        ];

        return view('dashboard.index', $data);
    }
}