@extends('layouts.admin')

@section('title', $event->title . ' - Event Details')

@section('content')
<!-- Header with Back Button -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.approvals.events.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Event Details</h1>
                <p class="text-gray-600 mt-1">Review and manage event submission</p>
            </div>
        </div>

        <!-- Status Badge -->
        <div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                @if($event->status === 'draft') bg-gray-100 text-gray-700
                @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($event->status === 'approved') bg-green-100 text-green-800
                @elseif($event->status === 'rejected') bg-red-100 text-red-800
                @elseif($event->status === 'published') bg-blue-100 text-blue-800
                @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                @elseif($event->status === 'completed') bg-purple-100 text-purple-800
                @else bg-gray-100 text-gray-800 @endif">
                Status: {{ ucfirst($event->status) }}
            </span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left Column - Event Details -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Event Image -->
        @if($event->event_image)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
            </div>
        @endif

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $event->title }}</h2>

            <div class="prose max-w-none text-gray-700">
                {{ $event->description }}
            </div>
        </div>

        <!-- Event Details Grid -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Event Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date & Time -->
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Date & Time</label>
                    <p class="mt-1 text-gray-900">
                        {{ $event->event_date?->format('F d, Y') }}
                        @if($event->is_multiday && $event->end_date)
                            - {{ $event->end_date->format('F d, Y') }}
                        @endif
                    </p>
                    <p class="text-gray-700">{{ $event->start_time }} - {{ $event->end_time }}</p>
                </div>

                <!-- Format -->
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Event Format</label>
                    <p class="mt-1 text-gray-900 capitalize">
                        @if($event->event_format === 'inperson') In-Person
                        @elseif($event->event_format === 'virtual') Virtual
                        @else Hybrid @endif
                    </p>
                </div>

                <!-- Venue (if in-person or hybrid) -->
                @if(in_array($event->event_format, ['inperson', 'hybrid']))
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase">Venue</label>
                        <p class="mt-1 text-gray-900">{{ $event->venue_name }}</p>
                        @if($event->venue_address)
                            <p class="text-sm text-gray-600">{{ $event->venue_address }}</p>
                        @endif
                        @if($event->venue_capacity)
                            <p class="text-sm text-gray-600">Capacity: {{ $event->venue_capacity }} people</p>
                        @endif
                    </div>
                @endif

                <!-- Virtual Platform (if virtual or hybrid) -->
                @if(in_array($event->event_format, ['virtual', 'hybrid']))
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase">Virtual Platform</label>
                        <p class="mt-1 text-gray-900">{{ $event->platform === 'other' ? $event->custom_platform : ucfirst($event->platform) }}</p>
                        @if($event->meeting_link)
                            <a href="{{ $event->meeting_link }}" target="_blank" class="text-sm text-blue-600 hover:underline">Join Link</a>
                        @endif
                        @if($event->virtual_capacity)
                            <p class="text-sm text-gray-600">Virtual Capacity: {{ $event->virtual_capacity }} participants</p>
                        @endif
                    </div>
                @endif

                <!-- Registration -->
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Registration</label>
                    <p class="mt-1 text-gray-900">
                        {{ $event->registration_required ? 'Required' : 'Not Required' }}
                    </p>
                    @if($event->registration_deadline)
                        <p class="text-sm text-gray-600">Deadline: {{ $event->registration_deadline->format('M d, Y') }}</p>
                    @endif
                    @if($event->max_attendees)
                        <p class="text-sm text-gray-600">Max Attendees: {{ $event->max_attendees }}</p>
                    @endif
                </div>

                <!-- Walk-in -->
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Walk-in Allowed</label>
                    <p class="mt-1 text-gray-900">{{ $event->walkin_allowed ? 'Yes' : 'No' }}</p>
                </div>

                <!-- Target Audience -->
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Target Audience</label>
                    <p class="mt-1 text-gray-900 capitalize">{{ str_replace('_', ' & ', $event->target_audience) }}</p>
                </div>

                <!-- Departments -->
                @if($event->selected_departments)
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase">Selected Departments</label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach(json_decode($event->selected_departments) as $dept)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $dept }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tags -->
                @if($event->event_tags)
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase">Event Tags</label>
                        <p class="mt-1 text-gray-900">{{ $event->event_tags }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Contact Person</label>
                    <p class="mt-1 text-gray-900">{{ $event->contact_person }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Email</label>
                    <p class="mt-1 text-gray-900">{{ $event->contact_email }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $event->contact_phone }}</p>
                </div>
            </div>

            @if($event->special_instructions)
                <div class="mt-4">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Special Instructions</label>
                    <p class="mt-1 text-gray-700">{{ $event->special_instructions }}</p>
                </div>
            @endif
        </div>

        <!-- Rejection Reason (if rejected) -->
        @if($event->status === 'rejected' && $event->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-red-900 mb-2">Rejection Reason</h3>
                <p class="text-red-800">{{ $event->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <!-- Right Column - Actions & Metadata -->
    <div class="space-y-6">

        <!-- Status Management Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Status Management</h3>

            <div class="space-y-3">
                <!-- Approve (only for pending) -->
                @if($event->status === 'pending')
                    <button onclick="openApproveModal()"
                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve Event
                    </button>

                    <button onclick="openRejectModal()"
                        class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Event
                    </button>
                @endif

                <!-- Publish (only for approved) -->
                @if($event->status === 'approved')
                    <button onclick="changeStatus('published')"
                        class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Publish Event
                    </button>
                @endif

                <!-- Mark as Ongoing (only for published) -->
                @if($event->status === 'published')
                    <button onclick="changeStatus('ongoing')"
                        class="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark as Ongoing
                    </button>
                @endif

                <!-- Mark as Completed (only for ongoing) -->
                @if($event->status === 'ongoing')
                    <button onclick="changeStatus('completed')"
                        class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark as Completed
                    </button>
                @endif
            </div>
        </div>

        <!-- Creator Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Event Creator</h3>

            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $event->creator->full_name }}</p>
                    <p class="text-sm text-gray-600">{{ $event->creator->email }}</p>
                    <p class="text-xs text-gray-500">Staff Member</p>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Timestamps</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <label class="font-semibold text-gray-600">Created At:</label>
                    <p class="text-gray-900">{{ $event->created_at->format('M d, Y g:i A') }}</p>
                </div>
                <div>
                    <label class="font-semibold text-gray-600">Last Updated:</label>
                    <p class="text-gray-900">{{ $event->updated_at->format('M d, Y g:i A') }}</p>
                </div>
                @if($event->published_at)
                    <div>
                        <label class="font-semibold text-gray-600">Published At:</label>
                        <p class="text-gray-900">{{ $event->published_at->format('M d, Y g:i A') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
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
                <p class="text-sm font-medium text-gray-900 mt-2">{{ $event->title }}</p>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="closeApproveModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="approveEvent()" id="approveButton" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
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
                <p class="text-sm text-gray-500 mb-3">Please provide a reason for rejecting this event:</p>

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
                    Reject
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Approve Modal
function openApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

async function approveEvent() {
    const button = document.getElementById('approveButton');
    button.disabled = true;
    button.textContent = 'Approving...';

    try {
        const response = await fetch(`/admin/approvals/events/{{ $event->id }}/approve`, {
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
            button.textContent = 'Approve';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
        button.disabled = false;
        button.textContent = 'Approve';
    }
}

// Reject Modal
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionError').classList.add('hidden');
}

async function rejectEvent() {
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
        const response = await fetch(`/admin/approvals/events/{{ $event->id }}/reject`, {
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
            button.textContent = 'Reject';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
        button.disabled = false;
        button.textContent = 'Reject';
    }
}

// Simple Status Change (for publish, ongoing, completed)
async function changeStatus(newStatus) {
    if (!confirm(`Are you sure you want to change status to "${newStatus}"?`)) {
        return;
    }

    try {
        const response = await fetch(`/admin/approvals/events/{{ $event->id }}/change-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await response.json();

        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to change status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
}

// Close modals on ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection
