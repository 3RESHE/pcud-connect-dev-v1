@extends('layouts.admin')

@section('title', $event->title . ' - Event Details')

@section('content')
    <!-- Back Button (Desktop) -->
    <div class="mb-6 hidden md:block">
        <a href="{{ route('admin.approvals.events.index') }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Event Approvals
        </a>
    </div>

    <!-- Status Alert Banner -->
    <div
        class="mb-6 rounded-r-lg @if ($event->status === 'pending') bg-yellow-50 border-l-4 border-yellow-400
    @elseif($event->status === 'approved') bg-green-50 border-l-4 border-green-400
    @elseif($event->status === 'rejected') bg-red-50 border-l-4 border-red-400
    @elseif($event->status === 'published') bg-blue-50 border-l-4 border-blue-400
    @elseif($event->status === 'ongoing') bg-orange-50 border-l-4 border-orange-400
    @elseif($event->status === 'completed') bg-gray-50 border-l-4 border-gray-400
    @else bg-gray-50 border-l-4 border-gray-400 @endif p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                @if ($event->status === 'pending')
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($event->status === 'approved')
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($event->status === 'rejected')
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($event->status === 'ongoing')
                    <svg class="h-5 w-5 text-orange-400 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m0 0l-2-1m2 1v2.5M14 4l-2 1m0 0l-2-1m2 1v2.5"></path>
                    </svg>
                @else
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif
            </div>
            <div class="ml-3 flex-1">
                <h3
                    class="text-sm font-medium @if ($event->status === 'pending') text-yellow-800
                @elseif($event->status === 'approved') text-green-800
                @elseif($event->status === 'rejected') text-red-800
                @elseif($event->status === 'published') text-blue-800
                @elseif($event->status === 'ongoing') text-orange-800
                @elseif($event->status === 'completed') text-gray-800
                @else text-gray-800 @endif">
                    @if ($event->status === 'pending')
                        Event Awaiting Review
                    @elseif($event->status === 'approved')
                        Event Approved - Ready for Publication
                    @elseif($event->status === 'rejected')
                        Event Rejected
                    @elseif($event->status === 'published')
                        Event Published
                    @elseif($event->status === 'ongoing')
                        Event Currently Happening - Live
                    @elseif($event->status === 'completed')
                        Event Completed
                    @endif
                </h3>
                <p
                    class="mt-2 text-sm @if ($event->status === 'pending') text-yellow-700
                @elseif($event->status === 'approved') text-green-700
                @elseif($event->status === 'rejected') text-red-700
                @elseif($event->status === 'published') text-blue-700
                @elseif($event->status === 'ongoing') text-orange-700
                @elseif($event->status === 'completed') text-gray-700
                @else text-gray-700 @endif">
                    @if ($event->status === 'pending')
                        Please review this event submission and approve or reject it.
                    @elseif($event->status === 'approved')
                        This event has been approved and is ready for publication by staff.
                    @elseif($event->status === 'rejected')
                        This event was rejected. View the reason below.
                    @elseif($event->status === 'published')
                        This event is now live and visible to all users.
                    @elseif($event->status === 'ongoing')
                        Staff is managing live attendance tracking.
                    @elseif($event->status === 'completed')
                        View attendance records and event performance metrics below.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Event Banner -->
    <div
        class="relative @if ($event->status === 'pending') bg-gradient-to-r from-yellow-600 to-amber-600
    @elseif($event->status === 'approved') bg-gradient-to-r from-green-600 to-emerald-600
    @elseif($event->status === 'rejected') bg-gradient-to-r from-red-600 to-pink-600
    @elseif($event->status === 'published') bg-gradient-to-r from-blue-600 to-cyan-600
    @elseif($event->status === 'ongoing') bg-gradient-to-r from-orange-600 to-red-600
    @elseif($event->status === 'completed') bg-gradient-to-r from-gray-600 to-gray-700
    @else bg-gradient-to-r from-blue-600 to-cyan-600 @endif rounded-xl overflow-hidden mb-8 shadow-lg @if ($event->status === 'pending') border-l-4 border-yellow-500
    @elseif($event->status === 'approved') border-l-4 border-green-500
    @elseif($event->status === 'rejected') border-l-4 border-red-500
    @elseif($event->status === 'published') border-l-4 border-blue-500
    @elseif($event->status === 'ongoing') border-l-4 border-orange-500
    @elseif($event->status === 'completed') border-l-4 border-gray-500
    @else border-l-4 border-blue-500 @endif">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative px-8 py-16 text-center">
            <div class="flex justify-center mb-4 space-x-2">
                <span
                    class="@if ($event->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($event->status === 'approved') bg-green-100 text-green-800
                @elseif($event->status === 'rejected') bg-red-100 text-red-800
                @elseif($event->status === 'published') bg-blue-100 text-blue-800
                @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800 animate-pulse
                @elseif($event->status === 'completed') bg-gray-100 text-gray-800
                @else bg-blue-100 text-blue-800 @endif px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($event->status) }}
                </span>
                <span
                    class="@if ($event->event_format === 'inperson') bg-purple-100 text-purple-800
                @elseif($event->event_format === 'virtual') bg-indigo-100 text-indigo-800
                @else bg-pink-100 text-pink-800 @endif px-3 py-1 rounded-full text-sm font-medium">
                    @if ($event->event_format === 'inperson')
                        In-Person
                    @elseif($event->event_format === 'virtual')
                        Virtual
                    @else
                        Hybrid
                    @endif
                </span>
                @if ($event->is_featured)
                    <span
                        class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        Featured
                    </span>
                @endif
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">{{ $event->title }}</h1>
            <p class="text-gray-100 text-lg max-w-3xl mx-auto">{{ Str::limit($event->description, 150) }}</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Overview -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Overview</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Event Organizer</p>
                        <p class="font-medium text-gray-900">{{ $event->creator->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="font-medium text-gray-900">{{ $event->creator->department?->name ?? 'N/A' }}</p>
                    </div>
                    @if ($event->approved_by)
                        <div>
                            <p class="text-sm text-gray-500">Approved Date</p>
                            <p class="font-medium text-gray-900">{{ $event->updated_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Event Date</p>
                        <p class="font-medium text-gray-900">{{ $event->event_date->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                </div>
                @if ($event->event_tags)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach (explode(',', $event->event_tags) as $tag)
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Event Schedule -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Schedule</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-1">Start Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $event->event_date->format('M d, Y') }}</p>
                    </div>
                    @if ($event->is_multiday && $event->end_date)
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-1">End Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->end_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-1">Time</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $event->formatted_start_time }} -
                            {{ $event->formatted_end_time }}</p>
                    </div>
                    @if ($event->max_attendees)
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-1">Capacity</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->max_attendees }} participants</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Venue Details -->
            @if (in_array($event->event_format, ['inperson', 'hybrid']))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Venue & Location</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Venue</p>
                            <p class="font-semibold text-gray-900">{{ $event->venue_name ?? 'TBA' }}</p>
                        </div>
                        @if ($event->venue_address)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Address</p>
                                <p class="font-semibold text-gray-900">{{ $event->venue_address }}</p>
                            </div>
                        @endif
                        @if ($event->venue_capacity)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Capacity</p>
                                <p class="font-semibold text-gray-900">{{ $event->venue_capacity }} people</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Virtual Details -->
            @if (in_array($event->event_format, ['virtual', 'hybrid']))
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Virtual Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Platform</p>
                            <p class="font-semibold text-gray-900">
                                {{ $event->platform === 'other' ? $event->custom_platform : ucfirst($event->platform) }}
                            </p>
                        </div>
                        @if ($event->meeting_link)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Join Link</p>
                                <a href="{{ $event->meeting_link }}" target="_blank"
                                    class="text-blue-600 hover:underline font-medium">Open Meeting Link</a>
                            </div>
                        @endif
                        @if ($event->virtual_capacity)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Virtual Capacity</p>
                                <p class="font-semibold text-gray-900">{{ $event->virtual_capacity }} participants</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Target Audience -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Target Audience</h2>
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="font-semibold text-gray-900">Audience Type</p>
                        <p class="text-gray-700 capitalize">{{ str_replace('_', ' ', $event->target_audience) }}</p>
                    </div>
                    @if ($event->selected_departments)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="font-semibold text-gray-900 mb-2">Selected Departments</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach (json_decode($event->selected_departments) as $dept)
                                    <span
                                        class="px-3 py-1 bg-white text-gray-700 text-sm rounded-full border">{{ $dept }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Registration Settings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Registration Settings</h2>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="font-semibold text-gray-900">Registration Required</p>
                        <p class="text-gray-700">{{ $event->registration_required ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="font-semibold text-gray-900">Walk-in Allowed</p>
                        <p class="text-gray-700">{{ $event->walkin_allowed ? 'Yes' : 'No' }}</p>
                    </div>
                    @if ($event->registration_deadline)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="font-semibold text-gray-900">Registration Deadline</p>
                            <p class="text-gray-700">{{ $event->registration_deadline->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rejection Reason (if applicable) -->
            @if ($event->status === 'rejected' && $event->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-red-900 mb-2">Rejection Reason</h3>
                    <p class="text-red-800">{{ $event->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Event Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Event Status</h3>
                <div class="space-y-4">
                    <div
                        class="@if ($event->status === 'pending') p-4 bg-yellow-50 rounded-lg border border-yellow-200
                    @elseif($event->status === 'approved') p-4 bg-green-50 rounded-lg border border-green-200
                    @elseif($event->status === 'rejected') p-4 bg-red-50 rounded-lg border border-red-200
                    @elseif($event->status === 'published') p-4 bg-blue-50 rounded-lg border border-blue-200
                    @elseif($event->status === 'ongoing') p-4 bg-orange-50 rounded-lg border border-orange-200
                    @elseif($event->status === 'completed') p-4 bg-gray-50 rounded-lg border border-gray-200
                    @else p-4 bg-blue-50 rounded-lg border border-blue-200 @endif">
                        <p
                            class="text-lg font-bold @if ($event->status === 'pending') text-yellow-800
                        @elseif($event->status === 'approved') text-green-800
                        @elseif($event->status === 'rejected') text-red-800
                        @elseif($event->status === 'published') text-blue-800
                        @elseif($event->status === 'ongoing') text-orange-800
                        @elseif($event->status === 'completed') text-gray-800
                        @else text-blue-800 @endif">
                            @if ($event->status === 'pending')
                                Awaiting Review
                            @elseif($event->status === 'approved')
                                Approved - Ready to Publish
                            @elseif($event->status === 'rejected')
                                Rejected
                            @elseif($event->status === 'published')
                                Published & Live
                            @elseif($event->status === 'ongoing')
                                Currently Happening
                            @elseif($event->status === 'completed')
                                Completed Successfully
                            @endif
                        </p>
                    </div>

                    @if ($event->approved_by)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Approved By</p>
                            <p class="font-semibold text-gray-900">{{ $event->approver?->full_name ?? 'N/A' }}</p>
                        </div>
                    @endif

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Created On</p>
                        <p class="font-semibold text-gray-900">{{ $event->created_at->format('M d, Y g:i A') }}</p>
                    </div>

                    @if ($event->published_at)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Published On</p>
                            <p class="font-semibold text-gray-900">{{ $event->published_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if ($event->status === 'pending')
                        <button onclick="openApproveModal()"
                            class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Approve Event
                        </button>
                        <button onclick="openRejectModal()"
                            class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject Event
                        </button>
                    @elseif(in_array($event->status, ['published', 'ongoing', 'completed']))
                        @if ($event->is_featured)
                            <button onclick="openUnfeatureModal()"
                                class="w-full px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Unfeature Event
                            </button>
                        @else
                            <button onclick="openFeatureModal()"
                                class="w-full px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                Feature Event
                            </button>
                        @endif
                        @if ($event->status === 'published')
                            <button onclick="openUnpublishModal()"
                                class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Unpublish & Reject
                            </button>
                        @endif
                    @elseif($event->status === 'approved')
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                Event is approved and ready for staff to publish.
                            </p>
                        </div>
                    @elseif($event->status === 'rejected')
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <p class="text-sm text-red-800">Event has been rejected and cannot be modified.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Event Coordinator -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Event Coordinator</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-900">{{ $event->contact_person }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <a href="mailto:{{ $event->contact_email }}"
                            class="text-blue-600 hover:underline">{{ $event->contact_email }}</a>
                    </div>
                    @if ($event->contact_phone)
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <a href="tel:{{ $event->contact_phone }}"
                                class="text-blue-600 hover:underline">{{ $event->contact_phone }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Modal -->
    <div id="featureModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Feature Event</h3>
                    <button onclick="closeFeatureModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        Featured events appear on the homepage and receive more visibility. Are you sure you want to
                        feature this event?
                    </p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $event->title }}</p>
                </div>

                <div class="flex gap-3 mt-6">
                    <button onclick="closeFeatureModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button onclick="featureEvent()" id="featureButton"
                        class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        Feature Event
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unfeature Modal -->
    <div id="unfeatureModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Unfeature Event</h3>
                    <button onclick="closeUnfeatureModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        This event will no longer appear on the homepage. Are you sure?
                    </p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $event->title }}</p>
                </div>

                <div class="flex gap-3 mt-6">
                    <button onclick="closeUnfeatureModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button onclick="unfeatureEvent()" id="unfeatureButton"
                        class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Unfeature Event
                    </button>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">Are you sure you want to approve this event?</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $event->title }}</p>
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="closeApproveModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</button>
                    <button onclick="approveEvent()" id="approveButton"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Approve</button>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-3">Please provide a reason for rejecting this event:</p>
                    <textarea id="rejectionReason" rows="4"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter rejection reason (minimum 10 characters)..." required></textarea>
                    <p class="text-xs text-red-600 hidden" id="rejectionError">Rejection reason is required (minimum 10
                        characters)</p>
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</button>
                    <button onclick="rejectEvent()" id="rejectButton"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unpublish Modal -->
    <div id="unpublishModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Unpublish & Reject Event</h3>
                    <button onclick="closeUnpublishModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-3">This will unpublish and reject the event. Please provide a
                        reason:</p>
                    <textarea id="unpublishReason" rows="4"
                        class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter reason (minimum 10 characters)..." required></textarea>
                    <p class="text-xs text-red-600 hidden" id="unpublishError">Reason is required (minimum 10 characters)
                    </p>
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="closeUnpublishModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</button>
                    <button onclick="unpublishEvent()" id="unpublishButton"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Unpublish &
                        Reject</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Feature Modal Functions
        function openFeatureModal() {
            document.getElementById('featureModal').classList.remove('hidden');
        }

        function closeFeatureModal() {
            document.getElementById('featureModal').classList.add('hidden');
        }

        async function featureEvent() {
            const button = document.getElementById('featureButton');
            button.disabled = true;
            button.textContent = 'Featuring...';

            try {
                const response = await fetch(`{{ route('admin.events.feature', $event->id) }}`, {
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
                    alert(data.message || 'Failed to feature event');
                    button.disabled = false;
                    button.textContent = 'Feature Event';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
                button.disabled = false;
                button.textContent = 'Feature Event';
            }
        }

        // Unfeature Modal Functions
        function openUnfeatureModal() {
            document.getElementById('unfeatureModal').classList.remove('hidden');
        }

        function closeUnfeatureModal() {
            document.getElementById('unfeatureModal').classList.add('hidden');
        }

        async function unfeatureEvent() {
            const button = document.getElementById('unfeatureButton');
            button.disabled = true;
            button.textContent = 'Unfeaturing...';

            try {
                const response = await fetch(`{{ route('admin.events.unfeature', $event->id) }}`, {
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
                    alert(data.message || 'Failed to unfeature event');
                    button.disabled = false;
                    button.textContent = 'Unfeature Event';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
                button.disabled = false;
                button.textContent = 'Unfeature Event';
            }
        }

        // Approve Modal Functions
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
                const response = await fetch(`{{ route('admin.approvals.events.approve', $event->id) }}`, {
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

        // Reject Modal Functions
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
                const response = await fetch(`{{ route('admin.approvals.events.reject', $event->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rejection_reason: reason
                    })
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

        // Unpublish Modal Functions
        function openUnpublishModal() {
            document.getElementById('unpublishModal').classList.remove('hidden');
        }

        function closeUnpublishModal() {
            document.getElementById('unpublishModal').classList.add('hidden');
            document.getElementById('unpublishReason').value = '';
            document.getElementById('unpublishError').classList.add('hidden');
        }

        async function unpublishEvent() {
            const reason = document.getElementById('unpublishReason').value.trim();
            const errorElement = document.getElementById('unpublishError');

            if (reason.length < 10) {
                errorElement.classList.remove('hidden');
                return;
            }

            errorElement.classList.add('hidden');
            const button = document.getElementById('unpublishButton');
            button.disabled = true;
            button.textContent = 'Processing...';

            try {
                const response = await fetch(`{{ route('admin.approvals.events.unpublish', $event->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        unpublish_reason: reason
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to unpublish event');
                    button.disabled = false;
                    button.textContent = 'Unpublish & Reject';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
                button.disabled = false;
                button.textContent = 'Unpublish & Reject';
            }
        }

        // Close modals on ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFeatureModal();
                closeUnfeatureModal();
                closeApproveModal();
                closeRejectModal();
                closeUnpublishModal();
            }
        });
    </script>
@endsection
