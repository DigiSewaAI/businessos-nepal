@extends('layouts.app')

@section('title', branding('meta_title', 'BusinessOS - SME Operating System'))

@section('content')

<!-- ============ HERO SECTION ============ -->
<section class="pt-32 pb-16 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30 overflow-hidden">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
        <!-- Left: Text -->
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-sm font-medium border border-blue-100">
                <i class="fa-solid fa-crown text-xs"></i>
                {{ branding('hero_badge', "Nepal's #1 SME Operating System") }}
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight text-gray-900">
                One Platform. <br />
                <span class="text-gradient">Every Business.</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                Inventory, POS, Accounting, and Reports — {{ branding('hero_subtitle', 'everything your business needs to grow') }}
                <span class="font-semibold text-teal-700">Free to start.</span>
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('register') }}" class="gradient-bg text-white px-8 py-3 rounded-xl text-lg font-semibold shadow-xl hover:shadow-2xl transition-all hover:scale-105 flex items-center gap-2">
                    {{ branding('cta_button_text', 'Start Free Trial') }} <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ route('ai.chat') }}" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-xl text-lg font-medium hover:bg-gray-50 transition-all flex items-center gap-2">
    <i class="fa-regular fa-comment-dots"></i> Ask AI
</a>
                <a href="#features" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-xl text-lg font-medium hover:bg-gray-50 transition-all flex items-center gap-2">
                    <i class="fa-regular fa-circle-play"></i> {{ branding('cta_alt_button_text', 'See How') }}
                </a>
            </div>
            <p class="text-sm text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-check-circle text-teal-500"></i> No credit card required
            </p>
        </div>

        <!-- Right: Dashboard Mockup -->
        <div class="relative flex justify-center">
            <div class="relative w-full max-w-lg">
                <div class="bg-white rounded-2xl shadow-2xl p-5 border border-gray-200 transform rotate-1 hover:rotate-0 transition-all duration-500">
                    <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500 live-dot"></div>
                                <span class="text-xs font-mono text-gray-500 font-bold">LIVE</span>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold">Dashboard</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Today's Sales</p>
                                <p class="text-xl font-extrabold text-gray-800">₨ 45,200</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Profit</p>
                                <p class="text-xl font-extrabold text-green-600">₨ 12,500</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Low Stock</p>
                                <p class="text-xl font-extrabold text-red-600">8</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Cash Balance</p>
                                <p class="text-xl font-extrabold text-blue-600">₨ 82,000</p>
                            </div>
                        </div>

                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 flex items-center justify-between float-notification">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-600">
                                    <i class="fa-solid fa-receipt text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Recent Sale</p>
                                    <p class="text-xs text-gray-500">2 min ago</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-teal-700">₨ 5,200</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-blue-100 text-blue-700 p-3 rounded-full shadow-lg border-2 border-white">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <div class="absolute -bottom-4 -left-4 bg-teal-100 text-teal-700 p-3 rounded-full shadow-lg border-2 border-white">
                    <i class="fa-solid fa-rocket text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ TRUST BADGES ============ -->
<section class="py-12 bg-white border-y border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-3xl font-extrabold text-gray-800">500+</p>
                <p class="text-sm text-gray-500">Trusted by SMEs</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800">₨ 10M+</p>
                <p class="text-sm text-gray-500">Processed</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800">99.9%</p>
                <p class="text-sm text-gray-500">Uptime</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-gray-800">4.8★</p>
                <p class="text-sm text-gray-500">User Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ INDUSTRIES ============ -->
<section id="industries" class="py-20 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ branding('industries_title', 'Built for every business') }}</h2>
            <p class="text-gray-500 mt-4">Retail, wholesale, services, and more.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 md:gap-4">
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Retail</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Wholesale</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Electronics</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Hardware</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Furniture</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Bakery</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Gym</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Travel</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">NGO</span>
            <span class="bg-white px-6 py-3 rounded-full shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:shadow-md transition-all">Cooperative</span>
        </div>
    </div>
</section>

