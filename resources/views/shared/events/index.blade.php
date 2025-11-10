@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Browse Events - PCU-DASMA Connect')
@section('page-title', 'Browse Events')

@section('content')
<div class="min-h-screen bg-gradient-to-br py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Explore Events</h1>
                    <p class="text-gray-600 mt-2">Discover exciting events happening at PCU-DASMA</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('events.registrations') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium whitespace-nowrap">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        My Registrations
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Events</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-indigo-100" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Upcoming</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['upcoming'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h12a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Past Events</p>
                        <p class="text-3xl font-bold text-gray-600 mt-1">{{ $stats['past'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-gray-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">My Registrations</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['registered'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('events.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Events</label>
                        <input type="text" name="search" id="search"
                               value="{{ $currentSearch }}"
                               placeholder="Event title or description..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                    </div>

                    <!-- Event Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <option value="">All Types</option>
                            <option value="in-person" {{ $currentType === 'in-person' ? 'selected' : '' }}>In-Person</option>
                            <option value="virtual" {{ $currentType === 'virtual' ? 'selected' : '' }}>Virtual</option>
                            <option value="hybrid" {{ $currentType === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>All Events</option>
                            <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if($currentSearch || $currentType || $currentStatus !== 'all')
                    <div class="pt-2 border-t border-gray-200">
                        <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            ✕ Clear all filters
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                        <!-- Event Banner/Image -->
                        <div class="relative h-48 bg-gradient-to-br from-indigo-400 to-blue-500 overflow-hidden">
                            @if($event->image_url)
                                <img src="{{ asset($event->image_url) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Event Type Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-block px-3 py-1 bg-white bg-opacity-90 text-indigo-700 text-xs font-semibold rounded-full">
                                    {{ ucfirst(str_replace('-', ' ', $event->event_type)) }}
                                </span>
                            </div>

                            <!-- Registration Status Badge -->
                            @if(in_array($event->id, $registeredEventIds))
                                <div class="absolute top-3 left-3">
                                    <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Registered
                                    </span>
                                </div>
                            @endif

                            <!-- Event Status -->
                            @if($event->activity_date <= now())
                                <div class="absolute bottom-3 left-3">
                                    <span class="inline-block px-3 py-1 bg-gray-800 bg-opacity-80 text-white text-xs font-semibold rounded-full">
                                        Past Event
                                    </span>
                                </div>
                            @else
                                <div class="absolute bottom-3 left-3">
                                    <span class="inline-block px-3 py-1 bg-blue-800 bg-opacity-80 text-white text-xs font-semibold rounded-full">
                                        Upcoming
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="p-5">
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('events.show', $event->id) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {{ $event->description }}
                            </p>

                            <!-- Event Info -->
                            <div class="space-y-2 mb-4 text-sm">
                                <!-- Date & Time -->
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $event->activity_date->format('M d, Y') }}</span>
                                    @if($event->activity_time)
                                        <span class="ml-2 text-gray-500">at {{ $event->activity_time->format('g:i A') }}</span>
                                    @endif
                                </div>

                                <!-- Location -->
                                <div class="flex items-start text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $event->venue_address }}</span>
                                </div>

                                <!-- Attendees -->
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zM7 20H4v-2a6 6 0 0112 0v2H7z"></path>
                                    </svg>
                                    <span>{{ $event->registrations()->count() }} {{ $event->registration_capacity ? '/ ' . $event->registration_capacity : '' }} registered</span>
                                </div>
                            </div>

                            <!-- Capacity Bar -->
                            @if($event->registration_capacity)
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-medium text-gray-600">Capacity</span>
                                        <span class="text-xs font-medium text-gray-600">{{ round(($event->registrations()->count() / $event->registration_capacity) * 100) }}%</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600 rounded-full" style="width: {{ round(($event->registrations()->count() / $event->registration_capacity) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="pt-4 border-t border-gray-200">
                                @if(in_array($event->id, $registeredEventIds))
                                    <a href="{{ route('events.show', $event->id) }}"
                                       class="w-full block text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                                        View Details
                                    </a>
                                @else
                                    <a href="{{ route('events.show', $event->id) }}"
                                       class="w-full block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                                        Register Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="flex flex-col items-center gap-4 mt-12">
                    <div class="flex justify-center">
                        {{ $events->links() }}
                    </div>
                    <p class="text-sm text-gray-600">
                        Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
                    </p>
                </div>
            @endif
        @else
            <!-- No Events Found -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-600 mb-6">We couldn't find any events matching your criteria. Try adjusting your filters.</p>
                <a href="{{ route('events.index') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    View All Events
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto-submit form on filter change
    const filterForm = document.querySelector('form[action*="events.index"]');
    if (filterForm) {
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }

    // Close alerts after 5 seconds
    document.querySelectorAll('[role="alert"]').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
</script>
@endsection
