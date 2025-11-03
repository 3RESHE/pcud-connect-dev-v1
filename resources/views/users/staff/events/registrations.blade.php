@extends('layouts.staff')

@section('title', 'Manage Registrations - ' . $event->title . ' - PCU-DASMA Connect')

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
            <h1 class="text-3xl font-bold text-gray-900">Manage Registrations</h1>
            <p class="text-gray-600 mt-1">{{ $event->title }}</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <p class="text-sm text-gray-600">Total Registrations</p>
        <p class="text-2xl font-bold text-gray-900">{{ $registrations->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-blue-200 bg-blue-50">
        <p class="text-sm text-gray-600">Pending</p>
        <p class="text-2xl font-bold text-blue-600">{{ $registrations->where('status', 'pending')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-green-200 bg-green-50">
        <p class="text-sm text-gray-600">Confirmed</p>
        <p class="text-2xl font-bold text-green-600">{{ $registrations->where('status', 'confirmed')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-orange-200 bg-orange-50">
        <p class="text-sm text-gray-600">Attended</p>
        <p class="text-2xl font-bold text-orange-600">{{ $registrations->whereNotNull('checked_in_at')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-red-200 bg-red-50">
        <p class="text-sm text-gray-600">Cancelled</p>
        <p class="text-2xl font-bold text-red-600">{{ $registrations->where('status', 'cancelled')->count() }}</p>
    </div>
</div>

<!-- Filters & Search -->
<div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search by Name or Email</label>
            <input type="text" id="searchInput" placeholder="Search..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary" />
        </div>
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <button onclick="exportRegistrations()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
            📥 Export List
        </button>
    </div>
</div>

<!-- Registrations Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Registration Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Checked In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="registrationsTable">
                @forelse($registrations as $registration)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 registration-row" data-status="{{ $registration->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $registration->user->first_name }} {{ $registration->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $registration->user->student_id ?? $registration->user->id }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="mailto:{{ $registration->user->email }}" class="text-sm text-primary hover:text-blue-700">
                                {{ $registration->user->email }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $registration->created_at->format('M d, Y - g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($registration->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($registration->status === 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registration->checked_in_at)
                                <span class="inline-flex items-center text-green-600">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $registration->checked_in_at->format('g:i A') }}
                                </span>
                            @else
                                <span class="text-gray-500">Not checked in</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            @if($registration->status === 'pending')
                                <button onclick="confirmRegistration({{ $registration->id }})" class="text-green-600 hover:text-green-900 font-medium">
                                    ✓ Confirm
                                </button>
                                <button onclick="cancelRegistration({{ $registration->id }})" class="text-red-600 hover:text-red-900 font-medium">
                                    ✕ Cancel
                                </button>
                            @elseif($registration->status === 'confirmed')
                                <button onclick="viewDetails({{ $registration->id }})" class="text-primary hover:text-blue-700 font-medium">
                                    View
                                </button>
                                <button onclick="cancelRegistration({{ $registration->id }})" class="text-red-600 hover:text-red-900 font-medium">
                                    Cancel
                                </button>
                            @else
                                <span class="text-gray-500">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                            <p>No registrations yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Registration?</h3>
            <p class="text-gray-700 mb-6">This registrant will be confirmed for the event.</p>
            <form id="confirmForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('confirmModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Registration Modal -->
<div id="cancelRegModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cancel Registration?</h3>
            <p class="text-gray-700 mb-4">This registrant will be removed from the event.</p>
            <p class="text-sm text-gray-600 mb-6">Send notification email?</p>
            <form id="cancelRegForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="send_notification" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Notify registrant via email</span>
                    </label>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('cancelRegModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Keep Registration
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Cancel Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRegistrationId = null;

function confirmRegistration(registrationId) {
    currentRegistrationId = registrationId;
    const form = document.getElementById('confirmForm');
    form.action = "{{ route('staff.events.registrations.confirm', [$event->id, ':id']) }}".replace(':id', registrationId);
    document.getElementById('confirmModal').classList.remove('hidden');
}

function cancelRegistration(registrationId) {
    currentRegistrationId = registrationId;
    const form = document.getElementById('cancelRegForm');
    form.action = "{{ route('staff.events.registrations.cancel', [$event->id, ':id']) }}".replace(':id', registrationId);
    document.getElementById('cancelRegModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function exportRegistrations() {
    window.location.href = "{{ route('staff.events.registrations.export', $event->id) }}";
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.registration-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const rows = document.querySelectorAll('.registration-row');
    rows.forEach(row => {
        if (selectedStatus === '' || row.dataset.status === selectedStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
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
