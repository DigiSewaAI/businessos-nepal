@if(request('q'))
    <div class="text-sm text-gray-500 mb-4" id="resultCount">
        Found <span class="font-semibold text-gray-700">{{ $products->total() }}</span> results for "<span class="font-semibold text-gray-700">{{ request('q') }}</span>"
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $product->sku ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->brand->name ?? $product->brand_name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold {{ $product->current_stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->current_stock }}
                            </span>
                            @if(isset($product->alert_quantity) && $product->alert_quantity && $product->current_stock <= $product->alert_quantity && $product->current_stock > 0)
                                <span class="ml-1 text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded-full">Low</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">Rs. {{ number_format($product->sale_price ?? 0, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $product->current_stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->current_stock > 0 ? '✅ In Stock' : '❌ Out of Stock' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            @if(request('q'))
                                No products found for "<strong>{{ request('q') }}</strong>".
                                <br><span class="text-sm">Try checking the spelling or use a different keyword.</span>
                            @else
                                Enter a search term above to find products.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
