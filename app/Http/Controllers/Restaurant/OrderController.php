<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RestaurantOrder;
use App\Models\RestaurantTable;
use App\Services\Restaurant\OrderService;
use App\Services\Restaurant\KOTService;  // ✅ import KOTService
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    protected $kotService;  // ✅ add property

    public function __construct(OrderService $orderService, KOTService $kotService)  // ✅ inject both
    {
        $this->orderService = $orderService;
        $this->kotService = $kotService;
    }

    public function index()
    {
        $orders = RestaurantOrder::with('table', 'items.product', 'user')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('restaurant.orders.index', compact('orders'));
    }

    public function active()
    {
        $orders = RestaurantOrder::with('table', 'items.product', 'user')
            ->where('organization_id', auth()->user()->organization_id)
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->orderBy('created_at')
            ->get();

        return view('restaurant.orders.active', compact('orders'));
    }

    public function create(Request $request)
    {
        $tableId = $request->table_id;
        $tables = RestaurantTable::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        $products = Product::where('organization_id', auth()->user()->organization_id)
            ->where('status', 'active')
            ->with('category')
            ->get();

        return view('restaurant.orders.create', compact('tables', 'products', 'tableId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'guest_count' => 'nullable|integer|min:1',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        try {
            $order = $this->orderService->createOrder($validated, $validated['items']);
            return redirect()->route('restaurant.orders.show', $order)->with('success', 'Order created!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(RestaurantOrder $order)
    {
        $order->load('items.product', 'table', 'user', 'kotLogs');
        return view('restaurant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, RestaurantOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
        ]);

        $this->orderService->updateStatus($order, $validated['status']);
        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function convertToSale(RestaurantOrder $order)
    {
        try {
            $sale = $this->orderService->convertToSale($order);
            return redirect()->route('sales.show', $sale)->with('success', 'Order converted to sale!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function kitchen()
{
    $kots = $this->kotService->getPendingKOTs();
    return view('restaurant.kitchen', compact('kots'));
}

}