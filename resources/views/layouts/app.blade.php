<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', branding('meta_title', 'BusinessOS - SME Operating System'))</title>
    
    <!-- Tailwind CSS (CDN - Temporary, Vite pachi replace garne) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <style>
        /* Custom Styles */
        html {
            scroll-behavior: smooth;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .text-gradient {
            background: linear-gradient(to right, #1e3a8a, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .live-dot {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .float-notification {
            animation: floatUp 2s ease-in-out infinite;
        }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .check-yes { color: #0d9488; }
        .check-no { color: #9ca3af; }

        /* AI Floating Button Animation */
        .ai-pulse {
            animation: aiPulse 2s ease-in-out infinite;
        }
        @keyframes aiPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 0 20px rgba(59, 130, 246, 0); }
        }
    </style>

    <!-- Extra Styles for Specific Pages -->
    @stack('styles')

    <!-- SEO Meta, OpenGraph, JSON-LD -->
    @include('partials.seo')
</head>
<body class="font-sans antialiased bg-white">

    <!-- ============ NAVBAR ============ -->
    <nav x-data="{ open: false }" class="bg-white shadow-sm fixed w-full z-50 top-0 left-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <img src="{{ asset(branding('logo')) }}" alt="{{ branding('brand_name') }}" style="height: 90px; width: auto;">
                        @if(branding('country_badge'))
                            <span class="hidden md:inline-block text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ branding('country_badge') }}</span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Nav -->
<div class="hidden md:flex items-center space-x-8">
    <!-- Public Links -->
    <a href="{{ route('pages.features') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Features</a>
    <a href="{{ route('pages.industries') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Industries</a>
    <a href="{{ route('pages.pricing') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Pricing</a>

    @auth
        <!-- ✅ Dynamic Sidebar with Route Existence Check -->
        @php
            try {
                $sidebar = app(App\Services\Sidebar\SidebarService::class)->getSidebar();
            } catch (\Exception $e) {
                $sidebar = [];
            }
        @endphp
        @foreach($sidebar as $item)
            @if(isset($item['permission']) && !auth()->user()->can($item['permission']))
                @continue
            @endif
            {{-- ✅ Skip if route doesn't exist --}}
            @if(!Route::has($item['route']))
                @continue
            @endif
            <a href="{{ route($item['route']) }}" 
               class="text-gray-600 hover:text-blue-700 transition font-medium text-sm flex items-center gap-1.5 {{ request()->routeIs($item['active']) ? 'text-blue-600' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
                @if(isset($item['badge']))
                    <span class="text-[10px] bg-blue-600 text-white px-1.5 py-0.5 rounded-full leading-none">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach

        <!-- Org & Branches (Always visible) -->
        <a href="{{ route('organization.edit') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">
            <i class="fa-regular fa-building mr-1"></i> Org
        </a>
        <a href="{{ route('branches.index') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">
            <i class="fa-regular fa-code-branch mr-1"></i> Branches
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800 transition font-medium text-sm">
                <i class="fa-solid fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Login</a>
    @endauth

    @guest
        <a href="{{ route('register') }}" class="gradient-bg text-white px-5 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all hover:scale-105">
            {{ branding('cta_button_text', 'Start Free') }}
        </a>
    @endguest
</div>

                <!-- Mobile Toggle -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2">
                        <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (unchanged – keep as is) -->
        <div x-show="open" x-transition.duration.300ms.opacity class="md:hidden bg-white border-b border-gray-100 py-4 px-4 shadow-lg">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('pages.features') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Features</a>
                <a href="{{ route('pages.industries') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Industries</a>
                <a href="{{ route('pages.pricing') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Pricing</a>
                <hr class="border-gray-200">

                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Dashboard</a>
                    <a href="{{ route('sales.pos') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">POS</a>
                    <a href="{{ route('organization.edit') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">
                        <i class="fa-regular fa-building mr-2"></i> Organization Settings
                    </a>
                    <a href="{{ route('branches.index') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">
                        <i class="fa-regular fa-code-branch mr-2"></i> Branches
                    </a>
                    <a href="{{ route('products.search') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <i class="fa-solid fa-search"></i> Search Products
                    </a>
                    <a href="{{ route('ai.chat') }}" class="text-blue-600 hover:text-blue-800 transition font-medium px-3 py-2 rounded-lg hover:bg-blue-50 flex items-center gap-2">
                        <i class="fa-regular fa-comment-dots text-blue-500"></i> AI Assistant
                        <span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full">New</span>
                    </a>
                    
                    <!-- Plan & Upgrade -->
                    @php
                        $plan = auth()->user()->organization->plan ?? null;
                    @endphp
                    @if($plan)
                        <div class="flex items-center justify-between px-3 py-2 bg-teal-50 rounded-lg">
                            <span class="text-sm font-medium text-teal-800">Plan: {{ $plan->name }}</span>
                            @if($plan->slug === 'starter')
                                <a href="{{ route('pages.pricing') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-800 underline">Upgrade</a>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 transition font-medium px-3 py-2 text-left w-full rounded-lg hover:bg-red-50">
                            <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Login</a>
                    <a href="{{ route('register') }}" class="gradient-bg text-white text-center px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-xl transition-all">{{ branding('cta_button_text', 'Start Free') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ============ ONBOARDING WARNING BAR ============ -->
    @auth
        @if(!auth()->user()->onboarding_completed)
            <div class="fixed top-24 left-0 right-0 z-40 bg-amber-50 border-b border-amber-200 px-4 py-2 text-center">
                <p class="text-sm text-amber-800">
                    <i class="fa-regular fa-circle-exclamation mr-2"></i>
                    Please complete your business setup to continue.
                    <a href="{{ route('onboarding') }}" class="font-semibold text-amber-600 hover:text-amber-800 underline ml-1">
                        Complete Setup Now
                    </a>
                </p>
            </div>
        @endif
    @endauth

    <!-- ============ MAIN CONTENT ============ -->
    <main class="pt-24 @auth @if(!auth()->user()->onboarding_completed) pt-28 @endif @endauth">
        @yield('content')
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-gray-900 text-gray-400 py-12 px-4 border-t border-gray-800">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <img src="{{ asset(branding('logo')) }}" alt="{{ branding('brand_name') }}" style="height: 80px; width: auto; filter: brightness(0) invert(1);">
                </div>
                <p class="text-sm">{{ branding('footer_description') }}</p>
                <div class="flex space-x-4 mt-4">
                    @foreach(branding('social_links', []) as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="hover:text-white"><i class="fa-brands fa-{{ $platform }}"></i></a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('pages.features') }}" class="hover:text-white">Features</a></li>
                    <li><a href="{{ route('pages.pricing') }}" class="hover:text-white">Pricing</a></li>
                    <li><a href="{{ route('pages.changelog') }}" class="hover:text-white">Changelog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('pages.about') }}" class="hover:text-white">About</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-white">Contact</a></li>
                    <li><a href="{{ route('pages.careers') }}" class="hover:text-white">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('pages.help') }}" class="hover:text-white">Help Center</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-gray-800 mt-8 pt-8 text-center text-sm">
            {{ branding('footer_copyright', '© BusinessOS') }} {{ date('Y') }}. All rights reserved.
        </div>
    </footer>

    <!-- ============ AI FLOATING BUTTON ============ -->
    @auth
    <a href="{{ route('ai.chat') }}" 
       x-data="{ showTooltip: false }"
       @mouseenter="showTooltip = true"
       @mouseleave="showTooltip = false"
       class="fixed bottom-6 right-6 z-50 group">
        <div class="relative">
            <!-- Pulse ring -->
            <div class="absolute inset-0 rounded-full bg-blue-400 opacity-60 ai-pulse"></div>
            
            <!-- Button -->
            <div class="relative bg-gradient-to-r from-blue-600 to-teal-500 text-white p-4 rounded-full shadow-2xl hover:shadow-xl transition hover:scale-110 cursor-pointer">
                <i class="fa-regular fa-comment-dots text-2xl"></i>
            </div>
            
            <!-- Tooltip -->
            <div x-show="showTooltip" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute bottom-full right-0 mb-3 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg whitespace-nowrap shadow-lg">
                Ask AI Assistant <i class="fa-regular fa-arrow-right ml-1"></i>
            </div>
        </div>
    </a>
    @endauth

    @stack('scripts')
</body>
</html>