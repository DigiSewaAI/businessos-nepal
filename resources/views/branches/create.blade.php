@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Add Branch</h1>
        
        <form method="POST" action="{{ route('branches.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Branch Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Create Branch
            </button>
        </form>
    </div>
</div>
@endsection
