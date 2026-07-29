<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Services\Restaurant\QRService;
use Illuminate\Http\Request;

class QRController extends Controller
{
    protected $qrService;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function menu($tableId)
    {
        // This is a public endpoint, no auth required
        $data = $this->qrService->getMenuData($tableId);

        return view('restaurant.public.menu', [
            'table' => $data['table'],
            'products' => $data['products'],
        ]);
    }

    public function placeOrder(Request $request, $tableId)
    {
        // Public endpoint for QR ordering
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'guest_name' => 'nullable|string|max:255',
        ]);

        // TODO: Create order from QR order
        // For now, just return success
        return response()->json(['success' => true, 'message' => 'Order placed!']);
    }
}