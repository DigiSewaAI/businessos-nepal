@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">🤖 AI Assistant</h1>
                <div class="flex gap-2">
                    <a href="{{ route('ai.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-chart-simple"></i>
                    </a>
                    <button onclick="newChat()" class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">
                        <i class="fa-solid fa-plus"></i> New Chat
                    </button>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4 bg-gray-50 relative">
                <!-- Initial empty state -->
                <div id="initialMessage" class="text-center text-gray-400 text-sm py-12">
                    <div class="text-4xl mb-3">🤖</div>
                    <p>Ask me anything about your business.</p>
                    <p class="text-xs mt-1">I can help with sales, inventory, finance, and more.</p>
                </div>

                <!-- Typing Indicator (hidden by default) -->
                <div id="typingIndicator" class="hidden flex items-center gap-2 text-gray-400 text-sm p-2">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                    <span>AI is thinking...</span>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 p-4 bg-white">
                <form id="chatForm" class="flex gap-2" autocomplete="off">
                    @csrf
                    <input type="hidden" id="conversation_id" value="">
                    <input type="text" id="userMessage" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Ask a question..." autocomplete="off">
                    <button type="submit" id="sendButton" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 text-sm">
                        <i class="fa-solid fa-paper-plane"></i> Send
                    </button>
                </form>

                <!-- ✅ Quick Action Buttons (E1) -->
                <div class="flex flex-wrap gap-2 mt-2">
                    <button onclick="quickQuery('Today ko sales kati cha?')" 
                        class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition flex items-center gap-1">
                        📊 Today's Sales
                    </button>
                    <button onclick="quickQuery('Low stock items haru dekhau')" 
                        class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition flex items-center gap-1">
                        📦 Low Stock
                    </button>
                    <button onclick="quickQuery('Profit kati cha?')" 
                        class="text-xs bg-green-50 text-green-600 px-3 py-1.5 rounded-lg hover:bg-green-100 transition flex items-center gap-1">
                        💰 Profit
                    </button>
                    <button onclick="quickQuery('BusinessOS ko pricing kati ho?')" 
                        class="text-xs bg-purple-50 text-purple-600 px-3 py-1.5 rounded-lg hover:bg-purple-100 transition flex items-center gap-1">
                        💎 Pricing
                    </button>
                    <button onclick="quickQuery('Attendence summary dekhau')" 
                        class="text-xs bg-orange-50 text-orange-600 px-3 py-1.5 rounded-lg hover:bg-orange-100 transition flex items-center gap-1">
                        🎓 Attendance
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar: Previous chats -->
        <div class="mt-4 bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Recent Chats</h3>
            <div id="recentChats" class="flex flex-wrap gap-2">
                @foreach($conversations as $conv)
                    <button onclick="loadChat('{{ $conv->id }}')" class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full hover:bg-gray-200">
                        {{ $conv->title }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

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
        function addMessage(role, content) {
            removeInitialMessage();
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
            const time = new Date().toLocaleTimeString();
            div.innerHTML = `
                <div class="${role === 'user' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-800'} rounded-xl px-4 py-2 max-w-lg shadow-sm">
                    ${content.replace(/\n/g, '<br>')}
                    <div class="text-xs ${role === 'user' ? 'text-blue-200' : 'text-gray-400'} mt-1">${time}</div>
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

            // Disable send button and show typing indicator
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            setTyping(true);

            // Add user message
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

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                convoIdInput.value = data.conversation_id;

                // Hide typing indicator before adding assistant reply
                setTyping(false);
                addMessage('assistant', data.response);

                // Optionally, update recent chats if a new conversation was created
                // (for simplicity, we might reload the page later)

            } catch (error) {
                console.error('Error:', error);
                setTyping(false);
                addMessage('assistant', 'I apologize, but I encountered an error. Please try again.');
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
                <div id="initialMessage" class="text-center text-gray-400 text-sm py-12">
                    <div class="text-4xl mb-3">🤖</div>
                    <p>Ask me anything about your business.</p>
                    <p class="text-xs mt-1">I can help with sales, inventory, finance, and more.</p>
                </div>
                <div id="typingIndicator" class="hidden flex items-center gap-2 text-gray-400 text-sm p-2">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                    <span>AI is thinking...</span>
                </div>
            `;
            // Re-set references (for simplicity, we just keep the global ones; they get updated)
            // We'll just reassign the initialMsg and typingIndicator
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
                    <div id="typingIndicator" class="hidden flex items-center gap-2 text-gray-400 text-sm p-2">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                        </div>
                        <span>AI is thinking...</span>
                    </div>
                `;
                messages.forEach(msg => {
                    addMessage(msg.role, msg.content);
                });
                // Reassign typing indicator reference
                window.typingIndicator = document.getElementById('typingIndicator');
            } catch (error) {
                console.error('Error loading chat:', error);
            }
        };

        // On initial load, if there are messages, remove initial message
        if (container.children.length > 1) {
            removeInitialMessage();
        }

        // Store references globally for reuse (optional)
        window.initialMsg = initialMsg;
        window.typingIndicator = typingIndicator;
    });
</script>

{{-- Include CSS for bounce animation if not already present --}}
<style>
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
        40% { transform: scale(1.2); opacity: 1; }
    }
    .animate-bounce {
        animation: bounce 1.4s infinite ease-in-out both;
    }
</style>
@endsection