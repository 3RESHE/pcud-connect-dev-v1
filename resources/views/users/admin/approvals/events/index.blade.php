@extends('layouts.admin')

@section('title', 'Event Management - PCU-DASMA Connect')

@section('content')
<!-- Header Section -->
<div class="mb-10">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Event Management</h1>
            <p class="text-gray-600 mt-2">Review and manage all event submissions across the platform</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Draft</p>
            <p class="text-3xl font-bold text-gray-600 mt-2">{{ $draft_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pending_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $approved_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Published</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $published_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-purple-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Completed</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $completed_count }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejected</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejected_count }}</p>
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
            <input type="text" id="searchInput" placeholder="Search events by title, staff, venue..."
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                onkeyup="filterEvents()">
        </div>

        <!-- Status Filter -->
        <div>
            <select id="statusFilter" onchange="filterEvents()"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer bg-white">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending Review</option>
                <option value="approved">Approved</option>
                <option value="published">Published</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsList">
    @forelse($events as $event)
        <a href="{{ route('admin.approvals.events.show', $event->id) }}"
           class="group event-card block"
           data-status="{{ $event->status }}"
           data-title="{{ strtolower($event->title) }}"
           data-staff="{{ strtolower($event->creator->full_name ?? '') }}">

            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-b-4 cursor-pointer
                @if($event->status === 'draft') border-b-gray-400
                @elseif($event->status === 'pending') border-b-yellow-500
                @elseif($event->status === 'approved') border-b-green-500
                @elseif($event->status === 'rejected') border-b-red-500
                @elseif($event->status === 'published') border-b-blue-500
                @elseif($event->status === 'ongoing') border-b-orange-500
                @elseif($event->status === 'completed') border-b-purple-500
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
                            @if($event->status === 'draft') bg-gray-100 text-gray-700
                            @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($event->status === 'approved') bg-green-100 text-green-800
                            @elseif($event->status === 'rejected') bg-red-100 text-red-800
                            @elseif($event->status === 'published') bg-blue-100 text-blue-800
                            @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                            @elseif($event->status === 'completed') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Title -->
                    <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">
                        {{ $event->title }}
                    </h3>

                    <!-- Event Info Grid -->
                    <div class="space-y-3 flex-grow">
                        <!-- Created By Staff -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Created By</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $event->creator->full_name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Date & Time</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $event->event_date?->format('M d, Y') }} at {{ $event->start_time }}</p>
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
                    </div>

                    <!-- Click to View Indicator -->
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <div class="flex items-center justify-center text-blue-600 font-medium text-sm group-hover:text-blue-700">
                            <span>Click to view details</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
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
            <p class="text-gray-600">No events match your search criteria</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $events->links() }}
    </div>
@endif

<!-- ============================== -->
<!-- APPROVE MODAL -->
<!-- ============================== -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Approve Event</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    Are you sure you want to approve this event?
                </p>
                <p class="text-sm font-medium text-gray-900 mt-2" id="approveEventTitle"></p>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="closeApproveModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="approveEvent()" id="approveButton" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Approve Event
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================== -->
<!-- REJECT MODAL -->
<!-- ============================== -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Reject Event</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-3">
                    Please provide a reason for rejecting this event:
                </p>
                <p class="text-sm font-medium text-gray-900 mb-3" id="rejectEventTitle"></p>

                <textarea
                    id="rejectionReason"
                    rows="4"
                    class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter rejection reason (minimum 10 characters)..."
                    required
                ></textarea>
                <p class="text-xs text-red-600 hidden" id="rejectionError">Rejection reason is required (minimum 10 characters)</p>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="closeRejectModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="rejectEvent()" id="rejectButton" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Reject Event
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
let currentEventId = null;

// FILTER EVENTS
function filterEvents() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.event-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const status = card.getAttribute('data-status');
        const staff = card.getAttribute('data-staff');

        const matchesSearch = title.includes(searchValue) || staff.includes(searchValue);
        const matchesStatus = statusValue === '' || status === statusValue;

        if (matchesSearch && matchesStatus) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
}

// APPROVE MODAL
function openApproveModal(eventId, eventTitle) {
    currentEventId = eventId;
    document.getElementById('approveEventTitle').textContent = eventTitle;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    currentEventId = null;
}

async function approveEvent() {
    if (!currentEventId) return;

    const button = document.getElementById('approveButton');
    button.disabled = true;
    button.textContent = 'Approving...';

    try {
        const response = await fetch(`/admin/approvals/events/${currentEventId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to approve event');
            button.disabled = false;
            button.textContent = 'Approve Event';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while approving the event');
        button.disabled = false;
        button.textContent = 'Approve Event';
    }
}

// REJECT MODAL
function openRejectModal(eventId, eventTitle) {
    currentEventId = eventId;
    document.getElementById('rejectEventTitle').textContent = eventTitle;
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionError').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    currentEventId = null;
}

async function rejectEvent() {
    if (!currentEventId) return;

    const reason = document.getElementById('rejectionReason').value.trim();
    const errorElement = document.getElementById('rejectionError');

    if (reason.length < 10) {
        errorElement.classList.remove('hidden');
        return;
    }

    errorElement.classList.add('hidden');
    const button = document.getElementById('rejectButton');
    button.disabled = true;
    button.textContent = 'Rejecting...';

    try {
        const response = await fetch(`/admin/approvals/events/${currentEventId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rejection_reason: reason })
        });

        const data = await response.json();

        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to reject event');
            button.disabled = false;
            button.textContent = 'Reject Event';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while rejecting the event');
        button.disabled = false;
        button.textContent = 'Reject Event';
    }
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection
