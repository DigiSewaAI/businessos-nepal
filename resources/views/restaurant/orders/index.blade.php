@extends('layouts.admin')

@section('title', 'All Orders')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">All Orders</h1>
            <a href="{{ route('restaurant.orders.active') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-clock"></i> Active Orders
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->table->number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->items->count() }}</td>
                            <td class="px-6 py-4 text-sm font-bold">Rs. {{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $order->status == 'pending' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $order->status == 'preparing' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $order->status == 'ready' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status == 'served' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status == 'completed' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $order->ordered_at ? $order->ordered_at->format('d M h:i A') : '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('restaurant.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
