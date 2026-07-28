<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index()
    {
        $purchases = Purchase::with('supplier', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('variants')->where('status', 'active')->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('purchases.create', compact('suppliers', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'invoice_no' => 'nullable|string|max:255',
            'expected_date' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        try {
            $purchase = $this->purchaseService->createPurchase($validated, $validated['items']);

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase Order #' . $purchase->po_no . ' created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('lines.product', 'lines.variant', 'supplier', 'user', 'lines.warehouse');
        return view('purchases.show', compact('purchase'));
    }

    public function receive(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.line_id' => 'required|exists:purchase_lines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->purchaseService->receivePurchase($purchase->id, $validated['items']);

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Stock received successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}