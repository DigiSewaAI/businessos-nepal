@extends('layouts.app')

@section('title', 'Contact - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gradient-to-br from-gray-50 via-white to-teal-50/30">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 text-center">Get in <span class="text-gradient">Touch</span></h1>
        <p class="text-center text-gray-500 mb-12">Have questions? We'd love to hear from you.</p>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" placeholder="John Doe">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" placeholder="john@example.com">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" placeholder="Tell us how we can help..."></textarea>
                </div>
                <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Send Message
                </button>
            </form>
            <div class="text-center mt-6 text-sm text-gray-400">
                <p>Or email us directly: <a href="mailto:support@businessos.com.np" class="text-teal-600 hover:underline">support@businessos.com.np</a></p>
            </div>
        </div>
    </div>
</div>
@endsection