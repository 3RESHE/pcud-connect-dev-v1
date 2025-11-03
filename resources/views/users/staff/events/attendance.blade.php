@extends('layouts.staff')

@section('title', 'Manage Attendance - ' . $event->title . ' - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('staff.events.show', $event->id) }}" class="text-gray-400 hover:text-gray-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">Manage Attendance</h1>
            <p class="text-gray-600 mt-1">{{ $event->title }}</p>
        </div>
    </div>
</div>

<!-- Status Banner -->
@if($event->status === 'ongoing')
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-5 h-5 rounded-full bg-orange-600 animate-pulse"></div>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-orange-800">🔴 Event is Live</h3>
                <p class="text-sm text-orange-700 mt-1">Check in attendees in real-time as they arrive.</p>
            </div>
        </div>
    </div>
@elseif($event->status === 'completed')
    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Event Completed</h3>
                <p class="text-sm text-gray-700 mt-1">Final attendance report. No further check-ins allowed.</p>
            </div>
        </div>
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <p class="text-sm text-gray-600">Total Registrations</p>
        <p class="text-2xl font-bold text-gray-900">{{ $registrations->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-green-200 bg-green-50">
        <p class="text-sm text-gray-600">Checked In</p>
        <p class="text-2xl font-bold text-green-600">{{ $checkedIn }}</p>
        <p class="text-xs text-gray-600 mt-1">{{ round(($checkedIn / $registrations->count()) * 100, 1) }}%</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-orange-200 bg-orange-50">
        <p class="text-sm text-gray-600">Pending</p>
        <p class="text-2xl font-bold text-orange-600">{{ $registrations->count() - $checkedIn }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-blue-200 bg-blue-50">
        <p class="text-sm text-gray-600">No-show</p>
        <p class="text-2xl font-bold text-blue-600">{{ $noShow }}</p>
    </div>
</div>

<!-- Quick Check-In Section -->
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎫 Quick Check-In</h3>
    <div class="flex gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Scan ID or Search Name</label>
            <input
                type="text"
                id="quickCheckIn"
                placeholder="Scan QR code or type student ID..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary"
                @keyup.enter="performQuickCheckIn"
            />
            <p class="text-xs text-gray-500 mt-1">Press Enter to check in</p>
        </div>
        <div class="flex items-end">
            <button onclick="clearQuickCheckIn()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Clear
            </button>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Attendee</label>
            <input type="text" id="searchInput" placeholder="Search by name or ID..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary" />
        </div>
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary">
                <option value="">All Attendees</option>
                <option value="checked_in">Checked In</option>
                <option value="not_checked_in">Not Checked In</option>
            </select>
        </div>
        <button onclick="exportAttendance()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
            📥 Export Report
        </button>
    </div>
</div>

<!-- Attendance Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Check-In Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="attendanceTable">
                @forelse($registrations as $registration)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 attendance-row" data-status="{{ $registration->checked_in_at ? 'checked_in' : 'not_checked_in' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $registration->user->first_name }} {{ $registration->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $registration->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-900">{{ $registration->user->student_id ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registration->checked_in_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Checked In
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏱ Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registration->checked_in_at)
                                <p class="text-sm text-gray-900">{{ $registration->checked_in_at->format('g:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $registration->checked_in_at->format('M d, Y') }}</p>
                            @else
                                <p class="text-sm text-gray-500">—</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($registration->checked_in_at && $registration->checked_out_at)
                                <p class="text-gray-900">
                                    {{ $registration->checked_in_at->diffInMinutes($registration->checked_out_at) }} min
                                </p>
                            @else
                                <p class="text-gray-500">—</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            @if($event->status === 'ongoing')
                                @if(!$registration->checked_in_at)
                                    <button onclick="checkIn({{ $registration->id }})" class="text-green-600 hover:text-green-900 font-medium">
                                        ✓ Check In
                                    </button>
                                @else
                                    @if(!$registration->checked_out_at)
                                        <button onclick="checkOut({{ $registration->id }})" class="text-orange-600 hover:text-orange-900 font-medium">
                                            ✕ Check Out
                                        </button>
                                    @else
                                        <span class="text-gray-500">Completed</span>
                                    @endif
                                @endif
                                <button onclick="toggleNoShow({{ $registration->id }})" class="text-red-600 hover:text-red-900 font-medium">
                                    ⚠ No-Show
                                </button>
                            @else
                                <span class="text-gray-500">View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                            <p>No registrations for this event.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Confirmation Modals -->

<!-- Check-In Modal -->
<div id="checkInModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">✓ Check In Attendee?</h3>
            <p class="text-gray-700 mb-6">Record this person as checked in for the event.</p>
            <form id="checkInForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('checkInModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Check In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Check-Out Modal -->
<div id="checkOutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">✕ Check Out Attendee?</h3>
            <p class="text-gray-700 mb-6">Record this person as checked out from the event.</p>
            <form id="checkOutForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('checkOutModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        Check Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- No-Show Modal -->
<div id="noShowModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⚠ Mark as No-Show?</h3>
            <p class="text-gray-700 mb-6">This will mark the attendee as not having shown up.</p>
            <form id="noShowForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('noShowModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Keep
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Mark No-Show
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRegistrationId = null;

function checkIn(registrationId) {
    currentRegistrationId = registrationId;
    const form = document.getElementById('checkInForm');
    form.action = "{{ route('staff.events.attendance.check-in', [$event->id, ':id']) }}".replace(':id', registrationId);
    document.getElementById('checkInModal').classList.remove('hidden');
}

function checkOut(registrationId) {
    currentRegistrationId = registrationId;
    const form = document.getElementById('checkOutForm');
    form.action = "{{ route('staff.events.attendance.check-out', [$event->id, ':id']) }}".replace(':id', registrationId);
    document.getElementById('checkOutModal').classList.remove('hidden');
}

function toggleNoShow(registrationId) {
    currentRegistrationId = registrationId;
    const form = document.getElementById('noShowForm');
    form.action = "{{ route('staff.events.attendance.check-out', [$event->id, ':id']) }}?type=no_show".replace(':id', registrationId);
    document.getElementById('noShowModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function clearQuickCheckIn() {
    document.getElementById('quickCheckIn').value = '';
    document.getElementById('quickCheckIn').focus();
}

function exportAttendance() {
    window.location.href = "{{ route('staff.events.attendance.export', $event->id) }}";
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.attendance-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('.attendance-row');
    rows.forEach(row => {
        if (selectedStatus === '' || row.dataset.status === selectedStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Quick check-in with QR code or ID scan
document.getElementById('quickCheckIn').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const searchTerm = this.value.trim();
        if (searchTerm) {
            // Find the registrant
            const rows = document.querySelectorAll('.attendance-row');
            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm.toLowerCase())) {
                    // Scroll to row and highlight
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    row.classList.add('bg-yellow-100');
                    setTimeout(() => row.classList.remove('bg-yellow-100'), 2000);
                    break;
                }
            }
            this.value = '';
        }
    }
});

// Close modals when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>

@endsection
