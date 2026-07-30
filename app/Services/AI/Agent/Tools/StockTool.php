<?php
namespace App\Services\AI\Agent\Tools;

use App\Services\AI\Agent\Contracts\ToolInterface;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockTool implements ToolInterface
{
    public function getName(): string
    {
        return 'stock_lookup';
    }

    public function getDescription(): string
    {
        return 'Get current stock of a product by name or SKU';
    }

    public function getParameters(): array
    {
        return [
            'product_name' => 'string|required',
        ];
    }

    public function execute(array $params): array
    {
        $product = Product::where('name', 'LIKE', "%{$params['product_name']}%")
            ->orWhere('sku', $params['product_name'])
            ->first();

        if (!$product) {
            return ['error' => 'Product not found'];
        }

        $stock = StockMovement::where('product_id', $product->id)
            ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock'))
            ->first();

        return [
            'product' => $product->name,
            'sku' => $product->sku,
            'stock' => $stock->stock ?? 0,
            'alert_quantity' => $product->alert_quantity ?? 0,
        ];
    }
}