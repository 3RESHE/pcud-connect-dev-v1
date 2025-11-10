@extends('layouts.admin')

@section('title', 'News Article Management - PCU-DASMA Connect')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">News Article Management</h1>
                <p class="text-gray-600">Review, approve, and manage all news articles from staff</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $total_count ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pending_count ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                    <p class="text-3xl font-bold text-green-600">{{ $approved_count ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Published</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $published_count ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rejected</p>
                    <p class="text-3xl font-bold text-red-600">{{ $rejected_count ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- Enhanced Search & Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <!-- Main Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="newsSearch" placeholder="🔍 Search by title, author, category..."
                    class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                    oninput="applyFilters()" />
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
                <button onclick="filterNews('all')"
                    class="news-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="true">
                    All Articles ({{ $total_count ?? 0 }})
                </button>
                <button onclick="filterNews('pending')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Pending ({{ $pending_count ?? 0 }})
                </button>
                <button onclick="filterNews('approved')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Approved ({{ $approved_count ?? 0 }})
                </button>
                <button onclick="filterNews('published')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Published ({{ $published_count ?? 0 }})
                </button>
                <button onclick="filterNews('rejected')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Rejected ({{ $rejected_count ?? 0 }})
                </button>
                <!-- Removed Draft tab -->
            </nav>
        </div>


        <!-- Advanced Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Sort By -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sortBy" onchange="applyFilters()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="title-asc">Title (A-Z)</option>
                    <option value="title-desc">Title (Z-A)</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Category</label>
                <select id="categoryFilter" onchange="applyFilters()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    <option value="announcement">Announcement</option>
                    <option value="event">Event</option>
                    <option value="achievement">Achievement</option>
                    <option value="update">Update</option>
                </select>
            </div>

            <!-- Author Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Author</label>
                <select id="authorFilter" onchange="applyFilters()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Authors</option>
                    @foreach ($articles->groupBy('creator.id') as $creatorArticles)
                        @if ($creatorArticles->first()->creator)
                            <option value="{{ $creatorArticles->first()->creator->id }}">
                                {{ $creatorArticles->first()->creator->first_name }}
                                {{ $creatorArticles->first()->creator->last_name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Reset Button -->
            <div class="flex items-end gap-2">
                <button onclick="resetFilters()"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Search Results Info -->
    <div id="searchInfo" class="mb-4 text-sm text-gray-600">
        Showing <span id="resultCount">{{ count($articles) }}</span> articles
    </div>

    <!-- News Articles List - CLICKABLE CARDS -->
    <div id="newsContainer" class="space-y-6">
        @forelse($articles as $article)
            <!-- Entire Card is Clickable -->
            <a href="{{ route('admin.approvals.news.show', $article->id) }}"
                class="block bg-white rounded-lg shadow-sm border-l-4 overflow-hidden hover:shadow-lg transition-all duration-200 transform hover:scale-[1.01] cursor-pointer
                @if ($article->status === 'published') border-blue-500
                @elseif($article->status === 'pending') border-yellow-500
                @elseif($article->status === 'rejected') border-red-500
                @elseif($article->status === 'approved') border-green-500
                @else border-gray-500 @endif"
                data-status="{{ $article->status }}" data-title="{{ strtolower($article->title) }}"
                data-category="{{ $article->category }}" data-author="{{ $article->creator->id ?? '' }}">

                <!-- Article Content -->
                <div class="p-4 sm:p-6">
                    <!-- Status Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <!-- Status Badge -->
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if ($article->status === 'published') bg-blue-100 text-blue-800
                            @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($article->status === 'rejected') bg-red-100 text-red-800
                            @elseif($article->status === 'approved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $article->getStatusDisplay() }}
                        </span>

                        <!-- Category Badge -->
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $article->getCategoryDisplayName() }}
                        </span>

                        <!-- Featured Badge -->
                        @if ($article->is_featured)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                ⭐ Featured
                            </span>
                        @endif

                        <!-- Time Info -->
                        <span class="text-xs text-gray-500 ml-auto">
                            @if ($article->status === 'published' && $article->published_at)
                                Published {{ $article->published_at->diffForHumans() }}
                            @elseif($article->status === 'pending')
                                Submitted {{ $article->created_at->diffForHumans() }}
                            @elseif($article->status === 'rejected')
                                Rejected {{ $article->updated_at->diffForHumans() }}
                            @else
                                Created {{ $article->created_at->diffForHumans() }}
                            @endif
                        </span>
                    </div>

                    <!-- Featured Image -->
                    @if ($article->featured_image)
                        <div class="mb-4 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                                class="w-full h-40 object-cover">
                        </div>
                    @endif

                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">{{ $article->title }}</h3>

                    <!-- Summary -->
                    @if ($article->summary)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->summary }}</p>
                    @else
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->getExcerpt(150) }}</p>
                    @endif

                    <!-- Meta Info -->
                    <div
                        class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-gray-600 mb-4 pb-4 border-b border-gray-100">
                        <!-- Author -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="truncate">{{ $article->creator->first_name ?? 'Unknown' }}
                                {{ $article->creator->last_name ?? '' }}</span>
                        </div>

                        <!-- Event Date -->
                        @if ($article->event_date)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>{{ $article->getEventDateDisplay() }}</span>
                            </div>
                        @endif

                        <!-- Partnership -->
                        @if ($article->partnership_with)
                            <div class="flex items-center col-span-2 sm:col-span-1">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                    </path>
                                </svg>
                                <span class="truncate">{{ $article->partnership_with }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Status-Specific Actions -->
                    @if ($article->status === 'pending')
                        <div
                            class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-xs text-yellow-800"><strong>Pending Review:</strong> Awaiting approval</p>
                            </div>
                            <span class="text-xs font-semibold text-yellow-700">ACTION NEEDED</span>
                        </div>
                    @elseif($article->status === 'rejected')
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <p class="text-xs font-medium text-red-900 mb-1">💬 Rejection Reason:</p>
                            <p class="text-xs text-red-700 line-clamp-2">
                                {{ $article->rejection_reason ?? 'No reason provided' }}</p>
                        </div>
                    @elseif($article->status === 'approved')
                        <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                            <p class="text-xs text-green-800"><strong>✓ Approved:</strong> Ready for staff to publish</p>
                        </div>
                    @endif

                    <!-- Click to View Notice -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 italic">Click card to view details and manage →</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No News Articles Found</h3>
                <p class="text-gray-600">Try adjusting your search or filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($articles->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

    <script>
        function filterNews(status) {
            document.querySelectorAll('.news-filter').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            event.target.classList.add('border-primary', 'text-primary');
            event.target.classList.remove('border-transparent', 'text-gray-500');

            window.currentStatusFilter = status;
            applyFilters();
        }

        function applyFilters() {
            const searchTerm = document.getElementById('newsSearch')?.value?.toLowerCase() || '';
            const sortBy = document.getElementById('sortBy')?.value || 'latest';
            const category = document.getElementById('categoryFilter')?.value || '';
            const author = document.getElementById('authorFilter')?.value || '';
            const statusFilter = window.currentStatusFilter || 'all';

            let articles = Array.from(document.querySelectorAll('#newsContainer > a'));
            let filtered = articles;

            // Filter by search term
            if (searchTerm) {
                filtered = filtered.filter(article => {
                    const title = article.dataset.title || '';
                    return title.includes(searchTerm);
                });
            }

            // Filter by status
            if (statusFilter !== 'all') {
                filtered = filtered.filter(article => article.dataset.status === statusFilter);
            }

            // Filter by category
            if (category) {
                filtered = filtered.filter(article => article.dataset.category === category);
            }

            // Filter by author
            if (author) {
                filtered = filtered.filter(article => article.dataset.author === author);
            }

            // Show/hide articles
            articles.forEach(article => {
                article.style.display = filtered.includes(article) ? '' : 'none';
            });

            // Update result count
            document.getElementById('resultCount').textContent = filtered.length;
        }

        function resetFilters() {
            document.getElementById('newsSearch').value = '';
            document.getElementById('sortBy').value = 'latest';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('authorFilter').value = '';
            window.currentStatusFilter = 'all';

            document.querySelectorAll('.news-filter').forEach((btn, idx) => {
                if (idx === 0) {
                    btn.classList.add('border-primary', 'text-primary');
                    btn.classList.remove('border-transparent', 'text-gray-500');
                } else {
                    btn.classList.remove('border-primary', 'text-primary');
                    btn.classList.add('border-transparent', 'text-gray-500');
                }
            });

            applyFilters();
        }
    </script>
@endsection
