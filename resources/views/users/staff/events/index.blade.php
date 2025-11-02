@extends('layouts.staff')

@section('title', 'Events - PCU-DASMA Connect')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">My Events</h1>
            <p class="text-gray-600">Manage and create events for the PCU-DASMA community</p>
        </div>
        <a href="{{ route('staff.events.create') }}"
            class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center sm:justify-start transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_events ?? 8 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $upcoming_events ?? 3 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Registrations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_registrations ?? 245 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completed_events ?? 5 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
                <button onclick="filterEvents('all')"
                    class="event-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="true">
                    All Events ({{ $total_events ?? 8 }})
                </button>
                <button onclick="filterEvents('upcoming')"
                    class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Upcoming ({{ $upcoming_events ?? 3 }})
                </button>
                <button onclick="filterEvents('completed')"
                    class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Completed ({{ $completed_events ?? 5 }})
                </button>
                <button onclick="filterEvents('draft')"
                    class="event-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Draft
                </button>
            </nav>
        </div>
    </div>

    <!-- Events List -->
    <div id="eventsContainer" class="space-y-6">
        @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 @if ($event->status === 'published' && $event->event_date > now()) border-green-500 @elseif($event->status === 'published') border-blue-500 @else border-yellow-500 @endif event-card"
                data-status="@if ($event->status === 'published' && $event->event_date > now()) upcoming@elseif($event->status === 'published')completed@else draft @endif"
                data-id="{{ $event->id }}">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center mb-2">
                            <span
                                class="@if ($event->status === 'published') @if ($event->event_date > now()) bg-green-100 text-green-800 @else bg-blue-100 text-blue-800 @endif
@else
bg-yellow-100 text-yellow-800 @endif px-2 py-1 rounded-full text-xs mr-3 mb-1">
                                @if ($event->status === 'published')
                                    @if ($event->event_date > now())
                                        Upcoming
                                    @else
                                        Completed
                                    @endif
                                @else
                                    Draft
                                @endif
                            </span>
                            <span class="text-sm text-gray-500 mb-1">
                                {{ $event->event_date->format('F j, Y') }} at {{ $event->event_date->format('g:i A') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 mb-3">{{ $event->location }}</p>

                        <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                {{ $event->registrations_count ?? 0 }} Registered
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $event->capacity ?? 'Unlimited' }} Capacity
                            </div>
                        </div>

                        @if ($event->status !== 'published')
                            <p class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded">
                                This event is currently in draft mode and not visible to attendees.
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 lg:mt-0 lg:ml-4 flex flex-col space-y-2">
                        <a href="{{ route('staff.events.show', $event->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>
                        @if ($event->status === 'published')
                            <a href="{{ route('staff.events.registrations', $event->id) }}"
                                class="px-4 py-2 bg-secondary text-white text-sm rounded-md hover:bg-blue-500 transition-colors duration-200 text-center">
                                View Registrations
                            </a>
                        @endif
                        <a href="{{ route('staff.events.edit', $event->id) }}"
                            class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center">
                            Edit Event
                        </a>
                        @if ($event->status !== 'published')
                            <button onclick="publishEvent({{ $event->id }})"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200">
                                Publish Event
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Events</h3>
                <p class="text-gray-600 mb-6">You haven't created any events yet. Start by creating your first event!</p>
                <a href="{{ route('staff.events.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Event
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($events->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif

    <!-- Publish Confirmation Modal -->
    <div id="publishModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish Event</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to publish this event? It will be visible to all attendees
                and can't be unpublished.</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelPublishBtn"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <button id="confirmPublishBtn"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                    Publish Event
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/staff/events.js') }}"></script>
@endsection
