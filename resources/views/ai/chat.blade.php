@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
<div class="pt-0 pb-4 px-4 bg-gradient-to-br from-gray-50 to-blue-50 h-screen overflow-hidden flex flex-col">
        <div class="max-w-5xl mx-auto w-full flex-1 flex flex-col min-h-0">
        <!-- Main Card with Glass Effect - using flex column to fill height -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 flex-1 flex flex-col overflow-hidden">

            <!-- Header with Gradient -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-teal-500 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                        <i class="fa-regular fa-comment-dots text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">AI Assistant</h1>
                        <p class="text-xs text-blue-100 opacity-90">Your business intelligence partner</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @auth
    @if(auth()->user()->organization)
        <div class="text-center text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full inline-block shrink-0 mr-2">
    Industry: <span class="font-semibold capitalize">{{ auth()->user()->organization->industry ?? 'retail' }}</span>
</div>
    @endif
@endauth
                    <button onclick="newChat()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl font-semibold text-sm transition flex items-center gap-2 backdrop-blur-sm">
                        <i class="fa-solid fa-plus"></i> New Chat
                    </button>
                </div>
            </div>

            <!-- Demo Banner (shown only for guests) -->
            @if($isGuest ?? false)
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-200 px-6 py-2 flex flex-wrap items-center justify-between gap-2 shrink-0">
                    <div class="flex items-center gap-2 text-amber-700">
                        <i class="fa-solid fa-circle-info text-amber-500 animate-pulse"></i>
                        <span class="font-semibold text-sm">🔬 Demo Mode</span>
                        <span class="text-sm text-gray-600 hidden sm:inline">— You're exploring with sample data.</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition">Login</a>
                        <span class="text-gray-400">|</span>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-sm">Start Free Trial</a>
                    </div>
                </div>
            @endif

            <!-- Messages Container - flex-1 to fill remaining space, overflow-y-auto -->
            <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50/80 to-white/80 scroll-smooth min-h-[200px]">
                <!-- Initial empty state -->
                <div id="initialMessage" class="text-center text-gray-400 text-sm py-16">
                    <div class="text-6xl mb-4 animate-bounce-slow">🤖</div>
                    <p class="text-lg font-medium text-gray-600">How can I help you today?</p>
                    <p class="text-xs mt-2 max-w-md mx-auto text-gray-400">Ask about sales, stock, profit, attendance, or anything business-related.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs">📊 Sales</span>
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs">📦 Stock</span>
                        <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-xs">💰 Profit</span>
                        <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs">🎓 Attendance</span>
                    </div>
                </div>

                <!-- Typing Indicator (hidden by default) -->
                <div id="typingIndicator" class="hidden flex items-center gap-3 text-gray-400 text-sm p-3 bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 max-w-fit">
                    <div class="flex gap-1.5">
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                    <span class="text-xs font-medium">AI is thinking...</span>
                </div>
            </div>

            <!-- Input Area - shrink-0 to stay at bottom -->
            <div class="border-t border-gray-200/80 p-4 bg-white/70 backdrop-blur-sm shrink-0">
                <form id="chatForm" class="flex gap-3" autocomplete="off">
                    @csrf
                    <input type="hidden" id="conversation_id" value="">
                    <div class="flex-1 relative">
                        <input type="text" id="userMessage"
                            class="w-full px-5 py-3 pr-12 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white/90 transition shadow-sm hover:shadow-md"
                            placeholder="Ask a question... (e.g., 'Today's sales')" autocomplete="off">
                        <i class="fa-regular fa-face-smile absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg hover:text-blue-500 cursor-pointer transition"></i>
                    </div>
                    <button type="submit" id="sendButton" class="gradient-bg text-white px-6 py-3 rounded-2xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200 text-sm flex items-center gap-2 shrink-0">
                        <i class="fa-solid fa-paper-plane"></i> Send
                    </button>
                </form>

      <!-- Quick Action Buttons - Industry Specific -->
<div class="flex flex-wrap gap-2 mt-3">
    @auth
        @php
            $industry = auth()->user()->organization->industry ?? 'retail';
            $quickActions = [
                'school' => [
                    ['label' => '📊 Today\'s Attendance', 'query' => 'Attendance summary dekhau'],
                    ['label' => '👨‍🎓 Total Students', 'query' => 'Total students kati chan?'],
                    ['label' => '💰 Pending Fees', 'query' => 'Pending fees kati cha?'],
                    ['label' => '📝 Upcoming Exams', 'query' => 'Upcoming exams dekhau'],
                ],
                'retail' => [
                    ['label' => '📊 Today\'s Sales', 'query' => 'Today ko sales kati cha?'],
                    ['label' => '📦 Low Stock', 'query' => 'Low stock items haru dekhau'],
                    ['label' => '💰 Profit', 'query' => 'Profit kati cha?'],
                ],
                'restaurant' => [
                    ['label' => '🍽️ Active Orders', 'query' => 'Active orders dekhau'],
                    ['label' => '🪑 Available Tables', 'query' => 'Available tables dekhau'],
                    ['label' => '💰 Today\'s Revenue', 'query' => 'Today ko revenue kati cha?'],
                ],
                'travel' => [
                    ['label' => '📅 Total Bookings', 'query' => 'Total bookings kati chan?'],
                    ['label' => '✈️ Active Packages', 'query' => 'Active packages dekhau'],
                ],
                    'ngo' => [
        ['label' => '📊 Total Projects', 'query' => 'Total projects kati chan?'],
        ['label' => '💰 Total Donations', 'query' => 'Total donations kati cha?'],
    ],
    'hospital' => [
        ['label' => '🏥 Total Patients', 'query' => 'Total patients kati chan?'],
        ['label' => '📋 Today\'s Appointments', 'query' => 'Today ko appointments kati chan?'],
    ],
    'manufacturing' => [
        ['label' => '🏭 Total Products', 'query' => 'Total products kati chan?'],
        ['label' => '📦 Pending Orders', 'query' => 'Pending orders kati chan?'],
    ],
    'service' => [
        ['label' => '📋 Today\'s Appointments', 'query' => 'Today ko appointments kati chan?'],
        ['label' => '👥 Active Clients', 'query' => 'Active clients kati chan?'],
    ],
];
            $actions = $quickActions[$industry] ?? $quickActions['retail'];
        @endphp
        @foreach($actions as $action)
            <button onclick="quickQuery('{{ $action['query'] }}')"
                class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full transition flex items-center gap-1.5 border border-blue-100">
                {{ $action['label'] }}
            </button>
        @endforeach
    @endauth
