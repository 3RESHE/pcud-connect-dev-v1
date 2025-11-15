@extends('layouts.staff')

@section('title', 'Attendance Records - PCU-DASMA Connect')

@section('content')
<!-- Back Button -->
<div class="mb-8">
    <a href="{{ route('staff.events.show', $event->id) }}" class="inline-flex items-center text-base text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Event Details
    </a>
</div>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                Attendance Records
                <span class="ml-3 px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                    @if($event->status === 'completed')
                        ✓ Completed
                    @else
                        ▶️ Ongoing
                    @endif
                </span>
            </h1>
            <p class="text-gray-600">{{ $event->title }} - {{ $event->event_date->format('F j, Y') }} • {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p>
        </div>
    </div>
</div>

<!-- Attendance Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Registered</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-green-200 bg-green-50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-700 text-sm font-medium">Attended</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['attended'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-yellow-200 bg-yellow-50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-700 text-sm font-medium">Registered</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['registered'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-red-200 bg-red-50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-700 text-sm font-medium">No Show</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['no_show'] }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-purple-200 bg-purple-50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-700 text-sm font-medium">Attendance Rate</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['attendance_rate'] }}%</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Rate Progress Bar -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Overall Attendance Rate</h3>
        <span class="text-2xl font-bold text-purple-600">{{ $stats['attendance_rate'] }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ $stats['attendance_rate'] }}%"></div>
    </div>
    <div class="mt-4 grid grid-cols-3 gap-4 text-center">
        <div>
            <p class="text-gray-600 text-sm">Attended vs Total</p>
            <p class="text-gray-900 font-semibold text-base">{{ $stats['attended'] }}/{{ $stats['total'] }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Expected Attendance</p>
            <p class="text-gray-900 font-semibold text-base">{{ $stats['total'] - $stats['no_show'] }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Unaccounted</p>
            <p class="text-red-600 font-semibold text-base">{{ $stats['no_show'] }}</p>
        </div>
    </div>
</div>

<!-- Export Button -->
<div class="mb-8">
    <a href="{{ route('staff.events.attendance.export', $event->id) }}" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors text-base">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        Export to CSV
    </a>
</div>

<!-- Attendance List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900">Participant Attendance Details</h2>
        <p class="text-gray-600 mt-1">Complete attendance information for all registered participants</p>
    </div>

    <!-- Search & Filter -->
    <div class="px-8 py-4 border-b border-gray-100 flex gap-4 flex-wrap">
        <input
            type="text"
            id="searchInput"
            placeholder="Search by name or email..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-primary"
            onkeyup="filterTable()"
        >
        <select
            id="statusFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-primary"
            onchange="filterTable()"
        >
            <option value="">All Status</option>
            <option value="attended">Attended</option>
            <option value="registered">Registered</option>
            <option value="no_show">No Show</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full" id="attendanceTable">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Participant</th>
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Type</th>
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Email</th>
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Registered On</th>
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Status</th>
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Check-In Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($registrations as $registration)
                <tr class="hover:bg-gray-50 transition-colors" data-search="{{ strtolower($registration->user->first_name . ' ' . $registration->user->last_name . ' ' . $registration->user->email) }}" data-status="{{ $registration->attendance_status }}">
                    <td class="px-8 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold text-sm">{{ substr($registration->user->first_name, 0, 1) }}{{ substr($registration->user->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-base">{{ $registration->user->first_name }} {{ $registration->user->last_name }}</p>
                                @if($registration->user_type === 'student' && $registration->user->studentProfile)
                                    <p class="text-gray-500 text-sm">ID: {{ $registration->user->studentProfile->student_id }}</p>
                                @elseif($registration->user_type === 'alumni')
                                    <p class="text-gray-500 text-sm">Alumni</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium @if($registration->user_type === 'student') bg-blue-100 text-blue-800 @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst($registration->user_type) }}
                        </span>
                    </td>
                    <td class="px-8 py-4">
                        <p class="text-gray-600 text-base break-all">{{ $registration->user->email }}</p>
                    </td>
                    <td class="px-8 py-4">
                        <p class="text-gray-600 text-base">{{ $registration->created_at->format('M d, Y g:i A') }}</p>
                    </td>
                    <td class="px-8 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($registration->attendance_status === 'attended')
                                bg-green-100 text-green-800
                            @elseif($registration->attendance_status === 'no_show')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            @if($registration->attendance_status === 'attended')
                                ✓ Attended
                            @elseif($registration->attendance_status === 'no_show')
                                ✗ No Show
                            @else
                                ◊ Registered
                            @endif
                        </span>
                    </td>
                    <td class="px-8 py-4">
                        <p class="text-gray-600 text-base">
                            @if($registration->checked_in_at)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                    </svg>
                                    {{ $registration->checked_in_at->format('M d, Y g:i A') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600 text-lg font-medium">No attendance records</p>
                            <p class="text-gray-400 text-base">This event has no registrations</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($registrations->hasPages())
    <div class="px-8 py-4 border-t border-gray-100">
        {{ $registrations->links('pagination::tailwind') }}
    </div>
    @endif
</div>

<!-- Summary Statistics Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
    <!-- Top Insights -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Event Insights</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b">
                <span class="text-gray-600">Attendance Rate</span>
                <span class="text-lg font-semibold text-gray-900">{{ $stats['attendance_rate'] }}%</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b">
                <span class="text-gray-600">Participants Attended</span>
                <span class="text-lg font-semibold text-green-600">{{ $stats['attended'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b">
                <span class="text-gray-600">No Shows</span>
                <span class="text-lg font-semibold text-red-600">{{ $stats['no_show'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-600">Total Capacity</span>
                <span class="text-lg font-semibold text-gray-900">{{ $stats['total'] }}</span>
            </div>
        </div>
    </div>

    <!-- Download Options -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Export Options</h3>
        <div class="space-y-3">
            <a href="{{ route('staff.events.attendance.export', $event->id) }}" class="flex items-center w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-blue-700 font-medium transition-colors text-base">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Download CSV Report
            </a>
            <button onclick="printReport()" class="flex items-center w-full px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg text-green-700 font-medium transition-colors text-base">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path>
                </svg>
                Print Report
            </button>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#attendanceTable tbody tr');

    rows.forEach(row => {
        const searchText = row.dataset.search || '';
        const status = row.dataset.status || '';

        const matchesSearch = searchInput === '' || searchText.includes(searchInput);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

function printReport() {
    window.print();
}
</script>

@endsection
