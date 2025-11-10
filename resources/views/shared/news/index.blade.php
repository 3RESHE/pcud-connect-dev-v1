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
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">News & Updates</h1>
        <p class="text-gray-600">Stay informed with the latest news and announcements from PCU-D</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Articles</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_count'] }}</p>
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

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Featured</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['featured_count'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Categories</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['categories']->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('news.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="🔍 Search news by title, content..."
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm" />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Category Filter -->
                <select name="category"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    <option value="university_updates" {{ request('category') === 'university_updates' ? 'selected' : '' }}>
                        University Updates</option>
                    <option value="alumni_success" {{ request('category') === 'alumni_success' ? 'selected' : '' }}>Alumni
                        Success</option>
                    <option value="partnership_highlights"
                        {{ request('category') === 'partnership_highlights' ? 'selected' : '' }}>Partnership Highlights
                    </option>
                    <option value="campus_events" {{ request('category') === 'campus_events' ? 'selected' : '' }}>Campus
                        Events</option>
                    <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General</option>
                </select>

                <!-- Featured Filter -->
                <select name="featured"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Articles</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                </select>

                <!-- Filter Button -->
                <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark font-medium transition-colors text-sm">
                    Apply Filters
                </button>

                <!-- Reset Button -->
                <a href="{{ route('news.index') }}"
                    class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors text-sm text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($articles as $article)
            <a href="{{ route('news.show', $article->id) }}"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02] cursor-pointer">

                <!-- Featured Image -->
                @if ($article->featured_image)
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                            class="w-full h-full object-cover" />
                    </div>
                @endif

                <div class="p-6">
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $article->getCategoryDisplayName() }}
                        </span>
                        @if ($article->is_featured)
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                ⭐ Featured
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $article->title }}</h3>

                    <!-- Summary -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ $article->summary ?? $article->getExcerpt(120) }}
                    </p>

                    <!-- Meta Info -->
                    <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $article->published_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span>{{ $article->getViewsDisplay() }} views</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
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
            {{ $articles->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
