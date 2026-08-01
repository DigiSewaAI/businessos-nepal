@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-credit-card text-white text-sm"></i>
                    </span>
                    Subscriptions
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage all organization subscriptions</p>
            </div>
            <a href="{{ route('admin.subscriptions.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                <i class="fa-solid fa-plus"></i>
                Add Subscription
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $subscriptions->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Active</p>
                <p class="text-xl font-bold text-emerald-600">{{ $subscriptions->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Expired</p>
                <p class="text-xl font-bold text-red-600">{{ $subscriptions->where('status', 'expired')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Revenue</p>
                <p class="text-xl font-bold text-indigo-600">Rs. {{ number_format($subscriptions->sum('price'), 0) }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" placeholder="Search organizations..." class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
                </div>
                <select class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition bg-white">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="pending">Pending</option>
                </select>
                <button class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-sliders"></i> Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">End</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subscriptions as $sub)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $sub->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-700 font-semibold text-xs">
                                        {{ substr($sub->organization->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $sub->organization->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-800">{{ $sub->plan->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'expired' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-100 text-gray-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sub->status === 'active' ? 'bg-emerald-500' : ($sub->status === 'expired' ? 'bg-red-500' : 'bg-gray-400') }}"></span>
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $sub->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.subscriptions.destroy', $sub->id) }}" method="POST" style="display:inline-block">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition" onclick="return confirm('Delete this subscription?')">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-credit-card text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No subscriptions found</p>
                                    <p class="text-xs text-gray-400">Start by adding your first subscription</p>
                                    <a href="{{ route('admin.subscriptions.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                        <i class="fa-solid fa-plus"></i> Add Subscription
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $subscriptions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection