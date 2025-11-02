@extends('layouts.partner')

@section('title', 'News & Updates - PCU-DASMA Connect')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">News & Updates</h1>
    <p class="text-gray-600">
        Stay connected with the latest news from PCU-DASMA and community partners
    </p>
</div>

<!-- Search and Filter Section -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex-1 max-w-md">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search news articles..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary"
                    oninput="filterNews()"
                />
            </div>
        </div>
        <div class="flex space-x-4">
            <select
                id="categoryFilter"
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                onchange="filterNews()"
            >
                <option value="">All Categories</option>
                <option value="university_updates">University Update</option>
                <option value="alumni_success">Alumni Success</option>
                <option value="campus_events">Campus Events</option>
                <option value="partnership_highlights">Partnership Success</option>
                <option value="general">General News</option>
            </select>
            <select
                id="sortFilter"
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                onchange="sortNews()"
            >
                <option value="recent">Most Recent</option>
                <option value="oldest">Oldest First</option>
                <option value="views">Most Viewed</option>
            </select>
        </div>
    </div>
</div>

<!-- Featured News Section -->
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured News</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Main Featured Article -->
        @if($featured_articles->count() > 0)
            @php $mainFeatured = $featured_articles->first(); @endphp
            <div onclick="viewNews('{{ $mainFeatured->id }}')" class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                    @if($mainFeatured->featured_image)
                        <img
                            class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                            src="{{ asset('storage/' . $mainFeatured->featured_image) }}"
                            alt="{{ $mainFeatured->title }}"
                        />
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span class="@if($mainFeatured->category === 'university_updates') bg-blue-100 text-blue-800 @elseif($mainFeatured->category === 'alumni_success') bg-yellow-100 text-yellow-800 @elseif($mainFeatured->category === 'campus_events') bg-red-100 text-red-800 @elseif($mainFeatured->category === 'partnership_highlights') bg-emerald-100 text-emerald-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-3">
                                {{ $mainFeatured->getCategoryDisplayName() }}
                            </span>
                            <span>{{ $mainFeatured->published_at->format('F j, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                            {{ $mainFeatured->title }}
                        </h3>
                        <p class="text-gray-600 mb-4">
                            {{ $mainFeatured->getExcerpt(200) }}
                        </p>
                        <span class="text-primary font-medium group-hover:underline">Read More →</span>
                    </div>
                </div>
            </div>

            <!-- Secondary Featured Articles -->
            <div class="space-y-6">
                @foreach($featured_articles->skip(1)->take(3) as $featured)
                    <div onclick="viewNews('{{ $featured->id }}')" class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="flex">
                            @if($featured->featured_image)
                                <img
                                    class="w-32 h-24 object-cover group-hover:scale-105 transition-transform duration-300"
                                    src="{{ asset('storage/' . $featured->featured_image) }}"
                                    alt="{{ $featured->title }}"
                                />
                            @else
                                <div class="w-32 h-24 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 p-4">
                                <div class="flex items-center text-xs text-gray-500 mb-1">
                                    <span class="@if($featured->category === 'university_updates') bg-blue-100 text-blue-800 @elseif($featured->category === 'alumni_success') bg-yellow-100 text-yellow-800 @elseif($featured->category === 'campus_events') bg-red-100 text-red-800 @elseif($featured->category === 'partnership_highlights') bg-emerald-100 text-emerald-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-2">
                                        {{ $featured->getCategoryDisplayName() }}
                                    </span>
                                    <span>{{ $featured->published_at->format('F j, Y') }}</span>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-primary transition-colors">
                                    {{ $featured->title }}
                                </h4>
                                <p class="text-xs text-gray-600">
                                    {{ $featured->getExcerpt(100) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-500">No featured articles available.</p>
            </div>
        @endif
    </div>
</div>

<!-- Latest News Section -->
<div class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
        <span class="text-sm text-gray-600">{{ $total_articles }} articles</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="newsGrid">
        @forelse($articles as $article)
            <div
                class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden news-card"
                onclick="viewNews('{{ $article->id }}')"
                data-category="{{ $article->category }}"
                data-date="{{ $article->published_at->timestamp }}"
                data-views="{{ $article->views_count }}"
            >
                @if($article->featured_image)
                    <img
                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                        src="{{ asset('storage/' . $article->featured_image) }}"
                        alt="{{ $article->title }}"
                    />
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                        <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span class="@if($article->category === 'university_updates') bg-blue-100 text-blue-800 @elseif($article->category === 'alumni_success') bg-yellow-100 text-yellow-800 @elseif($article->category === 'campus_events') bg-red-100 text-red-800 @elseif($article->category === 'partnership_highlights') bg-emerald-100 text-emerald-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-3">
                            {{ $article->getCategoryDisplayName() }}
                        </span>
                        <span>{{ $article->published_at->format('F j, Y') }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                        {{ $article->title }}
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $article->getExcerpt(150) }}
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $article->getViewsDisplay() }}
                        </span>
                        <span class="text-primary font-medium group-hover:underline">Read More</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No News Available</h3>
                <p class="text-gray-600">No news articles match your filters.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Load More Button -->
@if($articles->hasMorePages())
    <div class="text-center mb-12">
        <button
            onclick="loadMoreNews()"
            class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors"
        >
            Load More Articles
        </button>
    </div>
@endif

<!-- Pagination Info -->
@if($articles->total() > 0)
    <div class="text-center text-sm text-gray-600 mb-8">
        Showing {{ $articles->firstItem() ?? 0 }} to {{ $articles->lastItem() ?? 0 }} of {{ $articles->total() }} articles
    </div>
@endif

<script src="{{ asset('js/partner/news.js') }}"></script>
@endsection
