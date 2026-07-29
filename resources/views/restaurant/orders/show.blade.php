@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('restaurant.orders.active') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <span class="ml-4 px-3 py-1 rounded-full text-sm font-semibold 
                {{ $order->status == 'pending' ? 'bg-gray-200 text-gray-700' : '' }}
                {{ $order->status == 'preparing' ? 'bg-yellow-200 text-yellow-700' : '' }}
                {{ $order->status == 'ready' ? 'bg-blue-200 text-blue-700' : '' }}
                {{ $order->status == 'served' ? 'bg-green-200 text-green-700' : '' }}
                {{ $order->status == 'completed' ? 'bg-purple-200 text-purple-700' : '' }}
                {{ $order->status == 'cancelled' ? 'bg-red-200 text-red-700' : '' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Table</p>
                <p class="font-bold">{{ $order->table->number ?? 'N/A' }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Guest Count</p>
                <p class="font-bold">{{ $order->guest_count }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Ordered At</p>
                <p class="font-bold">{{ $order->ordered_at ? $order->ordered_at->format('d M Y h:i A') : '-' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Order Items</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm">Rs. {{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">Rs. {{ number_format($item->total, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $item->status == 'pending' ? 'bg-gray-100' : '' }}
                                    {{ $item->status == 'preparing' ? 'bg-yellow-100' : '' }}
                                    {{ $item->status == 'ready' ? 'bg-blue-100' : '' }}
                                    {{ $item->status == 'served' ? 'bg-green-100' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-semibold">Subtotal:</td>
                        <td class="px-6 py-3 font-bold">Rs. {{ number_format($order->subtotal, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-semibold">Discount:</td>
                        <td class="px-6 py-3">- Rs. {{ number_format($order->discount, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-semibold">Tax:</td>
                        <td class="px-6 py-3">Rs. {{ number_format($order->tax, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr class="border-t-2 border-gray-300">
                        <td colspan="3" class="px-6 py-3 text-right font-bold text-lg">Total:</td>
                        <td class="px-6 py-3 font-bold text-xl text-blue-600">Rs. {{ number_format($order->total, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->kotLogs->count())
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">KOT Logs</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KOT #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Printed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->kotLogs as $kot)
                            <tr>
                                <td class="px-6 py-3 text-sm font-mono">{{ $kot->kot_number }}</td>
                                <td class="px-6 py-3 text-sm">{{ ucfirst($kot->status) }}</td>
                                <td class="px-6 py-3 text-sm">{{ $kot->sent_at ? $kot->sent_at->format('d M h:i A') : '-' }}</td>
                                <td class="px-6 py-3 text-sm">{{ $kot->printed_at ? $kot->printed_at->format('d M h:i A') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            @if($order->status == 'pending')
                <form action="{{ route('restaurant.orders.status', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                        <i class="fa-solid fa-fire"></i> Start Preparing
                    </button>
                </form>
            @endif
            @if($order->status == 'preparing')
                <form action="{{ route('restaurant.orders.status', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fa-solid fa-check"></i> Mark Ready
                    </button>
                </form>
            @endif
            @if($order->status == 'ready')
                <form action="{{ route('restaurant.orders.status', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="served">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fa-solid fa-utensils"></i> Serve
                    </button>
                </form>
            @endif
            @if($order->status == 'served' && !$order->sale_id)
                <form action="{{ route('restaurant.orders.convert', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fa-solid fa-receipt"></i> Convert to Sale
                    </button>
                </form>
            @endif
            @if(in_array($order->status, ['pending', 'preparing', 'ready', 'served']))
                <form action="{{ route('restaurant.orders.status', $order) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        <i class="fa-solid fa-times"></i> Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection 
