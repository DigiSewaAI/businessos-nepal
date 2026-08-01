<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BusinessOS - Admin')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <style>
        /* Sidebar styles */
        .sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }
        /* Desktop: sidebar always visible */
        @media (min-width: 1024px) {
            .sidebar {
                display: block !important;
                transform: translateX(0) !important;
            }
        }
        .sidebar-item {
            transition: all 0.2s ease;
        }
        .sidebar-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }
        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: #2563eb;
        }
        .sidebar-item.active i {
            color: #2563eb;
        }
        .sidebar-overlay {
            z-index: 35;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .badge-new {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .sidebar-scroll {
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gray-50 font-sans antialiased">

    <!-- ============ SIDEBAR OVERLAY (Mobile) ============ -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 sidebar-overlay lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
    </div>

    <!-- ============ SIDEBAR ============ -->
    <aside x-data="{ sidebarOpen: false }"
           @click.away="sidebarOpen = false"
           class="sidebar fixed top-0 left-0 h-full w-72 bg-white border-r border-gray-200 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out"
           x-show="sidebarOpen"
           x-transition:enter="transition-transform duration-300 ease-in-out"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition-transform duration-300 ease-in-out"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           style="display: none;"
           :style="sidebarOpen ? 'display: block;' : 'display: none;'">
        <div class="flex flex-col h-full">
            <!-- Sidebar Logo -->
            <div class="flex items-center justify-between px-6 h-20 border-b border-gray-200 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset(branding('logo')) }}" alt="{{ branding('brand_name') }}" style="height: 80px; width: auto;">
                    @if(branding('country_badge'))
                        <span class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ branding('country_badge') }}</span>
                    @endif
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <nav class="flex-1 px-4 py-6 sidebar-scroll">
                @auth
                    @php
                        try {
                            $sidebar = app(App\Services\Sidebar\SidebarService::class)->getSidebar();
                        } catch (\Exception $e) {
                            $sidebar = [];
                        }
                    @endphp

                    <div class="space-y-1">
                        @foreach($sidebar as $item)
                            @if(isset($item['permission']) && !auth()->user()->can($item['permission']))
                                @continue
                            @endif
                            @if(!Route::has($item['route']))
                                @continue
                            @endif
                            <a href="{{ route($item['route']) }}"
                               class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 transition-all
                               {{ request()->routeIs($item['active']) ? 'active text-blue-600' : '' }}">
                                <i class="fa-solid {{ $item['icon'] }} w-5 text-center text-gray-400 {{ request()->routeIs($item['active']) ? 'text-blue-600' : '' }}"></i>
                                <span class="flex-1">{{ $item['label'] }}</span>
                                @if(isset($item['badge']))
                                    <span class="text-[10px] bg-blue-600 text-white px-1.5 py-0.5 rounded-full leading-none badge-new">{{ $item['badge'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Organization & Branch -->
                    <div class="space-y-1">
                        <a href="{{ route('organization.edit') }}"
                           class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 transition-all">
                            <i class="fa-regular fa-building w-5 text-center text-gray-400"></i>
                            <span>Organization</span>
                        </a>
                        <a href="{{ route('branches.index') }}"
                           class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 transition-all">
                            <i class="fa-regular fa-code-branch w-5 text-center text-gray-400"></i>
                            <span>Branches</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 transition-all w-full">
                            <i class="fa-solid fa-sign-out-alt w-5 text-center text-red-400"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @endauth
            </nav>
        </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="lg:pl-72 min-h-screen">
        <!-- Top Navbar -->
        <nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-30 lg:left-72 h-16 flex items-center justify-between px-4 shadow-sm">
            <!-- Left: Sidebar Toggle (Mobile) + Logo (Mobile) -->
            <div class="flex items-center gap-3 lg:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-900 p-1.5 rounded-lg hover:bg-gray-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset(branding('logo')) }}" alt="{{ branding('brand_name') }}" style="height: 40px; width: auto;">
                </a>
            </div>

            <!-- Center: Page Title (Optional) -->
            <div class="hidden lg:block flex-1">
                <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            </div>

            <!-- Right: User Profile -->
            <div class="flex items-center gap-3 ml-auto">
                <!-- AI Shortcut -->
                <a href="{{ route('ai.chat') }}" class="text-gray-500 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 relative" title="AI Assistant">
                    <i class="fa-regular fa-comment-dots text-lg"></i>
                    <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                </a>

                <!-- User Info -->
                <div class="flex items-center gap-2 border-l border-gray-200 pl-3">
                    <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-medium text-gray-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-gray-400 leading-tight">{{ auth()->user()->organization->name ?? '' }}</p>
                    </div>
                </div>

                <!-- Logout (Mobile only) -->
                <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-600 p-1">
                        <i class="fa-solid fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16 pb-8 px-4">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