<!-- ============ WHY BUSINESSOS (FEATURES) ============ -->
<section id="features" class="py-20 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Why <span class="text-gradient">{{ branding('brand_name') }}</span>?</h2>
            <p class="text-gray-500 mt-4">Built for Nepal, powered by Laravel.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $features = [
                    ['icon' => 'fa-brain', 'color' => 'blue', 'title' => 'AI Ready', 'desc' => 'Smart insights & forecasts.'],
                    ['icon' => 'fa-sitemap', 'color' => 'teal', 'title' => 'Multi-Branch', 'desc' => 'Unlimited branches, one dashboard.'],
                    ['icon' => 'fa-language', 'color' => 'indigo', 'title' => 'Nepali Ready', 'desc' => 'नेपाली + English interface.'],
                    ['icon' => 'fa-chart-simple', 'color' => 'green', 'title' => 'Real-time Reports', 'desc' => 'Instant insights, zero delay.'],
                    ['icon' => 'fa-users-gear', 'color' => 'amber', 'title' => 'Role Based Access', 'desc' => 'Granular permissions for teams.'],
                    ['icon' => 'fa-qrcode', 'color' => 'rose', 'title' => 'Barcode Ready', 'desc' => 'SKU & barcode support.'],
                    ['icon' => 'fa-cloud-arrow-up', 'color' => 'purple', 'title' => 'Cloud Backup', 'desc' => 'Automatic, secure backups.'],
                    ['icon' => 'fa-chart-pie', 'color' => 'cyan', 'title' => 'Business Analytics', 'desc' => 'Profit, stock, sales insights.'],
                ];
            @endphp
            @foreach($features as $f)
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 card-hover">
                <div class="w-12 h-12 rounded-xl bg-{{ $f['color'] }}-50 text-{{ $f['color'] }}-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid {{ $f['icon'] }}"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">{{ $f['title'] }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ AI ASSISTANT SECTION (NEW) ============ -->
<section class="py-20 px-4 bg-gradient-to-r from-blue-50 via-white to-teal-50">
    <div class="max-w-5xl mx-auto text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <span class="text-4xl">🤖</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Your <span class="text-gradient">AI Business Assistant</span>
            </h2>
        </div>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
            Ask anything about your business — sales, stock, profit, or get instant insights. 
            Just type your question and get answers powered by AI, completely free.
        </p>

        <!-- Chat Preview Box -->
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-2xl mx-auto border border-gray-200">
            <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3 border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm font-bold">
                    <i class="fa-regular fa-comment-dots"></i>
                </div>
                <input type="text" 
                       class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-gray-700 placeholder-gray-400 cursor-default" 
                       placeholder="Ask a question... e.g., 'What are my top products?'" readonly>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition cursor-default opacity-80">
                    <i class="fa-regular fa-paper-plane"></i> Ask
                </button>
            </div>
            <div class="text-xs text-gray-400 mt-3 flex flex-wrap justify-center gap-3">
                <span class="bg-gray-100 px-2 py-1 rounded-full">💡 "Today's sales"</span>
                <span class="bg-gray-100 px-2 py-1 rounded-full">💡 "Low stock items"</span>
                <span class="bg-gray-100 px-2 py-1 rounded-full">💡 "Profit summary"</span>
                <span class="bg-gray-100 px-2 py-1 rounded-full">💡 "Student attendance"</span>
            </div>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('ai.chat') }}" 
           class="inline-block mt-8 bg-gradient-to-r from-blue-600 to-teal-500 text-white px-10 py-4 rounded-xl text-lg font-bold shadow-xl hover:shadow-2xl transition-all hover:scale-105">
            <i class="fa-regular fa-comment-dots mr-2"></i> Try AI Assistant Now
        </a>
        <p class="text-sm text-gray-400 mt-3">✨ No login required for demo — but you'll get personalized insights after login.</p>
    </div>
</section>

<!-- ============ COMPARISON TABLE ============ -->
<section class="py-20 px-4 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Stop juggling tools. <span class="text-gradient">Start growing.</span></h2>
        </div>
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-600">Feature</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600">Excel</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600">Paper</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-600">WhatsApp</th>
                        <th class="px-6 py-4 text-center font-semibold text-teal-600 bg-teal-50">{{ branding('brand_name') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $rows = [
                            ['label' => 'Inventory Tracking', 'excel' => false, 'paper' => false, 'whatsapp' => false, 'biz' => true],
                            ['label' => 'POS & Invoices', 'excel' => false, 'paper' => false, 'whatsapp' => false, 'biz' => true],
                            ['label' => 'Reports & Analytics', 'excel' => false, 'paper' => false, 'whatsapp' => false, 'biz' => true],
                            ['label' => 'Multi-Branch', 'excel' => false, 'paper' => false, 'whatsapp' => false, 'biz' => true],
                            ['label' => 'Cloud & Mobile', 'excel' => false, 'paper' => false, 'whatsapp' => false, 'biz' => true],
                        ];
                    @endphp
                    @foreach($rows as $r)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $r['label'] }}</td>
                        <td class="px-6 py-4 text-center text-gray-400"><i class="fa-solid fa-xmark check-no text-xl"></i></td>
                        <td class="px-6 py-4 text-center text-gray-400"><i class="fa-solid fa-xmark check-no text-xl"></i></td>
                        <td class="px-6 py-4 text-center text-gray-400"><i class="fa-solid fa-xmark check-no text-xl"></i></td>
                        <td class="px-6 py-4 text-center text-teal-600 bg-teal-50/50"><i class="fa-solid fa-check check-yes text-xl"></i></td>
                    </tr>
                    @endforeach
                    <tr class="border-t-2 border-gray-200">
                        <td class="px-6 py-4 font-bold text-gray-800">Price</td>
                        <td class="px-6 py-4 text-center text-gray-500">Free <span class="text-xs block text-gray-400">but manual</span></td>
                        <td class="px-6 py-4 text-center text-gray-500">Costly</td>
                        <td class="px-6 py-4 text-center text-gray-500">Messy</td>
                        <td class="px-6 py-4 text-center font-bold text-teal-700 bg-teal-50/50">Free to start</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="py-20 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Start in <span class="text-gradient">3 easy steps</span></h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @php
                $steps = [
                    ['num' => '1', 'title' => 'Sign Up Free', 'desc' => '30 seconds. No card.'],
                    ['num' => '2', 'title' => 'Add Products', 'desc' => 'Import or add manually.'],
                    ['num' => '3', 'title' => 'Start Selling', 'desc' => 'Open POS & grow.'],
                ];
            @endphp
            @foreach($steps as $s)
            <div class="text-center">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-lg">{{ $s['num'] }}</div>
                <h3 class="text-xl font-semibold mt-6 mb-2">{{ $s['title'] }}</h3>
                <p class="text-gray-500 text-sm">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="py-20 px-4 bg-gray-50">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12">{{ branding('testimonials_title', 'Trusted by businesses worldwide') }}</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-left">
                <div class="flex text-teal-500 mb-3">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="text-gray-600 italic">“BusinessOS made our inventory management so easy. We saved 10+ hours per week.”</p>
                <p class="font-semibold text-gray-800 mt-4">– Retail Shop, Kathmandu</p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-left">
                <div class="flex text-teal-500 mb-3">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="text-gray-600 italic">“The POS is lightning fast. Our cashiers love it.”</p>
                <p class="font-semibold text-gray-800 mt-4">– Electronics Store, Pokhara</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ PRICING ============ -->
<section id="pricing" class="py-20 px-4 gradient-bg">
    <div class="max-w-7xl mx-auto text-center text-white">
        <h2 class="text-3xl md:text-5xl font-bold mb-4">Simple, transparent <span class="underline decoration-teal-300 decoration-4">pricing</span></h2>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto mb-12">Start free, scale as you grow.</p>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Starter -->
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 text-center">
                <h3 class="text-xl font-bold text-white">Starter</h3>
                <p class="text-4xl font-extrabold mt-4 text-white">Free</p>
                <ul class="text-sm text-blue-100 space-y-2 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> 100 Products</li>
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> 1 Branch</li>
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> Basic Reports</li>
                </ul>
                <a href="{{ route('register') }}" class="block mt-8 bg-white text-blue-700 py-2.5 px-6 rounded-lg font-semibold hover:bg-gray-100 transition">{{ branding('cta_button_text', 'Get Started') }}</a>
            </div>

            <!-- Pro -->
            <div class="bg-white rounded-2xl p-8 text-gray-900 shadow-2xl transform md:scale-105 border-4 border-teal-300 relative">
                <span class="absolute -top-3 right-4 bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full">Popular</span>
                <h3 class="text-xl font-bold">Pro</h3>
                <p class="text-4xl font-extrabold mt-4 text-gray-800">Rs. 999<span class="text-sm font-normal text-gray-400">/mo</span></p>
                <ul class="text-sm text-gray-600 space-y-2 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-600 mr-2"></i> Unlimited Products</li>
                    <li><i class="fa-solid fa-check text-teal-600 mr-2"></i> Unlimited Branches</li>
                    <li><i class="fa-solid fa-check text-teal-600 mr-2"></i> Advanced Reports</li>
                    <li><i class="fa-solid fa-check text-teal-600 mr-2"></i> Priority Support</li>
                </ul>
                <a href="{{ route('register') }}" class="block mt-8 gradient-bg text-white py-2.5 px-6 rounded-lg font-semibold hover:shadow-lg transition">{{ branding('cta_button_text', 'Start Trial') }}</a>
            </div>

            <!-- Enterprise -->
            <div class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl border border-white/20 text-center">
                <h3 class="text-xl font-bold text-white">Enterprise</h3>
                <p class="text-4xl font-extrabold mt-4 text-white">Custom</p>
                <ul class="text-sm text-blue-100 space-y-2 mt-6 text-left">
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> Everything in Pro</li>
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> Dedicated Support</li>
                    <li><i class="fa-solid fa-check text-teal-300 mr-2"></i> Custom Integrations</li>
                </ul>
                <a href="#" class="block mt-8 bg-white text-blue-700 py-2.5 px-6 rounded-lg font-semibold hover:bg-gray-100 transition">Contact Us</a>
            </div>
        </div>
        <p class="text-blue-200 text-sm mt-8">* 14-day free trial on Pro. No credit card required.</p>
    </div>
</section>

<!-- ============ FAQ ============ -->
<section class="py-20 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-12">Frequently Asked <span class="text-gradient">Questions</span></h2>
        <div x-data="{ open1: false, open2: false, open3: false }" class="space-y-4">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="open1 = !open1" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                    Can I use BusinessOS on mobile?
                    <i :class="open1 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-teal-600"></i>
                </button>
                <div x-show="open1" x-transition.duration.300ms class="px-6 pb-4 text-gray-500 text-sm">
                    Yes! BusinessOS is fully responsive and works on any smartphone, tablet, or desktop. A dedicated mobile app is planned for future releases.
                </div>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="open2 = !open2" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                    Is my data secure?
                    <i :class="open2 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-teal-600"></i>
                </button>
                <div x-show="open2" x-transition.duration.300ms class="px-6 pb-4 text-gray-500 text-sm">
                    Absolutely. We use enterprise-grade encryption, automatic daily backups, and strict access controls. Your data is yours, always.
                </div>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="open3 = !open3" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                    Can I upgrade or cancel anytime?
                    <i :class="open3 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-teal-600"></i>
                </button>
                <div x-show="open3" x-transition.duration.300ms class="px-6 pb-4 text-gray-500 text-sm">
                    Yes. You can upgrade, downgrade, or cancel your subscription at any time. No long-term contracts, no hidden fees.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ FINAL CTA ============ -->
<section class="py-20 px-4 gradient-bg">
    <div class="max-w-4xl mx-auto text-center text-white">
        <h2 class="text-3xl md:text-5xl font-bold mb-6">Ready to transform <br> your business?</h2>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto mb-10">Join 500+ Nepali businesses already using BusinessOS.</p>
        <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 px-12 py-4 rounded-xl text-lg font-bold shadow-xl hover:shadow-2xl transition-all hover:scale-105">
            {{ branding('cta_button_text', 'Start Free Trial') }}
        </a>
    </div>
</section>

@endsection