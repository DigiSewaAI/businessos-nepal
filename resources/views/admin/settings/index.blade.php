@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-gear text-white text-sm"></i>
                </span>
                System Settings
            </h1>
            <p class="text-sm text-gray-500 mt-1">Manage platform-wide settings</p>
        </div>

        <!-- Settings Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">App Name</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'BusinessOS Nepal' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select name="app_timezone" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition bg-white">
                            <option value="Asia/Kathmandu" {{ ($settings['app_timezone'] ?? '') === 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu (NPT)</option>
                            <option value="UTC" {{ ($settings['app_timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Kolkata" {{ ($settings['app_timezone'] ?? '') === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <input type="text" name="app_currency" value="{{ $settings['app_currency'] ?? 'NPR' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Language</label>
                        <select name="app_language" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition bg-white">
                            <option value="en" {{ ($settings['app_language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="np" {{ ($settings['app_language'] ?? '') === 'np' ? 'selected' : '' }}>Nepali (नेपाली)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white text-sm font-medium rounded-xl shadow-lg shadow-gray-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                        <i class="fa-solid fa-save"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection