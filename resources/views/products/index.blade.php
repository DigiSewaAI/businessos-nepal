@extends('layouts.app')

@section('title', 'Products - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-sm text-gray-500">Manage your product inventory</p>
            </div>
            <a href="{{ route('products.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-semibold transition shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fa-regular fa-plus"></i> Add Product
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Product</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">SKU</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Category</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Stock</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->sku }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-medium">Rs. {{ number_format($product->sale_price, 2) }}</td>
                            <td class="px-4 py-3 text-right {{ ($product->current_stock ?? 0) <= ($product->alert_quantity ?? 0) ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                {{ $product->current_stock ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this product?')">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fa-regular fa-box text-3xl block mb-2"></i>
                                No products found.
                                <a href="{{ route('products.create') }}" class="text-teal-600 hover:underline block mt-2">Add your first product</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection