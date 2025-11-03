@extends('layouts.staff')

@section('title', 'Edit Event - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('staff.events.show', $event->id) }}" class="text-gray-400 hover:text-gray-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
    </div>
    <p class="text-gray-600">Update your event details</p>
</div>

<!-- Form Container -->
<div class="bg-white shadow-sm rounded-lg">
    <form action="{{ route('staff.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="eventForm" class="divide-y divide-gray-200">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Event Information</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- Event Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Event Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        value="{{ old('title', $event->title) }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror"
                        placeholder="Enter event title..."
                    />
                    @error('title')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Event Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror"
                        placeholder="Provide a detailed description of the event..."
                    >{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Format -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_format" class="block text-sm font-medium text-gray-700 mb-1">
                            Event Format <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="event_format"
                            name="event_format"
                            required
                            onchange="toggleLocationFields()"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('event_format') border-red-500 @enderror"
                        >
                            <option value="">Select Format</option>
                            <option value="in_person" {{ old('event_format', $event->event_format) == 'in_person' ? 'selected' : '' }}>In-Person</option>
                            <option value="virtual" {{ old('event_format', $event->event_format) == 'virtual' ? 'selected' : '' }}>Virtual/Online</option>
                            <option value="hybrid" {{ old('event_format', $event->event_format) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        @error('event_format')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Date & Time</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- Event Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Event Date <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="event_date"
                            name="event_date"
                            required
                            value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('event_date') border-red-500 @enderror"
                        />
                        @error('event_date')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_multi_day"
                                name="is_multi_day"
                                onchange="toggleEndDate()"
                                {{ old('is_multi_day', $event->is_multi_day) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">Multi-day event</span>
                        </label>
                    </div>
                </div>

                <!-- End Date (Hidden by default) -->
                <div id="end_date_field" class="{{ old('is_multi_day', $event->is_multi_day) ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                        End Date
                    </label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}"
                        min="{{ date('Y-m-d') }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary md:w-1/2"
                    />
                </div>

                <!-- Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            required
                            value="{{ old('start_time', $event->start_time) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('start_time') border-red-500 @enderror"
                        />
                        @error('start_time')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            required
                            value="{{ old('end_time', $event->end_time) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('end_time') border-red-500 @enderror"
                        />
                        @error('end_time')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Location & Venue</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- In-Person Location -->
                <div id="in_person_location">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="venue_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Venue Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="venue_name"
                                name="venue_name"
                                required
                                value="{{ old('venue_name', $event->venue_name) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="e.g., PCU-DASMA Auditorium"
                            />
                        </div>
                        <div>
                            <label for="venue_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                                Venue Capacity <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="venue_capacity"
                                name="venue_capacity"
                                min="1"
                                required
                                value="{{ old('venue_capacity', $event->venue_capacity) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="Maximum number of attendees"
                            />
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="venue_address" class="block text-sm font-medium text-gray-700 mb-1">
                            Venue Address
                        </label>
                        <textarea
                            id="venue_address"
                            name="venue_address"
                            rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="Complete address of the venue..."
                        >{{ old('venue_address', $event->venue_address) }}</textarea>
                    </div>
                </div>

                <!-- Virtual Location -->
                <div id="virtual_location" class="{{ old('event_format', $event->event_format) == 'virtual' || old('event_format', $event->event_format) == 'hybrid' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700 mb-1">
                                Platform <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="platform"
                                name="platform"
                                onchange="toggleCustomPlatform()"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            >
                                <option value="">Select Platform</option>
                                <option value="zoom" {{ old('platform', $event->platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="teams" {{ old('platform', $event->platform) == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                <option value="meet" {{ old('platform', $event->platform) == 'meet' ? 'selected' : '' }}>Google Meet</option>
                                <option value="webex" {{ old('platform', $event->platform) == 'webex' ? 'selected' : '' }}>Webex</option>
                                <option value="facebook_live" {{ old('platform', $event->platform) == 'facebook_live' ? 'selected' : '' }}>Facebook Live</option>
                                <option value="youtube_live" {{ old('platform', $event->platform) == 'youtube_live' ? 'selected' : '' }}>YouTube Live</option>
                                <option value="other" {{ old('platform', $event->platform) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="virtual_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                                Virtual Capacity
                            </label>
                            <input
                                type="number"
                                id="virtual_capacity"
                                name="virtual_capacity"
                                min="1"
                                value="{{ old('virtual_capacity', $event->virtual_capacity) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="Maximum number of virtual attendees"
                            />
                        </div>
                    </div>
                    <div id="custom_platform_field" class="{{ old('platform', $event->platform) == 'other' ? '' : 'hidden' }} mt-4">
                        <label for="custom_platform" class="block text-sm font-medium text-gray-700 mb-1">
                            Custom Platform <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="custom_platform"
                            name="custom_platform"
                            value="{{ old('custom_platform', $event->custom_platform) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="Specify the custom platform"
                        />
                    </div>
                    <div class="mt-6">
                        <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-1">
                            Meeting Link/URL
                        </label>
                        <input
                            type="url"
                            id="meeting_link"
                            name="meeting_link"
                            value="{{ old('meeting_link', $event->meeting_link) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="https://zoom.us/j/... or meeting details"
                        />
                        <p class="text-xs text-gray-500 mt-1">This will be shared with registered participants</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Settings -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Registration Settings</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- Registration Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="registration_required"
                                name="registration_required"
                                onchange="toggleRegistrationFields()"
                                {{ old('registration_required', $event->registration_required) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">Registration Required</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                id="walk_in_allowed"
                                name="walk_in_allowed"
                                {{ old('walk_in_allowed', $event->walk_in_allowed) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">Allow Walk-In Registration</span>
                        </label>
                    </div>
                </div>

                <!-- Registration Details -->
                <div id="registration_fields" class="{{ old('registration_required', $event->registration_required) ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">
                            Registration Deadline
                        </label>
                        <input
                            type="date"
                            id="registration_deadline"
                            name="registration_deadline"
                            value="{{ old('registration_deadline', $event->registration_deadline ? $event->registration_deadline->format('Y-m-d') : '') }}"
                            min="{{ date('Y-m-d') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                    </div>
                    <div>
                        <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-1">
                            Maximum Attendees
                        </label>
                        <input
                            type="number"
                            id="max_attendees"
                            name="max_attendees"
                            min="1"
                            value="{{ old('max_attendees', $event->max_attendees) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="Leave blank for unlimited"
                        />
                    </div>
                </div>

                <!-- Target Audience Selection (SIMPLIFIED - NO DEPARTMENTS) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Target Audience <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2 mt-2">
                        <label class="flex items-center">
                            <input
                                type="radio"
                                name="target_audience"
                                value="all_students"
                                required
                                {{ old('target_audience', $event->target_audience) == 'all_students' ? 'checked' : '' }}
                                class="border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">All Students</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                type="radio"
                                name="target_audience"
                                value="alumni"
                                required
                                {{ old('target_audience', $event->target_audience) == 'alumni' ? 'checked' : '' }}
                                class="border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">Alumni</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                type="radio"
                                name="target_audience"
                                value="open_for_all"
                                required
                                {{ old('target_audience', $event->target_audience) == 'open_for_all' ? 'checked' : '' }}
                                class="border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-700">Open for All (Students, Alumni & Public)</span>
                        </label>
                    </div>
                    @error('target_audience')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="px-6 py-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h2>
            <div class="grid grid-cols-1 gap-6">
                <!-- Event Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                        Event Tags
                    </label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        value="{{ old('tags', $event->tags) }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="e.g., orientation, graduation, workshop, seminar (separate with commas)"
                    />
                    <p class="text-xs text-gray-500 mt-1">Tags help users find your event more easily</p>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Person <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="contact_person"
                            name="contact_person"
                            required
                            value="{{ old('contact_person', $event->contact_person) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="Staff member name"
                        />
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            required
                            value="{{ old('contact_email', $event->contact_email) }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="staff@pcu.edu.ph"
                        />
                    </div>
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Contact Phone
                    </label>
                    <input
                        type="tel"
                        id="contact_phone"
                        name="contact_phone"
                        value="{{ old('contact_phone', $event->contact_phone) }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary md:w-1/2"
                        placeholder="+639 XXX XXX XXX"
                    />
                </div>

                <!-- Special Instructions -->
                <div>
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">
                        Registration Instructions
                    </label>
                    <textarea
                        id="special_instructions"
                        name="special_instructions"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="Special instructions for registrants (requirements, what to bring, dress code, etc.)"
                    >{{ old('special_instructions', $event->special_instructions) }}</textarea>
                </div>

                <!-- Event Image Upload -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">
                        Event Banner/Image (Optional)
                    </label>

                    <!-- Current Image Preview -->
                    @if($event->featured_image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $event->featured_image) }}" alt="Event banner" class="h-32 w-auto rounded-md border border-gray-300">
                            </div>
                        </div>
                    @endif

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Upload a new file</span>
                                    <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*" />
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            @if($event->featured_image)
                                <p class="text-xs text-gray-500">(Leave empty to keep current image)</p>
                            @endif
                        </div>
                    </div>
                    @error('featured_image')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-6 bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between gap-3">
                <a href="{{ route('staff.events.show', $event->id) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-center transition-colors duration-200">
                    Cancel
                </a>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button
                        type="button"
                        onclick="previewEvent()"
                        id="preview_btn"
                        class="px-6 py-2 border border-primary text-primary rounded-md hover:bg-blue-50 font-medium transition-colors duration-200"
                    >
                        Preview Event
                    </button>
                    <button
                        type="submit"
                        name="action"
                        value="update"
                        id="update_btn"
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-blue-700 font-medium transition-colors duration-200"
                    >
                        Update Event
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePreviewModal()"></div>
        <div class="relative bg-white rounded-lg max-w-4xl w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Event Update Preview</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                <div id="previewContent">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closePreviewModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Keep Editing
                </button>
                <button onclick="submitFromPreview()" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Update Event
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/staff/event-form.js') }}"></script>
@endsection
