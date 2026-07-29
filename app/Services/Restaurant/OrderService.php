<?php

namespace App\Services\Restaurant;

use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\RestaurantTable;
use App\Models\Sale;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function createOrder(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::random(6);

            // Calculate totals
            $subtotal = collect($items)->sum('total');
            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            // Create order
            $order = RestaurantOrder::create([
                'organization_id' => auth()->user()->organization_id,
                'branch_id' => auth()->user()->branch_id ?? $data['branch_id'],
                'table_id' => $data['table_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'order_type' => $data['order_type'] ?? 'dine_in',
                'status' => 'pending',
                'guest_count' => $data['guest_count'] ?? 1,
                'special_instructions' => $data['special_instructions'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'ordered_at' => now(),
            ]);

            // Create order items
            foreach ($items as $item) {
                RestaurantOrderItem::create([
                    'restaurant_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'prepared_quantity' => 0,
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $item['total'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                    'status' => 'pending',
                ]);
            }

            // Update table status
            RestaurantTable::where('id', $data['table_id'])->update(['status' => 'occupied']);

            // Send KOT to kitchen
            app(KOTService::class)->sendKOT($order);

            return $order->load('items.product', 'table');
        });
    }

    public function updateStatus(RestaurantOrder $order, $status)
    {
        $order->update(['status' => $status]);

        if ($status === 'preparing') {
            $order->update(['prepared_at' => now()]);
        }

        if ($status === 'served') {
            $order->update(['served_at' => now()]);
        }

        if ($status === 'completed' || $status === 'cancelled') {
            // Free the table
            RestaurantTable::where('id', $order->table_id)->update(['status' => 'available']);
        }

        return $order;
    }

    public function convertToSale(RestaurantOrder $order)
    {
        return DB::transaction(function () use ($order) {
            if (!$order->canConvertToSale()) {
                throw new \Exception('Order cannot be converted to sale.');
            }

            // Create sale from order
            $sale = Sale::create([
                'organization_id' => $order->organization_id,
                'branch_id' => $order->branch_id,
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'invoice_no' => 'INV-' . date('Ymd') . '-' . Str::random(6),
                'date' => now(),
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'tax' => $order->tax,
                'total' => $order->total,
                'paid_amount' => 0,
                'due_amount' => $order->total,
                'payment_status' => 'unpaid',
                'payment_method' => 'cash',
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            // Create sale lines and deduct stock
            foreach ($order->items as $orderItem) {
                SaleLine::create([
                    'sale_id' => $sale->id,
                    'product_id' => $orderItem->product_id,
                    'product_variant_id' => $orderItem->product_variant_id,
                    'warehouse_id' => 1, // default warehouse
                    'quantity' => $orderItem->quantity,
                    'price' => $orderItem->price,
                    'discount' => $orderItem->discount,
                    'tax' => $orderItem->tax,
                    'total' => $orderItem->total,
                ]);

                // Deduct stock
                $this->stockService->removeStock(
                    $orderItem->product_id,
                    1, // default warehouse
                    $orderItem->quantity,
                    'restaurant_sale',
                    $sale->id,
                    $orderItem->product_variant_id,
                    'Restaurant Order #' . $order->order_number
                );
            }

            // Update order
            $order->update(['sale_id' => $sale->id, 'status' => 'completed']);

            // Free table
            RestaurantTable::where('id', $order->table_id)->update(['status' => 'available']);

            return $sale;
        });
    }
} 
