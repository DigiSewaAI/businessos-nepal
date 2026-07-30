@extends('layouts.app')

@section('title', 'AI Dashboard')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">🤖 AI Dashboard</h1>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Total Chats</p>
                <p class="text-2xl font-bold">{{ $totalChats ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Total Messages</p>
                <p class="text-2xl font-bold">{{ $totalMessages ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Unread Insights</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $insights->count() }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">AI Status</p>
                <p class="text-2xl font-bold text-green-600">
                    <i class="fa-solid fa-circle-check"></i> Online
                </p>
            </div>
        </div>

        <!-- Insights -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">📊 Latest Insights</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($insights as $insight)
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $insight->priority == 'high' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $insight->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $insight->priority == 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($insight->type) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-700">{{ $insight->data['message'] ?? 'New insight' }}</span>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('ai.anomalies.read', $insight) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">Mark Read</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400">No insights yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Chats -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">💬 Recent Chats</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($conversations as $conv)
                    <div class="px-6 py-4 flex justify-between items-center">
                        <a href="{{ route('ai.chat') }}" class="text-sm text-gray-700 hover:text-blue-600">
                            {{ $conv->title }}
                        </a>
                        <span class="text-xs text-gray-400">{{ $conv->updated_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400">No conversations yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 
