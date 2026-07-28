@extends('layouts.app')

@section('title', 'Stock Report - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Stock Report</h1>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Search -->
        <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex gap-4">
            <input type="text" name="search" placeholder="Search by name or SKU..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-search"></i> Search
            </button>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Alert Qty</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold 
                            @if($product->current_stock <= 0) text-red-600 
                            @elseif($product->current_stock <= $product->alert_quantity) text-yellow-600 
                            @else text-green-600 @endif">
                            {{ $product->current_stock }} {{ $product->unit->symbol ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right">{{ $product->alert_quantity }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($product->current_stock <= 0)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Out of Stock</span>
                            @elseif($product->current_stock <= $product->alert_quantity)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Low Stock</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-box text-3xl mb-2 block"></i>
                            No products found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection