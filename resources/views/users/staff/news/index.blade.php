@extends('layouts.staff')

@section('title', 'News Management - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
    <div class="mb-4 sm:mb-0">
        <h1 class="text-3xl font-bold text-gray-900">News Management</h1>
        <p class="text-gray-600">Create, manage, and track your news articles through the approval process</p>
    </div>
    <a
        href="{{ route('staff.news.create') }}"
        class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center sm:justify-start transition-colors duration-200"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Create News Article
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Published</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $published_count ?? 8 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 3 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $rejected_count ?? 1 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
            <button
                onclick="filterNews('all')"
                class="news-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="true"
            >
                All Articles ({{ $total_articles ?? 12 }})
            </button>
            <button
                onclick="filterNews('published')"
                class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Published ({{ $published_count ?? 8 }})
            </button>
            <button
                onclick="filterNews('pending')"
                class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Pending Review ({{ $pending_count ?? 3 }})
            </button>
            <button
                onclick="filterNews('rejected')"
                class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Rejected ({{ $rejected_count ?? 1 }})
            </button>
            <button
                onclick="filterNews('draft')"
                class="news-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Draft ({{ $draft_count ?? 2 }})
            </button>
        </nav>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <div class="relative">
        <input
            type="text"
            id="newsSearch"
            placeholder="Search news by title, author, or category..."
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
            oninput="applyFilters()"
        />
        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
</div>

<!-- News Articles List -->
<div id="newsContainer" class="space-y-6">
    @forelse($articles as $article)
        <div
            class="bg-white rounded-lg shadow-sm p-6 border-l-4
            @if($article->status === 'published') border-blue-500
            @elseif($article->status === 'pending') border-yellow-500
            @elseif($article->status === 'rejected') border-red-500
            @elseif($article->status === 'approved') border-green-500
            @else border-gray-500 @endif"
            data-status="{{ $article->status }}"
        >
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center mb-3">
                        <span class="
                            @if($article->status === 'published') bg-blue-100 text-blue-800
                            @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($article->status === 'rejected') bg-red-100 text-red-800
                            @elseif($article->status === 'approved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif
                            px-2 py-1 rounded-full text-xs mr-3 mb-1 font-medium"
                        >
                            {{ ucfirst($article->status) }}
                        </span>
                        @if($article->is_featured)
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">Featured</span>
                        @endif
                        <span class="bg-{{ $article->getCategoryColor() }}-100 text-{{ $article->getCategoryColor() }}-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">
                            {{ $article->getCategoryDisplayName() }}
                        </span>
                        <span class="text-sm text-gray-500 mb-1">
                            @if($article->status === 'published')
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

                    <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                        <div class="flex items-center mr-6 mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            By: {{ $article->creator->first_name }} {{ $article->creator->last_name }}
                        </div>
                        <div class="flex items-center mr-6 mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4v16a2 2 0 002 2h6a2 2 0 002-2V4M11 6v2M11 10v2"></path>
                            </svg>
                            Category: {{ $article->getCategoryDisplayName() }}
                        </div>
                        @if($article->status === 'published')
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $article->getViewsDisplay() }} views
                            </div>
                        @endif
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $article->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $article->getExcerpt(200) }}</p>

                    <!-- Status-specific messages -->
                    @if($article->status === 'pending')
                        <div class="bg-yellow-50 p-3 rounded-lg mb-4 border border-yellow-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-yellow-800">
                                    <strong>Status:</strong> Waiting for admin approval. You'll be notified once reviewed.
                                </p>
                            </div>
                        </div>
                    @elseif($article->status === 'rejected')
                        <div class="bg-red-50 p-3 rounded-lg mb-4 border border-red-200">
                            <h4 class="font-medium text-red-900 mb-1">Admin Feedback:</h4>
                            <p class="text-sm text-red-700">{{ $article->rejection_reason ?? 'Please contact administrator for details.' }}</p>
                        </div>
                    @elseif($article->status === 'approved')
                        <div class="bg-green-50 p-3 rounded-lg mb-4 border border-green-200">
                            <p class="text-sm text-green-800">
                                <strong>Approved!</strong> Your article has been approved by the administrator and is ready for publication.
                            </p>
                        </div>
                    @endif

                    @if($article->tags)
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $article->tags) as $tag)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
                    @if($article->status === 'published')
                        <a href="{{ route('staff.news.show', $article->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center transition-colors duration-200">
                            View Live
                        </a>
                        <a href="{{ route('staff.news.edit', $article->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 text-center transition-colors duration-200">
                            Edit Article
                        </a>
                    @elseif($article->status === 'pending')
                        <a href="{{ route('staff.news.show', $article->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 text-center transition-colors duration-200">
                            Preview
                        </a>
                        <a href="{{ route('staff.news.edit', $article->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 text-center transition-colors duration-200">
                            Edit
                        </a>
                        <button onclick="withdrawNews({{ $article->id }})" class="px-4 py-2 border border-yellow-300 text-yellow-700 text-sm rounded-md hover:bg-yellow-50 transition-colors duration-200">
                            Withdraw
                        </button>
                    @elseif($article->status === 'rejected')
                        <a href="{{ route('staff.news.show', $article->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 text-center transition-colors duration-200">
                            Preview
                        </a>
                        <a href="{{ route('staff.news.edit', $article->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 text-center transition-colors duration-200">
                            Edit & Resubmit
                        </a>
                    @elseif($article->status === 'approved')
                        <a href="{{ route('staff.news.show', $article->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 text-center transition-colors duration-200">
                            Preview
                        </a>
                        <button onclick="publishNews({{ $article->id }})" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200">
                            Publish Now
                        </button>
                    @else
                        <a href="{{ route('staff.news.edit', $article->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center transition-colors duration-200">
                            Continue Editing
                        </a>
                        <button onclick="submitNews({{ $article->id }})" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200">
                            Submit for Review
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No News Articles</h3>
            <p class="text-gray-600 mb-6">You haven't created any news articles yet. Start by creating your first article!</p>
            <a href="{{ route('staff.news.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
