<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Sale;
use App\Models\Payment;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalOrganizations = Organization::count();
        $totalUsers = User::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalSales = Sale::count();

        $organizationsByPlan = Organization::with('subscription.plan')
            ->get()
            ->groupBy('subscription.plan.name')
            ->map->count();

        $monthlyRegistrations = Organization::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('admin.analytics.index', compact(
            'totalOrganizations',
            'totalUsers',
            'totalRevenue',
            'totalSales',
            'organizationsByPlan',
            'monthlyRegistrations'
        ));
    }
}