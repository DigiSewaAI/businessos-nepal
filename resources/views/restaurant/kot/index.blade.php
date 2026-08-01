@extends('layouts.admin')

@section('title', 'KOT Logs')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">KOT Logs</h1>
            <a href="{{ route('restaurant.kitchen') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-kitchen-set"></i> Kitchen View
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KOT #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kots as $kot)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono">{{ $kot->kot_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $kot->order->order_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $kot->order->table->number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($kot->items)
                                    <ul>
                                        @foreach($kot->items as $item)
                                            <li>{{ $item['quantity'] }} × {{ $item['product'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $kot->status == 'sent' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $kot->status == 'printed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $kot->status == 'viewed' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucfirst($kot->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $kot->sent_at ? $kot->sent_at->format('d M h:i A') : '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($kot->status == 'sent')
                                    <form action="{{ route('restaurant.kot.print', $kot) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No KOT logs.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $kots->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
