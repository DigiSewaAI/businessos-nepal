@extends('layouts.admin')

@section('title', 'Edit Subscription')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.subscriptions.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-pen text-white text-sm"></i>
                    </span>
                    Edit Subscription #{{ $subscription->id }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">{{ $subscription->organization->name ?? 'Unknown' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Plan <span class="text-red-500">*</span></label>
                            <select id="plan_id" name="plan_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('plan_id') border-red-500 @enderror"
                                    required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id', $subscription->plan_id) == $plan->id ? 'selected' : '' }}>{{ $plan->name }} (Rs. {{ number_format($plan->price, 2) }})</option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('status') border-red-500 @enderror"
                                    required>
                                <option value="active" {{ old('status', $subscription->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status', $subscription->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('status', $subscription->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">End Date <span class="text-red-500">*</span></label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('end_date') border-red-500 @enderror"
                                   required>
                            @error('end_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Organization</label>
                            <p class="text-sm text-gray-800 py-2.5">{{ $subscription->organization->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.subscriptions.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-amber-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Update Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection