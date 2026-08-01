@extends('layouts.admin')

@section('title', 'Sales Report - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Total Sales</p>
                <p class="text-xl font-bold text-gray-900">Rs. {{ number_format($totalSales ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Total Paid</p>
                <p class="text-xl font-bold text-green-600">Rs. {{ number_format($totalPaid ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500">Total Due</p>
                <p class="text-xl font-bold text-red-600">Rs. {{ number_format($totalDue ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Due</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $sale->invoice_no }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-gray-800">Rs. {{ number_format($sale->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-green-600">Rs. {{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-red-600">Rs. {{ number_format($sale->due_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                            No sales found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
