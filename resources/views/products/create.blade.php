@extends('layouts.admin')

@section('title', 'Add Product - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
            <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fa-regular fa-arrow-left mr-1"></i> Back to Products
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500 @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500 @error('sku') border-red-500 @enderror" 
                               placeholder="Auto-generate if left blank">
                        @error('sku')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <select name="brand_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Warehouse --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse</label>
                        <select name="warehouse_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Purchase Price --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Price (Rs.)</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500" 
                               step="0.01" min="0">
                    </div>

                    {{-- Sale Price --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price (Rs.) *</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500 @error('sale_price') border-red-500 @enderror" 
                               step="0.01" min="0" required>
                        @error('sale_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alert Quantity --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alert Quantity</label>
                        <input type="number" name="alert_quantity" value="{{ old('alert_quantity', 5) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500" 
                               min="0">
                    </div>

                    {{-- Opening Stock --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opening Stock</label>
                        <input type="number" name="opening_stock" value="{{ old('opening_stock', 0) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500" 
                               min="0">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- Product Image --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <input type="file" name="image" accept="image/*" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>

                {{-- Submit --}}
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                        <i class="fa-regular fa-plus mr-1"></i> Create Product
                    </button>
                    <a href="{{ route('products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
