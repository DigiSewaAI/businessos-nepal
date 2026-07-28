@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gradient-to-br from-gray-50 via-white to-blue-50/30 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="bg-gradient-to-r from-blue-600 to-teal-500 text-transparent bg-clip-text">Super Admin</span>
                    <span class="text-sm font-normal text-gray-400">|</span>
                    <span class="text-sm font-medium text-gray-500">Full System Overview</span>
                </h1>
                <p class="text-gray-500 mt-1 text-sm">
                    <i class="fa-regular fa-calendar-alt mr-1"></i> {{ now()->format('l, F j, Y') }}
                </p>
            </div>
            <div class="flex gap-3 mt-3 md:mt-0">
                <a href="{{ route('sales.index') }}" class="bg-white px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-700 hover:shadow-md transition-all">
                    <i class="fa-solid fa-chart-simple mr-1"></i> View Sales
                </a>
                <!-- Reports link – placeholder, route not yet defined -->
                <a href="#" class="bg-white px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-700 hover:shadow-md transition-all">
                    <i class="fa-solid fa-file-pdf mr-1"></i> Reports
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <!-- Card 1: Organizations -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-building text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold mt-3">{{ $total_organizations }}</p>
                <p class="text-sm opacity-80">Organizations</p>
            </div>

            <!-- Card 2: Users -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-users text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold mt-3">{{ $total_users }}</p>
                <p class="text-sm opacity-80">Users</p>
            </div>

            <!-- Card 3: Branches -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-store text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold mt-3">{{ $total_branches }}</p>
                <p class="text-sm opacity-80">Branches</p>
            </div>

            <!-- Card 4: Products -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-boxes-stacked text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold mt-3">{{ $total_products }}</p>
                <p class="text-sm opacity-80">Products</p>
            </div>

            <!-- Card 5: Categories -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-tags text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-3xl font-bold mt-3">{{ $total_categories }}</p>
                <p class="text-sm opacity-80">Categories</p>
            </div>

            <!-- Card 6: Revenue -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <i class="fa-solid fa-coin-bill text-3xl opacity-50 group-hover:opacity-100 transition"></i>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Total</span>
                </div>
                <p class="text-2xl font-bold mt-3">Rs. {{ number_format($total_revenue, 0) }}</p>
                <p class="text-sm opacity-80">Revenue</p>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Recent Organizations -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-building text-blue-500"></i> Recent Organizations
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $recent_organizations->count() }}</span>
                    </h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($recent_organizations as $org)
                    <li class="px-6 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                {{ substr($org->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $org->name }}</p>
                                <p class="text-xs text-gray-400">{{ $org->email ?? 'No email' }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $org->created_at->diffForHumans() }}</span>
                    </li>
                    @empty
                    <li class="px-6 py-8 text-center text-gray-400 text-sm">
                        <i class="fa-regular fa-building text-2xl block mb-2"></i>
                        No organizations yet.
                    </li>
                    @endforelse
                </ul>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-users text-emerald-500"></i> Recent Users
                        <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">{{ $recent_users->count() }}</span>
                    </h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($recent_users as $u)
                    <li class="px-6 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                {{ substr($u->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">{{ $u->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $u->created_at->diffForHumans() }}</span>
                    </li>
                    @empty
                    <li class="px-6 py-8 text-center text-gray-400 text-sm">
                        <i class="fa-regular fa-user text-2xl block mb-2"></i>
                        No users yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Quick Actions -->
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
                <a href="{{ route('sales.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Sales Report</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection