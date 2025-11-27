@extends('layouts.staff')

@section('title', 'Create New Event - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('staff.events.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
    </div>
    <p class="text-gray-600">Create university-organized events for admin review</p>
</div>

<!-- Global Alert Messages -->
@if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-5 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-sm font-semibold text-red-800 mb-3">
                    We found {{ count($errors->all()) }} {{ count($errors->all()) == 1 ? 'error' : 'errors' }} preventing event creation:
                </h3>
                <ul class="space-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start text-sm text-red-700">
                            <svg class="h-4 w-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Success Message -->
@if (session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-5 shadow-sm">
        <div class="flex items-center">
            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
@endif

<!-- Form Container -->
<div class="bg-white shadow-sm rounded-lg">
    <form action="{{ route('staff.events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm" class="divide-y divide-gray-200" novalidate>
        @csrf

        <!-- Basic Information -->
        <div class="px-6 py-6">
            <div class="flex items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Event Information</h2>
                <span class="ml-2 text-sm text-gray-500">(Required fields marked with *)</span>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <!-- Event Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        value="{{ old('title') }}"
                        class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                        @error('title') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        placeholder="Enter event title..."
                    />
                    @error('title')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                        @error('description') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        placeholder="Provide a detailed description of the event..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Event Format -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Format <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="event_format"
                            name="event_format"
                            required
                            onchange="toggleLocationFields()"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('event_format') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        >
                            <option value="">-- Select Format --</option>
                            <option value="inperson" {{ old('event_format') == 'inperson' ? 'selected' : '' }}>In-Person</option>
                            <option value="virtual" {{ old('event_format') == 'virtual' ? 'selected' : '' }}>Virtual/Online</option>
                            <option value="hybrid" {{ old('event_format') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        @error('event_format')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Date & Time</h2>
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="event_date"
                            name="event_date"
                            required
                            value="{{ old('event_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('event_date') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('event_date')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                id="is_multiday"
                                name="is_multiday"
                                onchange="toggleEndDate()"
                                {{ old('is_multiday') ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-3 text-sm font-medium text-gray-700">Multi-day event</span>
                        </label>
                    </div>
                </div>

                <!-- End Date -->
                <div id="end_date_field" class="hidden">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date
                    </label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        min="{{ date('Y-m-d') }}"
                        class="block w-full md:w-1/2 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                    />
                </div>

                <!-- Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            required
                            value="{{ old('start_time') }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('start_time') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('start_time')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            required
                            value="{{ old('end_time') }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('end_time') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('end_time')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Location & Venue</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- In-Person Location -->
                <div id="in_person_location">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Venue Name <span class="text-red-500">*</span> <span class="text-gray-500 font-normal">(Required for in-person)</span>
                            </label>
                            <input
                                type="text"
                                id="venue_name"
                                name="venue_name"
                                value="{{ old('venue_name') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                                @error('venue_name') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                                placeholder="e.g., PCU-DASMA Auditorium"
                            />
                            @error('venue_name')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="venue_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Venue Capacity <span class="text-red-500">*</span> <span class="text-gray-500 font-normal">(Required for in-person)</span>
                            </label>
                            <input
                                type="number"
                                id="venue_capacity"
                                name="venue_capacity"
                                min="1"
                                value="{{ old('venue_capacity') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                                @error('venue_capacity') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                                placeholder="e.g., 150"
                            />
                            @error('venue_capacity')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="venue_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Venue Address
                        </label>
                        <textarea
                            id="venue_address"
                            name="venue_address"
                            rows="3"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                            placeholder="Complete address of the venue..."
                        >{{ old('venue_address') }}</textarea>
                    </div>
                </div>

                <!-- Virtual Location -->
                <div id="virtual_location" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700 mb-2">
                                Platform <span class="text-red-500">*</span> <span class="text-gray-500 font-normal">(Required for virtual)</span>
                            </label>
                            <select
                                id="platform"
                                name="platform"
                                onchange="toggleCustomPlatform()"
                                class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                                @error('platform') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                            >
                                <option value="">-- Select Platform --</option>
                                <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="teams" {{ old('platform') == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                <option value="meet" {{ old('platform') == 'meet' ? 'selected' : '' }}>Google Meet</option>
                                <option value="webex" {{ old('platform') == 'webex' ? 'selected' : '' }}>Webex</option>
                                <option value="facebook_live" {{ old('platform') == 'facebook_live' ? 'selected' : '' }}>Facebook Live</option>
                                <option value="youtube_live" {{ old('platform') == 'youtube_live' ? 'selected' : '' }}>YouTube Live</option>
                                <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('platform')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="virtual_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Virtual Capacity (Optional)
                            </label>
                            <input
                                type="number"
                                id="virtual_capacity"
                                name="virtual_capacity"
                                min="1"
                                value="{{ old('virtual_capacity') }}"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                placeholder="Maximum number of virtual attendees"
                            />
                        </div>
                    </div>
                    <div id="custom_platform_field" class="hidden mt-4">
                        <label for="custom_platform" class="block text-sm font-medium text-gray-700 mb-2">
                            Custom Platform <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="custom_platform"
                            name="custom_platform"
                            value="{{ old('custom_platform') }}"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('custom_platform') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                            placeholder="Specify the custom platform"
                        />
                        @error('custom_platform')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Meeting Link/URL
                        </label>
                        <input
                            type="url"
                            id="meeting_link"
                            name="meeting_link"
                            value="{{ old('meeting_link') }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('meeting_link') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                            placeholder="https://zoom.us/j/..."
                        />
                        <p class="text-xs text-gray-500 mt-2">Will be shared with registered participants</p>
                        @error('meeting_link')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Settings -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Registration Settings</h2>
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- FIX: Hidden input for is_multiday -->
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="is_multiday" value="0" />
                        <input
                            type="checkbox"
                            id="is_multiday_checkbox"
                            name="is_multiday"
                            value="1"
                            onchange="toggleEndDate()"
                            {{ old('is_multiday') ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        <span class="ml-3 text-sm font-medium text-gray-700">Registration Required</span>
                    </label>

                    <!-- FIX: Hidden input for walkin_allowed -->
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="walkin_allowed" value="0" />
                        <input
                            type="checkbox"
                            id="walkin_allowed_checkbox"
                            name="walkin_allowed"
                            value="1"
                            {{ old('walkin_allowed') ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        <span class="ml-3 text-sm font-medium text-gray-700">Allow Walk-In Registration</span>
                    </label>
                </div>

                <!-- FIX: Hidden input for registration_required -->
                <input type="hidden" name="registration_required" value="0" />
                <div id="registration_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                            Registration Deadline
                        </label>
                        <input
                            type="date"
                            id="registration_deadline"
                            name="registration_deadline"
                            value="{{ old('registration_deadline') }}"
                            min="{{ date('Y-m-d') }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('registration_deadline') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('registration_deadline')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Attendees
                        </label>
                        <input
                            type="number"
                            id="max_attendees"
                            name="max_attendees"
                            min="1"
                            value="{{ old('max_attendees') }}"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                            placeholder="Leave blank for unlimited"
                        />
                    </div>
                </div>

                <!-- Target Audience -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Target Audience <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="radio"
                                name="target_audience"
                                value="allstudents"
                                required
                                {{ old('target_audience', 'allstudents') == 'allstudents' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-3 text-sm text-gray-700">All Students</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="radio"
                                name="target_audience"
                                value="alumni"
                                required
                                {{ old('target_audience') == 'alumni' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-3 text-sm text-gray-700">Alumni</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input
                                type="radio"
                                name="target_audience"
                                value="openforall"
                                required
                                {{ old('target_audience') == 'openforall' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-3 text-sm text-gray-700">Open for All (Students, Alumni & Public)</span>
                        </label>
                    </div>
                    @error('target_audience')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="event_tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Tags
                    </label>
                    <input
                        type="text"
                        id="event_tags"
                        name="event_tags"
                        value="{{ old('event_tags') }}"
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                        placeholder="e.g., orientation, graduation, workshop, seminar (separate with commas)"
                    />
                    <p class="text-xs text-gray-500 mt-2">Tags help users find your event more easily</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Person <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="contact_person"
                            name="contact_person"
                            required
                            value="{{ old('contact_person', auth()->user()->first_name . ' ' . auth()->user()->last_name) }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('contact_person') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('contact_person')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            required
                            value="{{ old('contact_email', auth()->user()->email) }}"
                            class="block w-full px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                            @error('contact_email') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        />
                        @error('contact_email')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Phone
                    </label>
                    <input
                        type="tel"
                        id="contact_phone"
                        name="contact_phone"
                        value="{{ old('contact_phone') }}"
                        class="block w-full md:w-1/2 px-4 py-2.5 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200
                        @error('contact_phone') border-red-500 bg-red-50 @else border-gray-300 @enderror"
                        placeholder="+639 XXX XXX XXX"
                    />
                    @error('contact_phone')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                        Registration Instructions
                    </label>
                    <textarea
                        id="special_instructions"
                        name="special_instructions"
                        rows="3"
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                        placeholder="Special instructions for registrants (requirements, what to bring, dress code, etc.)"
                    >{{ old('special_instructions') }}</textarea>
                </div>

                <!-- Event Image -->
                <div>
                    <label for="event_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Banner/Image (Optional)
                    </label>
                    <div id="imagePreview" class="hidden mb-4">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img id="previewImg" src="" alt="Event Banner Preview" class="h-48 w-full object-cover rounded-md border border-gray-200">
                    </div>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="event_image" class="relative cursor-pointer font-medium text-primary hover:text-blue-500">
                                    <span>Upload a file</span>
                                    <input id="event_image" name="event_image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)" />
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    @error('event_image')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-700"><strong>Error:</strong> {{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-6 bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between gap-3">
                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200"
                >
                    Save as Draft
                </button>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button
                        type="submit"
                        name="action"
                        value="submit"
                        class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200"
                    >
                        Submit for Review
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleLocationFields() {
        const format = document.getElementById('event_format').value;
        document.getElementById('in_person_location').style.display = (format === 'inperson') ? 'block' : 'none';
        document.getElementById('virtual_location').style.display = (format === 'virtual' || format === 'hybrid') ? 'block' : 'none';
    }

    function toggleEndDate() {
        document.getElementById('end_date_field').style.display = document.getElementById('is_multiday').checked ? 'block' : 'none';
    }

    function toggleCustomPlatform() {
        document.getElementById('custom_platform_field').style.display = (document.getElementById('platform').value === 'other') ? 'block' : 'none';
    }

    function toggleRegistrationFields() {
        const show = document.getElementById('registration_required_checkbox').checked;
        document.getElementById('registration_fields').style.display = show ? 'grid' : 'none';
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewEvent() {
        document.getElementById('previewModal').classList.remove('hidden');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    function submitFromPreview() {
        document.getElementById('eventForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleLocationFields();
        toggleRegistrationFields();
    });
</script>

@endsection
