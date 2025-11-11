@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'My Event Registrations')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-12 mb-8">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2">My Event Registrations</h1>
            <p class="text-green-100">Manage your registered events and track your attendance</p>
        </div>

        <!-- Back to Browse Button -->
        <a href="{{ route('events.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-green-600 hover:bg-gray-100 rounded-lg font-semibold transition-colors shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Browse Events
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="container mx-auto px-4 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Registrations</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Upcoming Events</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Past Events</p>
            <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['past'] }}</p>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="container mx-auto px-4 mb-8">
    <div class="flex space-x-4 border-b border-gray-200">
        <a href="{{ route('events.myRegistrations') }}" class="px-6 py-3 font-medium text-sm @if($filter === 'all') text-green-600 border-b-2 border-green-600 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            All Events ({{ $stats['total'] }})
        </a>
        <a href="{{ route('events.myRegistrations', ['filter' => 'upcoming']) }}" class="px-6 py-3 font-medium text-sm @if($filter === 'upcoming') text-blue-600 border-b-2 border-blue-600 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            Upcoming ({{ $stats['upcoming'] }})
        </a>
        <a href="{{ route('events.myRegistrations', ['filter' => 'past']) }}" class="px-6 py-3 font-medium text-sm @if($filter === 'past') text-gray-700 border-b-2 border-gray-700 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            Past ({{ $stats['past'] }})
        </a>
    </div>
</div>

<!-- Registrations List -->
<div class="container mx-auto px-4 mb-12">
    <div class="space-y-4">
        @forelse($registrations as $registration)
            @php
                $event = $registration->event;
                $isPast = $event->event_date < now()->toDateString();
            @endphp

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 @if($isPast) border-l-gray-400 @else border-l-green-500 @endif">
                <div class="flex flex-col md:flex-row">
                    <!-- Image -->
                    <div class="md:w-48 h-48 md:h-auto bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0 overflow-hidden">
                        @if($event->event_image)
                            <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-grow p-6 flex flex-col justify-between">
                        <div>
                            <!-- Title & Status -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <a href="{{ route('events.show', $event->id) }}" class="text-xl font-bold text-gray-900 hover:text-green-600 transition-colors">
                                        {{ $event->title }}
                                    </a>
                                    <p class="text-sm text-gray-600 mt-1">Organized by {{ $event->creator->full_name ?? 'N/A' }}</p>
                                </div>

                                <!-- Status Badge -->
                                @if($isPast)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2zm-.464 5.464a1 1 0 10-1.415-1.414A3 3 0 0013 11a1 1 0 001.415 1.415 5 5 0 01-1.464 1.46z" clip-rule="evenodd"></path>
                                        </svg>
                                        Past Event
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Registered
                                    </span>
                                @endif
                            </div>

                            <!-- Event Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Date & Time -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date & Time</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $event->event_date?->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ $event->start_time }} - {{ $event->end_time }}</p>
                                </div>

                                <!-- Format -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Format</p>
                                    <p class="text-sm font-medium text-gray-900 capitalize">
                                        @if($event->event_format === 'inperson') In-Person
                                        @elseif($event->event_format === 'virtual') Virtual
                                        @else Hybrid @endif
                                    </p>
                                    @if($event->venue_name && $event->event_format !== 'virtual')
                                        <p class="text-xs text-gray-600">{{ $event->venue_name }}</p>
                                    @endif
                                </div>

                                <!-- Registration Date -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Registered On</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $registration->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ $registration->created_at->format('H:i A') }}</p>
                                </div>
                            </div>

                            <!-- Event Description -->
                            @if($event->description)
                                <p class="text-sm text-gray-700 line-clamp-2 mb-4">{{ $event->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-t md:border-t-0 md:border-l border-gray-200 p-6 flex flex-col justify-center space-y-3 md:w-40 flex-shrink-0">
                        <!-- View Details Button -->
                        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            View Details
                        </a>

                        <!-- Unregister Button (Only for upcoming events) -->
                        @if(!$isPast)
                            <form action="{{ route('events.unregister', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unregister?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors text-sm">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Unregister
                                </button>
                            </form>
                        @else
                            <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium text-sm text-center">
                                Event Completed
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-16 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Registrations</h3>
                <p class="text-gray-600 mb-6">
                    @if($filter === 'all')
                        You haven't registered for any events yet.
                    @elseif($filter === 'upcoming')
                        You don't have any upcoming events.
                    @else
                        You haven't attended any events yet.
                    @endif
                </p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Browse Events
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($registrations->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $registrations->links() }}
        </div>
    @endif
</div>

@endsection
