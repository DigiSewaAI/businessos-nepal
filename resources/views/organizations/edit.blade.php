@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Organization Settings</h1>
        
        <form method="POST" action="{{ route('organization.update') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Organization Name *</label>
                <input type="text" name="name" value="{{ old('name', $organization->name) }}" 
                       class="w-full border rounded-lg px-4 py-2" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address', $organization->address) }}" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $organization->email) }}" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Update Organization
            </button>
        </form>
    </div>
</div>
@endsection