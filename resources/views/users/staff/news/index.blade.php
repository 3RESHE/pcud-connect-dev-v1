@extends('layouts.staff')

@section('title', 'News Management - PCU-DASMA Connect')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">News Management</h1>
            <p class="text-gray-600">Create, manage, and track your news articles through the approval process</p>
        </div>
        <a href="{{ route('staff.news.create') }}"
            class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center sm:justify-start transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create News Article
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_articles ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Published</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $published_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $rejected_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
                <button onclick="filterNews('all')"
                    class="news-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="true">
                    All Articles ({{ $total_articles ?? 0 }})
                </button>
                <button onclick="filterNews('published')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Published ({{ $published_count ?? 0 }})
                </button>
                <button onclick="filterNews('pending')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Pending Review ({{ $pending_count ?? 0 }})
                </button>
                <button onclick="filterNews('rejected')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Rejected ({{ $rejected_count ?? 0 }})
                </button>
                <button onclick="filterNews('draft')"
                    class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Draft ({{ $draft_count ?? 0 }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="newsSearch"
                placeholder="Search news by title, author, category, or tags..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                oninput="applyFilters()" />
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- News Articles List - CLICKABLE CARDS -->
    <div id="newsContainer" class="space-y-6">
        @forelse($articles as $article)
            <!-- Entire Card is Clickable -->
            <a href="{{ route('staff.news.show', $article->id) }}"
                class="block bg-white rounded-lg shadow-sm border-l-4 overflow-hidden hover:shadow-lg transition-all duration-200 transform hover:scale-[1.01] cursor-pointer
                @if($article->status === 'published') border-blue-500
                @elseif($article->status === 'pending') border-yellow-500
                @elseif($article->status === 'rejected') border-red-500
                @elseif($article->status === 'approved') border-green-500
                @else border-gray-500 @endif"
                data-status="{{ $article->status }}">

                <!-- Article Content -->
                <div class="p-6">
                    <!-- Status Badges -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($article->status === 'published') bg-blue-100 text-blue-800
                            @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($article->status === 'rejected') bg-red-100 text-red-800
                            @elseif($article->status === 'approved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $article->getStatusDisplay() }}
                        </span>

                        <!-- Featured Badge -->
                        @if($article->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                ⭐ Featured
                            </span>
                        @endif

                        <!-- Category Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $article->getCategoryDisplayName() }}
                        </span>

                        <!-- Time Info -->
                        <span class="text-xs text-gray-500 ml-auto">
                            @if($article->status === 'published' && $article->published_at)
                                Published {{ $article->published_at->diffForHumans() }}
                            @elseif($article->status === 'pending')
                                Submitted {{ $article->created_at->diffForHumans() }}
                            @elseif($article->status === 'rejected')
                                Rejected {{ $article->updated_at->diffForHumans() }}
                            @else
                                Updated {{ $article->updated_at->diffForHumans() }}
                            @endif
                        </span>
                    </div>

                    <!-- Featured Image -->
                    @if($article->featured_image)
                        <div class="mb-4 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                                class="w-full h-40 object-cover">
                        </div>
                    @endif

                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">{{ $article->title }}</h3>

                    <!-- Summary -->
                    @if($article->summary)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->summary }}</p>
                    @else
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->getExcerpt(150) }}</p>
                    @endif

                    <!-- Meta Info -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-gray-600 mb-4 pb-4 border-b border-gray-100">
                        <!-- Author -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $article->creator->first_name ?? 'Unknown' }}</span>
                        </div>

                        <!-- Event Date -->
                        @if($article->event_date)
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
                        @if($article->partnership_with)
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

                        <!-- Views (Published Only) -->
                        @if($article->status === 'published')
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span>{{ $article->getViewsDisplay() }} views</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tags -->
                    @if($article->tags && $article->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($article->tags as $tag)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Status-Specific Messages -->
                    @if($article->status === 'pending')
                        <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-yellow-800">
                                    <strong>Pending Review:</strong> Your article is waiting for admin approval. You'll receive a notification once reviewed.
                                </p>
                            </div>
                        </div>
                    @elseif($article->status === 'rejected')
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <h4 class="font-medium text-red-900 text-xs mb-1">💬 Admin Feedback:</h4>
                            <p class="text-xs text-red-700">{{ $article->rejection_reason ?? 'Please contact administrator for details.' }}</p>
                        </div>
                    @elseif($article->status === 'approved')
                        <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                            <p class="text-xs text-green-800">
                                <strong>✓ Approved!</strong> Your article has been approved and is ready for publication.
                            </p>
                        </div>
                    @endif

                    <!-- Click to View Notice -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 italic">Click card to view details and manage article</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No News Articles Yet</h3>
                <p class="text-gray-600 mb-6">You haven't created any news articles yet. Start creating your first article to get started!</p>
                <a href="{{ route('staff.news.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create News Article
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

    <script src="{{ asset('js/staff/news.js') }}"></script>
@endsection
