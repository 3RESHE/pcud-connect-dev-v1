@extends('layouts.staff')

@section('title', 'Manage Attendance - ' . $event->title)

@section('content')
<!-- Back Button & Header -->
<div class="container mx-auto px-4 py-6 flex items-center justify-between">
    <div>
        <a href="{{ route('staff.events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Event
        </a>
        <h1 class="text-4xl font-bold text-gray-900">Attendance - {{ $event->title }}</h1>
        <p class="text-gray-600 mt-1">📅 {{ $event->event_date->format('F d, Y') }} • Status: <span class="font-semibold capitalize">{{ $event->status }}</span></p>
    </div>
</div>

<!-- Stats Row -->
<div class="container mx-auto px-4 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <p class="text-sm text-gray-600 font-semibold">TOTAL REGISTERED</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $registrations->total() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-600 font-semibold">CHECKED IN</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $checkedIn }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
        <p class="text-sm text-gray-600 font-semibold">NO SHOW</p>
        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $noShow }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <p class="text-sm text-gray-600 font-semibold">ATTENDANCE RATE</p>
        <p class="text-3xl font-bold text-purple-600 mt-2">
            {{ $registrations->total() > 0 ? round(($checkedIn / $registrations->total()) * 100) : 0 }}%
        </p>
    </div>
</div>

<!-- Export Button -->
<div class="container mx-auto px-4 mb-6 flex justify-end">
    <a href="{{ route('staff.events.attendance.export', $event->id) }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
        </svg>
        Export Attendance Report
    </a>
</div>

<!-- Attendance Table -->
<div class="container mx-auto px-4 mb-12">
    @if($registrations->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Table Header -->
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Check In</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Check Out</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Duration</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="divide-y divide-gray-200">
                    @foreach($registrations as $index => $registration)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $registrations->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($registration->user->first_name, 0, 1) }}{{ substr($registration->user->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-900">{{ $registration->user->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($registration->user->role) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->user->student_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($registration->checked_in_at)
                                    <div class="text-sm">
                                        <p class="font-medium text-green-600">✓ {{ $registration->checked_in_at->format('H:i A') }}</p>
                                        <p class="text-xs text-gray-500">{{ $registration->checked_in_at->format('M d') }}</p>
                                    </div>
                                @else
                                    <form action="{{ route('staff.events.attendance.check-in', [$event->id, $registration->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Check In
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($registration->checked_out_at)
                                    <div class="text-sm">
                                        <p class="font-medium text-red-600">✓ {{ $registration->checked_out_at->format('H:i A') }}</p>
                                        <p class="text-xs text-gray-500">{{ $registration->checked_out_at->format('M d') }}</p>
                                    </div>
                                @elseif($registration->checked_in_at)
                                    <form action="{{ route('staff.events.attendance.check-out', [$event->id, $registration->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Check Out
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($registration->checked_in_at && $registration->checked_out_at)
                                    <div class="font-medium text-gray-900">
                                        {{ $registration->checked_in_at->diffInHours($registration->checked_out_at) }}h
                                        {{ $registration->checked_in_at->diffInMinutes($registration->checked_out_at) % 60 }}m
                                    </div>
                                @elseif($registration->checked_in_at)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-medium">In Progress</span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(!$registration->checked_in_at)
                                    <button onclick="alert('Please check in first')" class="text-gray-400 cursor-not-allowed" disabled>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button class="text-gray-400 hover:text-red-600 transition-colors" onclick="if(confirm('Reset attendance for this user?')) { document.getElementById('reset-{{ $registration->id }}').submit(); }">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 1119.414 4.414a1 1 0 00-1.414-1.414A5.002 5.002 0 104.659 8.1V4a1 1 0 01-1-1V2a1 1 0 011-1zm3 5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $registrations->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Attendance Records</h3>
            <p class="text-gray-600">No confirmed registrations for this event.</p>
        </div>
    @endif
</div>
@endsection
