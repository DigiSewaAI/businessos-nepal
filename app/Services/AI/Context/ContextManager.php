<?php
namespace App\Services\AI\Context;

use Illuminate\Support\Facades\Auth;

class ContextManager
{
    protected $contexts = [];

    public function __construct()
    {
        $this->contexts = [
            'inventory' => InventoryContext::class,
            'sales' => SalesContext::class,
            'financial' => FinancialContext::class,
            'school' => SchoolContext::class,
            'restaurant' => RestaurantContext::class,
            'retail' => RetailContext::class,
            'travel' => TravelContext::class,
            'ngo' => NGOContext::class,
            'hospital' => HospitalContext::class,
            'manufacturing' => ManufacturingContext::class,
            'service' => ServiceContext::class,
        ];
    }

    public function getContext(string $intentCategory): array
    {
        $orgId = Auth::user()->organization_id;

        if (isset($this->contexts[$intentCategory])) {
            $class = $this->contexts[$intentCategory];
            return app($class)->getData($orgId);
        }

        return $this->getGeneralContext($orgId);
    }

    public function getGeneralContext($orgId): array
    {
        return [
            'total_sales_today' => \App\Models\Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total'),
            'total_products' => \App\Models\Product::where('organization_id', $orgId)->count(),
            'total_sales' => \App\Models\Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->count(),
        ];
    }
}