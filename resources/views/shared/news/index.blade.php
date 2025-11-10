@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        'partner' => 'layouts.partner',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'News & Updates - PCU-DASMA Connect')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">News & Updates</h1>
            <p class="text-gray-600">Stay connected with the latest news from PCU-DASMA</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8 border border-gray-200">
            <form method="GET" action="{{ route('news.index') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search news articles..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary text-sm" />
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex gap-3">
                        <select name="category"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">All Categories</option>
                            <option value="university_updates" {{ request('category') === 'university_updates' ? 'selected' : '' }}>
                                University Updates</option>
                            <option value="alumni_success" {{ request('category') === 'alumni_success' ? 'selected' : '' }}>
                                Alumni Success</option>
                            <option value="partnership_highlights"
                                {{ request('category') === 'partnership_highlights' ? 'selected' : '' }}>Partnership
                                Highlights</option>
                            <option value="campus_events" {{ request('category') === 'campus_events' ? 'selected' : '' }}>
                                Campus Events</option>
                            <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General</option>
                        </select>

                        <button type="submit"
                            class="bg-primary text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                            Apply
                        </button>

                        <a href="{{ route('news.index') }}"
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Featured News Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured News</h2>

            @if ($articles->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Main Featured Article -->
                    @php
                        $featuredArticles = $articles->filter(fn($a) => $a->is_featured)->take(1);
                        $featured = $featuredArticles->first() ?? $articles->first();
                    @endphp

                    <a href="{{ route('news.show', $featured->id) }}" class="group cursor-pointer block">
                        <div
                            class="relative overflow-hidden rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow duration-300 h-full">
                            @if ($featured->featured_image)
                                <img class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                    src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}" />
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            @if ($featured->is_featured)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Featured
                                    </span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <span
                                        class="@if ($featured->category === 'university_updates') bg-blue-100 text-blue-800
                                        @elseif($featured->category === 'alumni_success') bg-yellow-100 text-yellow-800
                                        @elseif($featured->category === 'partnership_highlights') bg-green-100 text-green-800
                                        @elseif($featured->category === 'campus_events') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-3">
                                        {{ $featured->getCategoryDisplayName() }}
                                    </span>
                                    <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $featured->title }}
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $featured->summary ?? $featured->getExcerpt(150) }}
                                </p>

                                <span class="text-primary font-medium group-hover:underline">Read More →</span>
                            </div>
                        </div>
                    </a>

                    <!-- Secondary Featured Articles -->
                    <div class="space-y-6">
                        @foreach ($articles->skip(1)->take(3) as $secondary)
                            <a href="{{ route('news.show', $secondary->id) }}" class="group cursor-pointer block">
                                <div
                                    class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200">
                                    <div class="flex">
                                        @if ($secondary->featured_image)
                                            <img class="w-32 h-24 object-cover group-hover:scale-105 transition-transform duration-300"
                                                src="{{ Storage::url($secondary->featured_image) }}"
                                                alt="{{ $secondary->title }}" />
                                        @else
                                            <div
                                                class="w-32 h-24 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="flex-1 p-4">
                                            <div class="flex items-center text-xs text-gray-500 mb-1">
                                                <span
                                                    class="@if ($secondary->category === 'university_updates') bg-blue-100 text-blue-800
                                                    @elseif($secondary->category === 'alumni_success') bg-yellow-100 text-yellow-800
                                                    @elseif($secondary->category === 'partnership_highlights') bg-green-100 text-green-800
                                                    @elseif($secondary->category === 'campus_events') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-2">
                                                    {{ $secondary->getCategoryDisplayName() }}
                                                </span>
                                                <span>{{ $secondary->published_at->format('M d, Y') }}</span>
                                            </div>

                                            <h4
                                                class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-2">
                                                {{ $secondary->title }}
                                            </h4>

                                            <p class="text-xs text-gray-600 line-clamp-2">
                                                {{ $secondary->summary ?? $secondary->getExcerpt(80) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                    <p class="text-gray-600">No featured articles available.</p>
                </div>
            @endif
        </div>

        <!-- Recent News Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
                <a href="{{ route('news.index') }}"
                    class="text-primary hover:text-blue-700 font-medium text-sm">View All →</a>
            </div>

            @if ($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($articles as $article)
                        <a href="{{ route('news.show', $article->id) }}"
                            class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200 block">

                            @if ($article->featured_image)
                                <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                    src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" />
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <span
                                        class="@if ($article->category === 'university_updates') bg-blue-100 text-blue-800
                                        @elseif($article->category === 'alumni_success') bg-yellow-100 text-yellow-800
                                        @elseif($article->category === 'partnership_highlights') bg-green-100 text-green-800
                                        @elseif($article->category === 'campus_events') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-3">
                                        {{ $article->getCategoryDisplayName() }}
                                    </span>
                                    <span>{{ $article->published_at->format('M d, Y') }}</span>
                                </div>

                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $article->title }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $article->summary ?? $article->getExcerpt(100) }}
                                </p>

                                <span class="text-primary font-medium group-hover:underline text-sm">Read More</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($articles->hasPages())
                    <div class="mt-8 flex justify-center">
                        <div class="flex gap-2">
                            @if ($articles->onFirstPage())
                                <span
                                    class="px-4 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $articles->previousPageUrl() }}"
                                    class="px-4 py-2 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                    Previous
                                </a>
                            @endif

                            @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                @if ($page == $articles->currentPage())
                                    <span class="px-4 py-2 bg-primary text-white border border-primary rounded-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($articles->hasMorePages())
                                <a href="{{ $articles->nextPageUrl() }}"
                                    class="px-4 py-2 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                    Next
                                </a>
                            @else
                                <span
                                    class="px-4 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No News Articles Found</h3>
                    <p class="text-gray-600">Try adjusting your search or filters</p>
                </div>
            @endif
        </div>
    </div>
@endsection
