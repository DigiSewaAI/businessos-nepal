@extends('layouts.admin')

@section('title', 'Profit & Loss - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profit & Loss Statement</h1>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
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

        <!-- P&L Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">Profit & Loss</h2>
                <p class="text-xs text-gray-500">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Income -->
                <div>
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Income</h3>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Total Sales</span>
                            <span class="font-semibold text-gray-800">Rs. {{ number_format($totalSales, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Other Income</span>
                            <span class="text-gray-400">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between py-2 font-bold text-lg">
                            <span class="text-gray-800">Total Income</span>
                            <span class="text-blue-600">Rs. {{ number_format($totalSales, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Expenses -->
                <div>
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Expenses</h3>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Cost of Goods Sold</span>
                            <span class="text-gray-800">Rs. {{ number_format($totalCost, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Operating Expenses</span>
                            <span class="text-gray-800">Rs. {{ number_format($totalExpenses, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Total Purchases (COGS)</span>
                            <span class="text-gray-800">Rs. {{ number_format($totalPurchases, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 font-bold text-lg">
                            <span class="text-gray-800">Total Expenses</span>
                            <span class="text-red-600">Rs. {{ number_format($totalCost + $totalExpenses, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="border-t-2 border-gray-200 pt-4">
                    <div class="flex justify-between py-2 font-bold text-xl">
                        <span class="text-gray-800">Net Profit</span>
                        <span class="{{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rs. {{ number_format($netProfit, 2) }}
                            @if($netProfit >= 0)
                                <i class="fa-solid fa-arrow-up text-sm"></i>
                            @else
                                <i class="fa-solid fa-arrow-down text-sm"></i>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between py-2 text-sm text-gray-500">
                        <span>Gross Profit (Sales - COGS)</span>
                        <span class="font-semibold">Rs. {{ number_format($grossProfit, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
