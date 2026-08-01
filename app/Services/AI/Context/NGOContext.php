<?php
namespace App\Services\AI\Context;

use App\Models\Sale;
use App\Models\Expense;

class NGOContext
{
    public function getData($orgId): array
    {
        // NGO module को tables नभएसम्म sales/donations को proxy
        return [
            'type' => 'ngo',
            'total_projects' => 0, // ✅ जब Project table बन्छ, यहाँ update गर्नुहोस्
            'total_donations' => Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->sum('total'),
            'active_projects' => 0, // ✅ जब Project table बन्छ, यहाँ update गर्नुहोस्
            'total_expenses' => Expense::where('organization_id', $orgId)
                ->sum('amount'),
        ];
    }
}