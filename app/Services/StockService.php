<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function getCurrentStock($productId, $warehouseId, $variantId = null)
    {
        $query = StockMovement::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId);

        if ($variantId) {
            $query->where('product_variant_id', $variantId);
        }

        $in = $query->clone()->where('type', 'in')->sum('quantity');
        $out = $query->clone()->where('type', 'out')->sum('quantity');

        return $in - $out;
    }

    public function addStock($productId, $warehouseId, $quantity, $referenceType, $referenceId, $variantId = null, $reason = null)
    {
        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $referenceType, $referenceId, $variantId, $reason) {
            $currentStock = $this->getCurrentStock($productId, $warehouseId, $variantId);

            $movement = StockMovement::create([
                'organization_id' => auth()->user()->organization_id,
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'type' => 'in',
                'quantity' => $quantity,
                'previous_quantity' => $currentStock,
                'current_quantity' => $currentStock + $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'created_by' => auth()->id(),
            ]);

            return $movement;
        });
    }

    public function removeStock($productId, $warehouseId, $quantity, $referenceType, $referenceId, $variantId = null, $reason = null)
    {
        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $referenceType, $referenceId, $variantId, $reason) {
            $currentStock = $this->getCurrentStock($productId, $warehouseId, $variantId);

            if ($currentStock < $quantity) {
                throw new \Exception('Insufficient stock. Available: ' . $currentStock);
            }

            $movement = StockMovement::create([
                'organization_id' => auth()->user()->organization_id,
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'type' => 'out',
                'quantity' => $quantity,
                'previous_quantity' => $currentStock,
                'current_quantity' => $currentStock - $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'created_by' => auth()->id(),
            ]);

            return $movement;
        });
    }
}