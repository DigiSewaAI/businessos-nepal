@extends('layouts.admin')

@section('title', 'Plan Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.plans.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-200">
                            <i class="fa-solid fa-crown text-white text-sm"></i>
                        </span>
                        {{ $plan->name }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Plan details and statistics</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.plans.edit', $plan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this plan?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Plan Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-purple-500 mr-2"></i> Plan Information</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">ID</span>
                        <span class="text-sm font-medium text-gray-800">#{{ $plan->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Name</span>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Price</span>
                        <span class="text-sm font-medium text-gray-800">Rs. {{ number_format($plan->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Duration</span>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->duration_months }} months</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Max Users</span>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->max_users }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Max Branches</span>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->max_branches }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Max Products</span>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->max_products }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $plan->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Features & Subscriptions -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-list-check text-blue-500 mr-2"></i> Features</h3>
                    </div>
                    <div class="p-6">
                        @if($plan->features && count($plan->features))
                            <ul class="space-y-1.5">
                                @foreach($plan->features as $feature)
                                    <li class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="fa-solid fa-check-circle text-emerald-500 text-xs"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 text-center py-2">No features defined</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-credit-card text-purple-500 mr-2"></i> Subscriptions</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total: <span class="font-bold text-gray-800">{{ $plan->subscriptions->count() }}</span></p>
                        @if($plan->subscriptions->count())
                            <div class="mt-2 text-xs text-gray-400">
                                {{ $plan->subscriptions->where('status', 'active')->count() }} active
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection