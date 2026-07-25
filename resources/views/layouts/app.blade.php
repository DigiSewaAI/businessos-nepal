<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BusinessOS Nepal - SME Operating System')</title>
    
    <!-- Tailwind CSS (CDN) -->
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
    </style>
</head>
<body class="font-sans antialiased bg-white">

    <!-- ============ NAVBAR ============ -->
    <nav x-data="{ open: false }" class="bg-white shadow-sm fixed w-full z-50 top-0 left-0 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center text-white font-bold text-lg">B</div>
                        <span class="text-xl font-bold text-gray-800">Business<span class="text-teal-600">OS</span></span>
                        <span class="hidden md:inline-block text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Nepal</span>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Features</a>
                    <a href="#industries" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Industries</a>
                    <a href="#pricing" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Pricing</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700 transition font-medium text-sm">Login</a>
                    @endauth
                    <a href="{{ route('register') }}" class="gradient-bg text-white px-5 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all hover:scale-105">Start Free</a>
                </div>

                <!-- Mobile Toggle -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2">
                        <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition.duration.300ms.opacity class="md:hidden bg-white border-b border-gray-100 py-4 px-4 shadow-lg">
            <div class="flex flex-col space-y-3">
                <a href="#features" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Features</a>
                <a href="#industries" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Industries</a>
                <a href="#pricing" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2 rounded-lg hover:bg-gray-50">Pricing</a>
                <hr class="border-gray-200">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700 transition font-medium px-3 py-2">Login</a>
                @endauth
                <a href="{{ route('register') }}" class="gradient-bg text-white text-center px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-xl transition-all">Start Free</a>
            </div>
        </div>
    </nav>

    <!-- ============ MAIN CONTENT ============ -->
    <main>
        @yield('content')
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-gray-900 text-gray-400 py-12 px-4 border-t border-gray-800">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center text-white font-bold text-lg">B</div>
                    <span class="text-white font-bold text-xl">BusinessOS</span>
                </div>
                <p class="text-sm">Empowering Nepali SMEs with modern technology.</p>
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#features" class="hover:text-white">Features</a></li>
                    <li><a href="#pricing" class="hover:text-white">Pricing</a></li>
                    <li><a href="#" class="hover:text-white">Changelog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">About</a></li>
                    <li><a href="#" class="hover:text-white">Contact</a></li>
                    <li><a href="#" class="hover:text-white">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">Help Center</a></li>
                    <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-gray-800 mt-8 pt-8 text-center text-sm">
            &copy; {{ date('Y') }} BusinessOS Nepal. Made with ❤️ in Nepal.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>