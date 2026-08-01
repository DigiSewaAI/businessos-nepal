<?php
namespace App\Services\AI\Context;

use App\Models\Sale;
use App\Models\Product;

class TravelContext
{
    public function getData($orgId): array
    {
        // Travel module को tables नभएसम्म sales लाई proxy को रूपमा use गर्ने
        return [
            'type' => 'travel',
            'total_bookings' => Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->count(),
            'today_revenue' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
            'active_packages' => Product::where('organization_id', $orgId)
                ->where('is_active', true)
                ->count(),
            'pending_bookings' => Sale::where('organization_id', $orgId)
                ->where('status', 'pending')
                ->count(),
        ];
    }
}