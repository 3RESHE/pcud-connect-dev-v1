@extends('layouts.student')

@section('title', 'Events - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Available Events -->
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
                    <p class="text-sm font-medium text-gray-600">Available Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $events->total() }}</p>
                </div>
            </div>
        </div>

        <!-- My Registrations -->
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
                    <p class="text-sm font-medium text-gray-600">My Registrations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $myRegistrationsCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $upcomingCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Events -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completedCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
        <form method="GET" action="{{ route('student.events.index') }}" class="relative max-w-lg mx-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search events by title, description, or tags..."
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-primary text-lg"
                aria-label="Search events"
            />
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex justify-center space-x-8 overflow-x-auto" role="tablist">
                <a
                    href="{{ route('student.events.index', ['filter' => 'all']) }}"
                    class="@if(!request('filter') || request('filter') == 'all') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                    role="tab"
                    aria-selected="{{ !request('filter') || request('filter') == 'all' }}"
                >
                    All Events ({{ $events->total() }})
                </a>
                <a
                    href="{{ route('student.events.index', ['filter' => 'my_registrations']) }}"
                    class="@if(request('filter') == 'my_registrations') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                    role="tab"
                    aria-selected="{{ request('filter') == 'my_registrations' }}"
                >
                    My Registrations ({{ $myRegistrationsCount ?? 0 }})
                </a>
                <a
                    href="{{ route('student.events.index', ['filter' => 'upcoming']) }}"
                    class="@if(request('filter') == 'upcoming') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                    role="tab"
                    aria-selected="{{ request('filter') == 'upcoming' }}"
                >
                    Upcoming ({{ $upcomingCount ?? 0 }})
                </a>
                <a
                    href="{{ route('student.events.index', ['filter' => 'completed']) }}"
                    class="@if(request('filter') == 'completed') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                    role="tab"
                    aria-selected="{{ request('filter') == 'completed' }}"
                >
                    Completed ({{ $completedCount ?? 0 }})
                </a>
            </nav>
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-6" role="region" aria-live="polite">
        @forelse($events as $event)
            <!-- Event Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow border-l-4 @if($event->status == 'published') border-blue-500 @elseif($event->status == 'ongoing') border-orange-500 @else border-gray-300 @endif">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex items-center mb-2 flex-wrap gap-2">
                            <span class="@if($event->status == 'published') bg-blue-100 text-blue-800 @elseif($event->status == 'ongoing') bg-orange-100 text-orange-800 @else bg-gray-100 text-gray-600 @endif px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($event->status) }}
                            </span>
                            @if($event->event_format)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $event->event_format)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Event Title -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>

                        <!-- Organizer -->
                        @if($event->organizer)
                            <p class="text-sm text-blue-600 mb-3">Organized by {{ $event->organizer }}</p>
                        @endif

                        <!-- Description -->
                        <p class="text-gray-600 mb-3 line-clamp-2">
                            {{ strip_tags($event->description) }}
                        </p>

                        <!-- Event Details -->
                        <div class="flex items-center text-sm text-gray-500 mb-3 flex-wrap gap-3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $event->event_date->format('M d, Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $event->event_time ?? 'TBD' }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $event->location }}
                            </span>
                        </div>

                        <!-- Participants -->
                        @if($event->max_attendees)
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="participant-count">{{ $event->registered_count ?? 0 }}/{{ $event->max_attendees }} participants</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="ml-4 flex flex-col space-y-2">
                        <a
                            href="{{ route('student.events.show', $event->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 text-center font-medium whitespace-nowrap"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- No Results -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-600">Try adjusting your search criteria or filters</p>
                <a href="{{ route('student.events.index') }}" class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                    View All Events
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
