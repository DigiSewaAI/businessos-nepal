<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::where('organization_id', auth()->user()->organization_id)
            ->with(['category', 'brand', 'unit'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate current stock for each product
        foreach ($products as $product) {
            $stock = StockMovement::where('product_id', $product->id)
                ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock'))
                ->first();
            $product->current_stock = $stock ? (int) $stock->stock : 0;
        }

        return view('products.index', compact('products'));
    }

    /**
     * Show form to create new product
     */
    public function create()
    {
        $categories = Category::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('organization_id', auth()->user()->organization_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories', 'brands', 'units', 'warehouses'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'opening_stock' => 'nullable|integer|min:0',
        ]);

        $orgId = auth()->user()->organization_id;

        // Generate SKU if not provided
        $sku = $validated['sku'] ?? Str::upper(Str::random(6));

        DB::beginTransaction();

        try {
            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'sku' => $sku,
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'unit_id' => $validated['unit_id'],
                'sale_price' => $validated['sale_price'],
                'purchase_price' => $validated['purchase_price'],
                'alert_quantity' => $validated['alert_quantity'] ?? 0,
                'description' => $validated['description'],
                'organization_id' => $orgId,
                'status' => 'active',
            ]);

            // Add opening stock if provided
            if (!empty($validated['opening_stock']) && $validated['opening_stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $validated['warehouse_id'],
                    'type' => 'in',
                    'quantity' => $validated['opening_stock'],
                    'note' => 'Opening stock',
                    'reference_type' => 'opening_stock',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Show product details
     */
    public function show($id)
    {
        $product = Product::where('organization_id', auth()->user()->organization_id)
            ->with(['category', 'brand', 'unit', 'variants'])
            ->findOrFail($id);

        $stock = StockMovement::where('product_id', $product->id)
            ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock'))
            ->first();
        $product->current_stock = $stock ? (int) $stock->stock : 0;

        return view('products.show', compact('product'));
    }

    /**
     * Show form to edit product
     */
    public function edit($id)
    {
        $product = Product::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        $categories = Category::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('organization_id', auth()->user()->organization_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'warehouses'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        // Check if product has sales or stock
        $hasStock = StockMovement::where('product_id', $product->id)->exists();

        if ($hasStock) {
            return back()->with('error', 'Cannot delete product with stock or sales history.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Search products (Public + Authenticated)
     */
    public function search(Request $request)
    {
        // This method already exists in your file — keep it as is
    }
}