@extends('layouts.admin')

@section('title', 'Review Article - PCU-DASMA Connect')

@section('content')
    <!-- Header Section with Status -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Review Article</h1>
                <p class="text-gray-600">Review and manage submitted news articles from staff</p>
            </div>
            <span
                class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                @if ($article->status === 'published') bg-blue-100 text-blue-800
                @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($article->status === 'approved') bg-green-100 text-green-800
                @elseif($article->status === 'rejected') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $article->getStatusDisplay() }}
            </span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-red-900">Validation Errors</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

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
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium
                                @if ($article->status === 'published') bg-blue-100 text-blue-800
                                @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($article->status === 'approved') bg-green-100 text-green-800
                                @elseif($article->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $article->getStatusDisplay() }}
                            </span>

                            <!-- Category Badge -->
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
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

            <!-- Previous Rejection Reason (if was rejected) -->
            @if ($article->status !== 'rejected' && $article->rejection_reason)
                <div class="bg-orange-50 rounded-xl shadow-sm p-4 sm:p-6 border border-orange-200">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-orange-900 mb-2">Previous Rejection Reason</h3>
                            <p class="text-orange-700 break-words">{{ $article->rejection_reason }}</p>
                            <p class="text-sm text-orange-600 mt-2">Note: Article was resubmitted after rejection</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Details & Admin Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Article Details Card (Read-Only) -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article Details</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="font-medium text-gray-500">Author</label>
                        <p class="text-gray-900 font-medium break-words">
                            {{ $article->creator->first_name ?? '' }} {{ $article->creator->last_name ?? '' }}
                        </p>
                    </div>

                    <div>
                        <label class="font-medium text-gray-500">Department</label>
                        <p class="text-gray-900 break-words">{{ $article->creator->department ?? 'N/A' }}</p>
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
                        <label class="font-medium text-gray-500">Submitted On</label>
                        <p class="text-gray-900 text-xs sm:text-sm">{{ $article->created_at->format('M j, Y - g:i A') }}</p>
                    </div>

                    @if ($article->published_at)
                        <div>
                            <label class="font-medium text-gray-500">Published On</label>
                            <p class="text-gray-900 text-xs sm:text-sm">{{ $article->published_at->format('M j, Y - g:i A') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Admin Notice -->
                <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-700">
                        <strong>Note:</strong> As admin, you can only change the status. Article content cannot be edited.
                    </p>
                </div>
            </div>

            <!-- Status Management Card -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Manage Status</h3>

                @if ($article->status === 'pending')
                    <div class="space-y-3">
                        <!-- Approve Button -->
                        <button onclick="openApproveModal()"
                            class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Approve Article
                        </button>

                        <!-- Reject Button -->
                        <button onclick="openRejectModal()"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject Article
                        </button>
                    </div>

                    <!-- Pending Status Info -->
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            <strong>⏳ Status:</strong> Waiting for your approval or rejection
                        </p>
                    </div>

                @elseif($article->status === 'approved')
                    <!-- Already Approved Info -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-semibold text-green-900">✓ Approved</p>
                        </div>
                        <p class="text-xs text-green-700 mb-3">This article has been approved and is ready for the staff
                            member to publish.</p>
                        <button onclick="openRejectModal()"
                            class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 font-medium transition-colors">
                            Change to Rejected
                        </button>
                    </div>

                @elseif($article->status === 'rejected')
                    <!-- Already Rejected Info -->
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-semibold text-red-900">✗ Rejected</p>
                        </div>
                        <p class="text-xs text-red-700 mb-3 font-medium">Current Rejection Reason:</p>
                        @if ($article->rejection_reason)
                            <p class="text-xs text-red-600 mb-3 p-2 bg-red-100 rounded">{{ $article->rejection_reason }}</p>
                        @else
                            <p class="text-xs text-red-600 mb-3 p-2 bg-red-100 rounded italic">No rejection reason provided</p>
                        @endif
                    </div>

                    <!-- Update Rejection or Approve -->
                    <div class="space-y-3">
                        <button onclick="openRejectModal()"
                            class="w-full px-4 py-2 bg-orange-600 text-white text-sm rounded-lg hover:bg-orange-700 font-medium transition-colors">
                            Update Rejection Reason
                        </button>
                        <button onclick="openApproveModal()"
                            class="w-full px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Change to Approved
                        </button>
                    </div>

                @elseif($article->status === 'published')
                    <!-- Published - No Changes Allowed -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-semibold text-blue-900">📰 Published</p>
                        </div>
                        <p class="text-xs text-blue-700">This article is now live and visible to the public. No changes can
                            be made.</p>
                    </div>
                @endif
            </div>

            <!-- All Tags -->
            @if ($article->tags && $article->tags->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
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

    <!-- Back Button -->
    <div class="flex gap-3 mb-8">
        <a href="{{ route('admin.approvals.news.index') }}"
            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
            ← Back to Articles
        </a>
    </div>

    <!-- ========== MODALS ========== -->

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Approve Article?</h3>
                <p class="text-gray-600 text-center mb-6">
                    This article will be approved and the staff member can publish it. Are you sure?
                </p>
                <div class="flex gap-3">
                    <button onclick="closeApproveModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('admin.approvals.news.approve', $article->id) }}" method="POST"
                        class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Yes, Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Reject Article?</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">
                    Provide a reason for rejection so the staff member knows what to fix.
                </p>
                <form action="{{ route('admin.approvals.news.reject', $article->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                        <textarea name="rejection_reason" rows="4" placeholder="Explain why this article is being rejected..."
                            class="w-full px-3 py-2 border @error('rejection_reason') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                            required></textarea>
                        @error('rejection_reason')
                            <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors">
                            Reject Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openApproveModal() {
            document.getElementById('approveModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');

            if (event.target === approveModal) {
                closeApproveModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>
@endsection
