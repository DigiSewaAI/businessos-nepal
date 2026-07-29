<?php

namespace App\Services\Restaurant;

use App\Models\Product;  // ✅ import Product
use App\Models\RestaurantTable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRService
{
    public function generateQR(RestaurantTable $table)
    {
        $url = route('restaurant.menu', ['table' => $table->id]);
        $qrCode = QrCode::size(200)->generate($url);

        // Save QR code image
        $filename = 'qr_' . $table->id . '_' . time() . '.svg';
        $path = storage_path('app/public/qr/' . $filename);
        file_put_contents($path, $qrCode);

        $table->update(['qr_code' => $filename]);
        return $table;
    }

    public function getMenuData($tableId)
    {
        $table = RestaurantTable::with('branch')->findOrFail($tableId);
        $products = Product::where('organization_id', auth()->user()->organization_id)
            ->where('status', 'active')
            ->with('category')
            ->get();

        return [
            'table' => $table,
            'products' => $products,
        ];
    }
}