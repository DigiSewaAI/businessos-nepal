<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $organizationId = auth()->user()->organization_id;
        
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfDay();

        $sales = Sale::with(['customer', 'user'])
            ->where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalSales = Sale::where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');

        $totalPaid = Sale::where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('paid_amount');

        $totalDue = $totalSales - $totalPaid;

        return view('reports.sales', compact('sales', 'totalSales', 'totalPaid', 'totalDue', 'startDate', 'endDate'));
    }

    /**
     * Stock Report
     */
    public function stock(Request $request)
    {
        $organizationId = auth()->user()->organization_id;
        $search = $request->search;

        $products = Product::with(['category', 'unit'])
            ->where('organization_id', $organizationId)
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            })
            ->paginate(20);

        // Attach current stock to each product
        foreach ($products as $product) {
            $stock = StockMovement::where('product_id', $product->id)
                ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as current_stock'))
                ->first();
            $product->current_stock = $stock->current_stock ?? 0;
        }

        return view('reports.stock', compact('products', 'search'));
    }

    /**
     * Profit & Loss Report
     */
    public function profitLoss(Request $request)
    {
        $organizationId = auth()->user()->organization_id;
        
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfDay();

        // Total Sales
        $totalSales = Sale::where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');

        // Cost of Goods Sold
        $costData = DB::table('sale_lines')
            ->join('products', 'sale_lines.product_id', '=', 'products.id')
            ->join('sales', 'sale_lines.sale_id', '=', 'sales.id')
            ->where('sales.organization_id', $organizationId)
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->where('sales.status', 'completed')
            ->select(DB::raw('SUM(products.purchase_price * sale_lines.quantity) as total_cost'))
            ->first();

        $totalCost = $costData->total_cost ?? 0;

        // Total Expenses
        $totalExpenses = Expense::where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Total Purchases (Phase 3)
        $totalPurchases = Purchase::where('organization_id', $organizationId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total') ?? 0;

        $grossProfit = $totalSales - $totalCost;
        $netProfit = $grossProfit - $totalExpenses;

        return view('reports.profit_loss', compact(
            'totalSales',
            'totalCost',
            'totalExpenses',
            'totalPurchases',
            'grossProfit',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }
}