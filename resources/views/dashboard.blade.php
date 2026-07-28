@extends('layouts.app')

@section('title', 'Dashboard - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Welcome, {{ Auth::user()->name }}! 🎉
                </h1>
                <p class="text-sm text-gray-500">
                    {{ Auth::user()->organization->name }} | 
                    {{ Auth::user()->branch->name }} | 
                    Role: <span class="font-medium text-gray-700">{{ Auth::user()->roles->first()->name ?? 'No Role' }}</span>
                </p>
            </div>
            <span class="text-sm text-gray-400">{{ now()->format('d M Y, h:i A') }}</span>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Today's Sales</p>
                        <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($todaySales ?? 0, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Today's Profit</p>
                        <p class="text-2xl font-bold {{ ($todayProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rs. {{ number_format($todayProfit ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Low Stock</p>
                        <p class="text-2xl font-bold text-red-600">{{ $lowStockCount ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Cash Balance</p>
                        <p class="text-2xl font-bold text-teal-600">Rs. {{ number_format($cashBalance ?? 0, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products + Monthly Chart -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Top Products -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-yellow-500"></i> Top Selling Products
                </h3>
                @if(isset($topProducts) && count($topProducts) > 0)
                    <div class="space-y-3">
                        @foreach($topProducts as $product)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-sm text-gray-700">{{ $product->name }}</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $product->total_qty }} units</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-6">
                        <i class="fa-regular fa-face-frown text-2xl block mb-2"></i>
                        No sales yet
                    </p>
                @endif
            </div>

            <!-- Monthly Sales Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-blue-500"></i> Monthly Sales ({{ now()->year }})
                </h3>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-chart-bar text-blue-500"></i> Monthly Sales ({{ now()->year }})
    </h3>
    @if(isset($monthlySales) && count($monthlySales) > 0)
        @php
            $maxValue = $monthlySales->max() ?: 1;
        @endphp
        <div class="flex items-end h-48 space-x-2">
            @foreach(range(1, 12) as $month)
                @php
                    $value = $monthlySales[$month] ?? 0;
                    $height = $value > 0 ? max(($value / $maxValue) * 100, 5) : 5;
                @endphp
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-teal-500/20 rounded-t" style="height: {{ $height }}%; min-height: 5px;"></div>
                    <span class="text-xs text-gray-500 mt-1">{{ date('M', mktime(0,0,0,$month,1)) }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-400 text-center py-6">
            <i class="fa-regular fa-calendar text-2xl block mb-2"></i>
            No sales data yet
        </p>
    @endif
</div>
        </div>
    </div>
</div>
@endsection