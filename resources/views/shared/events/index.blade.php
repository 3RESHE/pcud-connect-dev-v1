@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Discover Events')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-12 mb-8">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-2">Discover Events</h1>
        <p class="text-blue-100">Find and register for amazing events happening at PCUD</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="container mx-auto px-4 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm mb-1">Total Events</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm mb-1">Upcoming</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm mb-1">Past Events</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['past'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm mb-1">Your Registrations</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['registered'] }}</p>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="container mx-auto px-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Event title or description..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Event Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="inperson" @if($currentType === 'inperson') selected @endif>In-Person</option>
                    <option value="virtual" @if($currentType === 'virtual') selected @endif>Virtual</option>
                    <option value="hybrid" @if($currentType === 'hybrid') selected @endif>Hybrid</option>
                </select>
            </div>

            <!-- From Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                <input type="date" name="from_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- To Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                <input type="date" name="to_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Events Grid -->
<div class="container mx-auto px-4 mb-12">
    @if($events->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden border border-gray-100">
                    <!-- Event Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold mb-1">{{ $event->title }}</h3>
                                <p class="text-blue-100 text-sm">By {{ $event->creator->full_name }}</p>
                            </div>
                            @if($event->is_featured)
                                <div class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Featured
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>

                        <!-- Event Info -->
                        <div class="space-y-3 mb-6 border-b pb-4">
                            <!-- Date & Time -->
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">{{ $event->event_date->format('M d, Y') }} @ {{ $event->formatted_start_time }}</span>
                            </div>

                            <!-- Location/Type -->
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($event->event_format === 'inperson')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    @elseif($event->event_format === 'virtual')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20m0 0l-.75 3M9 20a6 6 0 1112 0m0 0l.75 3m-.75-3l.75-3"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    @endif
                                </svg>
                                <span class="text-sm">
                                    @if($event->event_format === 'inperson')
                                        In-Person
                                    @elseif($event->event_format === 'virtual')
                                        Virtual
                                    @else
                                        Hybrid
                                    @endif
                                </span>
                            </div>

                            <!-- Capacity -->
                            @if($event->max_attendees)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM4.318 20H4a2 2 0 01-2-2v-2a6 6 0 0112 0v2a2 2 0 01-2 2h-.682"></path>
                                    </svg>
                                    <span class="text-sm">{{ $event->registrations()->count() }} / {{ $event->max_attendees }} Registered</span>
                                </div>
                                <!-- Capacity Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min((($event->registrations()->count() / $event->max_attendees) * 100), 100) }}%"></div>
                                </div>
                            @endif
                        </div>

                        <!-- Registration Status & Action -->
                        <div class="flex items-center justify-between gap-3">
                            @if(in_array($event->id, $registeredEventIds))
                                <span class="flex items-center text-green-600 text-sm font-medium">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Registered
                                </span>
                            @else
                                <span></span>
                            @endif

                            <a href="{{ route('events.show', $event->id) }}"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No events found</h3>
            <p class="text-gray-600 mb-4">Try adjusting your filters or search criteria</p>
            <a href="{{ route('events.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                Clear Filters
            </a>
        </div>
    @endif
</div>
@endsection
