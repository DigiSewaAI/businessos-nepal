@extends('layouts.admin')

@section('title', 'Sales - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sales</h1>
            <a href="{{ route('sales.pos') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all">
                <i class="fa-solid fa-plus"></i> New Sale
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $sale->invoice_no }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-blue-600">Rs. {{ number_format($sale->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                @if($sale->status == 'completed') bg-green-100 text-green-700
                                @elseif($sale->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
