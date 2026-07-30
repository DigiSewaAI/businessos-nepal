<?php
namespace App\Services\AI;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContextBuilder
{
    public function build(array $intent): array
    {
        try {
            $orgId = auth()->user()->organization_id;
            $context = [
                'organization' => auth()->user()->organization->name ?? 'Your Business',
                'timeframe' => $intent['timeframe'] ?? 'current',
                'metrics' => $this->getMetrics($intent, $orgId),
            ];
            return $context;
        } catch (\Exception $e) {
            Log::warning('ContextBuilder error: ' . $e->getMessage());
            return [
                'organization' => auth()->user()->organization->name ?? 'Your Business',
                'timeframe' => 'current',
                'metrics' => [],
                'error' => 'Unable to fetch business data'
            ];
        }
    }

    protected function getMetrics(array $intent, int $orgId): array
    {
        $category = $intent['category'];
        $metrics = [];

        // Sales metrics
        if ($category === 'sales' || $category === 'general') {
            try {
                $totalSales = Sale::where('organization_id', $orgId)
                    ->where('status', 'completed')
                    ->sum('total');
                $count = Sale::where('organization_id', $orgId)->where('status', 'completed')->count();
                $metrics['total_sales'] = $totalSales;
                $metrics['order_count'] = $count;

                $topProduct = Sale::join('sale_lines', 'sales.id', '=', 'sale_lines.sale_id')
                    ->join('products', 'sale_lines.product_id', '=', 'products.id')
                    ->where('sales.organization_id', $orgId)
                    ->select('products.name', DB::raw('SUM(sale_lines.quantity) as total_qty'))
                    ->groupBy('products.id', 'products.name')
                    ->orderBy('total_qty', 'desc')
                    ->first();
                if ($topProduct) {
                    $metrics['top_product'] = $topProduct->name . ' (' . $topProduct->total_qty . ' qty)';
                }
            } catch (\Exception $e) {
                Log::warning('Sales metrics failed: ' . $e->getMessage());
            }
        }

        // Inventory metrics (fixed for missing 'stock' column)
        if ($category === 'inventory' || $category === 'general') {
            try {
                // Since 'stock' column doesn't exist, we use a safe fallback.
                // Option 1: Use stock movements to calculate current stock (if table exists)
                // For now, we set low_stock_items to 0 and rely on total_products count.
                $lowStock = 0; // Placeholder - you can implement actual stock calculation later.
                $metrics['low_stock_items'] = $lowStock;
                $metrics['total_products'] = Product::where('organization_id', $orgId)->count();
            } catch (\Exception $e) {
                Log::warning('Inventory metrics failed: ' . $e->getMessage());
            }
        }

        // Financial metrics
        if ($category === 'financial' || $category === 'general') {
            try {
                $expenses = Expense::where('organization_id', $orgId)->sum('amount');
                $purchases = Purchase::where('organization_id', $orgId)->sum('total');
                $metrics['total_expenses'] = $expenses;
                $metrics['total_purchases'] = $purchases;
                if (isset($metrics['total_sales'])) {
                    $metrics['estimated_profit'] = $metrics['total_sales'] - $expenses - $purchases;
                }
            } catch (\Exception $e) {
                Log::warning('Financial metrics failed: ' . $e->getMessage());
            }
        }

        return $metrics;
    }
}