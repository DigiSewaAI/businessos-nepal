@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fa-solid fa-chart-line text-white text-sm"></i>
                </span>
                Analytics
            </h1>
            <p class="text-sm text-gray-500 mt-1">Platform-wide analytics and insights</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Orgs</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalOrganizations ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Users</p>
                <p class="text-2xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Revenue</p>
                <p class="text-2xl font-bold text-emerald-600">Rs. {{ number_format($totalRevenue ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Sales</p>
                <p class="text-2xl font-bold text-purple-600">{{ $totalSales ?? 0 }}</p>
            </div>
        </div>

        <!-- Charts Placeholder -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Revenue Trend</h3>
                <div class="h-48 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <span>📊 Revenue chart coming soon</span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Organization Growth</h3>
                <div class="h-48 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <span>📈 Growth chart coming soon</span>
                </div>
            </div>
        </div>

        <!-- Plans Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Plans Distribution</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($organizationsByPlan ?? [] as $plan => $count)
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500">{{ $plan ?? 'Unknown' }}</p>
                    <p class="text-xl font-bold text-gray-900">{{ $count }}</p>
                </div>
                @empty
                <p class="text-sm text-gray-400 col-span-4 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection