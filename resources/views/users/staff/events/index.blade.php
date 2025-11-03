@extends('layouts.staff')

@section('title', 'My Events - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">My Events</h1>
        <p class="text-gray-600">Create and manage your event proposals and submissions</p>
    </div>
    <div class="flex space-x-3">
        <a
            href="{{ route('staff.events.create') }}"
            class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 flex items-center transition-colors duration-200"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Event
        </a>
    </div>
</div>

<!-- Staff Info Notice -->
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Event Submission Process</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>
                    As a staff member, you can create event proposals that will be reviewed by administrators. Once submitted, administrators will review, approve, or request changes to your events.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">My Total Events</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_events ?? 9 }}</p>
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
                <p class="text-sm font-medium text-gray-600">Published</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $published_events ?? 3 }}</p>
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
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_events ?? 3 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $rejected_events ?? 1 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-lg shadow-sm mb-6">
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Search Bar -->
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="searchEvents"
                        placeholder="Search my events by title or description..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary"
                        oninput="searchEvents()"
                    />
                </div>
            </div>

            <!-- Filter Dropdowns -->
            <div class="flex space-x-3">
                <select
                    id="statusFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary focus:border-primary"
                    onchange="applyStatusFilter()"
                >
                    <option value="">All Status</option>
                    <option value="under_review">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="published">Published</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
            <button
                onclick="filterEvents('all')"
                class="event-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="true"
            >
                All My Events ({{ $total_events ?? 9 }})
            </button>
            <button
                onclick="filterEvents('under_review')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Pending ({{ $pending_events ?? 3 }})
            </button>
            <button
                onclick="filterEvents('approved')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Approved ({{ $approved_events ?? 1 }})
            </button>
            <button
                onclick="filterEvents('published')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Published ({{ $published_events ?? 2 }})
            </button>
            <button
                onclick="filterEvents('ongoing')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Ongoing ({{ $ongoing_events ?? 1 }})
            </button>
            <button
                onclick="filterEvents('rejected')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Rejected ({{ $rejected_events ?? 1 }})
            </button>
            <button
                onclick="filterEvents('completed')"
                class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Completed ({{ $completed_events ?? 1 }})
            </button>
        </nav>
    </div>
</div>

