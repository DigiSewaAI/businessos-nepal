<?php
namespace App\Services\AI\Context;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;

class ManufacturingContext
{
    public function getData($orgId): array
    {
        return [
            'type' => 'manufacturing',
            'total_products' => Product::where('organization_id', $orgId)->count(),
            'total_raw_materials' => Product::where('organization_id', $orgId)
                ->where('is_raw_material', true)
                ->count(),
            'pending_orders' => Sale::where('organization_id', $orgId)
                ->where('status', 'pending')
                ->count(),
            'today_production' => 0, // ✅ जब Production table बन्छ, update गर्नुहोस्
            'total_purchases' => Purchase::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->count(),
        ];
    }
}