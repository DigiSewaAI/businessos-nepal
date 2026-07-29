@extends('layouts.app')

@section('title', 'About - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">About <span class="text-gradient">BusinessOS Nepal</span></h1>
        <p class="text-lg text-gray-600 leading-relaxed mb-12">Empowering Nepali SMEs with modern technology.</p>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Our Mission</h2>
                <p class="text-gray-600 mt-2">Empower Nepalese SMEs with affordable, scalable, and localized business management technology.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800">Our Vision</h2>
                <p class="text-gray-600 mt-2">A unified, modular, enterprise-grade SaaS platform for Nepalese SMEs, enabling them to manage daily business operations from a single platform.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800">Core Values</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <i class="fa-solid fa-lightbulb text-2xl text-blue-500"></i>
                        <p class="text-sm font-semibold mt-2">Innovation</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <i class="fa-solid fa-shield text-2xl text-teal-500"></i>
                        <p class="text-sm font-semibold mt-2">Integrity</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <i class="fa-solid fa-hand text-2xl text-green-500"></i>
                        <p class="text-sm font-semibold mt-2">Simplicity</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <i class="fa-solid fa-rocket text-2xl text-purple-500"></i>
                        <p class="text-sm font-semibold mt-2">Scalability</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800">Built in Nepal, for Nepal</h2>
                <p class="text-gray-600 mt-2">BusinessOS Nepal is proudly built by Nepali developers for Nepali businesses. We understand the local market, the challenges, and the opportunities.</p>
            </div>
        </div>
    </div>
</div>
@endsection