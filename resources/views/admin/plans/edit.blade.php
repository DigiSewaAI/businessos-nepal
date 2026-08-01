@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.plans.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-pen text-white text-sm"></i>
                    </span>
                    Edit Plan: {{ $plan->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Update plan details</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Plan Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('name') border-red-500 @enderror"
                                   required>
                            @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Price (NPR) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" value="{{ old('price', $plan->price) }}" step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('price') border-red-500 @enderror"
                                   required>
                            @error('price') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-1.5">Duration (months) <span class="text-red-500">*</span></label>
                            <input type="number" id="duration_months" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('duration_months') border-red-500 @enderror"
                                   min="1" required>
                            @error('duration_months') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="max_users" class="block text-sm font-medium text-gray-700 mb-1.5">Max Users <span class="text-red-500">*</span></label>
                            <input type="number" id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('max_users') border-red-500 @enderror"
                                   min="1" required>
                            @error('max_users') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="max_branches" class="block text-sm font-medium text-gray-700 mb-1.5">Max Branches <span class="text-red-500">*</span></label>
                            <input type="number" id="max_branches" name="max_branches" value="{{ old('max_branches', $plan->max_branches) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('max_branches') border-red-500 @enderror"
                                   min="1" required>
                            @error('max_branches') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="max_products" class="block text-sm font-medium text-gray-700 mb-1.5">Max Products <span class="text-red-500">*</span></label>
                        <input type="number" id="max_products" name="max_products" value="{{ old('max_products', $plan->max_products) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('max_products') border-red-500 @enderror"
                               min="0" required>
                        @error('max_products') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-1.5">Features (JSON format)</label>
                        <textarea id="features" name="features" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('features') border-red-500 @enderror"
                                  placeholder='["feature1", "feature2"]'>{{ old('features', json_encode($plan->features)) }}</textarea>
                        @error('features') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.plans.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-amber-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection