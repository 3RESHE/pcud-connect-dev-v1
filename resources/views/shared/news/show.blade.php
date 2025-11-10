@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        'partner' => 'layouts.partner',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', $newsArticle->title . ' - PCU-DASMA Connect')

@section('content')
    <!-- Back Button & Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('news.index') }}"
            class="inline-flex items-center text-primary hover:text-primary-dark font-medium mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to News
        </a>
    </div>

    <!-- Main Article Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - 2 Columns -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Featured Image -->
            @if ($newsArticle->featured_image)
                <div class="rounded-xl overflow-hidden bg-gray-100 h-96 sm:h-[500px]">
                    <img src="{{ Storage::url($newsArticle->featured_image) }}" alt="{{ $newsArticle->title }}"
                        class="w-full h-full object-cover" />
                </div>
            @endif

            <!-- Article Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-gray-200">
                <!-- Title -->
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">{{ $newsArticle->title }}</h1>

                <!-- Metadata -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600 mb-4 sm:mb-0">
                        <!-- Author -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">{{ $newsArticle->creator->first_name ?? '' }}
                                {{ $newsArticle->creator->last_name ?? '' }}</span>
                        </div>

                        <!-- Published Date -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $newsArticle->published_at->format('F j, Y') }}</span>
                        </div>

                        <!-- Views -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span>{{ $newsArticle->getViewsDisplay() }} views</span>
                        </div>
                    </div>

                    <!-- Share Icons -->
                    <div class="flex items-center gap-3">
                        <button onclick="shareArticle('facebook')"
                            class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center"
                            title="Share on Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z">
                                </path>
                            </svg>
                        </button>
                        <button onclick="shareArticle('twitter')"
                            class="w-10 h-10 bg-blue-50 text-blue-400 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center"
                            title="Share on Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7s1.1 6 5 7a4.5 4.5 0 001.5-1">
                                </path>
                            </svg>
                        </button>
                        <button onclick="shareArticle('email')"
                            class="w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center"
                            title="Share via Email">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <!-- Category -->
                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $newsArticle->getCategoryDisplayName() }}
                    </span>

                    <!-- Featured -->
                    @if ($newsArticle->is_featured)
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            ⭐ Featured Article
                        </span>
                    @endif

                    <!-- Event Date (if applicable) -->
                    @if ($newsArticle->event_date)
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-amber-50 text-amber-700 border border-amber-200">
                            📅 {{ $newsArticle->getEventDateDisplay() }}
                        </span>
                    @endif

                    <!-- Partnership -->
                    @if ($newsArticle->partnership_with)
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-200">
                            🤝 {{ $newsArticle->partnership_with }}
                        </span>
                    @endif
                </div>

                <!-- Summary/Excerpt -->
                @if ($newsArticle->summary)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-lg text-gray-700 font-medium italic">{{ $newsArticle->summary }}</p>
                    </div>
                @endif
            </div>

            <!-- Full Article Content -->
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-gray-200">
                <div class="prose prose-sm sm:prose max-w-full text-gray-700 leading-relaxed">
                    {!! $newsArticle->content !!}
                </div>
            </div>

            <!-- Article Tags -->
            @if ($newsArticle->tags && $newsArticle->tags->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Topics</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($newsArticle->tags as $tag)
                            <a href="{{ route('news.index', ['tag' => $tag->slug]) }}"
                                class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium hover:bg-indigo-100 transition-colors border border-indigo-200">
                                # {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Author Information Card -->
            <div class="bg-gradient-to-r from-primary to-primary-dark rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-16 h-16 bg-white bg-opacity-20 rounded-full p-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ $newsArticle->creator->first_name ?? '' }}
                            {{ $newsArticle->creator->last_name ?? '' }}</h3>
                        <p class="text-white text-opacity-90">
                            {{ $newsArticle->creator->department ?? 'Staff Member' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - 1 Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Article Info Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article Information</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="font-medium text-gray-500">Status</label>
                        <p class="text-gray-900 mt-1">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                📰 Published
                            </span>
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <label class="font-medium text-gray-500">Category</label>
                        <p class="text-gray-900 mt-1">{{ $newsArticle->getCategoryDisplayName() }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <label class="font-medium text-gray-500">Published</label>
                        <p class="text-gray-900 mt-1 text-xs">{{ $newsArticle->published_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <label class="font-medium text-gray-500">Views</label>
                        <p class="text-gray-900 mt-1 text-2xl font-bold">{{ $newsArticle->getViewsDisplay() }}</p>
                    </div>

                    @if ($newsArticle->event_date)
                        <div class="border-t border-gray-200 pt-4">
                            <label class="font-medium text-gray-500">Event Date</label>
                            <p class="text-gray-900 mt-1">{{ $newsArticle->getEventDateDisplay() }}</p>
                        </div>
                    @endif

                    @if ($newsArticle->partnership_with)
                        <div class="border-t border-gray-200 pt-4">
                            <label class="font-medium text-gray-500">Partnership</label>
                            <p class="text-gray-900 mt-1">{{ $newsArticle->partnership_with }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Articles -->
            @if ($relatedArticles->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Articles</h3>
                    <div class="space-y-3">
                        @foreach ($relatedArticles as $related)
                            <a href="{{ route('news.show', $related->id) }}"
                                class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group">
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-primary">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $related->published_at->format('M j, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Share Box -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Share This Article</h3>
                <div class="space-y-2">
                    <button onclick="shareArticle('facebook')"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                        Share on Facebook
                    </button>
                    <button onclick="shareArticle('twitter')"
                        class="w-full px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors font-medium text-sm">
                        Share on Twitter
                    </button>
                    <button onclick="shareArticle('email')"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                        Share via Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Share Functionality -->
    <script>
        function shareArticle(platform) {
            const url = window.location.href;
            const title = '{{ $newsArticle->title }}';

            if (platform === 'facebook') {
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
                    'facebook-share', 'width=500,height=600');
            } else if (platform === 'twitter') {
                window.open(
                    `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
                    'twitter-share', 'width=500,height=600');
            } else if (platform === 'email') {
                window.location.href =
                    `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent('Check out this article: ' + url)}`;
            }
        }
    </script>
@endsection
