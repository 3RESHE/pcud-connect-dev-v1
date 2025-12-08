@extends('layouts.partner')


@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <a href="javascript:history.back()" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Job Info Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Job Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Position</p>
                            <p class="text-lg text-gray-900 font-semibold">{{ $jobPosting->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Company</p>
                            <p class="text-lg text-gray-900">{{ $jobPosting->partnerProfile->company_name }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <a href="{{ route('partner.job-postings.applications', $jobPosting) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                ← Back to all applications
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Applicant Profile Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Applicant Profile</h2>
                    <div class="space-y-6">
                        <!-- Profile Header -->
                        <div class="flex flex-col sm:flex-row items-start gap-4 pb-4 border-b">
                            {{-- Profile Photo --}}
                            @if ($applicantProfile && $applicantProfile->profile_photo)
                                <img src="{{ asset('storage/' . $applicantProfile->profile_photo) }}"
                                     alt="{{ $applicant->name }}"
                                     class="h-16 w-16 rounded-full object-cover flex-shrink-0 border-2 border-gray-200">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                    {{ substr($applicant->name, 0, 1) }}
                                </div>
                            @endif


                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 break-words">
                                    {{ $applicant->name }}</h3>
                                <p class="text-gray-600 text-sm break-words">{{ $applicant->email }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    @if ($application->applicant_type === 'student') bg-blue-100 text-blue-800
                                    @else
                                        bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($application->applicant_type) }}
                                    </span>
                                    @if ($applicant->department)
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $applicant->department->code }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <!-- Basic Information -->
                        @if ($applicantProfile)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($applicant->department)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Department</p>
                                        <p class="text-gray-900 break-words">{{ $applicant->department->title }}</p>
                                    </div>
                                @endif


                                @if ($application->applicant_type === 'student')
                                    @if ($applicantProfile->student_id)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Student ID</p>
                                            <p class="text-gray-900">{{ $applicantProfile->student_id }}</p>
                                        </div>
                                    @endif
                                    @if ($applicantProfile->date_of_birth)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Date of Birth</p>
                                            <p class="text-gray-900">{{ $applicantProfile->date_of_birth->format('M d, Y') }}</p>
                                        </div>
                                    @endif
                                    @if ($applicantProfile->gender)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Gender</p>
                                            <p class="text-gray-900 capitalize">{{ $applicantProfile->gender }}</p>
                                        </div>
                                    @endif
                                @else
                                    {{-- Alumni --}}
                                    @if ($applicantProfile->graduation_year)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Graduation Year</p>
                                            <p class="text-gray-900">Class of {{ $applicantProfile->graduation_year }}</p>
                                        </div>
                                    @endif
                                    @if ($applicantProfile->gwa)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">GWA/GPA</p>
                                            <p class="text-gray-900">{{ number_format($applicantProfile->gwa, 2) }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>


                            <!-- Contact Information -->
                            <div class="border-t pt-4">
                                <h3 class="text-sm font-bold text-gray-900 mb-3">Contact Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if ($applicantProfile->phone)
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                                            <p class="text-sm text-gray-900">{{ $applicantProfile->phone }}</p>
                                        </div>
                                    @endif


                                    @if ($applicantProfile->personal_email)
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider">Personal Email</p>
                                            <p class="text-sm text-gray-900">{{ $applicantProfile->personal_email }}</p>
                                        </div>
                                    @endif


                                    @if ($application->applicant_type === 'student')
                                        @if ($applicantProfile->emergency_contact)
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Emergency Contact</p>
                                                <p class="text-sm text-gray-900">{{ $applicantProfile->emergency_contact }}</p>
                                            </div>
                                        @endif
                                        @if ($applicantProfile->address)
                                            <div class="md:col-span-2">
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                                                <p class="text-sm text-gray-900">{{ $applicantProfile->address }}</p>
                                            </div>
                                        @endif
                                    @else
                                        {{-- Alumni --}}
                                        @if ($applicantProfile->current_location)
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider">Current Location</p>
                                                <p class="text-sm text-gray-900">{{ $applicantProfile->current_location }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>


                            <!-- Headline & Bio -->
                            <div class="border-t pt-4">
                                @if ($applicantProfile->headline)
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-500 mb-2">Headline</p>
                                        <p class="text-gray-700 italic">{{ $applicantProfile->headline }}</p>
                                    </div>
                                @endif


                                @if ($application->applicant_type === 'alumni' && $applicantProfile->professional_summary)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 mb-2">Professional Summary</p>
                                        <p class="text-gray-700 text-sm whitespace-pre-wrap break-words">
                                            {{ $applicantProfile->professional_summary }}</p>
                                    </div>
                                @endif
                            </div>


                            <!-- Skills -->
                            @if ($applicantProfile->technical_skills)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-500 mb-3">Technical Skills</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (array_map('trim', explode(',', $applicantProfile->technical_skills)) as $skill)
                                            @if (!empty($skill))
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                    {{ $skill }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            @if ($applicantProfile->soft_skills)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-500 mb-3">Soft Skills</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (array_map('trim', explode(',', $applicantProfile->soft_skills)) as $skill)
                                            @if (!empty($skill))
                                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                                    {{ $skill }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <!-- Work Experience Section (Alumni & Students) -->
                            @if ($experiences->count() > 0)
                                <div class="border-t pt-4">
                                    <h3 class="text-sm font-bold text-gray-900 mb-4">Work Experience</h3>
                                    <div class="space-y-4">
                                        @foreach ($experiences as $experience)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $experience->role_position }}</p>
                                                        <p class="text-sm text-gray-600">{{ $experience->organization }}</p>
                                                    </div>
                                                    @if ($experience->is_current)
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Current</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    {{ $experience->start_date->format('M Y') }}
                                                    @if (!$experience->is_current && $experience->end_date)
                                                        - {{ $experience->end_date->format('M Y') }}
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-700">{{ $experience->getFormattedDuration() }}</p>
                                                @if ($experience->description)
                                                    <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $experience->description }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <!-- Projects Section (Alumni & Students) -->
                            @if ($projects->count() > 0)
                                <div class="border-t pt-4">
                                    <h3 class="text-sm font-bold text-gray-900 mb-4">Projects</h3>
                                    <div class="space-y-4">
                                        @foreach ($projects as $project)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $project->title }}</p>
                                                    </div>
                                                    @if ($project->url)
                                                        <a href="{{ $project->url }}" target="_blank" rel="noopener"
                                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                            View Project →
                                                        </a>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    {{ $project->start_date->format('M Y') }}
                                                    @if ($project->end_date)
                                                        - {{ $project->end_date->format('M Y') }}
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-700">{{ $project->getFormattedDuration() }}</p>
                                                @if ($project->description)
                                                    <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $project->description }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <!-- Links -->
                            @if ($applicantProfile->linkedin_url || $applicantProfile->github_url || $applicantProfile->portfolio_url)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-500 mb-3">Links & Profiles</p>
                                    <div class="space-y-2">
                                        @if ($applicantProfile->linkedin_url)
                                            <a href="{{ $applicantProfile->linkedin_url }}" target="_blank" rel="noopener"
                                                class="flex items-center text-blue-600 hover:text-blue-800 text-sm break-words">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.469v6.766z"/>
                                                </svg>
                                                LinkedIn Profile
                                            </a>
                                        @endif


                                        @if ($applicantProfile->github_url)
                                            <a href="{{ $applicantProfile->github_url }}" target="_blank" rel="noopener"
                                                class="flex items-center text-gray-800 hover:text-gray-600 text-sm break-words">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                </svg>
                                                GitHub Profile
                                            </a>
                                        @endif


                                        @if ($applicantProfile->portfolio_url)
                                            <a href="{{ $applicantProfile->portfolio_url }}" target="_blank" rel="noopener"
                                                class="flex items-center text-indigo-600 hover:text-indigo-800 text-sm break-words">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                Portfolio
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>


                <!-- Cover Letter Card - FIXED: Shows both written and uploaded cover letters -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Cover Letter</h2>

                    @if ($application->cover_letter)
                        <!-- Written Cover Letter Display -->
                        <div class="bg-gray-50 p-4 rounded-lg overflow-auto max-h-96">
                            <p class="text-gray-700 text-sm whitespace-pre-wrap break-words">
                                {{ $application->cover_letter }}
                            </p>
                        </div>
                    @elseif ($application->cover_letter_file)
                        <!-- Uploaded Cover Letter Display with Download Link -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h6a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1H6a1 1 0 000 2h6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-blue-900">Uploaded Cover Letter</p>
                                        <p class="text-xs text-blue-700 break-all">{{ basename($application->cover_letter_file) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('partner.applications.download-cover-letter', $application) }}"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition flex-shrink-0 whitespace-nowrap">
                                    📥 Download
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">No cover letter provided.</p>
                        </div>
                    @endif
                </div>


                <!-- Resume Card - FIXED: Shows resume details with download button -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resume / CV</h2>

                    @if ($application->resume_path)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h6a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1H6a1 1 0 000 2h6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-green-900">Resume Attached</p>
                                        <p class="text-xs text-green-700 break-all">{{ basename($application->resume_path) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('partner.applications.download-resume', $application) }}"
                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition flex-shrink-0 whitespace-nowrap">
                                    📥 Download
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">No resume found.</p>
                        </div>
                    @endif
                </div>


                <!-- Timeline Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase">Applied</p>
                                <p class="text-sm text-gray-900">
                                    {{ $application->created_at->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>


                        @if ($application->reviewed_at)
                            <div class="flex gap-4">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Reviewed</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $application->reviewed_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        @endif


                        @if ($application->last_contacted_at)
                            <div class="flex gap-4">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Last Contacted</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $application->last_contacted_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>


                    <div class="mb-6 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg text-center">
                        <p
                            class="text-4xl font-bold break-words
                        @if ($application->status === 'pending') text-yellow-600
                        @elseif($application->status === 'contacted')
                            text-purple-600
                        @elseif($application->status === 'approved')
                            text-green-600
                        @elseif($application->status === 'rejected')
                            text-red-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </p>
                    </div>


                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        {{-- Approve Button - Only show if pending or contacted --}}
                        @if ($application->status === 'pending' || $application->status === 'contacted')
                            <button type="button"
                                class="w-full approve-btn px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve
                            </button>


                            <button type="button"
                                class="w-full reject-btn px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        @endif


                        {{-- Send Email Button - Only show if NOT approved or rejected --}}
                        @if ($application->status !== 'approved' && $application->status !== 'rejected')
                            <button type="button"
                                class="w-full contact-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Send Email
                            </button>
                        @endif


                        {{-- Download Resume - Always visible --}}
                        <a href="{{ route('partner.applications.download-resume', $application) }}"
                            class="w-full block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition text-center">
                            📥 Download Resume
                        </a>

                        {{-- Download Cover Letter (if uploaded) - New button --}}
                        @if ($application->cover_letter_file)
                            <a href="{{ route('partner.applications.download-cover-letter', $application) }}"
                                class="w-full block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition text-center">
                                📥 Download Cover Letter
                            </a>
                        @endif
                    </div>
                </div>


                <!-- Quick Info Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Application ID</p>
                            <p class="text-sm font-mono text-gray-900 break-all">
                                {{ str_pad($application->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Applicant Type</p>
                            <p class="text-sm text-gray-900 capitalize">
                                {{ $application->applicant_type }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Applied</p>
                            <p class="text-sm text-gray-900">
                                {{ $application->created_at->diffForHumans() }}</p>
                        </div>
                        @if ($application->rejection_reason)
                            <div class="pt-3 border-t">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Rejection Reason</p>
                                <p class="text-sm text-gray-700 mt-1 break-words">
                                    {{ $application->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Contact Modal -->
    @include('users.partner.job-postings.contact-modal')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contact button
            document.querySelector('.contact-btn')?.addEventListener('click', function() {
                openContactModal(this.dataset.applicationId);
            });


            // Approve button
            document.querySelector('.approve-btn')?.addEventListener('click', function() {
                approveApplication(this.dataset.applicationId);
            });


            // Reject button
            document.querySelector('.reject-btn')?.addEventListener('click', function() {
                rejectApplication(this.dataset.applicationId);
            });
        });


        function openContactModal(applicationId) {
            const modal = document.getElementById('contactModal');
            const applicationIdInput = document.getElementById('applicationId');


            console.log('Opening modal for application:', applicationId);


            if (applicationIdInput) {
                applicationIdInput.value = applicationId;
            }


            modal.classList.remove('hidden');
        }


        function approveApplication(applicationId) {
            if (!confirm('Approve this application?')) return;


            fetch(`/partner/applications/${applicationId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('✓ Application approved!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('❌ ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('❌ Failed to approve application', 'error');
                });
        }


        function rejectApplication(applicationId) {
            const reason = prompt('Rejection reason (optional):');
            if (reason === null) return;


            fetch(`/partner/applications/${applicationId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        rejection_reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('✓ Application rejected!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('❌ ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('❌ Failed to reject application', 'error');
                });
        }


        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg animate-pulse
            ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);


            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
@endsection
