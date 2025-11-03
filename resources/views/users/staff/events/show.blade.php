@extends('layouts.staff')

@section('title', 'Event Details - PCU-DASMA Connect')

@section('content')
<!-- Header with Back Button -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('staff.events.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="text-gray-600 mt-1">Event ID: {{ $event->id }}</p>
        </div>
    </div>
</div>

<!-- Status Banner - Color Coded by Status -->
<div class="mb-8">
    @if($event->status === 'draft')
        <div class="bg-gray-100 border-l-4 border-gray-500 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.927a3 3 0 01-.856 2.122L7.793 17.586a1 1 0 01-1.414-1.414L10.31 11.35a1 1 0 00.293-.707V6.517a1.066 1.066 0 00-.937-1.021 2 2 0 00-1.164.484 3.066 3.066 0 01-3.976 0 2 2 0 00-1.164-.484 1.066 1.066 0 00-.937 1.021v4.043a1 1 0 00.293.707l3.071 3.071a1 1 0 01-1.414 1.414L2.293 10.82A3 3 0 011.87 8.584V6.517A3.066 3.066 0 016.267 3.455z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-800">Draft - Not Yet Submitted</h3>
                    <p class="text-sm text-gray-700 mt-1">This event is in draft mode. You can edit or submit it for admin review.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'pending')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Under Review</h3>
                    <p class="text-sm text-yellow-700 mt-1">Your event is waiting for admin approval. You can withdraw the submission if needed.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'approved')
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Approved - Ready to Publish</h3>
                    <p class="text-sm text-green-700 mt-1">Your event has been approved by the admin. Click the Publish button to make it live.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'published')
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Published - Live Event</h3>
                    <p class="text-sm text-blue-700 mt-1">Your event is now live! Registrations are open. You can manage registrations and view statistics.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'ongoing')
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-5 h-5 rounded-full bg-orange-600 animate-pulse"></div>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">🔴 Live Now - Event in Progress</h3>
                    <p class="text-sm text-orange-700 mt-1">Your event is currently happening! You can manage check-ins and track live attendance.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'completed')
        <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.707 6.707a1 1 0 010 1.414L5.414 9l1.293 1.293a1 1 0 01-1.414 1.414l-2-2a1 1 0 010-1.414l2-2a1 1 0 011.414 0zm7.586 0a1 1 0 011.414 0l2 2a1 1 0 010 1.414l-2 2a1 1 0 11-1.414-1.414L14.586 9l-1.293-1.293a1 1 0 010-1.414zM9 11a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-800">Completed</h3>
                    <p class="text-sm text-gray-700 mt-1">Your event has ended. View the final attendance report and send feedback surveys.</p>
                </div>
            </div>
        </div>
    @elseif($event->status === 'cancelled')
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Cancelled</h3>
                    <p class="text-sm text-red-700 mt-1">This event has been cancelled. Registrants will be notified.</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Event Stats Cards (Status-Specific) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Event Date Card -->
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Event Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $event->event_date->format('M d, Y') }}</p>
            </div>
            <svg class="w-10 h-10 text-blue-100" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6 2a1 1 0 000 2h8a1 1 0 100-2H6zM4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"></path>
            </svg>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Current Status</p>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
            </div>
            <svg class="w-10 h-10 text-purple-100" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c4.768-4.768 12.616-4.768 17.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>

    <!-- Registrations Card (if published/ongoing) -->
    @if(in_array($event->status, ['published', 'ongoing', 'completed']))
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Registrations</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $event->registrations->count() }}/{{ $event->max_attendees ?? '∞' }}
                    </p>
                </div>
                <svg class="w-10 h-10 text-green-100" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 16c0-2.09-1.179-3.903-2.927-4.872A6.967 6.967 0 0110 16c0 .888.146 1.741.428 2.526a7.006 7.006 0 01-1.428-2.526zM13 13a1 1 0 100-2 1 1 0 000 2zM18 9a1 1 0 100-2 1 1 0 000 2zM14 12a1 1 0 100-2 1 1 0 000 2z"></path>
                </svg>
            </div>
        </div>
    @endif

    <!-- Attendance Card (if ongoing/completed) -->
    @if(in_array($event->status, ['ongoing', 'completed']))
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Checked In</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $event->attendance->where('checked_in_at', '!=', null)->count() }}/{{ $event->registrations->count() }}
                    </p>
                </div>
                <svg class="w-10 h-10 text-orange-100" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    @endif
</div>

<!-- Main Content - Two Column Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column - Event Details (Spans 2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Featured Image -->
        @if($event->featured_image)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Banner</h3>
                <img src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded-lg">
            </div>
        @endif

        <!-- Event Description -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
            <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
        </div>

        <!-- Event Details -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Format</label>
                    <p class="text-gray-900 mt-1">
                        @if($event->event_format === 'in_person')
                            In-Person
                        @elseif($event->event_format === 'virtual')
                            Virtual/Online
                        @elseif($event->event_format === 'hybrid')
                            Hybrid
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Target Audience</label>
                    <p class="text-gray-900 mt-1">
                        @if($event->target_audience === 'all_students')
                            All Students
                        @elseif($event->target_audience === 'alumni')
                            Alumni
                        @elseif($event->target_audience === 'open_for_all')
                            Open for All
                        @endif
                    </p>
                </div>

                <!-- In-Person Details -->
                @if(in_array($event->event_format, ['in_person', 'hybrid']))
                    <div>
                        <label class="text-sm font-medium text-gray-600">Venue Name</label>
                        <p class="text-gray-900 mt-1">{{ $event->venue_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Capacity</label>
                        <p class="text-gray-900 mt-1">{{ $event->venue_capacity }} attendees</p>
                    </div>
                    @if($event->venue_address)
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Address</label>
                            <p class="text-gray-900 mt-1">{{ $event->venue_address }}</p>
                        </div>
                    @endif
                @endif

                <!-- Virtual Details -->
                @if(in_array($event->event_format, ['virtual', 'hybrid']))
                    <div>
                        <label class="text-sm font-medium text-gray-600">Platform</label>
                        <p class="text-gray-900 mt-1">
                            @if($event->platform === 'zoom') Zoom
                            @elseif($event->platform === 'teams') Microsoft Teams
                            @elseif($event->platform === 'meet') Google Meet
                            @elseif($event->platform === 'webex') Webex
                            @elseif($event->platform === 'facebook_live') Facebook Live
                            @elseif($event->platform === 'youtube_live') YouTube Live
                            @elseif($event->platform === 'other') {{ $event->custom_platform }}
                            @endif
                        </p>
                    </div>
                    @if($event->meeting_link)
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Meeting Link</label>
                            <p class="text-gray-900 mt-1">
                                <a href="{{ $event->meeting_link }}" target="_blank" class="text-primary hover:text-blue-700 break-all">
                                    {{ $event->meeting_link }}
                                </a>
                            </p>
                        </div>
                    @endif
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-600">Registration Required</label>
                    <p class="text-gray-900 mt-1">{{ $event->registration_required ? 'Yes' : 'No' }}</p>
                </div>

                @if($event->registration_required && $event->registration_deadline)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Registration Deadline</label>
                        <p class="text-gray-900 mt-1">{{ $event->registration_deadline->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Contact Person</label>
                    <p class="text-gray-900">{{ $event->contact_person }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900">
                        <a href="mailto:{{ $event->contact_email }}" class="text-primary hover:text-blue-700">
                            {{ $event->contact_email }}
                        </a>
                    </p>
                </div>
                @if($event->contact_phone)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Phone</label>
                        <p class="text-gray-900">{{ $event->contact_phone }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Special Instructions -->
        @if($event->special_instructions)
            <div class="bg-blue-50 rounded-lg shadow-sm p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Registration Instructions</h3>
                <p class="text-gray-700 leading-relaxed">{{ $event->special_instructions }}</p>
            </div>
        @endif
    </div>

    <!-- Right Column - Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Timeline Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
            <div class="space-y-4">
                <!-- Created -->
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="w-0.5 h-12 bg-gray-300"></div>
                    </div>
                    <div class="flex-1 pb-4">
                        <p class="text-sm font-medium text-gray-900">Created</p>
                        <p class="text-xs text-gray-500">{{ $event->created_at->format('M d, Y - g:i A') }}</p>
                    </div>
                </div>

                <!-- Status Update -->
                @if($event->status !== 'draft')
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full
                                @if($event->status === 'pending') bg-yellow-100
                                @elseif(in_array($event->status, ['approved', 'published', 'ongoing', 'completed'])) bg-green-100
                                @elseif($event->status === 'cancelled') bg-red-100
                                @else bg-gray-100
                                @endif
                                flex items-center justify-center">
                                <svg class="w-4 h-4
                                    @if($event->status === 'pending') text-yellow-600
                                    @elseif(in_array($event->status, ['approved', 'published', 'ongoing', 'completed'])) text-green-600
                                    @elseif($event->status === 'cancelled') text-red-600
                                    @else text-gray-600
                                    @endif" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @if($event->status !== 'completed' && $event->status !== 'cancelled')
                                <div class="w-0.5 h-12 bg-gray-300"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
                            <p class="text-xs text-gray-500">
                                @if($event->status === 'pending')
                                    Submitted for review
                                @elseif($event->status === 'approved')
                                    Approved by admin
                                @elseif($event->status === 'published')
                                    Now live
                                @elseif($event->status === 'ongoing')
                                    Event is happening
                                @elseif($event->status === 'completed')
                                    Event finished
                                @elseif($event->status === 'cancelled')
                                    Event cancelled
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-2">
                @if($event->status === 'draft')
                    <!-- Draft Actions -->
                    <a href="{{ route('staff.events.edit', $event->id) }}" class="block w-full px-4 py-2 border border-primary text-primary rounded-lg hover:bg-blue-50 text-center font-medium transition-colors duration-200">
                        ✏️ Edit Event
                    </a>
                    <button onclick="submitForReview()" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                        ➡️ Submit for Review
                    </button>
                    <button onclick="deleteEvent()" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition-colors duration-200">
                        🗑️ Delete Event
                    </button>

                @elseif($event->status === 'pending')
                    <!-- Pending Actions -->
                    <a href="{{ route('staff.events.edit', $event->id) }}" class="block w-full px-4 py-2 border border-primary text-primary rounded-lg hover:bg-blue-50 text-center font-medium transition-colors duration-200">
                        ✏️ Edit Event
                    </a>
                    <button onclick="withdrawSubmission()" class="w-full px-4 py-2 border border-yellow-300 text-yellow-600 rounded-lg hover:bg-yellow-50 font-medium transition-colors duration-200">
                        ↩️ Withdraw Submission
                    </button>

                @elseif($event->status === 'approved')
                    <!-- Approved Actions -->
                    <button onclick="publishEvent()" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                        🚀 Publish Event
                    </button>
                    <button onclick="cancelEvent()" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition-colors duration-200">
                        ❌ Cancel Event
                    </button>

                @elseif($event->status === 'published')
                    <!-- Published Actions -->
                    <a href="{{ route('staff.events.registrations', $event->id) }}" class="block w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center font-medium transition-colors duration-200">
                        👥 Manage Registrations
                    </a>
                    <button onclick="cancelEvent()" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition-colors duration-200">
                        ❌ Cancel Event
                    </button>

                @elseif($event->status === 'ongoing')
                    <!-- Ongoing Actions -->
                    <a href="{{ route('staff.events.registrations', $event->id) }}" class="block w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center font-medium transition-colors duration-200">
                        👥 Manage Registrations
                    </a>
                    <a href="{{ route('staff.events.attendance', $event->id) }}" class="block w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-center font-medium transition-colors duration-200">
                        ✅ Check-In Attendees
                    </a>
                    <button onclick="endEvent()" class="w-full px-4 py-2 border border-orange-300 text-orange-600 rounded-lg hover:bg-orange-50 font-medium transition-colors duration-200">
                        🏁 End Event
                    </button>

                @elseif($event->status === 'completed')
                    <!-- Completed Actions -->
                    <a href="{{ route('staff.events.attendance', $event->id) }}" class="block w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-center font-medium transition-colors duration-200">
                        📊 View Attendance Report
                    </a>
                    <button onclick="exportAttendance()" class="w-full px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 font-medium transition-colors duration-200">
                        📥 Export Attendee List
                    </button>

                @elseif($event->status === 'cancelled')
                    <!-- Cancelled - No Actions -->
                    <div class="text-center py-4">
                        <p class="text-gray-600">Event has been cancelled. No further actions available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Registrations Section (Published/Ongoing/Completed) -->
@if(in_array($event->status, ['published', 'ongoing', 'completed']))
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Quick Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-600">Total Registrations</p>
                <p class="text-2xl font-bold text-blue-600">{{ $event->registrations->count() }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <p class="text-sm text-gray-600">Confirmed</p>
                <p class="text-2xl font-bold text-green-600">{{ $event->registrations->where('status', 'confirmed')->count() }}</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <p class="text-sm text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-orange-600">{{ $event->registrations->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Confirmation Modals -->

<!-- Submit for Review Modal -->
<div id="submitModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit Event for Review?</h3>
            <p class="text-gray-700 mb-6">Once submitted, this event will be reviewed by the admin. You can still edit it while it's under review.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('submitModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <form action="{{ route('staff.events.submit', $event->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Publish Event Modal -->
<div id="publishModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish Event?</h3>
            <p class="text-gray-700 mb-6">This will make the event live and registrations will open immediately. Are you sure?</p>
            <div class="flex gap-3">
                <button onclick="closeModal('publishModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <form action="{{ route('staff.events.publish', $event->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Publish
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Event Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cancel Event?</h3>
            <p class="text-gray-700 mb-2">This action cannot be undone. All registrations will be cancelled and registrants will be notified.</p>
            <p class="text-gray-700 mb-6">Please provide a reason:</p>
            <form action="{{ route('staff.events.cancel', $event->id) }}" method="POST">
                @csrf
                <textarea name="cancellation_reason" rows="3" placeholder="Reason for cancellation..." class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-primary focus:border-primary" required></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('cancelModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Don't Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Cancel Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- End Event Modal -->
<div id="endModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">End Event?</h3>
            <p class="text-gray-700 mb-6">Mark this event as completed. You will then be able to view attendance reports.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('endModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Keep Going
                </button>
                <form action="{{ route('staff.events.end', $event->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                        End Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Event Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delete Event?</h3>
            <p class="text-red-600 font-semibold mb-4">⚠️ This action cannot be undone!</p>
            <p class="text-gray-700 mb-6">All event data will be permanently deleted.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('deleteModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Keep It
                </button>
                <form action="{{ route('staff.events.destroy', $event->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Submission Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Withdraw Submission?</h3>
            <p class="text-gray-700 mb-6">The event will return to draft status and can be edited again.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('withdrawModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </button>
                <form action="{{ route('staff.events.withdraw', $event->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                        Withdraw
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function submitForReview() {
    document.getElementById('submitModal').classList.remove('hidden');
}

function publishEvent() {
    document.getElementById('publishModal').classList.remove('hidden');
}

function cancelEvent() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function endEvent() {
    document.getElementById('endModal').classList.remove('hidden');
}

function deleteEvent() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function withdrawSubmission() {
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function exportAttendance() {
    // Trigger attendance export (will be handled by controller)
    window.location.href = "{{ route('staff.events.export-attendance', $event->id) }}";
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>

@endsection
