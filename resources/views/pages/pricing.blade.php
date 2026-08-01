@extends('layouts.app')

@section('title', 'Pricing - BusinessOS Nepal')

@section('content')

<!-- ============================================================ -->
<!-- HERO SECTION -->
<!-- ============================================================ -->
<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
            Simple, <span class="text-gradient">Transparent Pricing</span>
        </h1>
        <p class="text-lg text-gray-600 mt-4 max-w-2xl mx-auto">
            Start with the plan that fits your business today and upgrade seamlessly as you grow.
        </p>

        <!-- Trust Badges -->
        <div class="flex flex-wrap justify-center gap-3 mt-6 text-sm">
            <span class="inline-flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-regular fa-flag text-teal-600"></i> Built for Nepal
            </span>
            <span class="inline-flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-solid fa-lock text-teal-600"></i> Secure
            </span>
            <span class="inline-flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-solid fa-bolt text-teal-600"></i> Fast Setup
            </span>
            <span class="inline-flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-regular fa-credit-card text-teal-600"></i> No Hidden Fees
            </span>
            <span class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-regular fa-calendar-check text-blue-600"></i> 14-Day Free Trial
            </span>
            <span class="inline-flex items-center gap-2 bg-green-50 border border-green-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-regular fa-circle-check text-green-600"></i> Cancel Anytime
            </span>
            <span class="inline-flex items-center gap-2 bg-teal-50 border border-teal-200 px-4 py-2 rounded-full shadow-sm">
                <i class="fa-regular fa-headset text-teal-600"></i> Local Support
            </span>
        </div>

        <!-- Trust Numbers -->
        <div class="flex flex-wrap justify-center gap-8 mt-8 text-center">
            <div>
                <p class="text-2xl font-extrabold text-gray-900">500+</p>
                <p class="text-xs text-gray-500">Businesses</p>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">99.9%</p>
                <p class="text-xs text-gray-500">Uptime</p>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">24 hrs</p>
                <p class="text-xs text-gray-500">Average Setup</p>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">🔒</p>
                <p class="text-xs text-gray-500">Cloud Backup</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- BILLING TOGGLE + PRICING CARDS -->
