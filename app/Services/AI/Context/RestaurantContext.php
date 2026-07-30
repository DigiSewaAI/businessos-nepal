<?php
namespace App\Services\AI\Context;

use App\Models\RestaurantOrder;
use App\Models\RestaurantTable;

class RestaurantContext
{
    public function getData($orgId): array
    {
        return [
            'type' => 'restaurant',
            'active_orders' => RestaurantOrder::where('organization_id', $orgId)
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->count(),
            'available_tables' => RestaurantTable::where('organization_id', $orgId)
                ->where('status', 'available')
                ->count(),
            'today_orders' => RestaurantOrder::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->count(),
        ];
    }
}