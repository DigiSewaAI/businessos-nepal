<?php

namespace App\Services\Restaurant;

use App\Models\KOTLog;
use App\Models\RestaurantOrder;
use Illuminate\Support\Str;

class KOTService
{
    public function sendKOT(RestaurantOrder $order)
    {
        $order->load('items.product');

        $kot = KOTLog::create([
            'restaurant_order_id' => $order->id,
            'organization_id' => $order->organization_id,
            'kot_number' => 'KOT-' . date('Ymd') . '-' . Str::random(6),
            'items' => $order->items->map(function ($item) {
                return [
                    'product' => $item->product->name,
                    'quantity' => $item->quantity,
                    'special_instructions' => $item->special_instructions,
                ];
            }),
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return $kot;
    }

    public function markAsPrinted(KOTLog $kot)
    {
        $kot->update(['status' => 'printed', 'printed_at' => now()]);
        return $kot;
    }

    public function getPendingKOTs()
    {
        return KOTLog::with('order.table')
            ->where('status', 'sent')
            ->orderBy('created_at')
            ->get();
    }

    public function getKitchenOrders()
    {
        return RestaurantOrder::with('items.product', 'table')
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('ordered_at')
            ->get();
    }
} 
