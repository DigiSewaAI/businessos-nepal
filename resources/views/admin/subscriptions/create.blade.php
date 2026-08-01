@extends('layouts.admin')

@section('title', 'Create Subscription')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.subscriptions.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-credit-card text-white text-sm"></i>
                    </span>
                    Create Subscription
                </h1>
                <p class="text-sm text-gray-500 mt-1">Assign a plan to an organization</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1.5">Organization <span class="text-red-500">*</span></label>
                            <select id="organization_id" name="organization_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition @error('organization_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Organization</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organization_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Plan <span class="text-red-500">*</span></label>
                            <select id="plan_id" name="plan_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition @error('plan_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }} (Rs. {{ number_format($plan->price, 2) }})</option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition @error('start_date') border-red-500 @enderror"
                                   required>
                            @error('start_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">End Date <span class="text-red-500">*</span></label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition @error('end_date') border-red-500 @enderror"
                                   required>
                            @error('end_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition @error('status') border-red-500 @enderror"
                                required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.subscriptions.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-blue-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Create Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection