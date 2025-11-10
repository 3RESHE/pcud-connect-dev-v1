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
            <span class="mt-4 sm:mt-0 inline-block px-4 py-2 rounded-full text-sm font-semibold
                @if ($article->status === 'published') bg-blue-100 text-blue-800
                @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($article->status === 'approved') bg-green-100 text-green-800
                @elseif($article->status === 'rejected') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $article->getStatusDisplay() }}
            </span>
        </div>
    </div>

    <!-- Bento Box Grid - Responsive -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Article Content - Spans 2 columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Article Title & Summary Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 break-words">{{ $article->title }}</h2>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <!-- Status Badge -->
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if ($article->status === 'published') bg-blue-100 text-blue-800
                                @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($article->status === 'approved') bg-green-100 text-green-800
                                @elseif($article->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $article->getStatusDisplay() }}
                            </span>

                            <!-- Category Badge -->
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $article->getCategoryDisplayName() }}
                            </span>

                            <!-- Featured Badge -->
                            @if ($article->is_featured)
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                    ⭐ Featured
                                </span>
                            @endif

                            <!-- Tags (First 3) -->
                            @if ($article->tags && $article->tags->count() > 0)
                                @foreach ($article->tags->take(3) as $tag)
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-medium">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Article Summary</h3>
                    <p class="text-gray-600 leading-relaxed break-words">
                        {{ $article->summary ?? $article->getExcerpt(200) }}
                    </p>
                </div>
            </div>

            <!-- Featured Image Card -->
            @if ($article->featured_image)
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>
                    <div class="rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                            class="w-full h-auto max-h-96 object-cover" />
                    </div>
                </div>
            @endif

            <!-- Full Article Content Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Full Article Content</h3>
                <div class="prose prose-sm sm:prose max-w-full text-gray-700 leading-relaxed space-y-4 overflow-x-auto">
                    {!! $article->content !!}
                </div>
            </div>

            <!-- Rejection Reason (if rejected) -->
            @if ($article->status === 'rejected' && $article->rejection_reason)
                <div class="bg-red-50 rounded-xl shadow-sm p-4 sm:p-6 border border-red-200">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.268 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Admin Feedback</h3>
                            <p class="text-red-700 break-words">{{ $article->rejection_reason }}</p>
                            <p class="text-sm text-red-600 mt-2">Please revise the article based on this feedback and resubmit.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approval Message (if approved) -->
            @if ($article->status === 'approved')
                <div class="bg-green-50 rounded-xl shadow-sm p-4 sm:p-6 border border-green-200">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">Article Approved!</h3>
                            <p class="text-green-700">Your article has been approved by the administrator and is ready for publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Details & Timeline - Responsive -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Article Details Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article Details</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="font-medium text-gray-500">Author</label>
                        <p class="text-gray-900 font-medium break-words">{{ $article->creator->first_name ?? '' }} {{ $article->creator->last_name ?? '' }}</p>
                    </div>

                    <div>
                        <label class="font-medium text-gray-500">Category</label>
                        <p class="text-gray-900 break-words">{{ $article->getCategoryDisplayName() }}</p>
                    </div>

                    @if ($article->event_date)
                        <div>
                            <label class="font-medium text-gray-500">Event Date</label>
                            <p class="text-gray-900">{{ $article->getEventDateDisplay() }}</p>
                        </div>
                    @endif

                    @if ($article->partnership_with)
                        <div>
                            <label class="font-medium text-gray-500">Partnership With</label>
                            <p class="text-gray-900 break-words">{{ $article->partnership_with }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="font-medium text-gray-500">Created On</label>
                        <p class="text-gray-900 text-xs sm:text-sm">{{ $article->created_at->format('M j, Y - g:i A') }}</p>
                    </div>

                    @if ($article->published_at)
                        <div>
                            <label class="font-medium text-gray-500">Published On</label>
                            <p class="text-gray-900 text-xs sm:text-sm">{{ $article->published_at->format('M j, Y - g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submission Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Timeline</h3>
                <div class="space-y-4 text-sm">
                    <!-- Created -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center flex-shrink-0">
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
                        <div class="flex-1 pb-6 min-w-0">
                            <p class="font-medium text-gray-900">Article Created</p>
                            <p class="text-xs text-gray-500">{{ $article->created_at->format('M j, Y - g:i A') }}</p>
                            <p class="text-gray-600 mt-1 break-words">By {{ $article->creator->first_name ?? '' }}
                                {{ $article->creator->last_name ?? '' }}</p>
                        </div>
                    </div>

                    @if ($article->status === 'pending')
                        <!-- Pending Review -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">Pending Review</p>
                                <p class="text-xs text-gray-500">Awaiting admin approval</p>
                                <p class="text-gray-600 mt-1">Current Status</p>
                            </div>
                        </div>

                    @elseif($article->status === 'approved')
                        <!-- Approved -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="flex-1 pb-6 min-w-0">
                                <p class="font-medium text-gray-900">Approved by Admin</p>
                                <p class="text-xs text-gray-500">Ready to publish</p>
                            </div>
                        </div>

                        <!-- Ready to Publish -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">Ready to Publish</p>
                                <p class="text-xs text-gray-500">You can publish this article</p>
                                <p class="text-gray-600 mt-1">Current Status</p>
                            </div>
                        </div>

                    @elseif($article->status === 'published')
                        <!-- Approved -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="flex-1 pb-6 min-w-0">
                                <p class="font-medium text-gray-900">Approved by Admin</p>
                            </div>
                        </div>

                        <!-- Published -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">Published</p>
                                <p class="text-xs text-gray-500">{{ $article->published_at->format('M j, Y - g:i A') }}</p>
                                <p class="text-gray-600 mt-1">Live and visible to public</p>
                            </div>
                        </div>

                    @elseif($article->status === 'rejected')
                        <!-- Rejected -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">Rejected by Admin</p>
                                <p class="text-xs text-gray-500">{{ $article->updated_at->format('M j, Y - g:i A') }}</p>
                                <p class="text-gray-600 mt-1">Review feedback and resubmit</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- All Tags -->
            @if ($article->tags && $article->tags->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($article->tags as $tag)
                            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons - Responsive Bottom Section -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-between items-stretch sm:items-center">
            <a href="{{ route('staff.news.index') }}"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center font-medium transition-colors duration-200 order-2 sm:order-1">
                Back to List
            </a>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 order-1 sm:order-2">
                @if (in_array($article->status, ['draft', 'rejected']))
                    <a href="{{ route('staff.news.edit', $article->id) }}"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium text-center transition-colors duration-200">
                        Edit Article
                    </a>
                    @if ($article->status === 'draft')
                        <button onclick="openSubmitModal()"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors duration-200">
                            Submit for Review
                        </button>
                    @endif

                @elseif($article->status === 'pending')
                    <a href="{{ route('staff.news.edit', $article->id) }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-center transition-colors duration-200">
                        Edit Article
                    </a>
                    <button onclick="openWithdrawModal()"
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium transition-colors duration-200">
                        Withdraw Submission
                    </button>

                @elseif($article->status === 'approved')
                    <button onclick="openPublishModal()"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors duration-200">
                        Publish Now
                    </button>

                @elseif($article->status === 'published')
                    <!-- No edit button for published articles -->
                    <span class="px-6 py-3 bg-gray-100 text-gray-600 rounded-lg font-medium text-center cursor-not-allowed opacity-75">
                        Published (Read-only)
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- ========== MODALS ========== -->

    <!-- Submit Modal -->
    <div id="submitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Submit Article for Review?</h3>
                <p class="text-gray-600 text-center mb-6">
                    Your article will be sent to the admin for review. You'll be notified once they approve or reject it.
                </p>
                <div class="flex gap-3">
                    <button onclick="closeSubmitModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('staff.news.submit', $article->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Yes, Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div id="withdrawModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Withdraw Article?</h3>
                <p class="text-gray-600 text-center mb-6">
                    This article will be returned to draft status. You can edit and resubmit it later.
                </p>
                <div class="flex gap-3">
                    <button onclick="closeWithdrawModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('staff.news.withdraw', $article->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium transition-colors">
                            Yes, Withdraw
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Publish Modal -->
    <div id="publishModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Publish Article?</h3>
                <p class="text-gray-600 text-center mb-6">
                    This article will be published and visible to all users. This action cannot be undone easily.
                </p>
                <div class="flex gap-3">
                    <button onclick="closePublishModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('staff.news.publish', $article->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Yes, Publish
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function openWithdrawModal() {
            document.getElementById('withdrawModal').classList.remove('hidden');
        }

        function closeWithdrawModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }

        function openPublishModal() {
            document.getElementById('publishModal').classList.remove('hidden');
        }

        function closePublishModal() {
            document.getElementById('publishModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const submitModal = document.getElementById('submitModal');
            const withdrawModal = document.getElementById('withdrawModal');
            const publishModal = document.getElementById('publishModal');

            if (event.target === submitModal) {
                closeSubmitModal();
            }
            if (event.target === withdrawModal) {
                closeWithdrawModal();
            }
            if (event.target === publishModal) {
                closePublishModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSubmitModal();
                closeWithdrawModal();
                closePublishModal();
            }
        });
    </script>
@endsection
