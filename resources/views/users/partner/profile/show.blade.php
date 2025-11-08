@extends('layouts.partner')

@section('title', 'Company Profile - PCU-DASMA Connect')

@section('content')
<div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
    <div class="w-full max-w-6xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Company Profile</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">Your organization's information</p>
                </div>
                <a href="{{ route('partner.profile.edit') }}"
                    class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center sm:justify-start gap-2 text-sm sm:text-base whitespace-nowrap">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>

        <!-- Status Alerts -->
        @if ($isIncomplete)
            <div class="mb-4 sm:mb-6 bg-amber-50 border-l-4 border-amber-400 p-3 sm:p-4 rounded-r">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-amber-800 break-words"><strong>Incomplete Profile:</strong> Complete your company profile to unlock all features.</p>
                </div>
            </div>
        @else
            <div class="mb-4 sm:mb-6 bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 rounded-r">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-green-800 break-words"><strong>Profile Complete:</strong> Your profile is visible to students and partners.</p>
                </div>
            </div>
        @endif

        @if ($profile)
            <!-- Company Information -->
            <div class="bg-white shadow-sm rounded-lg mb-6 sm:mb-8 overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Company Information</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- Logo and Basic Info -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <div class="flex-shrink-0">
                            @if ($profile->company_logo)
                                <img src="{{ asset('storage/' . $profile->company_logo) }}"
                                    alt="{{ $profile->company_name }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg object-cover shadow-md">
                            @else
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Company Name</p>
                                    <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">{{ $profile->company_name }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Industry</p>
                                    <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 capitalize break-words">{{ $profile->industry }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Company Size</p>
                                    <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">{{ $profile->company_size ?? 'N/A' }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Founded</p>
                                    <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">{{ $profile->founded_year ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 break-words">Description</p>
                        <p class="text-sm sm:text-base text-gray-900 leading-relaxed break-words">{{ $profile->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Contact Information</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Primary Contact</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">{{ $profile->contact_person }}</p>
                            @if ($profile->contact_title)
                                <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">{{ $profile->contact_title }}</p>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Phone</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">
                                @if ($profile->phone)
                                    <a href="tel:{{ $profile->phone }}" class="text-blue-600 hover:underline">{{ $profile->phone }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Website</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">
                                @if ($profile->website)
                                    <a href="{{ $profile->website }}" target="_blank" class="text-blue-600 hover:underline truncate">Visit Website</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">LinkedIn</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">
                                @if ($profile->linkedin_url)
                                    <a href="{{ $profile->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline truncate">View Profile</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($profile->address)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-xs sm:text-sm text-gray-600 mb-2 break-words">Address</p>
                            <p class="text-sm sm:text-base text-gray-900 break-words">{{ $profile->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white shadow-sm rounded-lg p-6 sm:p-8 text-center">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 break-words">Profile Not Set Up</h3>
                <p class="text-xs sm:text-sm text-gray-600 mb-4 break-words">You haven't set up your company profile yet. Complete it to unlock all partner features.</p>
                <a href="{{ route('partner.profile.edit') }}" class="inline-block px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base whitespace-nowrap">
                    Create Profile
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
