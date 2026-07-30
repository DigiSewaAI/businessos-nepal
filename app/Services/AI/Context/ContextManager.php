<?php
namespace App\Services\AI\Context;

use Illuminate\Support\Facades\Auth;

class ContextManager
{
    protected $contexts = [];

    public function __construct()
    {
        $this->contexts = [
            'inventory' => new InventoryContext(),
            'sales' => new SalesContext(),
            'financial' => new FinancialContext(),
            'school' => new SchoolContext(),
            'restaurant' => new RestaurantContext(),
        ];
    }

    public function getContext(string $intentCategory): array
    {
        $orgId = Auth::user()->organization_id;
        
        // Only fetch context for the detected category
        if (isset($this->contexts[$intentCategory])) {
            return $this->contexts[$intentCategory]->getData($orgId);
        }
        
        // Fallback: General context (summary)
        return $this->getGeneralContext($orgId);
    }

    protected function getGeneralContext($orgId): array
    {
        return [
            'total_sales_today' => \App\Models\Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
            'total_products' => \App\Models\Product::where('organization_id', $orgId)->count(),
            'active_orders' => \App\Models\RestaurantOrder::where('organization_id', $orgId)
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->count(),
        ];
    }
}