@extends('layouts.admin')

@section('title', 'Table Layout - Restaurant')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header with AI Button & Search -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Table Layout</h1>
            <div class="flex flex-wrap gap-3">
                <!-- ✅ NEW: Search Products Button -->
                <a href="{{ route('products.search') }}" 
                   class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-200 transition flex items-center gap-2">
                    <i class="fa-solid fa-search"></i> Search Products
                </a>
                <!-- ✅ Existing: AI Assistant Button -->
                <a href="{{ route('ai.chat') }}?message={{ urlencode('Show me active restaurant orders and table status') }}" 
                   class="bg-teal-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-600 transition flex items-center gap-2">
                    <i class="fa-regular fa-comment-dots"></i> AI Assistant
                </a>
                <a href="{{ route('restaurant.tables.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-plus"></i> Add Table
                </a>
                <a href="{{ route('restaurant.orders.active') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-clock"></i> Active Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($layout as $area => $tables)
                @foreach($tables as $table)
                    <div class="bg-white rounded-2xl shadow-sm border 
                        {{ $table->status == 'available' ? 'border-green-300 bg-green-50' : '' }}
                        {{ $table->status == 'occupied' ? 'border-red-300 bg-red-50' : '' }}
                        {{ $table->status == 'reserved' ? 'border-yellow-300 bg-yellow-50' : '' }}
                        {{ $table->status == 'unavailable' ? 'border-gray-300 bg-gray-100' : '' }}
                        p-4 text-center">
                        <div class="text-3xl mb-2">🍽️</div>
                        <div class="font-bold text-lg">{{ $table->number }}</div>
                        <div class="text-sm text-gray-500">Capacity: {{ $table->capacity }}</div>
                        <div class="text-xs mt-2 font-semibold">
                            @if($table->status == 'available')
                                <span class="text-green-600">✅ Available</span>
                            @elseif($table->status == 'occupied')
                                <span class="text-red-600">🔴 Occupied</span>
                            @elseif($table->status == 'reserved')
                                <span class="text-yellow-600">🟡 Reserved</span>
                            @else
                                <span class="text-gray-400">⚫ Unavailable</span>
                            @endif
                        </div>
                        <div class="mt-3 flex flex-col gap-1">
                            @if($table->status == 'available')
                                <a href="{{ route('restaurant.orders.create', ['table_id' => $table->id]) }}" 
                                   class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">
                                    <i class="fa-solid fa-plus"></i> New Order
                                </a>
                            @endif
                            @if($table->activeOrder())
                                <a href="{{ route('restaurant.orders.show', $table->activeOrder()) }}" 
                                   class="text-sm bg-orange-500 text-white px-3 py-1 rounded-lg hover:bg-orange-600">
                                    <i class="fa-solid fa-eye"></i> View Order
                                </a>
                            @endif
                            <a href="{{ route('restaurant.tables.edit', $table) }}" 
                               class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-300">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection
