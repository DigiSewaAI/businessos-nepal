@extends('layouts.app')

@section('title', 'Help Center - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 text-center">How can we <span class="text-gradient">help</span>?</h1>
        <p class="text-center text-gray-500 mb-12">Find answers to your questions.</p>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-regular fa-circle-question text-teal-500 mr-3"></i> Getting Started</h3>
                <p class="text-gray-600 mt-2">Learn how to set up your business, add products, and start selling in minutes.</p>
                <a href="#" class="inline-block mt-3 text-teal-600 font-semibold text-sm hover:underline">Read Guide →</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-regular fa-file-lines text-teal-500 mr-3"></i> Video Tutorials</h3>
                <p class="text-gray-600 mt-2">Watch step-by-step tutorials on how to use POS, Inventory, and Reports.</p>
                <a href="#" class="inline-block mt-3 text-teal-600 font-semibold text-sm hover:underline">Watch Videos →</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-regular fa-message text-teal-500 mr-3"></i> Contact Support</h3>
                <p class="text-gray-600 mt-2">Need personalized help? Reach out to our support team.</p>
                <a href="{{ route('pages.contact') }}" class="inline-block mt-3 text-teal-600 font-semibold text-sm hover:underline">Contact Support →</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-regular fa-clock text-teal-500 mr-3"></i> FAQ</h3>
                <p class="text-gray-600 mt-2">Can I use BusinessOS on mobile? Is my data secure? Upgrade anytime?</p>
                <a href="#" class="inline-block mt-3 text-teal-600 font-semibold text-sm hover:underline">View FAQ →</a>
            </div>
        </div>
    </div>
</div>
@endsection