<!-- Events List -->
<div class="space-y-6" id="eventsList">
    @forelse($events as $event)
        <div
            class="event-card bg-white rounded-lg shadow-sm p-6 border-l-4
            @if($event->status === 'under_review') border-yellow-500
            @elseif($event->status === 'approved') border-green-400
            @elseif($event->status === 'published') border-blue-500
            @elseif($event->status === 'ongoing') border-orange-500
            @elseif($event->status === 'rejected') border-red-500
            @elseif($event->status === 'completed') border-gray-500
            @else border-gray-300 @endif"
            data-status="{{ $event->status }}"
        >
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <span class="
                            @if($event->status === 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($event->status === 'approved') bg-green-100 text-green-800
                            @elseif($event->status === 'published') bg-blue-100 text-blue-800
                            @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                            @elseif($event->status === 'rejected') bg-red-100 text-red-800
                            @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                            @else bg-gray-100 text-gray-800 @endif
                            px-2 py-1 rounded-full text-xs mr-3
                            @if($event->status === 'ongoing') animate-pulse @endif"
                        >
                            @if($event->status === 'under_review') Under Admin Review
                            @elseif($event->status === 'approved') Approved
                            @elseif($event->status === 'published') Published
                            @elseif($event->status === 'ongoing') Happening Now
                            @elseif($event->status === 'rejected') Rejected
                            @elseif($event->status === 'completed') Completed
                            @else {{ ucfirst($event->status) }}
                            @endif
                        </span>
                        <span class="text-sm text-gray-500">
                            @if($event->status === 'under_review')
                                Submitted {{ $event->created_at->format('F j, Y') }}
                            @elseif($event->status === 'approved')
                                Approved {{ $event->approved_at->format('F j, Y') ?? 'Recently' }}
                            @elseif($event->status === 'published')
                                Published {{ $event->published_at->format('F j, Y') ?? 'Recently' }}
                            @elseif($event->status === 'ongoing')
                                Event Date: Today
                            @elseif($event->status === 'rejected')
                                Rejected {{ $event->updated_at->format('F j, Y') }}
                            @elseif($event->status === 'completed')
                                Completed {{ $event->event_date->format('F j, Y') }}
                            @endif
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $event->description }}</p>

                    <!-- Status-specific messages -->
                    @if($event->status === 'under_review')
                        <div class="bg-yellow-50 p-3 rounded-lg mb-4 border border-yellow-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-yellow-800 text-sm">
                                    <strong>Review Status:</strong> Event submitted for administrator approval. Expected review completion soon.
                                </span>
                            </div>
                        </div>
                    @elseif($event->status === 'approved')
                        <div class="bg-green-50 p-3 rounded-lg mb-4 border border-green-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-green-800 text-sm">
                                    <strong>Event Status:</strong> Approved and ready to publish. No registrations yet.
                                </span>
                            </div>
                        </div>
                    @elseif($event->status === 'published')
                        <div class="bg-blue-50 p-3 rounded-lg mb-4 border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-blue-800 text-sm">
                                    <strong>Event Status:</strong> Published and accepting registrations. {{ $event->registrations_count ?? 0 }} confirmed.
                                </span>
                            </div>
                        </div>
                    @elseif($event->status === 'ongoing')
                        <div class="bg-orange-50 p-3 rounded-lg mb-4 border border-orange-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-orange-600 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-orange-800 text-sm">
                                    <strong>Live Event:</strong> Currently happening. {{ $event->checked_in_count ?? 0 }} checked in.
                                </span>
                            </div>
                        </div>
                    @elseif($event->status === 'rejected')
                        <div class="bg-red-50 p-3 rounded-lg mb-4 border border-red-200">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-red-800 text-sm">
                                    <strong>Rejection Reason:</strong><br />
                                    {{ $event->rejection_reason ?? 'Please contact administrator for details.' }}
                                </div>
                            </div>
                        </div>
                    @elseif($event->status === 'completed')
                        <div class="bg-gray-50 p-3 rounded-lg mb-4 border border-gray-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-800 text-sm">
                                    <strong>Event Completed Successfully:</strong> {{ $event->registrations_count ?? 0 }} attendees • {{ $event->rating ?? 'N/A' }} rating
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-2">
                        @if($event->tags)
                            @foreach(explode(',', $event->tags) as $tag)
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">{{ trim($tag) }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
                    @if($event->status === 'under_review')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            View Details
                        </a>
                        <button onclick="withdrawSubmission({{ $event->id }})" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50">
                            Withdraw Submission
                        </button>
                    @elseif($event->status === 'approved')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            View Details
                        </a>
                        <button onclick="publishEvent({{ $event->id }})" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            Publish Event
                        </button>
                    @elseif($event->status === 'published')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            View Details
                        </a>
                        <a href="{{ route('staff.events.registrations', $event->id) }}" class="px-4 py-2 border border-green-300 text-green-700 text-sm rounded-md hover:bg-green-50 text-center">
                            Manage Registrations
                        </a>
                    @elseif($event->status === 'ongoing')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 text-center">
                            View Details
                        </a>
                        <a href="{{ route('staff.events.registrations', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            Manage Attendance
                        </a>
                    @elseif($event->status === 'rejected')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            View Details
                        </a>
                    @elseif($event->status === 'completed')
                        <a href="{{ route('staff.events.show', $event->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center">
                            View Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Events</h3>
            <p class="text-gray-600 mb-6">You haven't created any events yet. Start by creating your first event!</p>
            <a href="{{ route('staff.events.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Event
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $events->links() }}
    </div>
@endif

<script src="{{ asset('js/staff/events.js') }}"></script>
@endsection
