@extends('layouts.partner')

@section('title', 'My Partnerships - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Partnership Proposals</h1>
            <p class="text-gray-600">Manage your community partnership proposals with PCU-DASMA</p>
        </div>
        <a href="{{ route('partner.partnerships.create') }}"
            class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 flex items-center justify-center sm:justify-start transition-colors duration-200 w-full sm:w-auto">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Partnership Proposal
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <!-- Total -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Proposals</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_partnerships ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Under Discussion</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $discussion_count ?? 0 }}</p>
                </div>
            </div>
        </div>

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
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $approved_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-400 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completed_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="-mb-px flex space-x-8 whitespace-nowrap" role="tablist">
                <button onclick="filterPartnerships('all')"
                    class="partnership-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="true">
                    All Proposals ({{ $total_partnerships ?? 0 }})
                </button>
                <button onclick="filterPartnerships('submitted')"
                    class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Pending Review ({{ $pending_count ?? 0 }})
                </button>
                <button onclick="filterPartnerships('under_review')"
                    class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Under Discussion ({{ $discussion_count ?? 0 }})
                </button>
                <button onclick="filterPartnerships('approved')"
                    class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Approved ({{ $approved_count ?? 0 }})
                </button>
                <button onclick="filterPartnerships('rejected')"
                    class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Rejected ({{ $rejected_count ?? 0 }})
                </button>
                <button onclick="filterPartnerships('completed')"
                    class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Completed ({{ $completed_count ?? 0 }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Partnerships List -->
    <div id="partnershipsContainer" class="space-y-4">
        @forelse($partnerships as $partnership)
            <a href="{{ route('partner.partnerships.show', $partnership->id) }}"
                class="partnership-card block bg-white rounded-lg shadow-sm hover:shadow-md border-l-4
                @if($partnership->status === 'submitted') border-yellow-500
                @elseif($partnership->status === 'under_review') border-blue-500
                @elseif($partnership->status === 'approved') border-green-500
                @elseif($partnership->status === 'rejected') border-red-500
                @elseif($partnership->status === 'completed') border-blue-400
                @endif transition-all duration-200 hover:-translate-y-1"
                data-status="{{ $partnership->status }}">

                <div class="p-6">
                    <!-- Status & Type Badges -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                        <div class="flex flex-wrap items-center gap-2 mb-3 sm:mb-0">
                            <span class="@if($partnership->status === 'submitted') bg-yellow-100 text-yellow-800
                                @elseif($partnership->status === 'under_review') bg-blue-100 text-blue-800
                                @elseif($partnership->status === 'approved') bg-green-100 text-green-800
                                @elseif($partnership->status === 'rejected') bg-red-100 text-red-800
                                @elseif($partnership->status === 'completed') bg-blue-100 text-blue-800
                                @endif px-3 py-1 rounded-full text-xs font-medium">
                                @if($partnership->status === 'submitted')
                                    Pending Review
                                @elseif($partnership->status === 'under_review')
                                    Under Discussion
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $partnership->status)) }}
                                @endif
                            </span>
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $partnership->getActivityTypeDisplay() }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap">
                            @if($partnership->status === 'submitted')
                                Submitted {{ $partnership->created_at->format('M j, Y') }}
                            @elseif($partnership->status === 'completed')
                                Completed {{ $partnership->completed_at?->format('M j, Y') ?? 'N/A' }}
                            @else
                                {{ $partnership->created_at->format('M j, Y') }}
                            @endif
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                        {{ $partnership->activity_title }}
                    </h3>
                    <p class="text-gray-600 text-sm sm:text-base mb-4 line-clamp-2">
                        {{ Str::limit($partnership->activity_description, 150) }}
                    </p>

                    <!-- Status-Specific Messages -->
                    @if($partnership->status === 'submitted')
                        <div class="bg-yellow-50 p-3 rounded-lg mb-4 border border-yellow-100">
                            <p class="text-xs sm:text-sm text-yellow-700">
                                ⏱️ Awaiting admin review. Click to see full details.
                            </p>
                        </div>
                    @elseif($partnership->status === 'under_review')
                        <div class="bg-blue-50 p-3 rounded-lg mb-4 border border-blue-100">
                            <p class="text-xs sm:text-sm text-blue-700">
                                💬 In discussion with admins. Click to view feedback.
                            </p>
                        </div>
                    @elseif($partnership->status === 'approved')
                        <div class="bg-green-50 p-3 rounded-lg mb-4 border border-green-100">
                            <p class="text-xs sm:text-sm text-green-700">
                                ✅ Approved! Click to view details. Admins will mark complete after the activity.
                            </p>
                        </div>
                    @elseif($partnership->status === 'rejected')
                        <div class="bg-red-50 p-3 rounded-lg mb-4 border border-red-100">
                            <p class="text-xs sm:text-sm text-red-700">
                                ❌ Not approved. Click to view feedback and resubmit.
                            </p>
                        </div>
                    @elseif($partnership->status === 'completed')
                        <div class="bg-blue-50 p-3 rounded-lg mb-4 border border-blue-100">
                            <p class="text-xs sm:text-sm text-blue-700">
                                🎉 Successfully completed on {{ $partnership->completed_at?->format('M j, Y') ?? 'N/A' }}. Thank you for partnering!
                            </p>
                        </div>
                    @endif

                    <!-- Partnership Details Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 pb-4 border-b border-gray-200">
                        <!-- Event Date -->
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Date</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">
                                {{ $partnership->activity_date?->format('M j') ?? 'TBA' }}
                            </p>
                        </div>

                        <!-- Event Time -->
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Time</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">
                                {{ $partnership->activity_time?->format('h:i A') ?? 'TBA' }}
                            </p>
                        </div>

                        <!-- Organization -->
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Organization</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">
                                {{ $partnership->organization_name }}
                            </p>
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Contact</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">
                                {{ $partnership->contact_name }}
                            </p>
                        </div>

                        <!-- Contact Email -->
                        <div class="col-span-2 sm:col-span-1">
                            <p class="text-xs text-gray-500 font-medium">Email</p>
                            <p class="text-xs sm:text-sm font-semibold text-primary truncate">
                                {{ $partnership->contact_email }}
                            </p>
                        </div>

                        <!-- Days Pending / Completed -->
                        <div>
                            <p class="text-xs text-gray-500 font-medium">
                                @if($partnership->status === 'completed')
                                    Duration
                                @else
                                    Days Pending
                                @endif
                            </p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">
                                @if($partnership->status === 'completed')
                                    {{ $partnership->getDaysToComplete() ?? 0 }} days
                                @else
                                    {{ $partnership->getDaysSinceSubmission() }} days
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Click to View Indicator -->
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Click to view full details
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Partnership Proposals</h3>
                <p class="text-gray-600 mb-6">You haven't submitted any partnership proposals yet. Start by creating your first proposal!</p>
                <a href="{{ route('partner.partnerships.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Partnership Proposal
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($partnerships->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $partnerships->links() }}
        </div>
    @endif
</div>

<script>
    let currentFilter = 'all';

    // Filter partnerships by status
    function filterPartnerships(status) {
        currentFilter = status;

        document.querySelectorAll('.partnership-filter').forEach((filter) => {
            filter.classList.remove('active', 'border-primary', 'text-primary');
            filter.classList.add('border-transparent', 'text-gray-500');
            filter.setAttribute('aria-selected', 'false');
        });

        event.target.classList.add('active', 'border-primary', 'text-primary');
        event.target.classList.remove('border-transparent', 'text-gray-500');
        event.target.setAttribute('aria-selected', 'true');

        applyFilter();
    }

    // Apply filter logic
    function applyFilter() {
        const cards = document.querySelectorAll('.partnership-card');
        cards.forEach((card) => {
            const cardStatus = card.getAttribute('data-status');
            if (currentFilter === 'all' || cardStatus === currentFilter) {
                card.classList.remove('hidden');
                card.style.display = 'block';
            } else {
                card.classList.add('hidden');
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection
