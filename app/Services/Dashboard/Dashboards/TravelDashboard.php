<?php

namespace App\Services\Dashboard\Dashboards;

use App\Services\Dashboard\BaseDashboard;

class TravelDashboard extends BaseDashboard
{
    public function getData(): array
    {
        // Note: Travel module tables don't exist yet, so we use sales as proxy
        // and prepare structure for future

        $todaySales = \App\Models\Sale::where('organization_id', $this->organizationId)
            ->whereDate('created_at', now())
            ->where('status', 'completed')
            ->sum('total');

        $totalBookings = \App\Models\Sale::where('organization_id', $this->organizationId)
            ->where('status', 'completed')
            ->count();

        // Placeholder data until Travel module is built
        return [
            'industry' => $this->industry,
            'business_category' => $this->businessCategory,
            'todayRevenue' => $todaySales,
            'totalBookings' => $totalBookings,
            'upcomingTours' => 0, // Will be from travel_bookings table
            'activePackages' => 0, // Will be from travel_packages table
            'popularDestinations' => [], // Will be from travel_destinations table
            // Placeholders for charts
            'monthlyRevenue' => collect([]),
        ];
    }

    public function getBusinessCategories(): array
    {
        return ['travel_agency', 'trekking', 'tour_operator'];
    }
}