<?php

namespace App\Services\Dashboard\Dashboards;

use App\Services\Dashboard\BaseDashboard;
use App\Models\RestaurantOrder;
use App\Models\RestaurantTable;
use App\Models\KOTLog;
use Illuminate\Support\Facades\DB;

class RestaurantDashboard extends BaseDashboard
{
    public function getData(): array
    {
        // 1. Active Orders
        $activeOrders = RestaurantOrder::where('organization_id', $this->organizationId)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->count();

        // 2. Available Tables
        $availableTables = RestaurantTable::where('organization_id', $this->organizationId)
            ->where('status', 'available')
            ->count();

        $occupiedTables = RestaurantTable::where('organization_id', $this->organizationId)
            ->where('status', 'occupied')
            ->count();

        // 3. Today's Revenue
        $todayRevenue = RestaurantOrder::where('organization_id', $this->organizationId)
            ->whereDate('created_at', now())
            ->where('status', 'completed')
            ->sum('total');

        // 4. Popular Dish
        $popularDish = DB::table('restaurant_order_items')
            ->join('products', 'restaurant_order_items.product_id', '=', 'products.id')
            ->join('restaurant_orders', 'restaurant_order_items.restaurant_order_id', '=', 'restaurant_orders.id')
            ->where('restaurant_orders.organization_id', $this->organizationId)
            ->whereDate('restaurant_orders.created_at', now())
            ->select('products.name', DB::raw('SUM(restaurant_order_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->first();

        // 5. Pending KOTs
        $pendingKOTs = KOTLog::where('organization_id', $this->organizationId)
            ->where('status', 'sent')
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        // 6. Monthly Revenue
        $monthlyRevenue = RestaurantOrder::where('organization_id', $this->organizationId)
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'industry' => $this->industry,
            'business_category' => $this->businessCategory,
            'activeOrders' => $activeOrders,
            'availableTables' => $availableTables,
            'occupiedTables' => $occupiedTables,
            'todayRevenue' => $todayRevenue,
            'popularDish' => $popularDish,
            'pendingKOTs' => $pendingKOTs,
            'monthlyRevenue' => $monthlyRevenue,
        ];
    }

    public function getBusinessCategories(): array
    {
        return ['cafe', 'bakery', 'fast_food', 'fine_dining'];
    }
}