<!-- ============================================================ -->
<section class="py-10 px-4 bg-white">
    <div class="max-w-7xl mx-auto" x-data="{ yearly: false }">

        <!-- Toggle -->
        <div class="flex flex-col items-center mb-12">
            <span class="inline-block bg-green-500 text-white text-[10px] font-bold px-3 py-0.5 rounded-full mb-2 tracking-wider">
                SAVE 30%
            </span>
            <div class="inline-flex rounded-xl border border-gray-200 bg-gray-50 p-1 shadow-sm">
                <button @click="yearly = false"
                        :class="{'bg-white text-gray-900 shadow-md': !yearly, 'text-gray-500 hover:text-gray-700': yearly}"
                        class="px-6 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200">
                    Monthly
                </button>
                <button @click="yearly = true"
                        :class="{'bg-white text-gray-900 shadow-md': yearly, 'text-gray-500 hover:text-gray-700': !yearly}"
                        class="px-6 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200">
                    Yearly
                </button>
            </div>
        </div>

        <!-- All Plans Include -->
        <div class="text-center mb-10">
            <p class="text-sm text-gray-500 font-medium">All plans include</p>
            <div class="flex flex-wrap justify-center gap-4 mt-2 text-xs text-gray-600">
                <span class="inline-flex items-center gap-1"><i class="fa-solid fa-cloud text-teal-500"></i> Secure Cloud Hosting</span>
                <span class="inline-flex items-center gap-1"><i class="fa-solid fa-rotate text-teal-500"></i> Automatic Updates</span>
                <span class="inline-flex items-center gap-1"><i class="fa-solid fa-database text-teal-500"></i> Daily Backups</span>
                <span class="inline-flex items-center gap-1"><i class="fa-solid fa-mobile-screen-button text-teal-500"></i> Mobile Friendly</span>
                <span class="inline-flex items-center gap-1"><i class="fa-solid fa-graduation-cap text-teal-500"></i> Free Onboarding</span>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid md:grid-cols-4 gap-6">

            @php
                $plans = [
                    [
                        'name' => 'Lite',
                        'slug' => 'lite',
                        'color' => 'green',
                        'badge' => 'Best for Small Businesses',
                        'price_monthly' => 300,
                        'price_yearly' => 2500,
                        'features' => [
                            ['icon' => 'fa-box', 'text' => '300 Products (SKUs)'],
                            ['icon' => 'fa-store', 'text' => '1 Branch'],
                            ['icon' => 'fa-users', 'text' => '3 Users'],
                            ['icon' => 'fa-receipt', 'text' => '400 Invoices / Month'],
                            ['icon' => 'fa-database', 'text' => '200 MB Storage'],
                            ['icon' => 'fa-warehouse', 'text' => 'Basic Inventory'],
                            ['icon' => 'fa-cash-register', 'text' => 'POS'],
                            ['icon' => 'fa-chart-simple', 'text' => 'Basic Reports'],
                            ['icon' => 'fa-envelope', 'text' => 'Email Support'],
                            ['icon' => 'fa-circle-check', 'text' => 'No Setup Fee'],
                            ['icon' => 'fa-rotate-left', 'text' => 'Cancel Anytime'],
                        ],
                        'cta' => 'Choose Lite',
                        'cta_link' => route('register'),
                        'footer_text' => '30-Day Money-Back Guarantee',
                        'perfect_for' => ['Kirana', 'Pharmacy', 'Bakery', 'Mobile Shop', 'Cosmetics', 'Gift Shop'],
                        'popular' => false,
                        'upgrade_to_unlock' => ['Purchase Module', 'Finance Module', 'API', 'Advanced AI'],
                    ],
                    [
                        'name' => 'Pro',
                        'slug' => 'pro',
                        'color' => 'blue',
                        'badge' => '⭐ MOST POPULAR',
                        'price_monthly' => 999,
                        'price_yearly' => 9999,
                        'features' => [
                            ['icon' => 'fa-box', 'text' => 'Unlimited Products*'],
                            ['icon' => 'fa-store', 'text' => '3 Branches'],
                            ['icon' => 'fa-users', 'text' => '20 Users'],
                            ['icon' => 'fa-receipt', 'text' => '5,000 Invoices / Month'],
                            ['icon' => 'fa-database', 'text' => '2 GB Storage'],
                            ['icon' => 'fa-warehouse', 'text' => 'Purchase Module'],
                            ['icon' => 'fa-coins', 'text' => 'Finance Module'],
                            ['icon' => 'fa-chart-line', 'text' => 'Advanced Reports'],
                            ['icon' => 'fa-brain', 'text' => 'AI Assistant'],
                            ['icon' => 'fa-code', 'text' => 'Basic API'],
                            ['icon' => 'fa-phone', 'text' => 'WhatsApp Support'],
                            ['icon' => 'fa-circle-check', 'text' => 'No Setup Fee'],
                            ['icon' => 'fa-rotate-left', 'text' => 'Cancel Anytime'],
                        ],
                        'cta' => 'Start 14-Day Free Trial',
                        'cta_link' => route('register'),
                        'footer_text' => 'No Credit Card Required',
                        'perfect_for' => ['Restaurant', 'School', 'Hardware', 'Electronics', 'Fashion Store', 'Multi-Branch Retail'],
                        'popular' => true,
                        'popular_sub' => 'Most chosen by growing businesses',
                        'upgrade_to_unlock' => [],
                    ],
                    [
                        'name' => 'Business',
                        'slug' => 'business',
                        'color' => 'purple',
                        'badge' => 'Best Value',
                        'price_monthly' => 2999,
                        'price_yearly' => 29999,
                        'features' => [
                            ['icon' => 'fa-box', 'text' => 'Unlimited Products*'],
                            ['icon' => 'fa-store', 'text' => '10 Branches'],
                            ['icon' => 'fa-users', 'text' => '100 Users'],
                            ['icon' => 'fa-receipt', 'text' => '25,000 Invoices / Month'],
                            ['icon' => 'fa-database', 'text' => '10 GB Storage'],
                            ['icon' => 'fa-warehouse', 'text' => 'Purchase Module'],
                            ['icon' => 'fa-coins', 'text' => 'Finance Module'],
                            ['icon' => 'fa-chart-pie', 'text' => 'Branch Analytics'],
                            ['icon' => 'fa-brain', 'text' => 'Advanced AI'],
                            ['icon' => 'fa-code', 'text' => 'Full API'],
                            ['icon' => 'fa-headset', 'text' => 'Priority Support'],
                            ['icon' => 'fa-circle-check', 'text' => 'No Setup Fee'],
                            ['icon' => 'fa-rotate-left', 'text' => 'Cancel Anytime'],
                        ],
                        'cta' => 'Start Free Trial',
                        'cta_link' => route('register'),
                        'footer_text' => 'Ideal for growing businesses.',
                        'perfect_for' => ['Wholesale', 'Distribution', 'Hospital', 'Large School', 'Multi-location Businesses'],
                        'popular' => false,
                        'upgrade_to_unlock' => [],
                    ],
                    [
                        'name' => 'Enterprise',
                        'slug' => 'enterprise',
                        'color' => 'dark',
                        'badge' => 'Custom Solutions',
                        'price_monthly' => null,
                        'price_yearly' => null,
                        'features' => [
                            ['icon' => 'fa-box', 'text' => 'Tailored Business Solution'],
                            ['icon' => 'fa-server', 'text' => 'Dedicated Server'],
                            ['icon' => 'fa-database', 'text' => 'Dedicated Database'],
                            ['icon' => 'fa-plug', 'text' => 'Custom Integrations'],
                            ['icon' => 'fa-paint-brush', 'text' => 'White Label'],
                            ['icon' => 'fa-shield', 'text' => 'Priority SLA'],
                            ['icon' => 'fa-user-tie', 'text' => 'Dedicated Account Manager'],
                            ['icon' => 'fa-graduation-cap', 'text' => 'Onboarding & Training'],
                            ['icon' => 'fa-brain', 'text' => 'Custom AI'],
                            ['icon' => 'fa-crown', 'text' => 'Priority Support'],
                            ['icon' => 'fa-circle-check', 'text' => 'No Setup Fee'],
                            ['icon' => 'fa-rotate-left', 'text' => 'Cancel Anytime'],
                        ],
                        'cta' => 'Contact Sales',
                        'cta_link' => route('pages.contact'),
                        'footer_text' => "Let's build a custom solution.",
                        'perfect_for' => ['Supermarket Chains', 'Franchises', 'Manufacturing', 'Corporate Groups'],
                        'popular' => false,
                        'upgrade_to_unlock' => [],
                    ],
                ];
            @endphp

            @foreach($plans as $plan)
                @php
                    $isEnterprise = $plan['slug'] === 'enterprise';
                    $textColor = $isEnterprise ? 'text-white' : 'text-gray-900';
                    $textMuted = $isEnterprise ? 'text-gray-300' : 'text-gray-600';
                    $textLight = $isEnterprise ? 'text-gray-200' : 'text-gray-700';
                    $bgMuted = $isEnterprise ? 'bg-gray-800' : 'bg-gray-100';
                    $borderColor = $isEnterprise ? 'border-gray-700' : 'border-gray-200';
                    $bgCard = $isEnterprise ? 'bg-gray-900' : 'bg-white';
                @endphp

                <div class="rounded-2xl border p-6 shadow-sm hover:shadow-xl transition-all duration-300
                          {{ $plan['popular'] ? 'border-blue-500 shadow-lg scale-105 md:scale-105' : 'border-gray-200' }}
                          {{ $bgCard }} {{ $borderColor }} flex flex-col">

                    <!-- Badge -->
                    @if($plan['badge'])
                        <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-2 self-start
                                   {{ $plan['popular'] ? 'bg-blue-500 text-white' : '' }}
                                   {{ $plan['slug'] === 'lite' ? 'bg-green-100 text-green-700' : '' }}
                                   {{ $plan['slug'] === 'business' ? 'bg-purple-100 text-purple-700' : '' }}
                                   {{ $isEnterprise ? 'bg-gray-700 text-gray-200' : '' }}">
                            {{ $plan['badge'] }}
                        </span>
                    @endif

                    <!-- Popular Sub-text -->
                    @if($plan['popular'] && isset($plan['popular_sub']))
                        <p class="text-xs text-blue-600 font-medium -mt-1 mb-2">{{ $plan['popular_sub'] }}</p>
                    @endif

                    <!-- Plan Name -->
                    <h3 class="text-xl font-bold {{ $textColor }}">
                        {{ $plan['name'] }}
                    </h3>

                    <!-- Price -->
                    <div class="mt-4">
                        <template x-if="!yearly">
                            <div>
                                @if($plan['price_monthly'] === null)
                                    <span class="text-3xl font-bold {{ $textColor }}">Custom</span>
                                @elseif($plan['price_monthly'] === 0)
                                    <span class="text-3xl font-bold {{ $textColor }}">Free</span>
                                @else
                                    <span class="text-4xl font-extrabold {{ $textColor }}">
                                        Rs. {{ number_format($plan['price_monthly']) }}
                                    </span>
                                    <span class="text-sm {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }}">/mo</span>
                                @endif
                            </div>
                        </template>
                        <template x-if="yearly">
                            <div>
                                @if($plan['price_yearly'] === null)
                                    <span class="text-3xl font-bold {{ $textColor }}">Custom</span>
                                @elseif($plan['price_yearly'] === 0)
                                    <span class="text-3xl font-bold {{ $textColor }}">Free</span>
                                @else
                                    <span class="text-4xl font-extrabold {{ $textColor }}">
                                        Rs. {{ number_format($plan['price_yearly']) }}
                                    </span>
                                    <span class="text-sm {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }}">/yr</span>
                                    <div class="text-xs {{ $isEnterprise ? 'text-green-400' : 'text-green-600' }} font-medium">
                                        Only Rs. {{ number_format($plan['price_yearly'] / 12) }}/mo
                                    </div>
                                @endif
                            </div>
                        </template>
                    </div>

                    <!-- Perfect For -->
                    <div class="mt-4">
                        <p class="text-xs font-medium {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }} uppercase tracking-wider">
                            Perfect For
                        </p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($plan['perfect_for'] as $industry)
                                <span class="text-xs {{ $isEnterprise ? 'text-gray-200 bg-gray-800' : 'text-gray-700 bg-gray-100' }} px-2 py-0.5 rounded">
                                    {{ $industry }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="mt-6 space-y-2 flex-1">
                        @foreach($plan['features'] as $feature)
                            <li class="flex items-start gap-2 text-sm {{ $isEnterprise ? 'text-gray-200' : 'text-gray-700' }}">
                                <i class="fa-regular fa-circle-check text-teal-500 mt-0.5 flex-shrink-0"></i>
                                <span>{{ $feature['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Upgrade to unlock (for Lite) -->
                    @if(!empty($plan['upgrade_to_unlock']))
                        <div class="mt-4 pt-4 border-t {{ $isEnterprise ? 'border-gray-700' : 'border-gray-200' }}">
                            <p class="text-xs font-medium {{ $isEnterprise ? 'text-gray-400' : 'text-gray-400' }} uppercase tracking-wider">
                                Upgrade to unlock
                            </p>
                            <ul class="mt-2 space-y-1">
                                @foreach($plan['upgrade_to_unlock'] as $item)
                                    <li class="flex items-center gap-2 text-xs {{ $isEnterprise ? 'text-gray-400' : 'text-gray-400' }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square {{ $isEnterprise ? 'text-gray-500' : 'text-gray-300' }}"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- CTA Button -->
                    <div class="mt-8">
                        <a href="{{ $plan['cta_link'] }}"
                           class="block text-center font-semibold py-3 px-4 rounded-xl transition-all duration-200
                                  {{ $plan['popular'] ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg hover:shadow-xl' : '' }}
                                  {{ $plan['slug'] === 'lite' ? 'bg-green-600 hover:bg-green-700 text-white' : '' }}
                                  {{ $plan['slug'] === 'business' ? 'bg-purple-600 hover:bg-purple-700 text-white' : '' }}
                                  {{ $isEnterprise ? 'bg-gray-700 hover:bg-gray-600 text-white' : '' }}
                                  {{ !$plan['popular'] && $plan['slug'] !== 'lite' && $plan['slug'] !== 'business' && !$isEnterprise ? 'bg-gray-800 hover:bg-gray-900 text-white' : '' }}">
                            {{ $plan['cta'] }}
                        </a>
                        @if($plan['footer_text'])
                            <p class="text-xs text-center mt-2 {{ $isEnterprise ? 'text-gray-400' : 'text-gray-500' }}">
                                {{ $plan['footer_text'] }}
                            </p>
                        @endif
                    </div>

                    <!-- Trial Badge for Pro and Business -->
@if(in_array($plan['slug'], ['pro', 'business']))
    <p class="text-xs text-center {{ $plan['slug'] === 'pro' ? 'text-blue-600' : 'text-purple-600' }} mt-2 font-medium">
        🔥 14-day free trial. No credit card required.
    </p>
@endif
                </div>
            @endforeach

        </div>

        <!-- Fair Use Policy Note -->
        <p class="text-center text-xs text-gray-400 mt-8">
            * Unlimited features are subject to our
            <a href="{{ route('pages.terms') }}" class="text-teal-600 hover:underline">Fair Use Policy</a>.
        </p>

        <!-- Industry Recommendation Banner -->
        <div class="mt-12 bg-gray-50 rounded-2xl p-6 border border-gray-200">
            <p class="text-center text-sm font-medium text-gray-700 mb-4">Not sure which plan to choose?</p>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2 text-xs">
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-green-600">Lite</span>
                    <span class="text-gray-500">🏪 Kirana</span>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-blue-600">Pro</span>
                    <span class="text-gray-500">🍽️ Restaurant</span>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-blue-600">Pro</span>
                    <span class="text-gray-500">🏫 School</span>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-green-600">Lite</span>
                    <span class="text-gray-500">💊 Pharmacy</span>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-purple-600">Business</span>
                    <span class="text-gray-500">📦 Wholesale</span>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-center shadow-sm border border-gray-100">
                    <span class="block font-bold text-gray-800">Enterprise</span>
                    <span class="text-gray-500">🏬 Supermarket</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FEATURE COMPARISON TABLE -->
<!-- ============================================================ -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-12">
            Compare <span class="text-gradient">All Features</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm bg-white rounded-2xl shadow-lg border border-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Feature</th>
                        <th class="px-4 py-3 text-center font-semibold text-green-600">Lite</th>
                        <th class="px-4 py-3 text-center font-semibold text-blue-600 bg-blue-50">Pro</th>
                        <th class="px-4 py-3 text-center font-semibold text-purple-600">Business</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-800">Enterprise</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $compareFeatures = [
                            ['label' => 'Products', 'lite' => '300 SKUs', 'pro' => 'Unlimited*', 'business' => 'Unlimited*', 'enterprise' => 'Unlimited'],
                            ['label' => 'Branches', 'lite' => '1', 'pro' => '3', 'business' => '10', 'enterprise' => 'Unlimited'],
                            ['label' => 'Users', 'lite' => '3', 'pro' => '20', 'business' => '100', 'enterprise' => 'Unlimited'],
                            ['label' => 'Invoices / Month', 'lite' => '400', 'pro' => '5,000', 'business' => '25,000', 'enterprise' => 'Unlimited'],
                            ['label' => 'Storage', 'lite' => '200 MB', 'pro' => '2 GB', 'business' => '10 GB', 'enterprise' => 'Custom'],
                            ['label' => 'POS', 'lite' => '✅', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'Inventory', 'lite' => 'Basic', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'Purchase Module', 'lite' => '—', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'Finance Module', 'lite' => '—', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'AI Assistant', 'lite' => '—', 'pro' => 'Basic', 'business' => 'Advanced', 'enterprise' => 'Custom'],
                            ['label' => 'API Access', 'lite' => '—', 'pro' => 'Basic', 'business' => 'Full', 'enterprise' => 'Custom'],
                            ['label' => 'Support', 'lite' => 'Email', 'pro' => 'WhatsApp', 'business' => 'Priority', 'enterprise' => 'Dedicated'],
                            ['label' => 'Reports', 'lite' => 'Basic', 'pro' => 'Advanced', 'business' => 'Advanced + Analytics', 'enterprise' => 'Custom'],
                            ['label' => 'Role Management', 'lite' => '—', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'Backups', 'lite' => 'Weekly', 'pro' => 'Daily', 'business' => 'Real-time', 'enterprise' => 'Custom'],
                            ['label' => 'Audit Logs', 'lite' => '—', 'pro' => '✅', 'business' => '✅', 'enterprise' => '✅'],
                            ['label' => 'Custom Branding', 'lite' => '—', 'pro' => '—', 'business' => '—', 'enterprise' => '✅'],
                            ['label' => 'Dedicated Server', 'lite' => '—', 'pro' => '—', 'business' => '—', 'enterprise' => '✅'],
                        ];
                    @endphp
                    @foreach($compareFeatures as $row)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $row['label'] }}</td>
                            <td class="px-4 py-3 text-center text-green-600">{{ $row['lite'] }}</td>
                            <td class="px-4 py-3 text-center text-blue-600 bg-blue-50/50">{{ $row['pro'] }}</td>
                            <td class="px-4 py-3 text-center text-purple-600">{{ $row['business'] }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">{{ $row['enterprise'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            * Unlimited features subject to Fair Use Policy.
        </p>
    </div>
</section>

<!-- ============================================================ -->
<!-- "PERFECT FOR" INDUSTRY CARDS -->
<!-- ============================================================ -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-4">
            Built for <span class="text-gradient">Every Nepali Business</span>
        </h2>
        <p class="text-center text-gray-500 mb-12">Find the perfect plan for your industry</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $industries = [
                    ['name' => 'Retail', 'icon' => '🏪', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'Restaurant', 'icon' => '🍽️', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'School', 'icon' => '🏫', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'Pharmacy', 'icon' => '💊', 'plan' => 'Lite', 'color' => 'green'],
                    ['name' => 'Bakery', 'icon' => '🧁', 'plan' => 'Lite', 'color' => 'green'],
                    ['name' => 'Travel', 'icon' => '✈️', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'NGO', 'icon' => '🤝', 'plan' => 'Business', 'color' => 'purple'],
                    ['name' => 'Wholesale', 'icon' => '📦', 'plan' => 'Business', 'color' => 'purple'],
                    ['name' => 'Hardware', 'icon' => '🔧', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'Electronics', 'icon' => '💻', 'plan' => 'Pro', 'color' => 'blue'],
                    ['name' => 'Cooperative', 'icon' => '🏢', 'plan' => 'Business', 'color' => 'purple'],
                    ['name' => 'Hospital', 'icon' => '🏥', 'plan' => 'Business', 'color' => 'purple'],
                ];
            @endphp
            @foreach($industries as $industry)
                <div class="bg-gray-50 hover:bg-gray-100 rounded-xl p-4 text-center transition border border-gray-100">
                    <span class="text-2xl block">{{ $industry['icon'] }}</span>
                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $industry['name'] }}</p>
                    <span class="text-xs font-bold text-{{ $industry['color'] }}-600 bg-{{ $industry['color'] }}-50 px-3 py-0.5 rounded-full inline-block mt-1">
                        ⭐ {{ $industry['plan'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FAQ SECTION (Accordion) -->
<!-- ============================================================ -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-12">
            Frequently Asked <span class="text-gradient">Questions</span>
        </h2>

        <div x-data="faqAccordion()" class="space-y-3">

            @php
                $faqs = [
                    ['id' => 1, 'q' => 'Can I upgrade or downgrade anytime?', 'a' => 'Yes! You can upgrade, downgrade, or cancel your subscription at any time from your dashboard. No long-term contracts, no hidden fees. Your data stays safe.'],
                    ['id' => 2, 'q' => 'Is my data safe and secure?', 'a' => 'Absolutely. We use enterprise-grade encryption, automatic daily backups, and strict access controls. Your data is yours, always.'],
                    ['id' => 3, 'q' => 'Do I need a credit card to start?', 'a' => 'No. You can start with a 14-day free trial on Pro and Business plans. No credit card required.'],
                    ['id' => 4, 'q' => 'Do you offer refunds?', 'a' => 'Yes. We offer a 30-day money-back guarantee on all paid plans. If you\'re not satisfied, we\'ll refund your payment.'],
                    ['id' => 5, 'q' => 'Can I switch from monthly to yearly billing?', 'a' => 'Yes, you can switch anytime from your billing settings. You\'ll get a pro-rated adjustment for the remaining period.'],
                    ['id' => 6, 'q' => 'How does the 14-day free trial work?', 'a' => 'You get full access to Pro or Business features for 14 days. No credit card required. If you don\'t cancel, your paid subscription starts automatically.'],
                ];
            @endphp

            @foreach($faqs as $faq)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <button @click="toggle({{ $faq['id'] }})"
                            @keydown.enter="toggle({{ $faq['id'] }})"
                            @keydown.space.prevent="toggle({{ $faq['id'] }})"
                            :aria-expanded="isOpen({{ $faq['id'] }})"
                            class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center transition focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 rounded-xl">
                        <span>{{ $faq['q'] }}</span>
                        <i class="fa-solid text-teal-600 transition-transform duration-200"
                           :class="isOpen({{ $faq['id'] }}) ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="isOpen({{ $faq['id'] }})"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="px-6 pb-4 text-gray-500 text-sm leading-relaxed"
                         x-cloak>
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CTA: Need help choosing? -->
<!-- ============================================================ -->
<section class="py-16 px-4 bg-white border-t border-gray-100">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
            Need help choosing the right plan?
        </h2>
        <p class="text-gray-500 mb-6">Our team is here to help you find the perfect fit for your business.</p>
        <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-3 rounded-xl transition shadow-lg hover:shadow-xl">
            <i class="fa-regular fa-comment"></i> Talk to Sales
        </a>
    </div>
</section>

<!-- ============================================================ -->
<!-- FINAL CTA -->
<!-- ============================================================ -->
<section class="py-16 px-4 gradient-bg">
    <div class="max-w-4xl mx-auto text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to simplify your business operations?</h2>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto mb-8">
            One platform for inventory, POS, accounting, reports, and business growth.
        </p>
        <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 px-12 py-4 rounded-xl text-lg font-bold shadow-xl hover:shadow-2xl transition-all hover:scale-105">
            Choose Your Plan
        </a>
        <p class="text-blue-200 text-sm mt-4">No credit card required. 14-day free trial on Pro.</p>
    </div>
</section>

<!-- Alpine.js FAQ Accordion Logic -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('faqAccordion', () => ({
            open: null,
            toggle(id) {
                this.open = this.open === id ? null : id;
            },
            isOpen(id) {
                return this.open === id;
            }
        }));
    });
</script>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection
