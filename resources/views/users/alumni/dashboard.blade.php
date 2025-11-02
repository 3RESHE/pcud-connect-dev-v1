@extends('layouts.app')

@section('title', 'Alumni Dashboard')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-8">Alumni Dashboard</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Job Applications</div>
            <div class="text-3xl font-bold">{{ $total_applications }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Pending</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pending_applications }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Approved</div>
            <div class="text-3xl font-bold text-green-600">{{ $approved_applications }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Registered Events</div>
            <div class="text-3xl font-bold">{{ $total_event_registrations }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Notifications</div>
            <div class="text-3xl font-bold text-blue-600">{{ $unread_notifications }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('alumni.jobs.index') }}" class="bg-blue-600 text-white p-4 rounded text-center hover:bg-blue-700">
            Browse Jobs
        </a>
        <a href="{{ route('alumni.events.index') }}" class="bg-green-600 text-white p-4 rounded text-center hover:bg-green-700">
            Find Events
        </a>
        <a href="{{ route('alumni.profile') }}" class="bg-purple-600 text-white p-4 rounded text-center hover:bg-purple-700">
            My Profile
        </a>
        <a href="{{ route('alumni.notifications.index') }}" class="bg-orange-600 text-white p-4 rounded text-center hover:bg-orange-700">
            Notifications
        </a>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Upcoming Events</h2>
        <ul class="space-y-2">
            @foreach ($upcoming_events as $registration)
                <li class="flex justify-between items-center border-b pb-2">
                    <div>
                        <div class="font-bold">{{ $registration->event->title }}</div>
                        <div class="text-sm text-gray-600">{{ $registration->event->event_date->format('M d, Y h:i A') }}</div>
                    </div>
                    <span class="text-sm px-2 py-1 rounded bg-green-100 text-green-800">Registered</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
