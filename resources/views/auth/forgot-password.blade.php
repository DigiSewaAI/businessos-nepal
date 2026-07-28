<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - BusinessOS Nepal</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
        }
        .gradient-bg:hover {
            background: linear-gradient(135deg, #1e40af 0%, #0f766e 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-teal-50/30 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center text-white font-bold text-xl shadow-lg">B</div>
                <span class="text-2xl font-bold text-gray-800">Business<span class="text-teal-600">OS</span></span>
            </a>
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Reset Password</h2>
            <p class="mt-1 text-sm text-gray-500">We'll send you a link to reset your password</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all"
                        placeholder="your@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Send Reset Link
                </button>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-teal-600 hover:text-teal-800 font-medium">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Sign In
                    </a>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} BusinessOS Nepal. All rights reserved.
        </p>
    </div>

</body>
</html>