@extends('layouts.alumni')

@section('title', $job->title . ' - PCU-DASMA Connect')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('alumni.jobs.index') }}"
                    class="inline-flex items-center text-primary hover:text-blue-700 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Job Opportunities
                </a>
            </div>
            @include('users.alumni.jobs.sessions')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Job Header -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center mb-4 gap-2">
                            @if ($job->is_featured)
                                <span
                                    class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Featured</span>
                            @endif
                            <span
                                class="@if ($job->job_type === 'fulltime') bg-blue-100 text-blue-800 @elseif($job->job_type === 'parttime') bg-purple-100 text-purple-800 @elseif($job->job_type === 'internship') bg-yellow-100 text-yellow-800 @else bg-orange-100 text-orange-800 @endif px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                {{ $job->getJobTypeDisplay() }}
                            </span>

                            @if ($alreadyApplied)
                                <span
                                    class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Applied</span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4 break-words line-clamp-3">
                            {{ $job->title }}</h1>

                        <!-- Job Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 text-sm text-gray-600">
                            <!-- Company -->
                            <div class="flex items-start sm:items-center gap-2 min-w-0">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span
                                    class="font-semibold break-words truncate">{{ $job->partnerProfile?->company_name ?? 'N/A' }}</span>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start sm:items-center gap-2 min-w-0">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                </svg>
                                <span class="break-words truncate">{{ $job->location ?? 'Remote' }}</span>
                            </div>

                            <!-- Department -->
                            <div class="flex items-start sm:items-center gap-2 min-w-0">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6">
                                    </path>
                                </svg>
                                <span class="truncate">{{ $job->department ?? 'N/A' }}</span>
                            </div>

                            <!-- Posted -->
                            <div class="flex items-start sm:items-center gap-2 min-w-0">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="truncate">Posted {{ $job->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Applicants -->
                            <div class="flex items-start sm:items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>{{ $applicantCount }} applicants</span>
                            </div>

                            <!-- Work Setup -->
                            <div class="flex items-start sm:items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $job->getWorkSetupDisplay() }}</span>
                            </div>
                        </div>

                        <!-- Salary -->
                        <div class="text-xl sm:text-2xl font-bold text-green-600 mb-4">
                            {{ $job->getSalaryRangeDisplay() }}
                        </div>

                        <!-- Action Buttons - FULLY UPDATED -->
                        @if (!$alreadyApplied)
                            <button onclick="applyForJob()"
                                class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                                Apply Now
                            </button>
                        @else
                            @php
                                $application = auth()
                                    ->user()
                                    ->jobApplications()
                                    ->where('job_posting_id', $job->id)
                                    ->first();
                            @endphp

                            <!-- Status: Contacted -->
                            @if ($application && $application->status === 'contacted')
                                <!-- Contacted Banner -->
                                <div
                                    class="w-full bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-4 sm:p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg sm:text-xl font-bold text-purple-900 mb-2">
                                                ✨ Great News!
                                            </h3>
                                            <p class="text-sm sm:text-base text-purple-800 mb-1">
                                                The company has reached out to you about your application!
                                            </p>
                                            <p class="text-xs sm:text-sm text-purple-700 font-medium">
                                                📧 Please check your email for their message and next steps.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status: Rejected -->
                            @elseif ($application && $application->status === 'rejected')
                                <!-- Rejection Banner -->
                                <div
                                    class="w-full bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 rounded-lg p-4 sm:p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg sm:text-xl font-bold text-red-900 mb-2">
                                                Application Not Selected
                                            </h3>
                                            <p class="text-sm sm:text-base text-red-800 mb-1">
                                                The employer has decided to look for other candidates.
                                            </p>
                                            <p class="text-xs sm:text-sm text-red-700 font-medium">
                                                💡 Keep exploring other opportunities!
                                            </p>
                                            @if ($application->rejection_reason)
                                                <p class="text-xs sm:text-sm text-red-700 mt-3 p-2 bg-red-200 rounded">
                                                    <strong>Reason:</strong> {{ $application->rejection_reason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Status: Approved -->
                            @elseif ($application && $application->status === 'approved')
                                <!-- Approval Banner -->
                                <div
                                    class="w-full bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 rounded-lg p-4 sm:p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg sm:text-xl font-bold text-green-900 mb-2">
                                                🎉 Congratulations!
                                            </h3>
                                            <p class="text-sm sm:text-base text-green-800 mb-1">
                                                Your application has been approved!
                                            </p>
                                            <p class="text-xs sm:text-sm text-green-700 font-medium">
                                                ✅ Please check your email for next steps and details.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status: Pending (Show Withdraw Button) -->
                            @else
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <div class="px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-700 font-medium">You have already applied for this
                                            position</p>
                                    </div>
                                    <!-- Withdraw Button -->
                                    <form
                                        action="{{ route('alumni.applications.destroy', auth()->user()->jobApplications()->where('job_posting_id', $job->id)->first()->id ?? '') }}"
                                        method="POST" class="inline"
                                        data-application-id="{{ auth()->user()->jobApplications()->where('job_posting_id', $job->id)->first()->id ?? '' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openWithdrawModal(this.form.dataset.applicationId)"
                                            class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors text-sm">
                                            Withdraw Application
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                    </div>

                    <!-- Job Description -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Job Description</h2>
                        <div id="descriptionContainer"
                            class="text-gray-600 leading-relaxed space-y-4 prose prose-sm max-w-none text-sm sm:text-base overflow-hidden transition-all duration-300"
                            style="max-height: 400px;">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                        @if (strlen($job->description) > 500)
                            <button onclick="toggleDescription()"
                                class="mt-4 text-primary hover:text-blue-700 font-medium text-sm flex items-center gap-2 transition-colors">
                                <span id="descriptionToggle">Read More</span>
                                <svg id="descriptionIcon" class="w-4 h-4 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Requirements -->
                    @if ($job->requirements)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Requirements</h2>
                            <div id="requirementsContainer" class="overflow-hidden transition-all duration-300"
                                style="max-height: 400px;">
                                <div class="text-gray-600 leading-relaxed prose prose-sm max-w-none text-sm sm:text-base">
                                    {!! nl2br(e($job->requirements)) !!}
                                </div>
                            </div>
                            @if (strlen($job->requirements) > 500)
                                <button onclick="toggleRequirements()"
                                    class="mt-4 text-primary hover:text-blue-700 font-medium text-sm flex items-center gap-2 transition-colors">
                                    <span id="requirementsToggle">Read More</span>
                                    <svg id="requirementsIcon" class="w-4 h-4 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Benefits -->
                    @if ($job->benefits)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Benefits & Perks</h2>
                            <div id="benefitsContainer" class="overflow-hidden transition-all duration-300"
                                style="max-height: 400px;">
                                <ul class="list-disc list-inside text-gray-700 space-y-2 text-sm sm:text-base">
                                    @foreach (explode("\n", $job->benefits) as $benefit)
                                        @if (trim($benefit))
                                            <li class="break-words">{{ trim($benefit) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @if (count(array_filter(explode("\n", $job->benefits))) > 8)
                                <button onclick="toggleBenefits()"
                                    class="mt-4 text-primary hover:text-blue-700 font-medium text-sm flex items-center gap-2 transition-colors">
                                    <span id="benefitsToggle">Read More</span>
                                    <svg id="benefitsIcon" class="w-4 h-4 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Application Process -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Application Process</h2>
                        <ol class="list-decimal list-inside text-gray-700 space-y-2 text-sm sm:text-base">
                            <li>Submit your resume and cover letter through the application form</li>
                            <li>The employer will review your application</li>
                            <li>Shortlisted candidates will be contacted for an interview</li>
                            <li>Final evaluation and job offer</li>
                        </ol>
                    </div>

                    <!-- Related Jobs -->
                    @if ($relatedJobs->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Similar Opportunities</h2>
                            <div class="space-y-3">
                                @foreach ($relatedJobs as $relatedJob)
                                    <div
                                        class="p-3 sm:p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-3">
                                            <div class="flex-1 min-w-0 w-full">
                                                <h3
                                                    class="font-semibold text-gray-900 text-sm sm:text-base break-words line-clamp-2">
                                                    {{ $relatedJob->title }}</h3>
                                                <p class="text-xs sm:text-sm text-gray-600 truncate">
                                                    {{ $relatedJob->partnerProfile?->company_name ?? 'N/A' }} ·
                                                    {{ $relatedJob->location ?? 'Remote' }}</p>
                                            </div>
                                            <a href="{{ route('alumni.jobs.show', $relatedJob->id) }}"
                                                class="text-primary hover:text-blue-700 font-medium text-xs sm:text-sm whitespace-nowrap flex-shrink-0">View</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Info Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <div class="text-gray-500 text-xs font-medium">Job Type</div>
                                <div class="font-semibold text-gray-900 mt-1">{{ $job->getJobTypeDisplay() }}</div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Experience Level</div>
                                <div class="font-semibold text-gray-900 mt-1">{{ $job->getExperienceLevelDisplay() }}
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Work Setup</div>
                                <div class="font-semibold text-gray-900 mt-1">{{ $job->getWorkSetupDisplay() }}</div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Salary Range</div>
                                <div class="font-semibold text-green-600 mt-1">{{ $job->getSalaryRangeDisplay() }}</div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Positions Available</div>
                                <div class="font-semibold text-gray-900 mt-1">{{ $job->positions_available }}</div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Total Applicants</div>
                                <div class="font-semibold text-gray-900 mt-1">{{ $applicantCount }}</div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="text-gray-500 text-xs font-medium">Application Deadline</div>
                                <div
                                    class="font-semibold @if ($job->application_deadline < now()) text-red-600 @else text-gray-900 @endif mt-1">
                                    {{ $job->application_deadline->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Timeline (Only if student already applied) - FULLY UPDATED -->
                    @if ($alreadyApplied)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Application Status</h3>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                @php
                                    $application = auth()
                                        ->user()
                                        ->jobApplications()
                                        ->where('job_posting_id', $job->id)
                                        ->first();
                                    $statusDisplay = $application?->getStatusDisplay();
                                @endphp

                                @if ($statusDisplay)
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                    @if ($statusDisplay['badge'] === 'warning') bg-yellow-100 text-yellow-800
                    @elseif($statusDisplay['badge'] === 'success') bg-green-100 text-green-800
                    @elseif($statusDisplay['badge'] === 'danger') bg-red-100 text-red-800
                    @elseif($statusDisplay['badge'] === 'purple') bg-purple-100 text-purple-800
                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ $statusDisplay['text'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Timeline -->
                            <div class="space-y-4">
                                <!-- Submitted -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if ($application)
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900 text-sm">Application Submitted</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $application?->created_at->format('M d, Y') ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Under Review (Only show if not rejected) -->
                                @if ($application?->status !== 'rejected')
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @if ($application?->reviewed_at || $application?->status === 'contacted' || $application?->status === 'approved')
                                                <svg class="w-6 h-6 text-green-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <div class="w-6 h-6 border-2 border-yellow-400 rounded-full animate-pulse">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900 text-sm">Under Review</p>
                                            <p class="text-xs text-gray-600">
                                                @if ($application?->reviewed_at || $application?->status === 'contacted' || $application?->status === 'approved')
                                                    {{ $application->reviewed_at?->format('M d, Y') ?? 'Reviewed' }}
                                                @else
                                                    Waiting for review...
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Contacted Step -->
                                @if ($application?->status === 'contacted' || $application?->last_contacted_at)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                                </path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-purple-900 text-sm">Company Reached Out 📧</p>
                                            <p class="text-xs text-purple-600 font-medium">
                                                {{ $application->last_contacted_at?->format('M d, Y') ?? 'Recently' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Approved Step -->
                                @if ($application?->status === 'approved')
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-green-900 text-sm">Application Approved ✨</p>
                                            <p class="text-xs text-green-600 font-medium">
                                                Congratulations! You're selected.
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Rejected Step -->
                                @if ($application?->status === 'rejected')
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-red-900 text-sm">Application Not Selected</p>
                                            <p class="text-xs text-red-600 font-medium">
                                                {{ $application->reviewed_at?->format('M d, Y') ?? 'Recently' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Status Details -->
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <p class="text-gray-500 font-medium">Applied On</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ $application?->created_at->diffForHumans() ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium">Status</p>
                                            <p
                                                class="font-semibold
                            @if ($application?->status === 'pending') text-yellow-600
                            @elseif($application?->status === 'contacted') text-purple-600
                            @elseif($application?->status === 'approved') text-green-600
                            @elseif($application?->status === 'rejected') text-red-600
                            @else text-gray-600 @endif">
                                                {{ ucfirst($application?->status ?? 'N/A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Show rejection reason if available -->
                                @if ($application?->status === 'rejected' && $application->rejection_reason)
                                    <div class="border-t border-gray-200 pt-4 mt-4">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">Feedback from Employer:</p>
                                        <div class="bg-red-50 border border-red-200 rounded p-3">
                                            <p class="text-xs text-red-700">{{ $application->rejection_reason }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif



                    <!-- Company Info Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">About Company</h3>

                        <!-- Company Logo/Icon -->
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4 flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>

                        <!-- Company Details -->
                        <div class="space-y-3 text-sm mb-4">
                            @if ($job->partnerProfile?->company_name)
                                <div>
                                    <div class="text-gray-500 text-xs font-medium mb-1">Company Name</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->company_name }}
                                    </div>
                                </div>
                            @endif

                            @if ($job->partnerProfile?->industry)
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="text-gray-500 text-xs font-medium mb-1">Industry</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->industry }}</div>
                                </div>
                            @endif

                            @if ($job->partnerProfile?->company_size)
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="text-gray-500 text-xs font-medium mb-1">Company Size</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->company_size }}
                                    </div>
                                </div>
                            @endif

                            @if ($job->partnerProfile?->founded_year)
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="text-gray-500 text-xs font-medium mb-1">Founded</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->founded_year }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Company Description -->
                        <div id="companyDescContainer"
                            class="overflow-hidden transition-all duration-300 border-t border-gray-200 pt-3"
                            style="max-height: 200px;">
                            <p class="text-sm text-gray-600 mb-3">
                                {{ $job->partnerProfile?->company_description ?? 'Information about the company is not available.' }}
                            </p>
                        </div>
                        @if ($job->partnerProfile?->company_description && strlen($job->partnerProfile->company_description) > 300)
                            <button onclick="toggleCompanyDesc()"
                                class="text-primary hover:text-blue-700 font-medium text-xs flex items-center gap-1 transition-colors">
                                <span id="companyDescToggle">Read More</span>
                                <svg id="companyDescIcon" class="w-3 h-3 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </button>
                        @endif

                        <!-- Website Link -->
                        @if ($job->partnerProfile?->company_website)
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <a href="{{ $job->partnerProfile->company_website }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-primary hover:text-blue-700 font-medium text-sm inline-flex items-center gap-1 break-all">
                                    Visit Website
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Contact Info -->
                    @if ($job->partnerProfile?->contact_email || $job->partnerProfile?->contact_phone || $job->partnerProfile?->address)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="space-y-3 text-sm">
                                @if ($job->partnerProfile?->contact_email)
                                    <div>
                                        <div class="text-gray-500 text-xs font-medium mb-1">Email</div>
                                        <a href="mailto:{{ $job->partnerProfile->contact_email }}"
                                            class="text-primary hover:text-blue-700 break-all text-xs sm:text-sm font-medium">
                                            {{ $job->partnerProfile->contact_email }}
                                        </a>
                                    </div>
                                @endif
                                @if ($job->partnerProfile?->contact_phone)
                                    <div class="@if ($job->partnerProfile->contact_email) border-t border-gray-200 pt-3 @endif">
                                        <div class="text-gray-500 text-xs font-medium mb-1">Phone</div>
                                        <a href="tel:{{ $job->partnerProfile->contact_phone }}"
                                            class="text-primary hover:text-blue-700 block break-all text-xs sm:text-sm font-medium">
                                            {{ $job->partnerProfile->contact_phone }}
                                        </a>
                                    </div>
                                @endif
                                @if ($job->partnerProfile?->address)
                                    <div class="@if ($job->partnerProfile->contact_email || $job->partnerProfile->contact_phone) border-t border-gray-200 pt-3 @endif">
                                        <div class="text-gray-500 text-xs font-medium mb-1">Address</div>
                                        <p class="text-gray-900 text-xs sm:text-sm">{{ $job->partnerProfile->address }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Share Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Share This Job</h3>
                        <div class="space-y-2">
                            <button onclick="shareJob('facebook')"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                                Share on Facebook
                            </button>
                            <button onclick="shareJob('twitter')"
                                class="w-full px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 text-sm font-medium transition-colors">
                                Share on Twitter
                            </button>
                            <button onclick="shareJob('linkedin')"
                                class="w-full px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 text-sm font-medium transition-colors">
                                Share on LinkedIn
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('users.alumni.jobs.application-modal')
    @include('users.alumni.jobs.withdraw-modal')
    @include('users.alumni.jobs.script')
@endsection