</div>
            </div>
        </div>

        <!-- Sidebar: Recent Chats (optional) -->
        <div class="mt-4 bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-4 shrink-0">
            <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <i class="fa-regular fa-clock text-blue-500"></i> Recent Chats
            </h3>
            <div id="recentChats" class="flex flex-wrap gap-2">
                @forelse($conversations ?? [] as $conv)
                    <button onclick="loadChat('{{ $conv->id }}')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-full transition shadow-sm">
                        {{ $conv->title }}
                    </button>
                @empty
                    <span class="text-xs text-gray-400">No previous chats. Start a new one above!</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2.5s infinite ease-in-out;
    }
    .gradient-bg {
        background: linear-gradient(135deg, #1e3a8a 0%, #0d9488 100%);
    }
    .gradient-bg:hover {
        background: linear-gradient(135deg, #1e40af 0%, #0f766e 100%);
    }
    /* Scrollbar styling */
    #chatMessages::-webkit-scrollbar {
        width: 5px;
    }
    #chatMessages::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatMessages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #chatMessages::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('chatForm');
        const input = document.getElementById('userMessage');
        const sendBtn = document.getElementById('sendButton');
        const container = document.getElementById('chatMessages');
        const initialMsg = document.getElementById('initialMessage');
        const convoIdInput = document.getElementById('conversation_id');
        const typingIndicator = document.getElementById('typingIndicator');

        // Helper: Remove initial message
        function removeInitialMessage() {
            if (initialMsg) initialMsg.remove();
        }

        // Helper: Show/hide typing indicator
        function setTyping(show) {
            if (show) {
                typingIndicator.classList.remove('hidden');
                container.scrollTop = container.scrollHeight;
            } else {
                typingIndicator.classList.add('hidden');
            }
        }

        // Add a message to the chat
        function addMessage(role, content, isDemo = false) {
            removeInitialMessage();
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} animate-fadeIn`;
            const time = new Date().toLocaleTimeString();
            let extraClass = role === 'user' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-800';
            if (isDemo && role === 'assistant') {
                extraClass += ' border-l-4 border-amber-400';
            }
            div.innerHTML = `
                <div class="${extraClass} rounded-2xl px-5 py-3 max-w-[85%] shadow-sm hover:shadow-md transition">
                    ${content.replace(/\n/g, '<br>')}
                    <div class="text-xs ${role === 'user' ? 'text-blue-200' : 'text-gray-400'} mt-1.5 flex items-center gap-2">
                        ${time}
                        ${isDemo ? '<span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-medium">Demo</span>' : ''}
                    </div>
                </div>
            `;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            setTyping(true);

            addMessage('user', message);
            input.value = '';

            try {
                const convoId = convoIdInput.value;
                const response = await fetch('{{ route("ai.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message, conversation_id: convoId })
                });

                if (!response.ok) throw new Error('Network error');

                const data = await response.json();
                convoIdInput.value = data.conversation_id;

                setTyping(false);
                const isDemo = data.is_demo || false;
                addMessage('assistant', data.response, isDemo);

                if (data.needs_login) {
                    const loginDiv = document.createElement('div');
                    loginDiv.className = 'flex justify-start mt-1';
                    loginDiv.innerHTML = `
                        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-gray-700 flex items-center gap-3">
                            <i class="fa-solid fa-lock text-blue-500"></i>
                            <span>Want real data? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login</a> or <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Start Free Trial</a></span>
                        </div>
                    `;
                    container.appendChild(loginDiv);
                    container.scrollTop = container.scrollHeight;
                }

            } catch (error) {
                console.error(error);
                setTyping(false);
                addMessage('assistant', '⚠️ Sorry, something went wrong. Please try again.');
            } finally {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send';
            }
        });

        // Quick query function
        window.quickQuery = function(message) {
            input.value = message;
            form.dispatchEvent(new Event('submit'));
        };

        // New chat
        window.newChat = function() {
            convoIdInput.value = '';
            container.innerHTML = `
                <div id="initialMessage" class="text-center text-gray-400 text-sm py-16">
                    <div class="text-6xl mb-4 animate-bounce-slow">🤖</div>
                    <p class="text-lg font-medium text-gray-600">How can I help you today?</p>
                    <p class="text-xs mt-2 max-w-md mx-auto text-gray-400">Ask about sales, stock, profit, attendance, or anything business-related.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs">📊 Sales</span>
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs">📦 Stock</span>
                        <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-xs">💰 Profit</span>
                        <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs">🎓 Attendance</span>
                    </div>
                </div>
                <div id="typingIndicator" class="hidden flex items-center gap-3 text-gray-400 text-sm p-3 bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 max-w-fit">
                    <div class="flex gap-1.5">
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                    <span class="text-xs font-medium">AI is thinking...</span>
                </div>
            `;
            window.initialMsg = document.getElementById('initialMessage');
            window.typingIndicator = document.getElementById('typingIndicator');
        };

        // Load existing chat
        window.loadChat = async function(id) {
            try {
                const response = await fetch('/ai/conversation/' + id);
                const messages = await response.json();
                convoIdInput.value = id;
                container.innerHTML = `
                    <div id="typingIndicator" class="hidden flex items-center gap-3 text-gray-400 text-sm p-3 bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 max-w-fit">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                            <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                            <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                        </div>
                        <span class="text-xs font-medium">AI is thinking...</span>
                    </div>
                `;
                messages.forEach(msg => {
                    addMessage(msg.role, msg.content);
                });
                window.typingIndicator = document.getElementById('typingIndicator');
            } catch (error) {
                console.error('Error loading chat:', error);
            }
        };

        // On initial load, if there are messages, remove initial message
        if (container.children.length > 1) {
            removeInitialMessage();
        }

        window.initialMsg = initialMsg;
        window.typingIndicator = typingIndicator;
    });
</script>

{{-- Add fade-in animation --}}
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
        40% { transform: scale(1.2); opacity: 1; }
    }
    .animate-bounce {
        animation: bounce 1.4s infinite ease-in-out both;
    }
</style>
@endsection