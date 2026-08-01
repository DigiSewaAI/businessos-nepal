<?php
namespace App\Services\AI\Context;

use App\Models\Sale;
use App\Models\User;

class HospitalContext
{
    public function getData($orgId): array
    {
        // Hospital module को tables नभएसम्म sales/user लाई proxy
        return [
            'type' => 'hospital',
            'total_patients' => User::where('organization_id', $orgId)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Patient');
                })
                ->count(),
            'today_appointments' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->count(),
            'pending_prescriptions' => 0, // ✅ जब Prescription table बन्छ, update गर्नुहोस्
            'today_revenue' => Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
        ];
    }
}