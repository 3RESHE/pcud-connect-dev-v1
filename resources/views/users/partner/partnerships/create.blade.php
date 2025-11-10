@extends('layouts.partner')

@section('title', 'Create Partnership Proposal - PCU-DASMA Connect')

@section('content')
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('partner.partnerships.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    Create Community Partnership Proposal
                </h1>
            </div>
            <p class="text-gray-600">
                Submit a proposal for walk-in community activities, outreach programs,
                and inclusive events that require no registration, welcome open
                participation, and have no participant limit for maximum community engagement.
            </p>
        </div>

        <!-- Form Container -->
        <div class="bg-white shadow-sm rounded-lg">
            <form id="partnershipForm" method="POST" action="{{ route('partner.partnerships.store') }}"
                class="divide-y divide-gray-200">
                @csrf

                <!-- Community Activity Type Selection -->
                <div class="px-6 py-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Community Activity Type
                    </h2>
                    <p class="text-sm text-gray-600 mb-6">
                        Select the type of community activity that allows open, walk-in
                        participation without formal registration or participant limits.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Feeding Programs -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="feedingprogram" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'feedingprogram')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Feeding Programs</h3>
                                            <p class="text-sm text-gray-500">On-site meals for children & vulnerable groups
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Brigada Eskwela -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="brigadaeskwela" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'brigadaeskwela')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Brigada Eskwela</h3>
                                            <p class="text-sm text-gray-500">School clean-up, repair & maintenance</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Community Clean-up -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="communitycleanup" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'communitycleanup')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Community Clean-up</h3>
                                            <p class="text-sm text-gray-500">Environmental protection activities</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Tree Planting -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="treeplanting" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'treeplanting')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Tree Planting</h3>
                                            <p class="text-sm text-gray-500">Park beautification & environmental care</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Donation Drive -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="donationdrive" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'donationdrive')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Donation Drive</h3>
                                            <p class="text-sm text-gray-500">Clothes, food & school supplies collection</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Other -->
                        <div>
                            <label
                                class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="activity_type" value="other" class="sr-only"
                                    onchange="toggleActivityFields()" @checked(old('activity_type') === 'other')>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div
                                            class="activity-type-icon w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">Other</h3>
                                            <p class="text-sm text-gray-500">Custom community activity</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-type-radio hidden">
                                    <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Other Activity Type Input -->
                    <div id="other_activity_type" class="hidden mt-4">
                        <label for="custom_activity_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Please specify the type of community activity
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="custom_activity_type" name="custom_activity_type"
                            value="{{ old('custom_activity_type') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="e.g., Mobile Health Clinic, Book Distribution, Relief Goods Distribution, etc.">
                        @error('custom_activity_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @error('activity_type')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Organization Information -->
                <div class="px-6 py-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Organization Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div>
                            <label for="organization_name" class="block text-sm text-gray-700 font-medium mb-1">
                                Organization Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="organization_name" name="organization_name"
                                value="{{ old('organization_name') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="e.g., Green Earth Foundation">
                            @error('organization_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Organization Website -->
                        <div>
                            <label for="organization_website" class="block text-sm text-gray-700 font-medium mb-1">
                                Website
                            </label>
                            <input type="url" id="organization_website" name="organization_website"
                                value="{{ old('organization_website') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="https://example.com">
                            @error('organization_website')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Organization Background -->
                    <div class="mt-6">
                        <label for="organization_background" class="block text-sm text-gray-700 font-medium mb-1">
                            Organization Background <span class="text-red-500">*</span>
                        </label>
                        <textarea id="organization_background" name="organization_background" rows="4" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Describe your organization, its mission, and experience...">{{ old('organization_background') }}</textarea>
                        @error('organization_background')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label for="organization_phone" class="block text-sm text-gray-700 font-medium mb-1">
                            Organization Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="organization_phone" name="organization_phone"
                            value="{{ old('organization_phone') }}" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="+63 9XX-XXXX-XXXX">
                        @error('organization_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Activity Details -->
                <div class="px-6 py-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Activity Details
                    </h2>
                    <!-- Activity Title -->
                    <div class="mb-6">
                        <label for="activity_title" class="block text-sm text-gray-700 font-medium mb-1">
                            Activity Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="activity_title" name="activity_title"
                            value="{{ old('activity_title') }}" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="e.g., Community Garden Development">
                        @error('activity_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Activity Description -->
                    <div class="mb-6">
                        <label for="activity_description" class="block text-sm text-gray-700 font-medium mb-1">
                            Activity Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="activity_description" name="activity_description" rows="4" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Describe the activity in detail...">{{ old('activity_description') }}</textarea>
                        @error('activity_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Activity Date -->
                        <div>
                            <label for="activity_date" class="block text-sm text-gray-700 font-medium mb-1">
                                Activity Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="activity_date" name="activity_date"
                                value="{{ old('activity_date') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            @error('activity_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Activity Time -->
                        <div>
                            <label for="activity_time" class="block text-sm text-gray-700 font-medium mb-1">
                                Activity Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="activity_time" name="activity_time"
                                value="{{ old('activity_time') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            @error('activity_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Venue Address -->
                    <div class="mb-6">
                        <label for="venue_address" class="block text-sm text-gray-700 font-medium mb-1">
                            Venue Address <span class="text-red-500">*</span>
                        </label>
                        <textarea id="venue_address" name="venue_address" rows="2" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Complete venue address...">{{ old('venue_address') }}</textarea>
                        @error('venue_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Objectives -->
                    <div class="mb-6">
                        <label for="activity_objectives" class="block text-sm text-gray-700 font-medium mb-1">
                            Activity Objectives <span class="text-red-500">*</span>
                        </label>
                        <textarea id="activity_objectives" name="activity_objectives" rows="3" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="What are the main goals of the activity?">{{ old('activity_objectives') }}</textarea>
                        @error('activity_objectives')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Expected Impact -->
                    <div>
                        <label for="expected_impact" class="block text-sm text-gray-700 font-medium mb-1">
                            Expected Impact <span class="text-red-500">*</span>
                        </label>
                        <textarea id="expected_impact" name="expected_impact" rows="3" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Describe expected outcome and community impact...">{{ old('expected_impact') }}</textarea>
                        @error('expected_impact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="px-6 py-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Contact Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_name" class="block text-sm text-gray-700 font-medium mb-1">
                                Contact Person <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact_name" name="contact_name"
                                value="{{ old('contact_name') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="Full name">
                            @error('contact_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_position" class="block text-sm text-gray-700 font-medium mb-1">
                                Contact Position <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact_position" name="contact_position"
                                value="{{ old('contact_position') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="e.g., Program Manager">
                            @error('contact_position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="contact_email" class="block text-sm text-gray-700 font-medium mb-1">
                                Contact Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="contact_email" name="contact_email"
                                value="{{ old('contact_email') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="email@example.com">
                            @error('contact_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm text-gray-700 font-medium mb-1">
                                Contact Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_phone" name="contact_phone"
                                value="{{ old('contact_phone') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="+63 9XX-XXXX-XXXX">
                            @error('contact_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Additional Information -->
                <div class="px-6 py-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Additional Information
                    </h2>
                    <div class="mb-6">
                        <label for="previous_experience" class="block text-sm text-gray-700 font-medium mb-1">
                            Previous Partnership Experience
                        </label>
                        <textarea id="previous_experience" name="previous_experience" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Describe any previous partnerships or similar activities...">{{ old('previous_experience') }}</textarea>
                        @error('previous_experience')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="additional_notes" class="block text-sm text-gray-700 font-medium mb-1">
                            Additional Notes
                        </label>
                        <textarea id="additional_notes" name="additional_notes" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary resize-none"
                            placeholder="Any other information you'd like to share...">{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row gap-3 justify-between">
                    <a href="{{ route('partner.partnerships.index') }}"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors font-medium text-center">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-primary hover:bg-blue-700 text-white rounded-md transition-colors font-medium">
                        Submit Partnership Proposal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle custom activity type field
        function toggleActivityFields() {
            const activityType = document.querySelector('input[name="activity_type"]:checked')?.value;
            const customContainer = document.getElementById('other_activity_type');
            const customInput = document.getElementById('custom_activity_type');

            // Update radio button styles
            document.querySelectorAll('.activity-type-radio').forEach(radio => {
                radio.classList.add('hidden');
            });
            document.querySelectorAll('input[name="activity_type"]:checked').forEach(radio => {
                const parent = radio.closest('label');
                if (parent) {
                    const radioDisplay = parent.querySelector('.activity-type-radio');
                    if (radioDisplay) radioDisplay.classList.remove('hidden');
                }
            });

            if (activityType === 'other') {
                customContainer.classList.remove('hidden');
                customInput.required = true;
            } else {
                customContainer.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleActivityFields();

            // Add change listener to all radio buttons
            document.querySelectorAll('input[name="activity_type"]').forEach(radio => {
                radio.addEventListener('change', toggleActivityFields);
            });

            // Handle form submission with better UX
            const form = document.getElementById('partnershipForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            });
        });

        // Validation helper - check form before submit
        function validateForm() {
            const activityType = document.querySelector('input[name="activity_type"]:checked');
            if (!activityType) {
                alert('Please select an activity type');
                return false;
            }
            return true;
        }
    </script>

@endsection
