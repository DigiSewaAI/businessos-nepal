<?php
namespace App\Services\AI;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataService
{
    public function getAnswer($message)
    {
        $lower = strtolower($message);

        $stockKeywords = ['stock', 'inventory', 'quantity', 'available', 'have', 'got'];
        $isStockQuery = false;
        
        foreach ($stockKeywords as $keyword) {
            if (stripos($lower, $keyword) !== false) {
                $isStockQuery = true;
                break;
            }
        }
        
        $words = array_filter(explode(' ', $lower));
        if (count($words) <= 5 && !$isStockQuery) {
            $isStockQuery = true;
        }

        if ($isStockQuery) {
            return $this->getStockResponse($message);
        }

        if (stripos($lower, 'sales') !== false || stripos($lower, 'sale') !== false || stripos($lower, 'today') !== false) {
            return $this->getSalesResponse();
        }

        if (stripos($lower, 'profit') !== false) {
            return $this->getProfitResponse();
        }

        return null;
    }

    protected function getStockResponse($message)
    {
        $orgId = auth()->user()->organization_id;
        $productName = $this->extractProductName($message);

        try {
            $allProducts = Product::where('organization_id', $orgId)->get();
            if ($allProducts->isEmpty()) {
                return "📦 No products found in your inventory.";
            }

            // --- "stock" only → show all products ---
            if (empty($productName) || strtolower(trim($message)) === 'stock') {
                return $this->getAllProductsStock($allProducts);
            }

            // --- Extract base brand/product name (e.g., "coca cola" → "Coca-Cola") ---
            $normalizedQuery = $this->normalizeName($productName);
            
            // --- Step 1: EXACT brand match (e.g., "coca cola" → "Coca-Cola" variants) ---
            $brandMatches = $this->findByBrand($allProducts, $normalizedQuery);
            if (!empty($brandMatches)) {
                return $this->formatVariantList($brandMatches, $productName);
            }

            // --- Step 2: Product name matches (e.g., "Rice (1kg)" → exact product) ---
            $productMatches = $this->findByProductName($allProducts, $normalizedQuery);
            if (!empty($productMatches)) {
                // If more than 1 match, show all variants
                if (count($productMatches) > 1) {
                    return $this->formatVariantList($productMatches, $productName);
                }
                // Single product → show detail
                return $this->formatSingleProduct($productMatches[0]);
            }

            // --- Step 3: Fuzzy fallback (suggestions) ---
            $suggestions = $this->getSuggestions($allProducts, $normalizedQuery);
            $suggestionText = !empty($suggestions) ? "\n\n💡 Did you mean: " . implode(', ', array_slice($suggestions, 0, 5)) : "";
            return "❌ No product found for '{$productName}'.{$suggestionText}";

        } catch (\Exception $e) {
            Log::error('Stock query error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch stock information at this time.";
        }
    }

    /**
     * Find all products belonging to a brand
     */
    protected function findByBrand($products, $query)
    {
        $results = [];
        foreach ($products as $product) {
            $brandName = $this->extractBrandName($product->name);
            $normalizedBrand = $this->normalizeName($brandName);
            
            // Check if query matches brand
            if (strpos($normalizedBrand, $query) !== false || strpos($query, $normalizedBrand) !== false) {
                // Check similarity
                similar_text($query, $normalizedBrand, $percent);
                if ($percent > 60) {
                    $results[] = $product;
                }
            }
        }
        return $results;
    }

    /**
     * Find products by name (exact or close match)
     */
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

    /**
     * Get suggestions for unclear queries
     */
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

    /**
     * Format single product stock detail
     */
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

    /**
     * Format list of product variants
     */
    protected function formatVariantList($products, $searchTerm)
    {
        $response = "📦 **Products matching '{$searchTerm}'**\n\n";
        $response .= "| # | Product Name | Brand | Stock | Price |\n";
        $response .= "|---|--------------|-------|-------|-------|\n";
        
        $index = 1;
        foreach ($products as $product) {
            $stock = $this->getProductStock($product->id);
            $status = $stock > 0 ? '✅' : '❌';
            $price = $product->sale_price ?? 0;
            $brand = $this->extractBrandName($product->name);
            
            $response .= "| {$index} | {$product->name} | {$brand} | {$status} {$stock} units | Rs. " . number_format($price, 2) . " |\n";
            $index++;
        }
        return $response;
    }

    /**
     * Show all products with SN, Checkmark, Price
     */
    protected function getAllProductsStock($products)
    {
        $response = "📦 **Complete Stock List**\n\n";
        $response .= "| SN | Product Name | Brand | Stock | Price |\n";
        $response .= "|----|--------------|-------|-------|-------|\n";
        
        $index = 1;
        foreach ($products as $product) {
            $stock = $this->getProductStock($product->id);
            $status = $stock > 0 ? '✅' : '❌';
            $price = $product->sale_price ?? 0;
            $brand = $this->extractBrandName($product->name);
            
            $response .= "| {$index} | {$product->name} | {$brand} | {$status} {$stock} units | Rs. " . number_format($price, 2) . " |\n";
            $index++;
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

    /**
     * Extract brand name from product name (e.g., "Coca-Cola (500ml)" → "Coca-Cola")
     */
    protected function extractBrandName($name)
    {
        // Remove parentheses content
        $cleaned = preg_replace('/\s*\([^)]*\)/', '', $name);
        // Remove size/weight (e.g., "500ml", "1kg")
        $cleaned = preg_replace('/\s*\d+(kg|g|ml|l|gm|pcs|pc)\b/i', '', $cleaned);
        // Remove trailing " - " or extra spaces
        $cleaned = preg_replace('/\s*-\s*$/', '', $cleaned);
        return trim($cleaned) ?: 'General';
    }

    /**
     * ✅ UPDATED: Explainability with breakdown & 7-day comparison
     */
    protected function getSalesResponse()
    {
        try {
            $orgId = auth()->user()->organization_id;
            
            // Today's sales
            $todaySales = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->sum('total');
            
            $todayOrders = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->where('status', 'completed')
                ->count();
            
            // Last 7 days sales
            $lastWeekSales = Sale::where('organization_id', $orgId)
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->where('status', 'completed')
                ->sum('total');
            
            // Growth percentage
            $growth = $lastWeekSales > 0 ? round(($todaySales / $lastWeekSales) * 100, 1) : 0;
            
            // ✅ Explainability: Breakdown with calculation details
            return "📊 **Today's Sales Report**\n\n" .
                   "**Total:** Rs. " . number_format($todaySales, 2) . "\n" .
                   "**Orders:** {$todayOrders} completed orders\n" .
                   "**Last 7 Days:** Rs. " . number_format($lastWeekSales, 2) . "\n" .
                   "**Growth:** {$growth}% vs last week\n\n" .
                   "📌 *Calculation: Today's sales = " . number_format($todaySales, 2) . 
                   " from {$todayOrders} orders. Last 7 days = " . number_format($lastWeekSales, 2) . ".*";
                   
        } catch (\Exception $e) {
            Log::error('Sales data error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch sales data at this time.";
        }
    }

    /**
     * ✅ UPDATED: Explainability with breakdown & calculation source
     */
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
            
            // ✅ Explainability: Full breakdown
            return "{$emoji} **Today's Profit Report**\n\n" .
                   "**Profit:** Rs. " . number_format($profit, 2) . "\n" .
                   "**Sales:** Rs. " . number_format($sales, 2) . " (Revenue)\n" .
                   "**Expenses:** Rs. " . number_format($expenses, 2) . " (Costs)\n\n" .
                   "📌 *Calculation: Sales ({$sales}) - Expenses ({$expenses}) = Profit ({$profit})*";
                   
        } catch (\Exception $e) {
            Log::error('Profit data error: ' . $e->getMessage());
            return "I'm sorry, I couldn't fetch profit data at this time.";
        }
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
}