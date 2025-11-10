@extends('layouts.staff')

@section('title', 'My Events - PCU-DASMA Connect')

@section('content')
<!-- Header Section -->
<div class="mb-10">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">My Events</h1>
            <p class="text-gray-600 mt-2">Manage and track your event submissions</p>
        </div>
        <a href="{{ route('staff.events.create') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pending_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $approved_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Published</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $published_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-orange-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Ongoing</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $ongoing_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejected</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejected_events ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Search Input -->
        <div class="relative">
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Search events by title..."
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                onkeyup="filterEvents()">
        </div>

        <!-- Status Filter -->
        <div>
            <select id="statusFilter" onchange="filterEvents()"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer bg-white">
                <option value="">All Status</option>
                <option value="pending">Pending Review</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="published">Published</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
                <option value="draft">Draft</option>
            </select>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsList">
    @forelse($events as $event)
        <a href="{{ route('staff.events.show', $event->id) }}"
            class="group event-card block"
            data-status="{{ $event->status }}"
            data-title="{{ strtolower($event->title) }}">

            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-b-4
                @if($event->status === 'pending') border-b-yellow-500
                @elseif($event->status === 'approved') border-b-green-500
                @elseif($event->status === 'rejected') border-b-red-500
                @elseif($event->status === 'published') border-b-blue-500
                @elseif($event->status === 'ongoing') border-b-orange-500
                @elseif($event->status === 'completed') border-b-gray-500
                @elseif($event->status === 'draft') border-b-gray-400
                @else border-b-gray-300 @endif">

                <!-- Image Container -->
                <div class="relative w-full h-44 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    @if($event->event_image)
                        <img src="{{ asset($event->event_image) }}"
                            alt="{{ $event->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-blue-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-blue-600 font-medium text-sm">No Image</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm bg-opacity-95
                            @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($event->status === 'approved') bg-green-100 text-green-800
                            @elseif($event->status === 'rejected') bg-red-100 text-red-800
                            @elseif($event->status === 'published') bg-blue-100 text-blue-800
                            @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                            @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                            @elseif($event->status === 'draft') bg-gray-100 text-gray-700
                            @else bg-gray-100 text-gray-800 @endif">
                            @switch($event->status)
                                @case('pending') Pending @break
                                @case('approved') Approved @break
                                @case('rejected') Rejected @break
                                @case('published') Published @break
                                @case('ongoing') Ongoing @break
                                @case('completed') Completed @break
                                @case('draft') Draft @break
                                @default {{ ucfirst($event->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Title -->
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2 mb-3">
                        {{ $event->title }}
                    </h3>

                    <!-- Event Info Grid -->
                    <div class="space-y-3 mb-4 flex-grow">
                        <!-- Date & Time -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Date & Time</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $event->event_date?->format('M d') }} at {{ $event->start_time }}</p>
                            </div>
                        </div>

                        <!-- Format -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Format</p>
                                <p class="text-sm text-gray-900 font-medium capitalize">
                                    @if($event->event_format === 'inperson')
                                        In-Person
                                    @elseif($event->event_format === 'virtual')
                                        Virtual
                                    @else
                                        Hybrid
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 006-6V9a6 6 0 00-6-6H6a6 6 0 00-6 6v5a6 6 0 006 6z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Capacity</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $event->max_attendees ?? 'Unlimited' }}</p>
                            </div>
                        </div>

                        <!-- Target Audience -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Audience</p>
                                <p class="text-sm text-gray-900 font-medium">{{ ucfirst(str_replace('openforall', 'All', $event->target_audience)) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer with View Arrow -->
                    <div class="pt-4 border-t border-gray-100 text-right">
                        <span class="text-sm text-blue-600 font-semibold group-hover:translate-x-1 transition-transform duration-200 inline-flex items-center">
                            View Details
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white rounded-lg shadow-md p-16 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Events Found</h3>
            <p class="text-gray-600 mb-6">Get started by creating your first event</p>
            <a href="{{ route('staff.events.create') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Your First Event
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
    <div class="mt-12 flex justify-center">
        <div class="space-x-1">
            {{ $events->links() }}
        </div>
    </div>
@endif

<script>
function filterEvents() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.event-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const status = card.getAttribute('data-status');

        const matchesSearch = title.includes(searchValue);
        const matchesStatus = statusValue === '' || status === statusValue;

        if (matchesSearch && matchesStatus) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

@endsection
