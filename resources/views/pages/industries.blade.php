@extends('layouts.app')

@section('title', 'Industries - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">Built for <span class="text-gradient">every Nepali business</span></h1>
            <p class="text-gray-500 mt-4 text-lg">From retail to services — we've got you covered.</p>
        </div>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $industries = [
                    ['name' => 'Retail', 'icon' => 'fa-store', 'desc' => 'Shops, supermarkets, convenience stores'],
                    ['name' => 'Wholesale', 'icon' => 'fa-warehouse', 'desc' => 'Distributors, bulk suppliers'],
                    ['name' => 'Electronics', 'icon' => 'fa-laptop', 'desc' => 'Mobile shops, computer stores'],
                    ['name' => 'Hardware', 'icon' => 'fa-hammer', 'desc' => 'Construction materials, tools'],
                    ['name' => 'Furniture', 'icon' => 'fa-couch', 'desc' => 'Furniture showrooms, custom makers'],
                    ['name' => 'Bakery', 'icon' => 'fa-bread-slice', 'desc' => 'Bakeries, cake shops'],
                    ['name' => 'Gym', 'icon' => 'fa-dumbbell', 'desc' => 'Fitness centers, yoga studios'],
                    ['name' => 'Travel', 'icon' => 'fa-plane', 'desc' => 'Tour operators, travel agencies'],
                    ['name' => 'NGO', 'icon' => 'fa-hand-holding-heart', 'desc' => 'Community organizations, INGOs'],
                    ['name' => 'Cooperative', 'icon' => 'fa-handshake', 'desc' => 'Savings & credit cooperatives'],
                    ['name' => 'Agriculture', 'icon' => 'fa-seedling', 'desc' => 'Seed/fertilizer shops, farms'],
                    ['name' => 'Auto Parts', 'icon' => 'fa-car', 'desc' => 'Spare parts, tyre shops'],
                    ['name' => 'Beauty Salon', 'icon' => 'fa-spa', 'desc' => 'Salons, parlours'],
                    ['name' => 'Printing Press', 'icon' => 'fa-print', 'desc' => 'Printing, photocopy, design'],
                    ['name' => 'Manufacturing', 'icon' => 'fa-industry', 'desc' => 'Small factories, production units'],
                ];
            @endphp

            @foreach($industries as $i)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center card-hover">
                <div class="w-14 h-14 rounded-xl gradient-bg flex items-center justify-center text-white text-2xl mx-auto mb-4">
                    <i class="fa-solid {{ $i['icon'] }}"></i>
                </div>
                <h3 class="font-bold text-gray-800">{{ $i['name'] }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $i['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection