@extends('layouts.admin')

@section('title', 'News Article Management - PCU-DASMA Connect')

@section('content')
<!-- Header Section -->
<div class="mb-10">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">News Article Management</h1>
            <p class="text-gray-600 mt-2">Review, approve, and manage all news articles from staff</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pending_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $approved_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Published</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $published_count ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejected</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejected_count ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- Search Input -->
        <div class="relative md:col-span-2">
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="newsSearch" placeholder="Search by title, author..."
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                oninput="applyFilters()">
        </div>

        <!-- Category Filter -->
        <div>
            <select id="categoryFilter" onchange="applyFilters()"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer bg-white">
                <option value="">All Categories</option>
                <option value="announcement">Announcement</option>
                <option value="event">Event</option>
                <option value="achievement">Achievement</option>
                <option value="update">Update</option>
            </select>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="border-t border-gray-200 pt-4">
        <nav class="flex space-x-6 overflow-x-auto" role="tablist">
            <button onclick="filterNews('all')"
                class="news-filter active pb-2 px-1 text-sm font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap transition-colors"
                role="tab">
                All ({{ $total_count ?? 0 }})
            </button>
            <button onclick="filterNews('pending')"
                class="news-filter pb-2 px-1 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:border-gray-300 whitespace-nowrap transition-colors"
                role="tab">
                Pending ({{ $pending_count ?? 0 }})
            </button>
            <button onclick="filterNews('approved')"
                class="news-filter pb-2 px-1 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:border-gray-300 whitespace-nowrap transition-colors"
                role="tab">
                Approved ({{ $approved_count ?? 0 }})
            </button>
            <button onclick="filterNews('published')"
                class="news-filter pb-2 px-1 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:border-gray-300 whitespace-nowrap transition-colors"
                role="tab">
                Published ({{ $published_count ?? 0 }})
            </button>
            <button onclick="filterNews('rejected')"
                class="news-filter pb-2 px-1 text-sm font-medium text-gray-600 border-b-2 border-transparent hover:border-gray-300 whitespace-nowrap transition-colors"
                role="tab">
                Rejected ({{ $rejected_count ?? 0 }})
            </button>
        </nav>
    </div>
</div>

<!-- News Articles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="newsContainer">
    @forelse($articles as $article)
        <!-- Clickable Article Card -->
        <a href="{{ route('admin.approvals.news.show', $article->id) }}"
           class="group news-card block"
           data-status="{{ $article->status }}"
           data-title="{{ strtolower($article->title) }}"
           data-category="{{ $article->category }}">

            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-b-4 cursor-pointer
                @if($article->status === 'published') border-b-blue-500
                @elseif($article->status === 'pending') border-b-yellow-500
                @elseif($article->status === 'rejected') border-b-red-500
                @elseif($article->status === 'approved') border-b-green-500
                @else border-b-gray-400 @endif">

                <!-- Featured Image -->
                <div class="relative w-full h-44 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ Storage::url($article->featured_image) }}"
                            alt="{{ $article->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-blue-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-blue-600 font-medium text-sm">No Image</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm bg-opacity-95
                            @if($article->status === 'published') bg-blue-100 text-blue-800
                            @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($article->status === 'rejected') bg-red-100 text-red-800
                            @elseif($article->status === 'approved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($article->status) }}
                        </span>
                    </div>

                    <!-- Featured Badge -->
                    @if($article->is_featured)
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 backdrop-blur-sm bg-opacity-95">
                                ⭐ Featured
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Content Container -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $article->getCategoryDisplayName() ?? 'News' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $article->title }}
                    </h3>

                    <!-- Summary -->
                    @if($article->summary)
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $article->summary }}</p>
                    @else
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}</p>
                    @endif

                    <!-- Meta Info -->
                    <div class="space-y-2 mb-4 pb-4 border-t border-gray-100 flex-grow">
                        <!-- Author -->
                        <div class="flex items-center space-x-2 pt-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-xs text-gray-600">
                                <span class="font-medium">{{ $article->creator->first_name ?? 'Unknown' }} {{ $article->creator->last_name ?? '' }}</span>
                            </p>
                        </div>

                        <!-- Published Date -->
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs text-gray-600">
                                @if($article->status === 'published' && $article->published_at)
                                    Published {{ $article->published_at->format('M d, Y') }}
                                @else
                                    Created {{ $article->created_at->format('M d, Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Status Info -->
                    @if($article->status === 'pending')
                        <div class="bg-yellow-50 p-2 rounded text-xs text-yellow-700 border border-yellow-200 text-center font-medium">
                            ⏳ Awaiting Approval
                        </div>
                    @elseif($article->status === 'rejected')
                        <div class="bg-red-50 p-2 rounded text-xs text-red-700 border border-red-200 text-center font-medium">
                            ❌ Rejected
                        </div>
                    @elseif($article->status === 'approved')
                        <div class="bg-green-50 p-2 rounded text-xs text-green-700 border border-green-200 text-center font-medium">
                            ✓ Approved
                        </div>
                    @elseif($article->status === 'published')
                        <div class="bg-blue-50 p-2 rounded text-xs text-blue-700 border border-blue-200 text-center font-medium">
                            👁️ Published
                        </div>
                    @endif

                    <!-- Click to View Notice -->
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-500 italic">Click card to view & manage →</p>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white rounded-lg shadow-md p-16 text-center border border-gray-200">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No News Articles Found</h3>
            <p class="text-gray-600">Try adjusting your search or filters</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($articles->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $articles->links() }}
    </div>
@endif

<!-- JavaScript -->
<script>
    function filterNews(status) {
        // Update tab styling
        document.querySelectorAll('.news-filter').forEach(btn => {
            btn.classList.remove('text-blue-600', 'border-b-blue-600');
            btn.classList.add('text-gray-600', 'border-b-transparent');
        });

        event.target.classList.add('text-blue-600', 'border-b-blue-600');
        event.target.classList.remove('text-gray-600', 'border-b-transparent');

        window.currentStatusFilter = status;
        applyFilters();
    }

    function applyFilters() {
        const searchTerm = document.getElementById('newsSearch')?.value?.toLowerCase() || '';
        const category = document.getElementById('categoryFilter')?.value || '';
        const statusFilter = window.currentStatusFilter || 'all';

        let cards = Array.from(document.querySelectorAll('#newsContainer > a'));
        let filtered = cards;

        // Filter by search term
        if (searchTerm) {
            filtered = filtered.filter(card => {
                const title = card.dataset.title || '';
                return title.includes(searchTerm);
            });
        }

        // Filter by status
        if (statusFilter !== 'all') {
            filtered = filtered.filter(card => card.dataset.status === statusFilter);
        }

        // Filter by category
        if (category) {
            filtered = filtered.filter(card => card.dataset.category === category);
        }

        // Show/hide articles
        cards.forEach(card => {
            card.style.display = filtered.includes(card) ? '' : 'none';
        });
    }

    // Initialize
    window.currentStatusFilter = 'all';
</script>
@endsection
