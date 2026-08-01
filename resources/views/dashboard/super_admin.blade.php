@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="pt-4 pb-8 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Good Morning, {{ $admin_name }}! ☀️
                </h1>
                <p class="text-sm text-gray-500">
                    <i class="fa-regular fa-circle-check text-green-500 mr-1"></i>
                    BusinessOS Platform Overview · <span class="font-semibold">{{ $org_count }}</span> Organizations
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400">{{ now()->format('l, F j, Y') }}</span>
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs text-green-600">System Online</span>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Organizations</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $total_organizations }}</p>
                <div class="flex gap-3 mt-2 text-xs">
                    <span class="text-green-600">Paid: {{ $paid_organizations }}</span>
                    <span class="text-yellow-600">Trial: {{ $trial_organizations }}</span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">MRR</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">Rs. {{ number_format($mrr, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Monthly Recurring Revenue</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Today's Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rs. {{ number_format($today_revenue, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">This Month: Rs. {{ number_format($month_revenue, 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Revenue</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Rs. {{ number_format($total_revenue, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Lifetime platform revenue</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Today's Signups</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $today_signups }}</p>
                <p class="text-xs text-gray-400 mt-1">New organizations today</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Expiring Plans</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $expiring_plans }}</p>
                <p class="text-xs text-gray-400 mt-1">Within 7 days</p>
            </div>
        </div>

        <!-- Charts (Placeholder) -->
        <div class="grid lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-area text-blue-500"></i> Revenue Trend
                </h3>
                <div class="h-48 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <span>📊 Chart coming soon</span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-500"></i> Organization Growth
                </h3>
                <div class="h-48 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <span>📈 Chart coming soon</span>
                </div>
            </div>
        </div>

        <!-- Recent Lists -->
        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <!-- Recent Organizations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-500"></i> Recent Organizations
                </h3>
                <div class="space-y-4">
                    @forelse($recent_organizations as $org)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $org->name }}</p>
                                <p class="text-xs text-gray-400">{{ $org->plan }} · {{ ucfirst($org->status) }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $org->joined }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No organizations yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-green-500"></i> Recent Payments
                </h3>
                <div class="space-y-4">
                    @forelse($recent_payments as $payment)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Rs. {{ number_format($payment->amount, 2) }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->plan }} · {{ $payment->status }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $payment->date }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No payments recorded</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-users text-purple-500"></i> Recent Users
                </h3>
                <div class="space-y-4">
                    @forelse($recent_users as $user)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->role }} · {{ $user->organization }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $user->last_login }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No users yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="grid lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">AI Usage</p>
                <div class="flex items-end gap-3 mt-1">
                    <span class="text-2xl font-bold">{{ $system_health['ai_usage'] }}%</span>
                    <span class="text-xs text-gray-400">of capacity</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ $system_health['ai_usage'] }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Storage</p>
                <div class="flex items-end gap-3 mt-1">
                    <span class="text-2xl font-bold">{{ $system_health['storage'] }}%</span>
                    <span class="text-xs text-gray-400">used</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
                    <div class="h-2 bg-yellow-500 rounded-full" style="width: {{ $system_health['storage'] }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Queue</p>
                <div class="flex items-end gap-3 mt-1">
                    <span class="text-2xl font-bold text-green-600">{{ $system_health['queue'] }}</span>
                    <span class="text-xs text-gray-400">pending</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">All jobs healthy</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Failed Jobs</p>
                <div class="flex items-end gap-3 mt-1">
                    <span class="text-2xl font-bold text-red-600">{{ $system_health['failed_jobs'] }}</span>
                    <span class="text-xs text-gray-400">last 24h</span>
                </div>
                <p class="text-xs text-green-600 mt-2">✅ No failures</p>
            </div>
        </div>

        <!-- Quick Actions (Super Admin specific) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-500"></i> Platform Actions
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Create Org</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-green-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Manage Plans</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Users</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-amber-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Backup</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-red-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Logs</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center group-hover:bg-gray-600 group-hover:text-white transition">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Settings</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection