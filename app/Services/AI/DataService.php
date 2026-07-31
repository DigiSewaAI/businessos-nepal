<?php
namespace App\Services\AI;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\School\Attendance;
use App\Models\School\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataService
{
    public function getAnswer($message)
{
    $lower = strtolower($message);

    // ─── 1. ATTENDANCE ──────────────────────────────────────────────
    if (stripos($lower, 'attendance') !== false || 
        stripos($lower, 'attendence') !== false || 
        stripos($lower, 'student') !== false) {
        return $this->getAttendanceResponse();
    }

    // ─── 2. SALES ────────────────────────────────────────────────────
    // ✅ More robust sales detection
    $salesKeywords = ['sales', 'sale', 'today', 'bikri', 'bikri', 'revenue', 'income'];
    foreach ($salesKeywords as $keyword) {
        if (stripos($lower, $keyword) !== false) {
            return $this->getSalesResponse();
        }
    }

    // ─── 3. PROFIT / FINANCIAL ─────────────────────────────────────
    $financialKeywords = ['profit', 'laabh', 'loss', 'expense', 'kharcha', 'laabha'];
    foreach ($financialKeywords as $keyword) {
        if (stripos($lower, $keyword) !== false) {
            return $this->getProfitResponse();
        }
    }

    // ─── 4. STOCK / INVENTORY ──────────────────────────────────────
    // ✅ More robust stock detection
    $stockKeywords = ['stock', 'inventory', 'quantity', 'available', 'have', 'got', 'low', 'alert', 'item', 'product'];
    foreach ($stockKeywords as $keyword) {
        if (stripos($lower, $keyword) !== false) {
            return $this->getStockResponse($message);
        }
    }

    // ─── 5. Short messages (<=5 words) → treat as stock query ──
    $words = array_filter(explode(' ', $lower));
    if (count($words) <= 5) {
        return $this->getStockResponse($message);
    }

    // ─── 6. NOTHING MATCHED ────────────────────────────────────────
    return null;
}

    // ================================================================
    // STOCK
    // ================================================================

    protected function getStockResponse($message)
    {
        $orgId = auth()->user()->organization_id;
        $lower = strtolower($message);

        // ── Special: "low stock" ──
        if (stripos($lower, 'low stock') !== false || 
            stripos($lower, 'low') !== false || 
            stripos($lower, 'alert') !== false) {
            return $this->getLowStockResponse($orgId);
        }

        $productName = $this->extractProductName($message);

        try {
            $allProducts = Product::where('organization_id', $orgId)->get();
            if ($allProducts->isEmpty()) {
                return "📦 No products found in your inventory.";
            }

            // ── "stock" or empty → ALL products ──
            if (empty($productName) || 
                strtolower(trim($message)) === 'stock' || 
                strtolower(trim($message)) === 'total stock') {
                return $this->getAllProductsStock($allProducts);
            }

            $normalizedQuery = $this->normalizeName($productName);

            // Brand match
            $brandMatches = $this->findByBrand($allProducts, $normalizedQuery);
            if (!empty($brandMatches)) {
                return $this->formatVariantList($brandMatches, $productName);
            }

            // Product name match
            $productMatches = $this->findByProductName($allProducts, $normalizedQuery);
            if (!empty($productMatches)) {
                if (count($productMatches) > 1) {
                    return $this->formatVariantList($productMatches, $productName);
                }
                return $this->formatSingleProduct($productMatches[0]);
            }

            // Suggestions
            $suggestions = $this->getSuggestions($allProducts, $normalizedQuery);
            $suggestionText = !empty($suggestions)
                ? "\n\n💡 Did you mean: " . implode(', ', array_slice($suggestions, 0, 5))
                : "";
            return "❌ No product found for '{$productName}'.{$suggestionText}";

        } catch (\Exception $e) {
            Log::error('Stock query error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch stock information at this time.";
        }
    }

    protected function getLowStockResponse($orgId)
    {
        try {
            $products = Product::where('organization_id', $orgId)->get();
            $lowStockItems = [];

            foreach ($products as $product) {
                $stock = $this->getProductStock($product->id);
                $alertQty = $product->alert_quantity ?? 0;
                if ($stock <= $alertQty && $stock > 0) {
                    $lowStockItems[] = [
                        'name'  => $product->name,
                        'stock' => $stock,
                        'alert' => $alertQty,
                        'price' => $product->sale_price ?? 0,
                    ];
                }
            }

            if (empty($lowStockItems)) {
                return "✅ **All products are above their low-stock thresholds.**\nNo low-stock items found.";
            }

            $response = "⚠️ **Low Stock Items**\n\n";
            $response .= "| # | Product | Stock | Alert Level | Price |\n";
            $response .= "|---|---------|-------|-------------|-------|\n";
            $i = 1;
            foreach ($lowStockItems as $item) {
                $response .= sprintf(
                    "| %d | %s | %d | %d | Rs. %.2f |\n",
                    $i++,
                    $item['name'],
                    $item['stock'],
                    $item['alert'],
                    $item['price']
                );
            }
            return $response;

        } catch (\Exception $e) {
            Log::error('Low stock error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch low stock information at this time.";
        }
    }

    protected function getAllProductsStock($products)
    {
        $response = "📦 **Complete Stock List**\n\n";
        $response .= "| SN | Product Name | Brand | Stock | Price |\n";
        $response .= "|----|--------------|-------|-------|-------|\n";
        $i = 1;
        foreach ($products as $product) {
            $stock = $this->getProductStock($product->id);
            $status = $stock > 0 ? '✅' : '❌';
            $price = $product->sale_price ?? 0;
            $brand = $this->extractBrandName($product->name);
            $response .= sprintf(
                "| %d | %s | %s | %s %d units | Rs. %.2f |\n",
                $i++,
                $product->name,
                $brand,
                $status,
                $stock,
                $price
            );
        }
        return $response;
    }

    protected function formatSingleProduct($product)
    {
        $stock = $this->getProductStock($product->id);
        $alertQty = $product->alert_quantity ?? 0;
        $status = $stock <= $alertQty && $stock > 0 ? '⚠️ Low Stock' : ($stock > 0 ? '✅ In Stock' : '❌ Out of Stock');
        $price = $product->sale_price ?? 0;
        $brand = $this->extractBrandName($product->name);

        return "📦 **Stock Detail**\n\n" .
               "Product: **{$product->name}**\n" .
               "Brand: {$brand}\n" .
               "SKU: {$product->sku}\n" .
               "Stock: **{$stock}** units\n" .
               "Price: Rs. " . number_format($price, 2) . "\n" .
               "Status: {$status}";
    }

    protected function formatVariantList($products, $searchTerm)
    {
        $response = "📦 **Products matching '{$searchTerm}'**\n\n";
        $response .= "| # | Product Name | Brand | Stock | Price |\n";
        $response .= "|---|--------------|-------|-------|-------|\n";
        $i = 1;
        foreach ($products as $product) {
            $stock = $this->getProductStock($product->id);
            $status = $stock > 0 ? '✅' : '❌';
            $price = $product->sale_price ?? 0;
            $brand = $this->extractBrandName($product->name);
            $response .= sprintf(
                "| %d | %s | %s | %s %d units | Rs. %.2f |\n",
                $i++,
                $product->name,
                $brand,
                $status,
                $stock,
                $price
            );
        }
        return $response;
    }

    protected function getProductStock($productId)
    {
        $movements = StockMovement::where('product_id', $productId)
            ->select(DB::raw('
                SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) 
                - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) 
                as stock
            '))
            ->first();
        return $movements ? (int) $movements->stock : 0;
    }

    protected function findByBrand($products, $query)
    {
        $results = [];
        foreach ($products as $product) {
            $brandName = $this->extractBrandName($product->name);
            $normalizedBrand = $this->normalizeName($brandName);
            similar_text($query, $normalizedBrand, $percent);
            if ($percent > 60) {
                $results[] = $product;
            }
        }
        return $results;
    }

    protected function findByProductName($products, $query)
    {
        $results = [];
        foreach ($products as $product) {
            $normalizedProduct = $this->normalizeName($product->name);
            similar_text($query, $normalizedProduct, $percent);
            if ($percent > 70) {
                $results[] = $product;
            }
        }
        return $results;
    }

    protected function getSuggestions($products, $query)
    {
        $suggestions = [];
        foreach ($products as $product) {
            $normalizedProduct = $this->normalizeName($product->name);
            similar_text($query, $normalizedProduct, $percent);
            if ($percent > 30 && $percent < 70) {
                $suggestions[] = $product->name;
            }
            if (count($suggestions) >= 5) break;
        }
        return $suggestions;
    }

    protected function extractBrandName($name)
    {
        $cleaned = preg_replace('/\s*\([^)]*\)/', '', $name);
        $cleaned = preg_replace('/\s*\d+(kg|g|ml|l|gm|pcs|pc)\b/i', '', $cleaned);
        $cleaned = preg_replace('/\s*-\s*$/', '', $cleaned);
        return trim($cleaned) ?: 'General';
    }

    protected function extractProductName($message)
    {
        $removeWords = [
            'stock', 'inventory', 'quantity', 'available', 'tell', 'me', 'the',
            'of', 'in', 'my', 'store', 'how', 'much', 'please', 'pls', 'can',
            'you', 'i', 'want', 'know', 'ko', 'have', 'do', 'we', 'for', 'is',
            'are', 'what', 'price', 'cost', 'give', 'show', 'find', 'search',
            'list', 'all', 'left', 'remaining', '?', '!', '.', ',', ';', ':'
        ];
        $cleaned = str_replace(['?', '!', '.', ',', ';', ':'], ' ', $message);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        $words = explode(' ', trim($cleaned));
        $filtered = array_filter($words, function($w) use ($removeWords) {
            $w = trim($w, '()[]{}');
            return !in_array(strtolower($w), $removeWords) && strlen($w) > 1;
        });
        return trim(implode(' ', $filtered));
    }

    private function normalizeName($name)
    {
        $name = preg_replace('/[^\w\s]/u', ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return trim(strtolower($name));
    }

    // ================================================================
    // SALES
    // ================================================================

    protected function getSalesResponse()
    {
        try {
            $orgId = auth()->user()->organization_id;

            $todaySales = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total');

            $todayOrders = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->count();

            if ($todaySales == 0 && $todayOrders == 0) {
                return "📊 **Today's Sales Report**\n\n" .
                       "📅 " . now()->format('d M Y') . "\n" .
                       "💰 **Total Sales**: Rs. 0.00\n" .
                       "📦 **Orders**: 0\n\n" .
                       "💡 *No sales recorded today yet. Start selling!*";
            }

            $lastWeekSales = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->where('status', 'completed')
                ->sum('total');

            $growth = $lastWeekSales > 0 ? round(($todaySales / $lastWeekSales) * 100, 1) : 0;

            return "📊 **Today's Sales Report**\n\n" .
                   "📅 " . now()->format('d M Y') . "\n" .
                   "💰 **Total Sales**: Rs. " . number_format($todaySales, 2) . "\n" .
                   "📦 **Orders**: {$todayOrders} completed orders\n" .
                   "📈 **Last 7 Days**: Rs. " . number_format($lastWeekSales, 2) . "\n" .
                   "📊 **Growth**: {$growth}% vs last week\n\n" .
                   "📌 *Calculation: " . number_format($todaySales, 2) . " from {$todayOrders} orders.*";

        } catch (\Exception $e) {
            Log::error('Sales data error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch sales data at this time.";
        }
    }

    // ================================================================
    // PROFIT
    // ================================================================

    protected function getProfitResponse()
    {
        try {
            $orgId = auth()->user()->organization_id;

            $sales = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total');

            $expenses = Expense::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->sum('amount');

            $profit = $sales - $expenses;
            $emoji = $profit >= 0 ? '📈' : '📉';

            return "{$emoji} **Today's Profit Report**\n\n" .
                   "💵 **Profit**: Rs. " . number_format($profit, 2) . "\n" .
                   "💰 **Sales**: Rs. " . number_format($sales, 2) . " (Revenue)\n" .
                   "💸 **Expenses**: Rs. " . number_format($expenses, 2) . " (Costs)\n\n" .
                   "📌 *Calculation: Sales (" . number_format($sales, 2) . ") - Expenses (" . number_format($expenses, 2) . ") = Profit (" . number_format($profit, 2) . ")*";

        } catch (\Exception $e) {
            Log::error('Profit data error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch profit data at this time.";
        }
    }

    // ================================================================
    // ATTENDANCE
    // ================================================================

    protected function getAttendanceResponse()
    {
        try {
            $orgId = auth()->user()->organization_id;
            $today = now()->toDateString();

            $present = Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'present')
                ->count();

            $absent = Attendance::where('organization_id', $orgId)
                ->where('date', $today)
                ->where('status', 'absent')
                ->count();

            $total = Student::where('organization_id', $orgId)->count();

            return "🎓 **Attendance Summary**\n\n" .
                   "📅 Date: " . now()->format('d M Y') . "\n" .
                   "✅ Present: {$present}\n" .
                   "❌ Absent: {$absent}\n" .
                   "👨‍🎓 Total Students: {$total}\n\n" .
                   "📌 *Attendance Rate: " . ($total > 0 ? round(($present / $total) * 100, 1) : 0) . "%*";

        } catch (\Exception $e) {
            Log::error('Attendance query error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch attendance data at this time.";
        }
    }
}