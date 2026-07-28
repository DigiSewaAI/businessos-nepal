<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Show POS interface
     */
    public function pos()
    {
        $products = Product::with('variants', 'unit')
            ->where('status', 'active')
            ->get();

        $customers = Customer::orderBy('name')->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('sales.pos', compact('products', 'customers', 'warehouses'));
    }

    /**
     * Store a new sale
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'discount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,card,bank,mobile,credit',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        try {
            $sale = $this->saleService->createSale($validated, $validated['items']);

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'invoice_no' => $sale->invoice_no,
                'sale' => $sale,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show sale details
     */
    public function show(Sale $sale)
    {
        $sale->load('lines.product', 'lines.variant', 'customer', 'user');
        return view('sales.show', compact('sale'));
    }

    /**
     * List sales (index)
     */
    public function index()
    {
        $sales = Sale::with('customer', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('sales.index', compact('sales'));
    }
}