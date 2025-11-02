@extends('layouts.partner')

@section('title', 'Partnerships - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
    <div class="mb-4 sm:mb-0">
        <h1 class="text-3xl font-bold text-gray-900">Partnership Proposals</h1>
        <p class="text-gray-600">Manage your community partnership proposals with PCU-DASMA</p>
    </div>
    <a
        href="{{ route('partner.partnerships.create') }}"
        class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 flex items-center justify-center sm:justify-start transition-colors duration-200"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        New Partnership Proposal
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 1 }}</p>
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
                <p class="text-2xl font-semibold text-gray-900">{{ $discussion_count ?? 1 }}</p>
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
                <p class="text-2xl font-semibold text-gray-900">{{ $approved_count ?? 1 }}</p>
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
                onclick="filterPartnerships('all')"
                class="partnership-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="true"
            >
                All Proposals ({{ $total_partnerships ?? 5 }})
            </button>
            <button
                onclick="filterPartnerships('pending')"
                class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Pending Review ({{ $pending_count ?? 1 }})
            </button>
            <button
                onclick="filterPartnerships('discussion')"
                class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Under Discussion ({{ $discussion_count ?? 1 }})
            </button>
            <button
                onclick="filterPartnerships('approved')"
                class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Approved ({{ $approved_count ?? 1 }})
            </button>
            <button
                onclick="filterPartnerships('rejected')"
                class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Rejected ({{ $rejected_count ?? 1 }})
            </button>
            <button
                onclick="filterPartnerships('completed')"
                class="partnership-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Completed ({{ $completed_count ?? 1 }})
            </button>
        </nav>
    </div>
</div>

<!-- Partnerships List -->
<div id="partnershipsContainer" class="space-y-6">
    @forelse($partnerships as $partnership)
        <div
            class="bg-white rounded-lg shadow-sm p-6 border-l-4 @if($partnership->status === 'pending') border-yellow-500 @elseif($partnership->status === 'discussion') border-blue-500 @elseif($partnership->status === 'approved') border-green-500 @elseif($partnership->status === 'rejected') border-red-500 @else border-blue-400 @endif partnership-card"
            data-status="{{ $partnership->status }}"
            data-id="{{ $partnership->id }}"
        >
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="@if($partnership->status === 'pending') bg-yellow-100 text-yellow-800 @elseif($partnership->status === 'discussion') bg-blue-100 text-blue-800 @elseif($partnership->status === 'approved') bg-green-100 text-green-800 @elseif($partnership->status === 'rejected') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif px-2 py-1 rounded-full text-xs mr-3 mb-1">
                            {{ ucfirst(str_replace('_', ' ', $partnership->status)) }}
                        </span>
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">
                            {{ $partnership->type }}
                        </span>
                        <span class="text-sm text-gray-500 mb-1">
                            @if($partnership->status === 'pending')
                                Submitted {{ $partnership->created_at->format('F j, Y') }}
                            @elseif($partnership->status === 'completed')
                                Completed {{ $partnership->completed_at->format('F j, Y') ?? 'N/A' }}
                            @else
                                {{ $partnership->created_at->format('F j, Y') }}
                            @endif
                        </span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $partnership->title }}</h3>
                    <p class="text-gray-600 mb-3">{{ $partnership->description }}</p>

                    <!-- Status-specific messages -->
                    @if($partnership->status === 'pending')
                        <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-yellow-700">
                                Your partnership proposal has been submitted and is currently under review by PCU-DASMA administrators. We'll notify you once they begin the discussion process or if any additional information is needed.
                            </p>
                        </div>
                    @elseif($partnership->status === 'discussion')
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-blue-700 mb-2">
                                PCU-DASMA administrators have initiated discussions about your partnership proposal. They have some questions and points to clarify before proceeding with approval.
                            </p>
                        </div>
                    @elseif($partnership->status === 'approved')
                        <div class="bg-green-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-green-700">
                                Your partnership proposal has been approved by PCU-DASMA administrators. Please check your email for next steps and coordination details.
                            </p>
                        </div>
                    @elseif($partnership->status === 'rejected')
                        <div class="bg-red-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-red-700 mb-2">
                                Your partnership proposal was reviewed but unfortunately not approved. Please check your email for feedback and suggestions for resubmission.
                            </p>
                            @if($partnership->rejection_reason)
                                <p class="text-sm text-red-700 mt-2"><strong>Feedback:</strong> {{ $partnership->rejection_reason }}</p>
                            @endif
                        </div>
                    @elseif($partnership->status === 'completed')
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <p class="text-sm text-blue-700">
                                Community activity successfully completed. Thank you for your partnership with PCU-DASMA.
                            </p>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500">
                        @if($partnership->event_date)
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $partnership->event_date->format('F j, Y') }}
                            </div>
                        @endif

                        @if($partnership->slots)
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $partnership->slots }} slots available
                            </div>
                        @endif

                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $partnership->participation_type ?? 'Open participation' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 lg:mt-0 lg:ml-4 flex flex-col space-y-2">
                    @if($partnership->status === 'pending' || $partnership->status === 'discussion' || $partnership->status === 'rejected')
                        <a
                            href="{{ route('partner.partnerships.show', $partnership->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center"
                        >
                            View Details
                        </a>
                        <a
                            href="{{ route('partner.partnerships.edit', $partnership->id) }}"
                            class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center"
                        >
                            Edit Proposal
                        </a>
                    @elseif($partnership->status === 'approved')
                        <a
                            href="{{ route('partner.partnerships.show', $partnership->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center"
                        >
                            View Details
                        </a>
                        <a
                            href="{{ route('partner.partnerships.edit', $partnership->id) }}"
                            class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center"
                        >
                            Edit Details
                        </a>
                        <button
                            onclick="completePartnership({{ $partnership->id }})"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200"
                        >
                            Mark Complete
                        </button>
                    @else
                        <a
                            href="{{ route('partner.partnerships.show', $partnership->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center"
                        >
                            View Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Partnership Proposals</h3>
            <p class="text-gray-600 mb-6">You haven't submitted any partnership proposals yet. Start by creating your first proposal!</p>
            <a
                href="{{ route('partner.partnerships.create') }}"
                class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200"
            >
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

<!-- Complete Confirmation Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mark Partnership as Complete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to mark this partnership as complete? This will archive it and thank PCU-DASMA for the collaboration.</p>
        <div class="flex justify-end space-x-4">
            <button
                id="cancelCompleteBtn"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200"
            >
                Cancel
            </button>
            <button
                id="confirmCompleteBtn"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
            >
                Mark Complete
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/partner/partnerships.js') }}"></script>
@endsection
