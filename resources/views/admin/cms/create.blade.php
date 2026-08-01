@extends('layouts.admin')

@section('title', 'Create CMS Page')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.cms.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-newspaper text-white text-sm"></i>
                    </span>
                    Create CMS Page
                </h1>
                <p class="text-sm text-gray-500 mt-1">Add a new page to the website</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.cms.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Page Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('title') border-red-500 @enderror"
                                   placeholder="About Us" required>
                            @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('slug') border-red-500 @enderror"
                                   placeholder="about-us" required>
                            @error('slug') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">Content <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" rows="8"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('content') border-red-500 @enderror"
                                  placeholder="Page content..." required>{{ old('content') }}</textarea>
                        @error('content') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description (SEO)</label>
                        <textarea id="meta_description" name="meta_description" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition @error('meta_description') border-red-500 @enderror"
                                  placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                        @error('meta_description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            <span class="text-sm font-medium text-gray-700">Published</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.cms.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-amber-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Create Page
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection