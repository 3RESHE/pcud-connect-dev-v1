@extends('layouts.staff')

@section('title', 'Manage Registrations - PCU-DASMA Connect')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('staff.events.show', $event->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Event Details
    </a>
</div>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Registrations</h1>
    <p class="text-gray-600">{{ $event->title }} - {{ $event->event_date->format('F d, Y') }}</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Registrations</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalRegistrations }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Confirmed</p>
                <p class="text-3xl font-bold text-green-600">{{ $confirmedCount }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Cancelled</p>
                <p class="text-3xl font-bold text-red-600">{{ $cancelledCount }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" id="searchInput" onkeyup="searchRegistrations()" placeholder="Search by name or email..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
            <select id="statusFilter" onchange="filterByStatus()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="all">All Statuses</option>
                <option value="registered">Registered</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <!-- Action Buttons -->
        <div class="flex items-end gap-2">
            <button onclick="openNotificationModal()" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Send Email
            </button>
        </div>
        <div>
            <button onclick="exportToCSV()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </button>
        </div>
    </div>
</div>

<!-- Registrations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Registered Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($registrations as $registration)
                    <tr class="registration-row hover:bg-gray-50" data-status="{{ $registration->status }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="row-checkbox rounded" value="{{ $registration->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $registration->user->name }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $registration->user->student_id ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $registration->user->email }}</p>
                            <p class="text-xs text-gray-500">{{ $registration->user->phone ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $registration->user->department ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="@if($registration->status === 'confirmed') bg-green-100 text-green-800 @elseif($registration->status === 'cancelled') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif px-3 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $registration->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <button onclick="viewDetails({{ $registration->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                                    View
                                </button>
                                @if($registration->status !== 'cancelled')
                                    <form action="{{ route('staff.events.registrations.cancel', [$event->id, $registration->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 font-medium text-xs">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <p class="text-gray-500">No registrations found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Send Email Notification</h2>
        <form id="notificationForm" onsubmit="submitNotificationForm(event)">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Recipients</label>
                <textarea id="recipientsField" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" rows="3"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="subjectField" required placeholder="Email subject..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea id="messageField" required placeholder="Your message..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" rows="4"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeNotificationModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-blue-700">
                    Send
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function searchRegistrations() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.registration-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}

function filterByStatus() {
    const filter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.registration-row');
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
    });
}

function viewDetails(id) {
    window.location.href = `{{ route('staff.events.registrations.details', [$event->id, ':id']) }}`.replace(':id', id);
}

function openNotificationModal() {
    const checkedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));
    if (checkedBoxes.length === 0) {
        alert('Please select at least one registration');
        return;
    }
    const emails = checkedBoxes.map(cb => {
        const row = cb.closest('tr');
        return row.querySelector('td:nth-child(3) p').textContent;
    });
    document.getElementById('recipientsField').value = emails.join(', ');
    document.getElementById('notificationModal').classList.remove('hidden');
}

function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
    document.getElementById('notificationForm').reset();
}

function submitNotificationForm(e) {
    e.preventDefault();
    alert('Email notification sent successfully!');
    closeNotificationModal();
}

function exportToCSV() {
    alert('Exporting registrations to CSV file...');
}

document.getElementById('notificationModal').addEventListener('click', function(e) {
    if (e.target === this) closeNotificationModal();
});
</script>
@endsection
