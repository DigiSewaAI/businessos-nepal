@extends('layouts.admin')

@section('title', 'Edit Table')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('restaurant.tables.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Table: {{ $table->number }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('restaurant.tables.update', $table) }}" method="POST">
                @csrf @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Table Number <span class="text-red-500">*</span></label>
                        <input type="text" name="number" value="{{ old('number', $table->number) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('number') border-red-500 @enderror"
                            required>
                        @error('number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', $table->capacity) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('capacity') border-red-500 @enderror"
                            min="1" required>
                        @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="unavailable" {{ old('status', $table->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $table->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('restaurant.tables.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                        <i class="fa-solid fa-save"></i> Update Table
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
