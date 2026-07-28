<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\Customer;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new sale with stock deduction
     */
    public function createSale(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            // Generate invoice number
            $invoiceNo = 'INV-' . date('Ymd') . '-' . Str::random(6);

            // Calculate totals
            $subtotal = collect($items)->sum('total');
            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            // Create customer if new
            $customerId = $data['customer_id'] ?? null;
            if ($data['customer_phone'] ?? false) {
                $customer = Customer::firstOrCreate(
                    ['phone' => $data['customer_phone']],
                    ['name' => $data['customer_name'] ?? 'Walk-in Customer', 'organization_id' => auth()->user()->organization_id]
                );
                $customerId = $customer->id;
            }

            // Create sale
            $sale = Sale::create([
                'organization_id' => auth()->user()->organization_id,
                'branch_id' => auth()->user()->branch_id ?? $data['branch_id'],
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'invoice_no' => $invoiceNo,
                'date' => now(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'discount_percent' => $data['discount_percent'] ?? 0,
                'tax' => $tax,
                'tax_percent' => $data['tax_percent'] ?? 0,
                'total' => $total,
                'paid_amount' => $data['paid_amount'] ?? $total,
                'due_amount' => ($data['paid_amount'] ?? $total) - $total,
                'payment_status' => ($data['paid_amount'] ?? $total) >= $total ? 'paid' : 'partial',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'note' => $data['note'] ?? null,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            // Create sale lines and deduct stock
            foreach ($items as $item) {
                // Create sale line
                SaleLine::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $item['total'],
                ]);

                // Deduct stock
                $this->stockService->removeStock(
                    $item['product_id'],
                    $item['warehouse_id'],
                    $item['quantity'],
                    'sale',
                    $sale->id,
                    $item['product_variant_id'] ?? null,
                    'Sale #' . $invoiceNo
                );
            }

            // Update customer total purchases
            if ($customerId) {
                Customer::where('id', $customerId)->increment('total_purchases', $total);
            }

            return $sale->load('lines', 'customer');
        });
    }

    /**
     * Return a sale (reverse stock)
     */
    public function returnSale($saleId, array $returnItems)
    {
        return DB::transaction(function () use ($saleId, $returnItems) {
            $sale = Sale::with('lines')->findOrFail($saleId);

            foreach ($returnItems as $return) {
                $line = SaleLine::findOrFail($return['line_id']);

                // Return stock
                $this->stockService->addStock(
                    $line->product_id,
                    $line->warehouse_id,
                    $return['quantity'],
                    'sale_return',
                    $saleId,
                    $line->product_variant_id,
                    'Return from Sale #' . $sale->invoice_no
                );

                // Update sale line
                $line->quantity -= $return['quantity'];
                $line->total = $line->quantity * $line->price;
                $line->save();
            }

            // Update sale total
            $sale->subtotal = $sale->lines->sum('total');
            $sale->total = $sale->subtotal - $sale->discount + $sale->tax;
            $sale->save();

            return $sale;
        });
    }
}