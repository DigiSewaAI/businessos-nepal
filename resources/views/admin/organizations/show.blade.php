@extends('layouts.admin')

@section('title', 'Organization Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.organizations.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-building text-white text-sm"></i>
                        </span>
                        {{ $organization->name }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Organization details and summary</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.organizations.edit', $organization->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.organizations.destroy', $organization->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this organization?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-blue-500 mr-2"></i> Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">ID</span>
                            <span class="text-sm font-medium text-gray-800">#{{ $organization->id }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Name</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->name }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Email</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Phone</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Address</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Subdomain</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->subdomain ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Created</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-users text-blue-500 mr-2"></i> Users ({{ $organization->users->count() }})</h3>
                    </div>
                    <div class="p-6">
                        @if($organization->users->count())
                            <ul class="divide-y divide-gray-100">
                                @foreach($organization->users->take(5) as $user)
                                <li class="py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-700 font-semibold text-xs">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-800">{{ $user->name }}</span>
                                        <span class="text-xs text-gray-400">({{ $user->email }})</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $user->roles->first()->name ?? 'No Role' }}</span>
                                </li>
                                @endforeach
                                @if($organization->users->count() > 5)
                                    <li class="pt-2 text-sm text-gray-400 text-center">+ {{ $organization->users->count() - 5 }} more users</li>
                                @endif
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 text-center py-2">No users in this organization</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-signal text-blue-500 mr-2"></i> Status</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ ucfirst($organization->status ?? 'Active') }}
                            </span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Branches</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->branches->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Plan</span>
                            <span class="text-sm font-medium text-gray-800">{{ $organization->subscription->plan->name ?? 'Free' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Subscription Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-credit-card text-purple-500 mr-2"></i> Subscription</h3>
                    </div>
                    <div class="p-6">
                        @if($organization->subscription)
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-gray-500">Plan</span> <span class="font-medium">{{ $organization->subscription->plan->name }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Status</span> <span class="font-medium text-emerald-600">{{ ucfirst($organization->subscription->status) }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Start</span> <span>{{ $organization->subscription->start_date->format('M d, Y') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">End</span> <span>{{ $organization->subscription->end_date->format('M d, Y') }}</span></div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 text-center py-2">No active subscription</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection