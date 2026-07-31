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

        <!-- ===== NEW: AI ASSISTANT WIDGET ===== -->
        <div class="mb-8 bg-gradient-to-r from-blue-50 via-white to-teal-50 rounded-2xl border border-blue-100 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">🤖 AI Assistant</h3>
                        <p class="text-sm text-gray-500">Ask anything about your business — sales, stock, profit, or get instant insights.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ai.chat') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fa-regular fa-paper-plane"></i> Ask AI
                    </a>
                    <button onclick="quickAIQuery('today_sales')" class="bg-white border border-gray-200 text-gray-700 px-3 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition flex items-center gap-1.5">
                        📊 Today's Sales
                    </button>
                    <button onclick="quickAIQuery('low_stock')" class="bg-white border border-gray-200 text-gray-700 px-3 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition flex items-center gap-1.5">
                        📦 Low Stock
                    </button>
                    <button onclick="quickAIQuery('profit')" class="bg-white border border-gray-200 text-gray-700 px-3 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition flex items-center gap-1.5">
                        💰 Profit
                    </button>
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

        <!-- ===== NEW: QUICK ACTIONS ===== -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-500"></i> Quick Actions
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('sales.pos') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid fa-cart-plus"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">POS</span>
                </a>
                <a href="{{ route('products.create') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-teal-400 hover:shadow-md transition-all group">
    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
        <i class="fa-regular fa-box"></i>
    </div>
    <span class="text-sm font-medium text-gray-700">Add Product</span>
</a>
                <a href="{{ route('purchases.create') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">New Purchase</span>
                </a>
                <a href="{{ route('expenses.create') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-amber-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Add Expense</span>
                </a>
                <!-- ✅ NEW: AI Assistant Quick Action -->
                <a href="{{ route('ai.chat') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-teal-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">AI Assistant</span>
                    <span class="text-[10px] bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded-full">New</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===== AI QUICK QUERY SCRIPT ===== -->
@push('scripts')
<script>
function quickAIQuery(type) {
    const queries = {
        'today_sales': 'What are my total sales today?',
        'low_stock': 'Which products are low in stock?',
        'profit': 'What is my profit today?'
    };
    const message = queries[type] || 'How is my business doing?';
    window.location.href = "{{ route('ai.chat') }}?message=" + encodeURIComponent(message);
}
</script>
@endpush
@endsection