@extends('layouts.student')

@section('title', 'My Profile - PCU-DASMA Connect')

@section('content')
<div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
    <div class="w-full max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">My Profile</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">Your academic and professional information</p>
                </div>
                <a href="{{ route('student.profile.edit') }}"
                    class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base whitespace-nowrap text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>

        <!-- Personal Information -->
        @if ($profile)
            <div class="bg-white shadow-sm rounded-lg mb-6 sm:mb-8 overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Personal Information</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- Profile Photo & Headline -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <div class="flex-shrink-0">
                            @if ($profile->profile_photo)
                                <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo"
                                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover">
                            @else
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="mb-4">
                                <p class="text-xs sm:text-sm text-gray-600 break-words">Professional Headline</p>
                                <p class="text-base sm:text-lg font-semibold text-gray-900 mt-1 break-words">
                                    {{ $profile->headline ?? 'Not specified' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Personal Email</p>
                                    <p class="text-sm sm:text-base text-gray-900 mt-1 break-words">
                                        {{ $profile->personal_email ?? 'Not specified' }}
                                    </p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">Phone</p>
                                    <p class="text-sm sm:text-base text-gray-900 mt-1 break-words">
                                        @if ($profile->phone)
                                            <a href="tel:{{ $profile->phone }}" class="text-blue-600 hover:underline">{{ $profile->phone }}</a>
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bio Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-4 border-t border-gray-200">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Date of Birth</p>
                            <p class="text-sm sm:text-base text-gray-900 mt-1 break-words">
                                {{ $profile->date_of_birth?->format('M d, Y') ?? 'Not specified' }}
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Gender</p>
                            <p class="text-sm sm:text-base text-gray-900 mt-1 capitalize break-words">
                                {{ $profile->gender ?? 'Not specified' }}
                            </p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Emergency Contact</p>
                            <p class="text-sm sm:text-base text-gray-900 mt-1 break-words">
                                {{ $profile->emergency_contact ?? 'Not specified' }}
                            </p>
                        </div>
                    </div>

                    <!-- Address -->
                    @if ($profile->address)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Address</p>
                            <p class="text-sm sm:text-base text-gray-900 mt-1 break-words">{{ $profile->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social & Professional Links -->
            @if ($profile->linkedin_url || $profile->github_url || $profile->portfolio_url)
                <div class="bg-white shadow-sm rounded-lg mb-6 sm:mb-8 overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Social & Professional Links</h3>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4">
                        @if ($profile->linkedin_url)
                            <a href="{{ $profile->linkedin_url }}" target="_blank" class="flex items-center gap-3 text-blue-600 hover:underline break-words">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.32 1.39v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.79M7 8a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                </svg>
                                <span class="text-sm sm:text-base">LinkedIn Profile</span>
                            </a>
                        @endif
                        @if ($profile->github_url)
                            <a href="{{ $profile->github_url }}" target="_blank" class="flex items-center gap-3 text-blue-600 hover:underline break-words">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v 3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                <span class="text-sm sm:text-base">GitHub Profile</span>
                            </a>
                        @endif
                        @if ($profile->portfolio_url)
                            <a href="{{ $profile->portfolio_url }}" target="_blank" class="flex items-center gap-3 text-blue-600 hover:underline break-words">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Portfolio Website</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Skills & Certifications -->
            @if ($profile->technical_skills || $profile->soft_skills || $profile->certifications || $profile->languages)
                <div class="bg-white shadow-sm rounded-lg mb-6 sm:mb-8 overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Skills & Competencies</h3>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                        @if ($profile->technical_skills)
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600 break-words font-medium">Technical Skills</p>
                                <p class="text-sm sm:text-base text-gray-900 mt-2 break-words">{{ $profile->technical_skills }}</p>
                            </div>
                        @endif
                        @if ($profile->soft_skills)
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600 break-words font-medium">Soft Skills</p>
                                <p class="text-sm sm:text-base text-gray-900 mt-2 break-words">{{ $profile->soft_skills }}</p>
                            </div>
                        @endif
                        @if ($profile->certifications)
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600 break-words font-medium">Certifications</p>
                                <p class="text-sm sm:text-base text-gray-900 mt-2 break-words">{{ $profile->certifications }}</p>
                            </div>
                        @endif
                        @if ($profile->languages)
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600 break-words font-medium">Languages</p>
                                <p class="text-sm sm:text-base text-gray-900 mt-2 break-words">{{ $profile->languages }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Experiences -->
            @if ($experiences->count() > 0)
                <div class="bg-white shadow-sm rounded-lg mb-6 sm:mb-8 overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Experience</h3>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                        @foreach ($experiences as $exp)
                            <div class="border-b border-gray-200 pb-4 sm:pb-6 last:border-b-0 last:pb-0">
                                <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-3 mb-2">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 break-words">
                                            {{ $exp->role_position }}
                                        </h4>
                                        <p class="text-xs sm:text-sm text-gray-600 break-words">{{ $exp->organization }}</p>
                                    </div>
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full whitespace-nowrap">
                                        {{ ucfirst(str_replace('_', ' ', $exp->experience_type)) }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-2 break-words">
                                    {{ $exp->start_date?->format('M Y') ?? 'N/A' }}
                                    —
                                    @if ($exp->is_current)
                                        <span class="text-green-600 font-medium">Present</span>
                                    @else
                                        {{ $exp->end_date?->format('M Y') ?? 'N/A' }}
                                    @endif
                                </p>
                                <p class="text-sm sm:text-base text-gray-700 break-words">{{ $exp->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Projects -->
            @if ($projects->count() > 0)
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Projects & Portfolio</h3>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                        @foreach ($projects as $proj)
                            <div class="border-b border-gray-200 pb-4 sm:pb-6 last:border-b-0 last:pb-0">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 break-words">
                                            {{ $proj->title }}
                                        </h4>
                                        @if ($proj->url)
                                            <a href="{{ $proj->url }}" target="_blank" class="text-blue-600 hover:underline text-xs sm:text-sm break-words">
                                                View Project →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-2 break-words">
                                    {{ $proj->start_date?->format('M Y') ?? 'N/A' }}
                                    —
                                    {{ $proj->end_date?->format('M Y') ?? 'N/A' }}
                                </p>
                                <p class="text-sm sm:text-base text-gray-700 break-words">{{ $proj->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @else
            <!-- No Profile Message -->
            <div class="bg-white shadow-sm rounded-lg p-6 sm:p-8 text-center">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 break-words">Profile Not Set Up</h3>
                <p class="text-xs sm:text-sm text-gray-600 mb-4 break-words">You haven't set up your profile yet. Complete it to showcase your skills and experience.</p>
                <a href="{{ route('student.profile.edit') }}" class="inline-block px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base whitespace-nowrap">
                    Create Profile
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
