@extends('layouts.alumni')

@section('title', 'Events - PCU-DASMA Connect')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
                        <p class="text-sm font-medium text-gray-600">Available Events</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $availableEventsCount ?? 0 }}</p>
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
                        <p class="text-sm font-medium text-gray-600">My Registrations</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $myRegistrationsCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Alumni Only</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $alumniOnlyCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedEventsCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
            <form method="GET" action="{{ route('alumni.events.index') }}">
                <div class="relative max-w-lg mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search events by title, description, or tags..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-primary text-lg"
                        aria-label="Search events" />
                </div>
            </form>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex justify-center space-x-8 overflow-x-auto" role="tablist">
                    <a href="{{ route('alumni.events.index') }}"
                        class="@if (!request('filter') || request('filter') == 'all') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                        role="tab">
                        All Events ({{ $availableEventsCount ?? 0 }})
                    </a>
                    <a href="{{ route('alumni.events.index', ['filter' => 'my_registrations']) }}"
                        class="@if (request('filter') == 'my_registrations') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                        role="tab">
                        My Registrations ({{ $myRegistrationsCount ?? 0 }})
                    </a>
                    <a href="{{ route('alumni.events.index', ['filter' => 'alumni_only']) }}"
                        class="@if (request('filter') == 'alumni_only') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                        role="tab">
                        Alumni Only ({{ $alumniOnlyCount ?? 0 }})
                    </a>
                    <a href="{{ route('alumni.events.index', ['filter' => 'completed']) }}"
                        class="@if (request('filter') == 'completed') border-b-2 border-primary text-primary @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 text-sm font-medium"
                        role="tab">
                        Completed Events ({{ $completedEventsCount ?? 0 }})
                    </a>
                </nav>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-6" role="region" aria-live="polite">
            @forelse($events as $event)
                <!-- Event Card -->
                <a href="{{ route('alumni.events.show', $event->id) }}"
                    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 border-l-4 border-primary">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Event Header -->
                            <div class="flex items-center mb-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if ($event->event_format === 'virtual') bg-blue-100 text-blue-800
                                @elseif($event->event_format === 'in_person')
                                    bg-green-100 text-green-800
                                @else
                                    bg-purple-100 text-purple-800 @endif
                            ">
                                    @if ($event->event_format === 'virtual')
                                        🌐 Virtual
                                    @elseif($event->event_format === 'in_person')
                                        📍 In-Person
                                    @else
                                        🔄 Hybrid
                                    @endif
                                </span>
                                @if ($event->is_alumni_only)
                                    <span
                                        class="inline-flex items-center ml-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ⭐ Alumni Only
                                    </span>
                                @endif
                            </div>

                            <!-- Event Title -->
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>

                            <!-- Event Description -->
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ strip_tags($event->description) }}</p>

                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>{{ $event->event_date->format('F d, Y') }} at
                                        {{ $event->event_date->format('g:i A') }}</span>
                                </div>
                                @if ($event->location)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>{{ $event->max_attendees ? $event->registered_count . ' / ' . $event->max_attendees . ' registered' : $event->registered_count . ' registered' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="ml-6 flex flex-col space-y-2">
                            @php
                                $isRegistered = \App\Models\EventRegistration::where('user_id', auth()->id())
                                    ->where('event_id', $event->id)
                                    ->exists();
                            @endphp

                            @if ($isRegistered)
                                <span
                                    class="px-4 py-2 bg-green-100 text-green-800 text-sm rounded-lg font-medium text-center">
                                    ✓ Registered
                                </span>
                            @else
                                <button
                                    onclick="event.preventDefault(); event.stopPropagation(); registerForEvent({{ $event->id }})"
                                    class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-blue-700 font-medium">
                                    Register
                                </button>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-lg">No events found.</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or search criteria.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($events->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Register for Event</h3>
                <p class="text-gray-700 mb-6">Are you sure you want to register for this event? You will receive a
                    confirmation email with event details.</p>
                <form id="registerForm" method="POST">
                    @csrf
                    <div class="flex gap-3">
                        <button type="button" onclick="closeRegisterModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentEventId = null;

        function registerForEvent(eventId) {
            currentEventId = eventId;
            const form = document.getElementById('registerForm');
            form.action = "{{ route('alumni.events.register', ':id') }}".replace(':id', eventId);
            document.getElementById('registerModal').classList.remove('hidden');
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
        }

        document.getElementById('registerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegisterModal();
            }
        });
    </script>
@endsection
