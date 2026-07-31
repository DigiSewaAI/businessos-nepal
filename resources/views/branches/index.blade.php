@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Branches</h1>
        <a href="{{ route('branches.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Add Branch
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($branches as $branch)
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-bold">{{ $branch->name }}</h3>
                @if($branch->is_default)
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Main Branch</span>
                @endif
                <p class="text-sm text-gray-600 mt-2">{{ $branch->address }}</p>
                <p class="text-sm text-gray-600">{{ $branch->phone }}</p>
            </div>
        @empty
            <p class="text-gray-500">No branches found. Add your first branch.</p>
        @endforelse
    </div>
</div>
@endsection