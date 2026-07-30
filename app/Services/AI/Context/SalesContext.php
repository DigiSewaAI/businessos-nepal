<?php
namespace App\Services\AI\Context;

use App\Models\Sale;

class SalesContext
{
    public function getData($orgId): array
    {
        return [
            'type' => 'sales',
            'today_sales' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
            'this_month_sales' => Sale::where('organization_id', $orgId)
                ->whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('total'),
            'total_orders' => Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->count(),
        ];
    }
}