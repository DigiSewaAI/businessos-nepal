@extends('layouts.admin')

@section('title', 'Create Support Ticket')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.support.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-lg shadow-rose-200">
                        <i class="fa-solid fa-ticket text-white text-sm"></i>
                    </span>
                    Create Support Ticket
                </h1>
                <p class="text-sm text-gray-500 mt-1">Create a new support ticket</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.support.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1.5">Organization <span class="text-red-500">*</span></label>
                            <select id="organization_id" name="organization_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition @error('organization_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Organization</option>
                                @foreach($organizations ?? [] as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organization_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority <span class="text-red-500">*</span></label>
                            <select id="priority" name="priority"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition @error('priority') border-red-500 @enderror"
                                    required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition @error('subject') border-red-500 @enderror"
                               placeholder="Brief description of the issue" required>
                        @error('subject') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition @error('message') border-red-500 @enderror"
                                  placeholder="Describe the issue in detail..." required>{{ old('message') }}</textarea>
                        @error('message') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.support.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-rose-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection