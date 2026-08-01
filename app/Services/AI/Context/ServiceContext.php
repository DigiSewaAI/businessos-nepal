<?php
namespace App\Services\AI\Context;

use App\Models\Sale;
use App\Models\User;

class ServiceContext
{
    public function getData($orgId): array
    {
        // Service module को tables नभएसम्म sales/user लाई proxy
        return [
            'type' => 'service',
            'total_clients' => User::where('organization_id', $orgId)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Client');
                })
                ->count(),
            'today_appointments' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->count(),
            'active_services' => Product::where('organization_id', $orgId)
                ->where('is_active', true)
                ->where('is_service', true)
                ->count(),
            'today_revenue' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
        ];
    }
}