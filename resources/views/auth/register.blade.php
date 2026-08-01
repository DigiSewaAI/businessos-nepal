<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - BusinessOS Nepal</title>
    
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
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
        }
        .gradient-bg:hover {
            background: linear-gradient(135deg, #1e40af 0%, #0f766e 100%);
        }
        .focus-ring:focus {
            outline: none;
            --tw-ring-color: #0d9488;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-teal-50/30 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">
        <!-- Brand -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center text-white font-bold text-xl shadow-lg">B</div>
                <span class="text-2xl font-bold text-gray-800">Business<span class="text-teal-600">OS</span></span>
                <span class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Nepal</span>
            </a>
            <h2 class="mt-6 text-2xl font-bold text-gray-900">Create Your Account</h2>
            <p class="mt-1 text-sm text-gray-500">Start managing your business in minutes</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                {{-- Full Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror" 
                           placeholder="Ashish Shrestha" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('email') border-red-500 @enderror" 
                           placeholder="your@email.com" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Organization Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Organization Name *</label>
                    <input type="text" name="org_name" value="{{ old('org_name') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('org_name') border-red-500 @enderror" 
                           placeholder="Shrestha Traders" required>
                    @error('org_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ NEW: Industry Dropdown --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Industry *</label>
                    <select name="industry" id="industry" 
                            class="w-full border rounded-lg px-4 py-2 @error('industry') border-red-500 @enderror" required>
                        <option value="">{{ __('Select Industry') }}</option>
                        @php
                            $industries = app(\App\Services\IndustryService::class)->getIndustries();
                        @endphp
                        @foreach($industries as $key => $industry)
                            <option value="{{ $key }}" {{ old('industry') == $key ? 'selected' : '' }}>
                                {{ $industry['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('industry')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ NEW: Business Category Dropdown --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Business Category</label>
                    <select name="business_category" id="business_category" 
                            class="w-full border rounded-lg px-4 py-2 @error('business_category') border-red-500 @enderror">
                        <option value="">{{ __('Select Business Category') }}</option>
                        @php
                            // Show categories for the default industry (retail) initially
                            $defaultIndustry = 'retail';
                            $categories = app(\App\Services\IndustryService::class)->getBusinessCategories($defaultIndustry);
                        @endphp
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('business_category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Phone --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('phone') border-red-500 @enderror" 
                           placeholder="9800000000" required>
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password *</label>
                    <input type="password" name="password" 
                           class="w-full border rounded-lg px-4 py-2 @error('password') border-red-500 @enderror" 
                           placeholder="••••••••" required>
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full border rounded-lg px-4 py-2" 
                           placeholder="••••••••" required>
                </div>
                
                {{-- Terms --}}
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" required class="mr-2">
                        <span class="text-sm">I agree to the <a href="{{ route('pages.terms') }}" class="text-blue-600">Terms of Service</a> and <a href="{{ route('pages.privacy') }}" class="text-blue-600">Privacy Policy</a></span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Create Account & Start Free
                </button>
                
                <p class="text-center text-sm text-gray-600 mt-4">
                    Already have an account? <a href="{{ route('login') }}" class="text-blue-600">Sign in here</a>
                </p>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} BusinessOS Nepal. All rights reserved.
        </p>
    </div>

    {{-- ✅ JavaScript for dynamic Business Category update --}}
    <script>
        (function() {
            // Pre‑load all categories from the backend (no extra API call)
            const allCategories = @json(
                collect(app(\App\Services\IndustryService::class)->getIndustries())
                    ->mapWithKeys(fn($ind, $key) => [$key => $ind['business_categories'] ?? []])
            );

            const industrySelect = document.getElementById('industry');
            const categorySelect = document.getElementById('business_category');

            function updateCategories(industryKey) {
                // Clear current options
                categorySelect.innerHTML = '<option value="">{{ __("Select Business Category") }}</option>';
                
                const categories = allCategories[industryKey] || {};
                for (const [key, label] of Object.entries(categories)) {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = label;
                    // If old value matches, select it
                    if (key === '{{ old('business_category') }}') {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                }
            }

            // Initial update based on the current selected industry (or default to retail)
            const initialIndustry = industrySelect.value || 'retail';
            updateCategories(initialIndustry);

            // Listen to industry change
            industrySelect.addEventListener('change', function() {
                updateCategories(this.value);
            });
        })();
    </script>

</body>
</html>
