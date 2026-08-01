@extends('layouts.app')

@section('title', 'Features - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">Everything you need to <span class="text-gradient">run your business</span></h1>
            <p class="text-gray-500 mt-4 text-lg">Built specifically for Nepali SMEs. No fluff. Just powerful features.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = [
                    ['icon' => 'fa-cubes', 'color' => 'blue', 'title' => 'Inventory Management', 'desc' => 'Manage products, variants, SKUs, and barcodes across multiple warehouses. Real-time stock updates.'],
                    ['icon' => 'fa-cash-register', 'color' => 'teal', 'title' => 'Point of Sale (POS)', 'desc' => 'Fast, intuitive POS with discounts, taxes, and instant receipt printing. Works offline-ready.'],
                    ['icon' => 'fa-chart-pie', 'color' => 'indigo', 'title' => 'Reports & Analytics', 'desc' => 'Know your top-selling products, profit margins, and daily cash flow with visual dashboards.'],
                    ['icon' => 'fa-truck', 'color' => 'green', 'title' => 'Purchase & Suppliers', 'desc' => 'Manage purchase orders, supplier history, and track payables to keep your supply chain smooth.'],
                    ['icon' => 'fa-book', 'color' => 'amber', 'title' => 'Cashbook & Expenses', 'desc' => 'Track every expense, manage daily cash closing, and get a simple ledger-ready foundation.'],
                    ['icon' => 'fa-users', 'color' => 'rose', 'title' => 'Multi-Branch & Roles', 'desc' => 'Unlimited branches, staff roles, and granular permissions. Manage your entire team securely.'],
                    ['icon' => 'fa-brain', 'color' => 'purple', 'title' => 'AI Ready', 'desc' => 'Smart insights and forecasts. Predict low stock alerts and auto-categorize products.'],
                    ['icon' => 'fa-language', 'color' => 'cyan', 'title' => 'Nepali & English', 'desc' => 'Full Nepali (नेपाली) and English interface. Switch anytime.'],
                    ['icon' => 'fa-cloud-arrow-up', 'color' => 'blue', 'title' => 'Cloud Backup', 'desc' => 'Automatic, secure daily backups. Your data is safe and always recoverable.'],
                    ['icon' => 'fa-qrcode', 'color' => 'teal', 'title' => 'Barcode Ready', 'desc' => 'Generate and scan barcodes. SKU auto-generation for quick product identification.'],
                    ['icon' => 'fa-mobile-screen', 'color' => 'indigo', 'title' => 'Mobile Optimized', 'desc' => 'Fully responsive. Works on any smartphone, tablet, or desktop.'],
                    ['icon' => 'fa-shield', 'color' => 'green', 'title' => 'Enterprise Security', 'desc' => 'Role-based access, audit logs, and data encryption. Built on Laravel\'s security standards.'],
                ];
            @endphp

            @foreach($features as $f)
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 card-hover">
                <div class="w-12 h-12 rounded-xl bg-{{ $f['color'] }}-50 text-{{ $f['color'] }}-600 flex items-center justify-center text-2xl mb-5">
                    <i class="fa-solid {{ $f['icon'] }}"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $f['title'] }}</h3>
                <p class="text-gray-500 mt-2 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
