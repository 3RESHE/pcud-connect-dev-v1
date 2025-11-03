@extends('layouts.staff')

@section('title', 'View Article - PCU-DASMA Connect')

@section('content')
    <!-- Header Section with Status -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">View Article</h1>
                <p class="text-gray-600">View details of your news article</p>
            </div>
            <span
                class="mt-4 sm:mt-0 inline-block
            @if ($article->status === 'published') bg-blue-100 text-blue-800
            @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($article->status === 'approved') bg-green-100 text-green-800
            @elseif($article->status === 'rejected') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif
            px-4 py-2 rounded-full text-sm font-semibold">
                @if ($article->status === 'published')
                    Published
                @elseif($article->status === 'pending')
                    Pending Approval
                @elseif($article->status === 'approved')
                    Approved
                @elseif($article->status === 'rejected')
                    Rejected
                @else
                    {{ ucfirst($article->status) }}
                @endif
            </span>
        </div>
    </div>

    <!-- Bento Box Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Article Content - Spans 2 columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Article Title & Summary Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ $article->title }}
                        </h2>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span
                                class="
                            @if ($article->category === 'university_updates') bg-blue-100 text-blue-800
                            @elseif($article->category === 'alumni_success') bg-yellow-100 text-yellow-800
                            @elseif($article->category === 'campus_events') bg-red-100 text-red-800
                            @elseif($article->category === 'partnership_highlights') bg-emerald-100 text-emerald-800
                            @else bg-gray-100 text-gray-800 @endif
                            px-3 py-1 rounded-full text-xs font-medium">
                                {{ $article->getCategoryDisplayName() }}
                            </span>
                            @if ($article->is_featured)
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Featured
                                </span>
                            @endif
                            @if ($article->tags)
                                @foreach (array_slice(explode(',', $article->tags), 0, 3) as $tag)
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Article Summary</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $article->summary ?? $article->getExcerpt(200) }}
                    </p>
                </div>
            </div>

            <!-- Featured Image Card -->
            @if ($article->featured_image)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>
                    <div class="rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                            class="w-full h-64 object-cover" />
                    </div>
                </div>
            @endif

            <!-- Full Article Content Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Full Article Content</h3>
                <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            <!-- Rejection Reason (if rejected) -->
            @if ($article->status === 'rejected' && $article->rejection_reason)
                <div class="bg-red-50 rounded-xl shadow-sm p-6 border border-red-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.268 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Admin Feedback</h3>
                            <p class="text-red-700">{{ $article->rejection_reason }}</p>
                            <p class="text-sm text-red-600 mt-2">Please revise the article based on this feedback and
                                resubmit.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approval Message (if approved) -->
            @if ($article->status === 'approved')
                <div class="bg-green-50 rounded-xl shadow-sm p-6 border border-green-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">Article Approved!</h3>
                            <p class="text-green-700">Your article has been approved by the administrator and is ready for
                                publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Details & Timeline -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Article Details Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Author</label>
                        <p class="text-gray-900">{{ $article->creator->first_name }} {{ $article->creator->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Category</label>
                        <p class="text-gray-900">{{ $article->getCategoryDisplayName() }}</p>
                    </div>
                    @if ($article->event_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Event Date</label>
                            <p class="text-gray-900">{{ $article->event_date->format('F j, Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created On</label>
                        <p class="text-gray-900">{{ $article->created_at->format('F j, Y - g:i A') }}</p>
                    </div>
                    @if ($article->published_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Published On</label>
                            <p class="text-gray-900">{{ $article->published_at->format('F j, Y - g:i A') }}</p>
                        </div>
                    @endif
                    @if ($article->partnership_with)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Partnership With</label>
                            <p class="text-gray-900">{{ $article->partnership_with }}</p>
                        </div>
                    @endif
                    @if ($article->status === 'published')
                        <div>
                            <label class="text-sm font-medium text-gray-500">Views</label>
                            <p class="text-gray-900">{{ $article->getViewsDisplay() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submission Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Timeline</h3>
                <div class="space-y-4">
                    <!-- Created -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @if ($article->status !== 'draft')
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <p class="text-sm font-medium text-gray-900">Article Created</p>
                            <p class="text-xs text-gray-500">{{ $article->created_at->format('M j, Y - g:i A') }}</p>
                            <p class="text-sm text-gray-600 mt-1">By {{ $article->creator->first_name }}
                                {{ $article->creator->last_name }}</p>
                        </div>
                    </div>

                    @if ($article->status === 'pending')
                        <!-- Pending Review -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pending Review</p>
                                <p class="text-xs text-gray-500">Awaiting admin approval</p>
                                <p class="text-sm text-gray-600 mt-1">Current Status</p>
                            </div>
                        </div>
                    @elseif($article->status === 'approved')
                        <!-- Approved -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                @if ($article->status === 'approved')
                                    <div class="w-0.5 h-full bg-gray-200"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-6">
                                <p class="text-sm font-medium text-gray-900">Approved by Admin</p>
                                <p class="text-xs text-gray-500">
                                    {{ $article->approved_at ? $article->approved_at->format('M j, Y - g:i A') : 'Recently' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Ready to publish</p>
                            </div>
                        </div>

                        <!-- Ready to Publish -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z">
                                        </path>
                                        <path
                                            d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Ready to Publish</p>
                                <p class="text-xs text-gray-500">You can publish this article</p>
                                <p class="text-sm text-gray-600 mt-1">Current Status</p>
                            </div>
                        </div>
                    @elseif($article->status === 'published')
                        <!-- Approved -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="flex-1 pb-6">
                                <p class="text-sm font-medium text-gray-900">Approved by Admin</p>
                                <p class="text-xs text-gray-500">
                                    {{ $article->approved_at ? $article->approved_at->format('M j, Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Published -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Published</p>
                                <p class="text-xs text-gray-500">{{ $article->published_at->format('M j, Y - g:i A') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Live and visible to public</p>
                            </div>
                        </div>
                    @elseif($article->status === 'rejected')
                        <!-- Rejected -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Rejected by Admin</p>
                                <p class="text-xs text-gray-500">{{ $article->updated_at->format('M j, Y - g:i A') }}</p>
                                <p class="text-sm text-gray-600 mt-1">Review feedback and resubmit</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- All Tags -->
            @if ($article->tags)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $article->tags) as $tag)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                                {{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons - Fixed at Bottom -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
            <a href="{{ route('staff.news.index') }}"
                class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center font-medium transition-colors duration-200">
                Back to List
            </a>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                @if (in_array($article->status, ['draft', 'rejected']))
                    <a href="{{ route('staff.news.edit', $article->id) }}"
                        class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium text-center transition-colors duration-200">
                        Edit Article
                    </a>
                    @if ($article->status === 'draft')
                        <form action="{{ route('staff.news.submit', $article->id) }}" method="POST"
                            class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors duration-200"
                                onclick="return confirm('Submit this article for admin review?')">
                                Submit for Review
                            </button>
                        </form>
                    @endif
                @elseif($article->status === 'pending')
                    <a href="{{ route('staff.news.edit', $article->id) }}"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center transition-colors duration-200">
                        Edit Article
                    </a>
                    <form action="{{ route('staff.news.withdraw', $article->id) }}" method="POST"
                        class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium transition-colors duration-200"
                            onclick="return confirm('Withdraw this article from review?')">
                            Withdraw Submission
                        </button>
                    </form>
                @elseif($article->status === 'approved')
                    {{-- <form action="{{ route('staff.news.publish', $article->id) }}" method="POST" class="w-full sm:w-auto"> --}}
                    <form action="" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors duration-200"
                            onclick="return confirm('Publish this approved article now?')">
                            Publish Now
                        </button>
                    </form>
                @elseif($article->status === 'published')
                    <a href="{{ route('staff.news.edit', $article->id) }}"
                        class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium text-center transition-colors duration-200">
                        Edit Article
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
