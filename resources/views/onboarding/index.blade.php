@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-2">🎉 Welcome to BusinessOS Nepal!</h1>
        <p class="text-gray-600 mb-6">Let's setup your business in just a few steps.</p>
        
        <form method="POST" action="{{ route('onboarding.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Business Type</label>
                <select name="business_type" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">Select your business type</option>
                    <option value="retail">🛒 Retail</option>
                    <option value="restaurant">🍽️ Restaurant</option>
                    <option value="school">🏫 School</option>
                    <option value="pharmacy">💊 Pharmacy</option>
                    <option value="hardware">🔧 Hardware</option>
                    <option value="wholesale">📦 Wholesale</option>
                    <option value="bakery">🧁 Bakery</option>
                    <option value="travel">✈️ Travel</option>
                    <option value="ngo">🤝 NGO</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Business Address</label>
                <input type="text" name="address" class="w-full border rounded-lg px-4 py-2" placeholder="Kathmandu, Nepal" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Currency</label>
                    <select name="currency" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="NPR">NPR (Nepalese Rupee)</option>
                        <option value="USD">USD</option>
                        <option value="INR">INR</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Language</label>
                    <select name="language" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="en">English</option>
                        <option value="ne">नेपाली (Nepali)</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                Complete Setup → Go to Dashboard
            </button>
        </form>
    </div>
</div>
@endsection
