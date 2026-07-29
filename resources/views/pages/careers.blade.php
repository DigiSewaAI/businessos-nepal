@extends('layouts.app')

@section('title', 'Careers - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Join the <span class="text-gradient">Team</span></h1>
        <p class="text-gray-500 text-lg mb-12">We're building the future of Nepali SMEs. Come grow with us.</p>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                <h3 class="text-xl font-bold text-gray-800">Laravel Developer</h3>
                <p class="text-sm text-gray-500 mt-1">Full-time • Kathmandu / Remote</p>
                <p class="text-gray-600 mt-3 text-sm">Build the core platform. Love PHP and clean code.</p>
                <a href="#" class="inline-block mt-4 text-teal-600 font-semibold text-sm hover:underline">Apply Now →</a>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                <h3 class="text-xl font-bold text-gray-800">UI/UX Designer</h3>
                <p class="text-sm text-gray-500 mt-1">Full-time • Kathmandu</p>
                <p class="text-gray-600 mt-3 text-sm">Design beautiful, intuitive interfaces for our users.</p>
                <a href="#" class="inline-block mt-4 text-teal-600 font-semibold text-sm hover:underline">Apply Now →</a>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                <h3 class="text-xl font-bold text-gray-800">Sales & Marketing</h3>
                <p class="text-sm text-gray-500 mt-1">Full-time • Kathmandu</p>
                <p class="text-gray-600 mt-3 text-sm">Help Nepali businesses discover BusinessOS.</p>
                <a href="#" class="inline-block mt-4 text-teal-600 font-semibold text-sm hover:underline">Apply Now →</a>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-left">
                <h3 class="text-xl font-bold text-gray-800">Customer Success</h3>
                <p class="text-sm text-gray-500 mt-1">Full-time • Kathmandu</p>
                <p class="text-gray-600 mt-3 text-sm">Help our customers succeed with BusinessOS.</p>
                <a href="#" class="inline-block mt-4 text-teal-600 font-semibold text-sm hover:underline">Apply Now →</a>
            </div>
        </div>
    </div>
</div>
@endsection