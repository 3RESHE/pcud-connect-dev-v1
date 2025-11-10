@extends('layouts.staff')

@section('title', 'My Events - PCU-DASMA Connect')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">My Events</h1>
            <p class="text-gray-600 mt-1">Manage and track your event submissions</p>
        </div>
        <a href="{{ route('staff.events.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-gray-400">
            <p class="text-xs font-semibold text-gray-600 uppercase">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $total_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-yellow-500">
            <p class="text-xs font-semibold text-gray-600 uppercase">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pending_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-400">
            <p class="text-xs font-semibold text-gray-600 uppercase">Approved</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $approved_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500">
            <p class="text-xs font-semibold text-gray-600 uppercase">Published</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $published_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-orange-500">
            <p class="text-xs font-semibold text-gray-600 uppercase">Ongoing</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $ongoing_events ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-500">
            <p class="text-xs font-semibold text-gray-600 uppercase">Rejected</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $rejected_events ?? 0 }}</p>
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
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                onkeyup="filterEvents()">
        </div>

        <!-- Status Filter -->
        <div>
            <select id="statusFilter" onchange="filterEvents()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                <option value="">All Status</option>
                <option value="pending">Pending Review</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="published">Published</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="eventsList">
    @forelse($events as $event)
        <a href="{{ route('staff.events.show', $event->id) }}" class="block group event-card" data-status="{{ $event->status }}" data-title="{{ strtolower($event->title) }}">
            <div class="bg-white rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 overflow-hidden h-full
                @if($event->status === 'pending') border-yellow-500
                @elseif($event->status === 'approved') border-green-400
                @elseif($event->status === 'rejected') border-red-500
                @elseif($event->status === 'published') border-blue-500
                @elseif($event->status === 'ongoing') border-orange-500
                @elseif($event->status === 'completed') border-gray-500
                @else border-gray-300 @endif">

                <!-- Image Container -->
                <div class="relative w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    @if($event->event_image)
                        <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-blue-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-blue-600 font-medium">No Image</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status Badge Overlay -->
                    <div class="absolute top-3 left-0 right-0 flex items-center justify-between px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm bg-opacity-90
                            @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($event->status === 'approved') bg-green-100 text-green-800
                            @elseif($event->status === 'rejected') bg-red-100 text-red-800
                            @elseif($event->status === 'published') bg-blue-100 text-blue-800
                            @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                            @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @switch($event->status)
                                @case('pending') Pending Review @break
                                @case('approved') Approved @break
                                @case('rejected') Rejected @break
                                @case('published') Published @break
                                @case('ongoing') Ongoing @break
                                @case('completed') Completed @break
                                @default {{ ucfirst($event->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-6 flex flex-col h-full">
                    <!-- Title & Header -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors duration-200 line-clamp-2">{{ $event->title }}</h3>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($event->status === 'pending')
                                Submitted {{ $event->created_at?->format('M d') }}
                            @elseif($event->status === 'approved')
                                Approved {{ $event->approved_at?->format('M d') ?? 'Recently' }}
                            @elseif($event->status === 'rejected')
                                Rejected {{ $event->updated_at?->format('M d') }}
                            @elseif($event->status === 'published')
                                Published {{ $event->published_at?->format('M d') ?? 'Recently' }}
                            @elseif($event->status === 'ongoing')
                                Started {{ $event->event_date?->format('M d') }}
                            @elseif($event->status === 'completed')
                                Completed {{ $event->event_date?->format('M d') }}
                            @endif
                        </p>
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-grow">{{ $event->description }}</p>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-3 py-4 border-t border-gray-100 mb-4">
                        <!-- Date -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->event_date?->format('M d') ?? 'TBD' }}</p>
                        </div>

                        <!-- Time -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Time</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->start_time ?? 'TBD' }}</p>
                        </div>

                        <!-- Format -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Format</p>
                            <p class="text-sm font-medium text-gray-900 capitalize">
                                @if($event->event_format === 'inperson')
                                    In-Person
                                @elseif($event->event_format === 'virtual')
                                    Virtual
                                @else
                                    Hybrid
                                @endif
                            </p>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Capacity</p>
                            <p class="text-sm font-medium text-gray-900">{{ $event->max_attendees ?? 'Unlimited' }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs">
                        <span class="text-gray-600">{{ ucfirst(str_replace('openforall', 'Open for All', $event->target_audience)) }}</span>
                        <span class="text-gray-400 group-hover:text-primary transition-colors duration-200">View →</span>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white rounded-lg shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Events Found</h3>
            <p class="text-gray-600 mb-6">Get started by creating your first event</p>
            <a href="{{ route('staff.events.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Your First Event
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $events->links() }}
    </div>
@endif

<script>
function filterEvents() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.event-card');

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const status = card.getAttribute('data-status');

        const matchesSearch = title.includes(searchValue);
        const matchesStatus = statusValue === '' || status === statusValue;

        if (matchesSearch && matchesStatus) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

@endsection
