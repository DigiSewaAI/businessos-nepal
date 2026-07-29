@extends('layouts.app')

@section('title', 'Active Orders')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Active Orders</h1>
            <div class="flex gap-3">
                <a href="{{ route('restaurant.orders.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                    <i class="fa-solid fa-list"></i> All Orders
                </a>
                <a href="{{ route('restaurant.orders.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-plus"></i> New Order
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm border 
                    {{ $order->status == 'pending' ? 'border-gray-300' : '' }}
                    {{ $order->status == 'preparing' ? 'border-yellow-300 bg-yellow-50' : '' }}
                    {{ $order->status == 'ready' ? 'border-blue-300 bg-blue-50' : '' }}
                    {{ $order->status == 'served' ? 'border-green-300 bg-green-50' : '' }}
                    p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-lg">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">Table: {{ $order->table->number ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Guest: {{ $order->guest_count }}</div>
                            <div class="text-sm font-semibold mt-1">Rs. {{ number_format($order->total, 2) }}</div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                            {{ $order->status == 'pending' ? 'bg-gray-200 text-gray-700' : '' }}
                            {{ $order->status == 'preparing' ? 'bg-yellow-200 text-yellow-700' : '' }}
                            {{ $order->status == 'ready' ? 'bg-blue-200 text-blue-700' : '' }}
                            {{ $order->status == 'served' ? 'bg-green-200 text-green-700' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="mt-3 text-sm">
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->quantity }} × {{ $item->product->name ?? 'Product' }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('restaurant.orders.show', $order) }}" class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        @if($order->status == 'pending')
                            <button onclick="updateStatus({{ $order->id }}, 'preparing')" class="text-sm bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">
                                <i class="fa-solid fa-fire"></i> Start Prep
                            </button>
                        @endif
                        @if($order->status == 'preparing')
                            <button onclick="updateStatus({{ $order->id }}, 'ready')" class="text-sm bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600">
                                <i class="fa-solid fa-check"></i> Mark Ready
                            </button>
                        @endif
                        @if($order->status == 'ready')
                            <button onclick="updateStatus({{ $order->id }}, 'served')" class="text-sm bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600">
                                <i class="fa-solid fa-utensils"></i> Serve
                            </button>
                        @endif
                        @if($order->status == 'served')
                            <form action="{{ route('restaurant.orders.convert', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700">
                                    <i class="fa-solid fa-receipt"></i> Convert to Sale
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-400">No active orders.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function updateStatus(orderId, status) {
        if (confirm('Change status to ' + status + '?')) {
            // Use form or AJAX
            alert('Status update route is ready.');
        }
    }
</script>
@endsection 
