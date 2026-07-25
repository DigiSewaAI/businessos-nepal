@extends('layouts.app')

@section('title', 'Dashboard - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}! 🎉</h1>
            <p class="text-gray-500 mt-2">
                Organization: <strong>{{ Auth::user()->organization->name }}</strong> | 
                Branch: <strong>{{ Auth::user()->branch->name }}</strong> | 
                Role: <strong>{{ Auth::user()->roles->first()->name ?? 'No Role' }}</strong>
            </p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-600 font-medium">Total Products</p>
                    <p class="text-3xl font-bold text-blue-800">0</p>
                </div>
                <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                    <p class="text-sm text-green-600 font-medium">Today's Sales</p>
                    <p class="text-3xl font-bold text-green-800">Rs. 0</p>
                </div>
                <div class="bg-red-50 p-6 rounded-xl border border-red-200">
                    <p class="text-sm text-red-600 font-medium">Low Stock Alert</p>
                    <p class="text-3xl font-bold text-red-800">0</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection