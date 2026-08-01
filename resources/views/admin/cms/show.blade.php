@extends('layouts.admin')

@section('title', 'CMS Page Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.cms.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-200">
                            <i class="fa-solid fa-file-lines text-white text-sm"></i>
                        </span>
                        {{ $page->title }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">/{{ $page->slug }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.cms.edit', $page->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this page?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-amber-500 mr-2"></i> Page Content</h3>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($page->content)) !!}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-info-circle text-blue-500 mr-2"></i> Page Info</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">ID</span>
                            <span class="text-sm font-medium text-gray-800">#{{ $page->id }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Slug</span>
                            <span class="text-sm font-medium text-gray-800">/{{ $page->slug }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $page->is_published ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Created</span>
                            <span class="text-sm font-medium text-gray-800">{{ $page->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                @if($page->meta_description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-magnifying-glass text-purple-500 mr-2"></i> SEO</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Meta Description</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $page->meta_description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection