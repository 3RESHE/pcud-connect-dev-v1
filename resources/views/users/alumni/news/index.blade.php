@extends('layouts.alumni')

@section('title', 'News & Updates - PCU-DASMA Connect')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">News & Updates</h1>
            <p class="text-gray-600">
                Stay connected with the latest news from PCU-DASMA and your fellow alumni
            </p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('alumni.news.index') }}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search news articles..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary" />
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <select name="category" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">All Categories</option>
                            <option value="university" {{ request('category') == 'university' ? 'selected' : '' }}>
                                University Update</option>
                            <option value="alumni" {{ request('category') == 'alumni' ? 'selected' : '' }}>Alumni Success
                            </option>
                            <option value="event" {{ request('category') == 'event' ? 'selected' : '' }}>Event News
                            </option>
                            <option value="partnership" {{ request('category') == 'partnership' ? 'selected' : '' }}>
                                Partnership Success</option>
                            <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General News
                            </option>
                        </select>
                        <select name="sort" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="recent" {{ request('sort') == 'recent' || !request('sort') ? 'selected' : '' }}>
                                Most Recent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Featured News Section -->
        @if ($featuredNews->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured News</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Main Featured Article -->
                    @if ($featuredNews->first())
                        @php $mainFeatured = $featuredNews->first(); @endphp
                        <a href="{{ route('alumni.news.show', $mainFeatured->id) }}" class="group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                                @if ($mainFeatured->featured_image)
                                    <img class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                        src="{{ asset('storage/' . $mainFeatured->featured_image) }}"
                                        alt="{{ $mainFeatured->title }}" />
                                @else
                                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        <span
                                            class="
                                        @if ($mainFeatured->category == 'university') bg-blue-100 text-blue-800
                                        @elseif($mainFeatured->category == 'alumni') bg-yellow-100 text-yellow-800
                                        @elseif($mainFeatured->category == 'event') bg-indigo-100 text-indigo-800
                                        @elseif($mainFeatured->category == 'partnership') bg-emerald-100 text-emerald-800
                                        @else bg-teal-100 text-teal-800 @endif
                                        px-2 py-1 rounded-full text-xs mr-3
                                    ">
                                            {{ ucfirst(str_replace('_', ' ', $mainFeatured->category)) }}
                                        </span>
                                        <span>{{ $mainFeatured->created_at->format('F d, Y') }}</span>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                        {{ $mainFeatured->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ strip_tags($mainFeatured->content) }}
                                    </p>
                                    <span class="text-primary font-medium group-hover:underline">Read More →</span>
                                </div>
                            </div>
                        </a>
                    @endif

                    <!-- Secondary Featured Articles -->
                    <div class="space-y-6">
                        @foreach ($featuredNews->skip(1)->take(3) as $news)
                            <a href="{{ route('alumni.news.show', $news->id) }}"
                                class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                                <div class="flex">
                                    @if ($news->featured_image)
                                        <img class="w-32 h-24 object-cover group-hover:scale-105 transition-transform duration-300"
                                            src="{{ asset('storage/' . $news->featured_image) }}"
                                            alt="{{ $news->title }}" />
                                    @else
                                        <div class="w-32 h-24 bg-gray-200 flex items-center justify-center flex-shrink-0">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 p-4">
                                        <div class="flex items-center text-xs text-gray-500 mb-1">
                                            <span
                                                class="
                                            @if ($news->category == 'university') bg-blue-100 text-blue-800
                                            @elseif($news->category == 'alumni') bg-yellow-100 text-yellow-800
                                            @elseif($news->category == 'event') bg-indigo-100 text-indigo-800
                                            @elseif($news->category == 'partnership') bg-emerald-100 text-emerald-800
                                            @else bg-teal-100 text-teal-800 @endif
                                            px-2 py-1 rounded-full text-xs mr-2
                                        ">
                                                {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                                            </span>
                                            <span>{{ $news->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h4
                                            class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-2">
                                            {{ $news->title }}
                                        </h4>
                                        <p class="text-xs text-gray-600 line-clamp-2">
                                            {{ strip_tags($news->content) }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent News Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($news as $article)
                    <!-- News Article Card -->
                    <a href="{{ route('alumni.news.show', $article->id) }}"
                        class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                        @if ($article->featured_image)
                            <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" />
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span
                                    class="
                            @if ($article->category == 'university') bg-blue-100 text-blue-800
                            @elseif($article->category == 'alumni') bg-yellow-100 text-yellow-800
                            @elseif($article->category == 'event') bg-indigo-100 text-indigo-800
                            @elseif($article->category == 'partnership') bg-emerald-100 text-emerald-800
                            @else bg-teal-100 text-teal-800 @endif
                            px-2 py-1 rounded-full text-xs mr-3
                        ">
                                    {{ ucfirst(str_replace('_', ' ', $article->category)) }}
                                </span>
                                <span>{{ $article->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ strip_tags($article->content) }}
                            </p>
                            <span class="text-primary font-medium group-hover:underline">Read More</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-lg">No news articles found.</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or search criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($news->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $news->links() }}
                </div>
            @endif
        </div>

        <!-- Load More Button (if no pagination) -->
        @if (!$news->hasPages() && $news->count() > 0)
            <div class="text-center">
                <button onclick="loadMoreNews()"
                    class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Load More Articles
                </button>
            </div>
        @endif
    </div>

    <script>
        function loadMoreNews() {
            alert('Loading more articles... This would fetch additional news items from the server.');
        }
    </script>
@endsection
