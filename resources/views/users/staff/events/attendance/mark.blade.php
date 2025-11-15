@extends('layouts.staff')

@section('title', 'Mark Attendance - PCU-DASMA Connect')

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
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                Mark Attendance
                <span class="ml-3 px-3 py-1 bg-purple-100 text-purple-800 text-sm font-medium rounded-full animate-pulse">Event Live</span>
            </h1>
            <p class="text-gray-600">{{ $event->title }} - {{ $event->event_date->format('F j, Y') }} • {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Registrations</p>
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
</div>

<!-- Action Buttons -->
<div class="mb-8 flex justify-between gap-4 flex-wrap">
    <div class="flex gap-3">
        <button onclick="markAllAttended()" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center text-base">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Mark All Attended
        </button>
        <!-- ✅ WALK-IN BUTTON -->
        <button onclick="openWalkinModal()" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors flex items-center text-base">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Walk-in
        </button>
    </div>
    <button onclick="exportAttendance()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors flex items-center text-base">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        Export to CSV
    </button>
</div>

<!-- Attendance List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900">Participant List</h2>
        <p class="text-gray-600 mt-1">Mark attendance for registered participants</p>
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
                    <th class="px-8 py-4 text-left text-gray-700 font-semibold text-base">Check-In Time</th>
                    <th class="px-8 py-4 text-center text-gray-700 font-semibold text-base">Status</th>
                    <th class="px-8 py-4 text-center text-gray-700 font-semibold text-base">Actions</th>
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
                        <p class="text-gray-600 text-base">
                            @if($registration->checked_in_at)
                                {{ $registration->checked_in_at->format('M d, Y g:i A') }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </p>
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
                        <div class="flex items-center justify-center gap-2">
                            <button
                                onclick="openDetailsModal('{{ $registration->user->first_name }} {{ $registration->user->last_name }}', '{{ $registration->user->email }}')"
                                class="px-3 py-1 bg-blue-50 text-blue-600 rounded text-xs font-medium hover:bg-blue-100"
                            >
                                View
                            </button>
                            <form action="{{ route('staff.events.attendance.update', [$event->id, $registration->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="attendance_status" value="attended">
                                <button
                                    type="submit"
                                    class="px-3 py-1 rounded text-xs font-medium transition-colors @if($registration->attendance_status === 'attended') bg-green-600 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif"
                                >
                                    Check In
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600 text-lg font-medium">No registrations yet</p>
                            <p class="text-gray-400 text-base">Participants will appear here when they register</p>
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

<!-- ✅ WALK-IN MODAL WITH STUDENT/ALUMNI TABS -->
<div id="walkinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Add Walk-in Participant</h2>
            <button onclick="closeWalkinModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 bg-gray-50 px-6">
            <div class="flex gap-8">
                <button
                    onclick="switchTab('student')"
                    id="studentTab"
                    class="px-4 py-4 text-base font-medium border-b-2 border-blue-600 text-blue-600 transition-all"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5m0 0l9 5m-9-5v10l9 5m0 0l9-5m-9 5v-10l9-5"></path>
                    </svg>
                    Student
                </button>
                <button
                    onclick="switchTab('alumni')"
                    id="alumniTab"
                    class="px-4 py-4 text-base font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Alumni
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- STUDENT TAB -->
            <form id="studentForm" method="POST" action="{{ route('staff.events.attendance.mark', $event->id) }}" class="space-y-4">
                @csrf
                <input type="hidden" name="user_type" value="student">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Juan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Dela Cruz">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                        <input type="text" name="student_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="2021-12345">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="student@pcu.edu.ph">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-gray-400">(Optional)</span></label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="+63 912 345 6789">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year Level <span class="text-gray-400">(Optional)</span></label>
                        <select name="year_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes <span class="text-gray-400">(Optional)</span></label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Any special requirements..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeWalkinModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add & Check In
                    </button>
                </div>
            </form>

            <!-- ALUMNI TAB (Hidden by default) -->
            <form id="alumniForm" method="POST" action="{{ route('staff.events.attendance.mark', $event->id) }}" class="space-y-4 hidden">
                @csrf
                <input type="hidden" name="user_type" value="alumni">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Maria">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Santos">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="alumni@pcu.edu.ph">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-gray-400">(Optional)</span></label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="+63 912 345 6789">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Graduation Year <span class="text-gray-400">(Optional)</span></label>
                        <input type="number" name="graduation_year" min="1990" max="2099" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="2020">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes <span class="text-gray-400">(Optional)</span></label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Any special requirements..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeWalkinModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add & Check In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PARTICIPANT DETAILS MODAL -->
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Participant Details</h2>
            <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-xl" id="detailInitials">JD</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900" id="detailName">Juan Dela Cruz</h3>
                        <p class="text-gray-600" id="detailSubtitle">Student ID: 2021-12345</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900" id="detailEmail">juan@pcu.edu.ph</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full inline-block mt-1">Checked In</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeDetailsModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Close</button>
            </div>
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

function markAllAttended() {
    if (confirm('Mark all registered participants as attended?')) {
        // This would need AJAX implementation for better UX
        alert('Feature to be implemented with AJAX for better UX');
    }
}

function exportAttendance() {
    window.location.href = "{{ route('staff.events.attendance.export', $event->id) }}";
}

// Walk-in Modal Functions
function openWalkinModal() {
    document.getElementById('walkinModal').classList.remove('hidden');
    switchTab('student'); // Open on student tab by default
}

function closeWalkinModal() {
    document.getElementById('walkinModal').classList.add('hidden');
    resetWalkinForms(); // Reset forms when closing
}

function resetWalkinForms() {
    document.getElementById('studentForm').reset();
    document.getElementById('alumniForm').reset();
}

function switchTab(tab) {
    // Hide both forms
    document.getElementById('studentForm').classList.add('hidden');
    document.getElementById('alumniForm').classList.add('hidden');

    // Reset tab styles
    document.getElementById('studentTab').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('studentTab').classList.add('border-transparent', 'text-gray-600');
    document.getElementById('alumniTab').classList.remove('border-purple-600', 'text-purple-600');
    document.getElementById('alumniTab').classList.add('border-transparent', 'text-gray-600');

    // Show selected tab
    if (tab === 'student') {
        document.getElementById('studentForm').classList.remove('hidden');
        document.getElementById('studentTab').classList.remove('border-transparent', 'text-gray-600');
        document.getElementById('studentTab').classList.add('border-blue-600', 'text-blue-600');
    } else {
        document.getElementById('alumniForm').classList.remove('hidden');
        document.getElementById('alumniTab').classList.remove('border-transparent', 'text-gray-600');
        document.getElementById('alumniTab').classList.add('border-purple-600', 'text-purple-600');
    }
}

// Details Modal Functions
function openDetailsModal(name, email) {
    const initials = name.split(' ').map(n => n[0]).join('');
    document.getElementById('detailInitials').textContent = initials.substring(0, 2);
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailEmail').textContent = email;
    document.getElementById('detailsModal').classList.remove('hidden');
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const walkinModal = document.getElementById('walkinModal');
    const detailsModal = document.getElementById('detailsModal');

    if (event.target === walkinModal) {
        closeWalkinModal();
    }
    if (event.target === detailsModal) {
        closeDetailsModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeWalkinModal();
        closeDetailsModal();
    }
});
</script>

@endsection
