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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Registrations</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Upcoming Events</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-orange-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Ongoing Events</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['ongoing'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Completed Events</p>
            <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['completed'] }}</p>
        </div>
    </div>
</div>

<!-- ✅ NEW FILTER TABS: All Events, Upcoming, Ongoing, Completed -->
<div class="container mx-auto px-4 mb-8">
    <div class="flex space-x-2 border-b border-gray-200 overflow-x-auto">
        <!-- All Events Tab -->
        <a href="{{ route('events.myRegistrations') }}" class="px-6 py-3 font-medium text-sm whitespace-nowrap @if($filter === 'all') text-green-600 border-b-2 border-green-600 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            All Events ({{ $stats['total'] }})
        </a>

        <!-- Upcoming Tab -->
        <a href="{{ route('events.myRegistrations', ['filter' => 'upcoming']) }}" class="px-6 py-3 font-medium text-sm whitespace-nowrap @if($filter === 'upcoming') text-blue-600 border-b-2 border-blue-600 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            Upcoming ({{ $stats['upcoming'] }})
        </a>

        <!-- Ongoing Tab -->
        <a href="{{ route('events.myRegistrations', ['filter' => 'ongoing']) }}" class="px-6 py-3 font-medium text-sm whitespace-nowrap @if($filter === 'ongoing') text-orange-600 border-b-2 border-orange-600 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            Ongoing ({{ $stats['ongoing'] }})
        </a>

        <!-- Completed Tab -->
        <a href="{{ route('events.myRegistrations', ['filter' => 'completed']) }}" class="px-6 py-3 font-medium text-sm whitespace-nowrap @if($filter === 'completed') text-gray-700 border-b-2 border-gray-700 @else text-gray-600 hover:text-gray-900 @endif transition-colors">
            Completed ({{ $stats['completed'] }})
        </a>
    </div>
</div>

<!-- ✅ HELPER FUNCTION: Get Event Status Badge -->
@php
    $getEventStatusBadge = function($event) {
        // Determine end date for multi-day events
        $eventEndDate = $event->is_multiday && $event->end_date
            ? \Carbon\Carbon::parse($event->end_date)->toDateString()
            : $event->event_date;

        $isOngoing = $event->status === 'ongoing';
        $isCompleted = $event->status === 'completed' || $eventEndDate < today();

        if ($isCompleted) {
            return [
                'status' => 'completed',
                'label' => 'Completed Event',
                'bgColor' => 'bg-gray-600',
                'textColor' => 'text-gray-900',
                'badgeBg' => 'bg-gray-100',
                'borderColor' => 'border-l-gray-400',
                'icon' => '✓'
            ];
        } elseif ($isOngoing) {
            return [
                'status' => 'ongoing',
                'label' => 'Ongoing Event',
                'bgColor' => 'bg-orange-600',
                'textColor' => 'text-orange-900',
                'badgeBg' => 'bg-orange-100',
                'borderColor' => 'border-l-orange-500',
                'icon' => '●'
            ];
        } else {
            return [
                'status' => 'upcoming',
                'label' => 'Upcoming Event',
                'bgColor' => 'bg-green-600',
                'textColor' => 'text-green-900',
                'badgeBg' => 'bg-green-100',
                'borderColor' => 'border-l-green-500',
                'icon' => '→'
            ];
        }
    };
@endphp

<!-- Registrations List -->
<div class="container mx-auto px-4 mb-12">
    <div class="space-y-4">
        @forelse($registrations as $registration)
            @php
                $event = $registration->event;
                $badgeInfo = $getEventStatusBadge($event);
            @endphp

            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 {{ $badgeInfo['borderColor'] }}">
                <div class="flex flex-col md:flex-row">
                    <!-- Image -->
                    <div class="md:w-48 h-48 md:h-auto bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0 overflow-hidden relative">
                        @if($event->event_image)
                            <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- ✅ Event Status Overlay Badge -->
                        <div class="absolute top-2 left-2 bg-black bg-opacity-50 rounded-lg px-3 py-1">
                            <span class="text-xs font-bold text-white">{{ $badgeInfo['label'] }}</span>
                        </div>
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

                                <!-- ✅ Enhanced Status Badge -->
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold {{ $badgeInfo['badgeBg'] }} {{ $badgeInfo['textColor'] }} whitespace-nowrap ml-4">
                                    @if($badgeInfo['status'] === 'completed')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    @elseif($badgeInfo['status'] === 'ongoing')
                                        <span class="w-2 h-2 bg-current rounded-full mr-2 animate-pulse"></span>
                                        Ongoing
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Upcoming
                                    @endif
                                </span>
                            </div>

                            <!-- Event Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Date & Time (Multi-day Support) -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date & Time</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($event->is_multiday && $event->end_date)
                                            {{ $event->event_date->format('M d') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                        @else
                                            {{ $event->event_date?->format('M d, Y') }}
                                        @endif
                                    </p>
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

                        <!-- ✅ ENHANCED: Unregister Button (Only for upcoming events) -->
                        @if($badgeInfo['status'] === 'upcoming')
                            <button
                                type="button"
                                onclick="openCancelModal('{{ $event->id }}', '{{ addslashes($event->title) }}')"
                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors text-sm">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Unregister
                            </button>
                        @elseif($badgeInfo['status'] === 'ongoing')
                            <div class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg font-medium text-sm text-center border border-orange-300">
                                <span class="flex items-center justify-center">
                                    <span class="w-2 h-2 bg-current rounded-full mr-2 animate-pulse"></span>
                                    Event in Progress
                                </span>
                            </div>
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
                    @elseif($filter === 'ongoing')
                        You don't have any ongoing events.
                    @else
                        You haven't attended any completed events yet.
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

<!-- ✅ CONFIRMATION MODAL -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full animate-in fade-in zoom-in duration-200">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-xl">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-white">Cancel Registration</h2>
                </div>
                <button onclick="closeCancelModal()" class="text-red-100 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-6">
            <div class="mb-4">
                <p class="text-gray-900 font-semibold text-lg">Are you sure?</p>
            </div>
            <p class="text-gray-600 mb-4">
                You are about to cancel your registration for <span class="font-bold text-gray-900" id="eventTitle"></span>
            </p>

            <!-- Warning Alert -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">
                            This action cannot be undone. You may need to register again if spots are still available.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end gap-3 border-t border-gray-200">
            <button
                onclick="closeCancelModal()"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium transition-colors">
                Keep Registration
            </button>
            <form id="cancelForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    Yes, Cancel Registration
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    function openCancelModal(eventId, eventTitle) {
        // Set event title in modal
        document.getElementById('eventTitle').textContent = eventTitle;

        // Set form action with correct route
        const actionUrl = `{{ route('events.unregister', ':eventId') }}`.replace(':eventId', eventId);
        document.getElementById('cancelForm').action = actionUrl;

        // Display modal with fade-in animation
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').offsetHeight; // Trigger reflow for animation
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    // Close modal when clicking outside (backdrop)
    document.getElementById('cancelModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeCancelModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !document.getElementById('cancelModal').classList.contains('hidden')) {
            closeCancelModal();
        }
    });
</script>

@endsection
