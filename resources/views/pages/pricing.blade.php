@extends('layouts.app')

@section('title', 'Pricing - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Simple, transparent <span class="text-gradient">pricing</span></h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-12">Start free, scale as you grow. No hidden fees.</p>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Starter -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 card-hover">
                <h3 class="text-xl font-bold text-gray-800">Starter</h3>
                <p class="text-4xl font-extrabold mt-4 text-gray-900">Free</p>
                <ul class="text-sm text-gray-600 space-y-3 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> 100 Products</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> 1 Branch</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Basic Reports</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> POS Access</li>
                </ul>
                <a href="{{ route('register') }}" class="block mt-8 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition">Get Started</a>
            </div>

            <!-- Pro -->
            <div class="bg-white rounded-2xl shadow-2xl border-4 border-teal-400 p-8 transform scale-105 relative card-hover">
                <span class="absolute -top-3 right-4 bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full">Popular</span>
                <h3 class="text-xl font-bold text-gray-800">Pro</h3>
                <p class="text-4xl font-extrabold mt-4 text-gray-900">Rs. 999 <span class="text-sm font-normal text-gray-400">/mo</span></p>
                <ul class="text-sm text-gray-600 space-y-3 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Unlimited Products</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Unlimited Branches</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Advanced Reports</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Priority Support</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Purchase & Finance Modules</li>
                </ul>
                <a href="{{ route('register') }}" class="block mt-8 gradient-bg text-white py-2.5 rounded-lg font-semibold hover:shadow-lg transition">Start Free Trial</a>
            </div>

            <!-- Enterprise -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 card-hover">
                <h3 class="text-xl font-bold text-gray-800">Enterprise</h3>
                <p class="text-4xl font-extrabold mt-4 text-gray-900">Custom</p>
                <ul class="text-sm text-gray-600 space-y-3 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Everything in Pro</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Dedicated Support</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> Custom Integrations</li>
                    <li><i class="fa-solid fa-check text-teal-500 mr-2"></i> API Access</li>
                </ul>
                <a href="{{ route('pages.contact') }}" class="block mt-8 bg-gray-100 text-gray-700 py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition">Contact Us</a>
            </div>
        </div>
        <p class="text-sm text-gray-400 mt-8">* 14-day free trial on Pro. No credit card required.</p>
    </div>
</div>
@endsection