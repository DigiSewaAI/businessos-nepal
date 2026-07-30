<?php
namespace App\Services\AI\Context;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryContext
{
    public function getData($orgId): array
    {
        // Only inventory-related data
        $lowStockCount = Product::where('organization_id', $orgId)
            ->whereColumn('stock', '<=', 'alert_quantity')
            ->count();
            
        $topProducts = Product::where('organization_id', $orgId)
            ->orderBy('stock', 'desc')
            ->limit(5)
            ->pluck('name')
            ->toArray();
            
        return [
            'type' => 'inventory',
            'low_stock_items' => $lowStockCount,
            'top_stock_products' => $topProducts,
            'total_products' => Product::where('organization_id', $orgId)->count(),
        ];
    }
}