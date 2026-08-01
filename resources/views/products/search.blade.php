@extends('layouts.admin')

@section('title', 'Search Products')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">🔍 Search Products</h1>
            <a href="{{ route('ai.chat') }}" class="ml-auto text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-100 transition">
                <i class="fa-regular fa-comment-dots"></i> Ask AI
            </a>
        </div>

        <!-- Demo Banner (for guests) -->
        @if($is_demo ?? false)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 text-sm flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2 text-amber-700">
                    <i class="fa-solid fa-info-circle text-amber-500 animate-pulse"></i>
                    <span class="font-semibold">🔬 Demo Mode</span>
                    <span class="text-gray-600 hidden sm:inline">— You're viewing sample products.</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition">Login</a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-sm">Start Free Trial</a>
                </div>
            </div>
        @endif

        <!-- Search Form -->
        <form method="GET" action="{{ route('products.search') }}" class="mb-6" id="searchForm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search by product name, SKU, or brand..."
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        autofocus id="searchInput">
                </div>
                <button type="submit" class="gradient-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center justify-center gap-2" id="searchBtn">
                    <i class="fa-solid fa-search"></i> Search
                </button>
                @if(request('q'))
                    <a href="{{ route('products.search') }}" class="text-gray-400 hover:text-gray-600 px-4 py-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition flex items-center gap-1" id="clearBtn">
                        <i class="fa-solid fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        <!-- Recent Searches -->
        <div id="recentSearches" class="flex flex-wrap gap-2 mb-4 text-sm">
            <!-- Rendered by JavaScript -->
        </div>

        <!-- Results -->
        @if(request('q'))
            <div class="text-sm text-gray-500 mb-4" id="resultCount">
                Found <span class="font-semibold text-gray-700">{{ $products->total() }}</span> results for "<span class="font-semibold text-gray-700">{{ request('q') }}</span>"
            </div>
        @endif

        <div id="results-container">
            @include('products._table', ['products' => $products])
        </div>

        <!-- Quick Tips -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500">
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                <span class="text-2xl">💡</span>
                <div>
                    <p class="font-medium text-gray-700">Try partial names</p>
                    <p class="text-xs">"cola" matches Coca-Cola</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                <span class="text-2xl">🔢</span>
                <div>
                    <p class="font-medium text-gray-700">Search by SKU</p>
                    <p class="text-xs">"BEV-001" finds Coca-Cola</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                <span class="text-2xl">🤖</span>
                <div>
                    <p class="font-medium text-gray-700">AI Assistant</p>
                    <p class="text-xs">Ask "stock of Coca-Cola" in chat</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('results-container');
        const resultCount = document.getElementById('resultCount');
        const searchForm = document.getElementById('searchForm');
        const clearBtn = document.getElementById('clearBtn');

        // ─── Debounce helper ────────────────────────────────────────────
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // ─── Update results via AJAX ──────────────────────────────────
        function fetchResults(query) {
            if (query.length === 0) {
                window.location.href = '{{ route("products.search") }}';
                return;
            }

            const url = new URL(window.location);
            url.searchParams.set('q', query);
            window.history.pushState({}, '', url);

            fetch('{{ route("products.search") }}?q=' + encodeURIComponent(query), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                resultsContainer.innerHTML = html;
                if (clearBtn) {
                    if (query) clearBtn.style.display = 'inline-flex';
                    else clearBtn.style.display = 'none';
                }
                saveRecentSearch(query);
            })
            .catch(err => console.error('Search error:', err));
        }

        const debouncedFetch = debounce(fetchResults, 300);

        // ─── Input event ──────────────────────────────────────────────
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                debouncedFetch(query);
            } else if (query.length === 0) {
                window.location.href = '{{ route("products.search") }}';
            }
        });

        // ─── Handle form submit ──────────────────────────────────────
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                fetchResults(query);
            }
        });

        // ─── Recent Searches (localStorage) ──────────────────────────
        function saveRecentSearch(term) {
            let searches = JSON.parse(localStorage.getItem('productSearches') || '[]');
            searches = searches.filter(s => s !== term);
            searches.unshift(term);
            searches = searches.slice(0, 5);
            localStorage.setItem('productSearches', JSON.stringify(searches));
            renderRecentSearches();
        }

        function renderRecentSearches() {
            const container = document.getElementById('recentSearches');
            const searches = JSON.parse(localStorage.getItem('productSearches') || '[]');
            if (searches.length === 0) {
                container.innerHTML = '';
                return;
            }
            container.innerHTML = '<span class="text-xs text-gray-400 mr-1">Recent:</span>' +
                searches.map(s => `
                    <button class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full hover:bg-gray-200 transition"
                            onclick="document.getElementById('searchInput').value='${s}'; fetchResults('${s}');">
                        ${s}
                    </button>
                `).join('');
        }

        window.fetchResults = fetchResults;
        renderRecentSearches();

        const initialQuery = '{{ request('q') }}';
        if (initialQuery) {
            saveRecentSearch(initialQuery);
        }
    });
</script>
@endpush
@endsection
