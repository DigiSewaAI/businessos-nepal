<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseLine;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new purchase order
     */
    public function createPurchase(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $poNo = 'PO-' . date('Ymd') . '-' . Str::random(6);

            $subtotal = collect($items)->sum('total');
            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            $purchase = Purchase::create([
                'organization_id' => auth()->user()->organization_id,
                'branch_id' => $data['branch_id'],
                'supplier_id' => $data['supplier_id'],
                'user_id' => auth()->id(),
                'po_no' => $poNo,
                'invoice_no' => $data['invoice_no'] ?? null,
                'date' => now(),
                'expected_date' => $data['expected_date'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'due_amount' => $total - ($data['paid_amount'] ?? 0),
                'payment_status' => ($data['paid_amount'] ?? 0) >= $total ? 'paid' : 'unpaid',
                'status' => 'ordered',
                'note' => $data['note'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($items as $item) {
                PurchaseLine::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'received_quantity' => 0,
                    'purchase_price' => $item['purchase_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $item['total'],
                ]);
            }

            // Update supplier total purchases
            Supplier::where('id', $data['supplier_id'])->increment('total_purchases', $total);

            return $purchase->load('lines', 'supplier');
        });
    }

    /**
     * Receive purchase (add stock to warehouse)
     */
    public function receivePurchase($purchaseId, array $receiveItems)
    {
        return DB::transaction(function () use ($purchaseId, $receiveItems) {
            $purchase = Purchase::with('lines')->findOrFail($purchaseId);

            foreach ($receiveItems as $receive) {
                $line = PurchaseLine::findOrFail($receive['line_id']);

                $receiveQty = $receive['quantity'];

                // Add stock to warehouse
                $this->stockService->addStock(
                    $line->product_id,
                    $line->warehouse_id,
                    $receiveQty,
                    'purchase',
                    $purchaseId,
                    $line->product_variant_id,
                    'Purchase #' . $purchase->po_no
                );

                // Update received quantity
                $line->received_quantity += $receiveQty;
                $line->save();
            }

            // Update purchase status
            $allReceived = $purchase->lines->every(function ($line) {
                return $line->received_quantity >= $line->quantity;
            });

            if ($allReceived) {
                $purchase->status = 'received';
                $purchase->save();
            }

            return $purchase;
        });
    }

    /**
     * Return purchase (reverse stock)
     */
    public function returnPurchase($purchaseId, array $returnItems)
    {
        return DB::transaction(function () use ($purchaseId, $returnItems) {
            $purchase = Purchase::findOrFail($purchaseId);

            foreach ($returnItems as $return) {
                $line = PurchaseLine::findOrFail($return['line_id']);

                // Remove stock from warehouse
                $this->stockService->removeStock(
                    $line->product_id,
                    $line->warehouse_id,
                    $return['quantity'],
                    'purchase_return',
                    $purchaseId,
                    $line->product_variant_id,
                    'Return from Purchase #' . $purchase->po_no
                );

                // Update line (reduce received quantity)
                $line->received_quantity -= $return['quantity'];
                $line->save();
            }

            $purchase->status = 'returned';
            $purchase->save();

            return $purchase;
        });
    }
}