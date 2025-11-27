@extends('layouts.staff')

@section('title', 'Event Details - PCU-DASMA Connect')

@section('content')
<!-- Back Button -->
<div class="mb-8">
    <a href="{{ route('staff.events.index') }}" class="inline-flex items-center text-base text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to My Events
    </a>
</div>

<!-- STATUS ALERTS -->
@php
    $statusConfig = [
        'draft' => ['bg' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Event Saved as Draft', 'message' => 'This event is still in draft mode. You can continue editing it or submit it for admin review when ready.'],
        'pending' => ['bg' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Event Submitted for Review', 'message' => 'This event was submitted on ' . $event->created_at->format('F j, Y') . ' and is currently under administrator review.'],
        'approved' => ['bg' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Event Approved - Ready to Publish', 'message' => 'This event was approved by the administrator. You can now publish this event to make it visible to students and start accepting registrations.'],
        'rejected' => ['bg' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12', 'title' => 'Event Rejected', 'message' => $event->rejection_reason ?? 'This event has been rejected. Please review the feedback and make necessary changes.'],
        'published' => ['bg' => 'blue', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Event Published - Now Live', 'message' => 'This event is now visible to all students and alumni. Registrations are ' . ($event->registration_required ? 'being accepted' : 'not required for this event') . '.'],
        'ongoing' => ['bg' => 'purple', 'icon' => 'M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m2-1l-2-1m2 1v2.5', 'title' => 'Event in Progress', 'message' => 'This event is currently happening. You can manage attendance and view real-time participant information.'],
        'completed' => ['bg' => 'gray', 'icon' => 'M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Event Completed', 'message' => 'This event has finished. You can view the final attendance records and event statistics.']
    ];

    $config = $statusConfig[$event->status] ?? $statusConfig['pending'];
    $bgClass = "bg-{$config['bg']}-50 border-{$config['bg']}-400";
    $textClass = "text-{$config['bg']}-800 text-{$config['bg']}-700";
@endphp

<div class="{{ $bgClass }} border-l-4 p-6 mb-8 rounded-r-lg">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-{{ $config['bg'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
            </svg>
        </div>
        <div class="ml-4 flex-1">
            <h3 class="text-base font-medium {{ $textClass }}">{{ $config['title'] }}</h3>
            <p class="mt-2 text-base {{ $textClass }}">{{ $config['message'] }}</p>
        </div>
    </div>
</div>

<!-- Event Banner -->
<div class="relative bg-gradient-to-r @switch($event->status) @case('draft') from-gray-600 to-slate-600 @break @case('pending') from-yellow-600 to-amber-600 @break @case('approved') from-green-600 to-teal-600 @break @case('rejected') from-red-600 to-pink-600 @break @case('published') from-blue-600 to-purple-600 @break @case('ongoing') from-purple-600 to-indigo-600 @break @default from-gray-600 to-slate-600 @endswitch rounded-xl overflow-hidden mb-8 shadow-lg">
    @if($event->event_image)
        <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-80 object-cover absolute inset-0">
    @endif
    <div class="absolute inset-0 bg-black opacity-40"></div>
    <div class="relative px-8 py-16 text-center">
        <div class="flex justify-center mb-4 space-x-3 flex-wrap">
            <span class="@switch($event->status) @case('draft') bg-gray-100 text-gray-800 @break @case('pending') bg-yellow-100 text-yellow-800 @break @case('approved') bg-green-100 text-green-800 @break @case('rejected') bg-red-100 text-red-800 @break @case('published') bg-blue-100 text-blue-800 @break @case('ongoing') bg-purple-100 text-purple-800 @break @default bg-gray-100 text-gray-800 @endswitch px-4 py-1 rounded-full text-base font-medium">
                @switch($event->status)
                    @case('draft') Draft @break
                    @case('pending') Under Review @break
                    @case('approved') Approved @break
                    @case('published') Published @break
                    @case('rejected') Rejected @break
                    @case('ongoing') Ongoing @break
                    @case('completed') Completed @break
                    @default Draft
                @endswitch
            </span>
            <span class="bg-green-100 text-green-800 px-4 py-1 rounded-full text-base font-medium">
                {{ ucfirst(str_replace('_', ' ', $event->event_format)) }}
            </span>
            @if($event->registration_required)
                <span class="bg-blue-100 text-blue-800 px-4 py-1 rounded-full text-base font-medium">Registration Required</span>
            @endif
        </div>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $event->title }}</h1>
        <p class="text-blue-100 text-lg max-w-3xl mx-auto">{{ $event->description }}</p>
    </div>
</div>

<!-- Action Buttons -->
<div class="mb-8 flex justify-end gap-4 flex-wrap">
    @if($event->status === 'draft')
        <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </form>
        <a href="{{ route('staff.events.edit', $event->id) }}" class="px-8 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Event
        </a>

    @elseif($event->status === 'pending')
        <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </form>
        <a href="{{ route('staff.events.edit', $event->id) }}" class="px-8 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Event
        </a>

    @elseif($event->status === 'approved')
        <form action="{{ route('staff.events.publish', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Publish Event
            </button>
        </form>

    @elseif($event->status === 'rejected')
        <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </form>
        <a href="{{ route('staff.events.edit', $event->id) }}" class="px-8 py-3 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Edit & Resubmit
        </a>

    @elseif($event->status === 'published')
        <a href="{{ route('staff.events.registrations.index', $event->id) }}" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
            </svg>
            Manage Registrations
        </a>
        <form action="{{ route('staff.events.mark-ongoing', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="px-8 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mark Ongoing
            </button>
        </form>

    @elseif($event->status === 'ongoing')
        <!-- ✅ NEW: MARK ATTENDANCE BUTTON FOR ONGOING EVENTS -->
        <a href="{{ route('staff.events.attendance.mark', $event->id) }}" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mark Attendance
        </a>
        <form action="{{ route('staff.events.mark-completed', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="px-8 py-3 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 flex items-center text-base transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Mark Completed
            </button>
        </form>

    @elseif($event->status === 'completed')
        <!-- ✅ NEW: VIEW ATTENDANCE BUTTON FOR COMPLETED EVENTS -->
        <a href="{{ route('staff.events.attendance.view', $event->id) }}" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 flex items-center text-base transition-colors">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            View Attendance Records
        </a>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Event Information -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Event Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-base text-gray-500">Event Title</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $event->title }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-500">Event Format</p>
                    <p class="font-medium text-gray-900 text-lg">{{ ucfirst(str_replace('_', ' ', $event->event_format)) }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-500">Submitted Date</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $event->created_at->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-500">Contact Person</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $event->contact_person }}</p>
                </div>
            </div>
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-900 text-lg mb-3">Description</h3>
                <p class="text-gray-700 text-base leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
            </div>
            @if($event->event_tags)
                <div class="mt-6 flex flex-wrap gap-3">
                    @foreach(explode(',', $event->event_tags) as $tag)
                        <span class="px-4 py-1 bg-blue-100 text-blue-800 text-base rounded-full">#{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Date & Time -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Date & Time
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-{{ $event->is_multiday ? '3' : '2' }} gap-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-base font-medium text-gray-700">{{ $event->is_multiday ? 'Start Date' : 'Event Date' }}</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ $event->event_date->format('F j, Y') }}</p>
                </div>

                @if($event->is_multiday && $event->end_date)
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-center mb-2">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-base font-medium text-gray-700">End Date</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y') }}</p>
                    </div>
                @endif

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-base font-medium text-gray-700">Time</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Location & Venue -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Location & Venue
            </h2>
            @if($event->event_format === 'inperson' || $event->event_format === 'hybrid')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-base text-gray-500 mb-1">Venue Name</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->venue_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-base text-gray-500 mb-1">Venue Capacity</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->venue_capacity ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-base text-gray-500 mb-1">Venue Address</p>
                        <p class="text-gray-700 font-medium text-lg">{{ $event->venue_address ?? 'N/A' }}</p>
                    </div>
                </div>
            @endif

            @if($event->event_format === 'virtual' || $event->event_format === 'hybrid')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-base text-gray-500 mb-1">Platform</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->platform ? ucfirst($event->platform) : ($event->custom_platform ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-base text-gray-500 mb-1">Virtual Capacity</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->virtual_capacity ?? 'Unlimited' }}</p>
                    </div>
                    @if($event->meeting_link)
                        <div class="md:col-span-2">
                            <p class="text-base text-gray-500 mb-1">Meeting Link</p>
                            <a href="{{ $event->meeting_link }}" target="_blank" class="text-blue-600 font-medium text-lg break-all hover:underline">{{ $event->meeting_link }}</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Registration Settings -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path>
                </svg>
                Registration Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <span class="text-base font-medium text-gray-700">Registration Required</span>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $event->registration_required ? '✓ Yes' : '✗ No' }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <span class="text-base font-medium text-gray-700">Walk-In Registration</span>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $event->walkin_allowed ? '✓ Allowed' : '✗ Not Allowed' }}</p>
                </div>
                @if($event->registration_deadline)
                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                        <span class="text-base font-medium text-gray-700">Registration Deadline</span>
                        <p class="text-lg font-semibold text-gray-900 mt-2">{{ \Carbon\Carbon::parse($event->registration_deadline)->format('F j, Y') }}</p>
                    </div>
                @endif
                @if($event->max_attendees)
                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                        <span class="text-base font-medium text-gray-700">Maximum Attendees</span>
                        <p class="text-lg font-semibold text-gray-900 mt-2">{{ $event->max_attendees }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Target Audience -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Target Audience
            </h2>
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                <p class="font-semibold text-gray-900 text-lg">
                    @switch($event->target_audience)
                        @case('allstudents') 👥 All Students @break
                        @case('alumni') 🎓 Alumni @break
                        @case('openforall') 🌐 Open for All (Students, Alumni & Public) @break
                        @default 👥 All Students
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Event Status -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h3 class="font-bold text-gray-900 text-xl mb-6">Event Status</h3>
            <div class="space-y-6">
                <div class="p-4 @switch($event->status) @case('draft') bg-gray-50 border border-gray-200 @break @case('pending') bg-yellow-50 border border-yellow-200 @break @case('approved') bg-green-50 border border-green-200 @break @case('rejected') bg-red-50 border border-red-200 @break @case('published') bg-blue-50 border border-blue-200 @break @case('ongoing') bg-purple-50 border border-purple-200 @break @default bg-gray-50 @endswitch rounded-lg">
                    <p class="text-lg font-bold @switch($event->status) @case('draft') text-gray-800 @break @case('pending') text-yellow-800 @break @case('approved') text-green-800 @break @case('rejected') text-red-800 @break @case('published') text-blue-800 @break @case('ongoing') text-purple-800 @break @default text-gray-800 @endswitch">
                        @switch($event->status)
                            @case('draft') 📝 Draft @break
                            @case('pending') 🕐 Under Review @break
                            @case('approved') ✓ Approved @break
                            @case('published') 🚀 Published @break
                            @case('rejected') ✗ Rejected @break
                            @case('ongoing') ▶️ Ongoing @break
                            @case('completed') ✓ Completed @break
                            @default Draft
                        @endswitch
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-base text-gray-500 mb-1">Current Registrations</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $event->registrations()->count() }} participants</p>
                    @if($event->max_attendees)
                        <div class="w-full bg-gray-300 rounded-full h-2 mt-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($event->registrations()->count() / $event->max_attendees) * 100) }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h3 class="font-bold text-gray-900 text-xl mb-6">Contact Information</h3>
            <div class="space-y-6">
                <div>
                    <p class="text-base text-gray-500">Contact Person</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $event->contact_person }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-500">Contact Email</p>
                    <a href="mailto:{{ $event->contact_email }}" class="text-blue-600 hover:underline text-lg break-all">{{ $event->contact_email }}</a>
                </div>
                @if($event->contact_phone)
                    <div>
                        <p class="text-base text-gray-500">Contact Phone</p>
                        <a href="tel:{{ $event->contact_phone }}" class="text-blue-600 hover:underline text-lg">{{ $event->contact_phone }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
