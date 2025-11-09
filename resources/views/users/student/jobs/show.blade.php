@extends('layouts.student')

@section('title', $job->title . ' - PCU-DASMA Connect')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('student.jobs.index') }}"
                    class="inline-flex items-center text-primary hover:text-blue-700 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Job Opportunities
                </a>
            </div>

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
                                    class="font-semibold break-words truncate">{{ $job->partnerProfile->company_name ?? 'N/A' }}</span>
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

                        <!-- Action Buttons -->
                        @if (!$alreadyApplied)
                            <button onclick="applyForJob()"
                                class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                                Apply Now
                            </button>
                        @else
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-700 font-medium">You have already applied for this position
                                    </p>
                                </div>
                                <!-- Withdraw Button -->
                                <form
                                    action="{{ route('student.applications.destroy', auth()->user()->jobApplications()->where('job_posting_id', $job->id)->first()->id ?? '') }}"
                                    method="POST" class="inline"
                                    data-application-id="{{ auth()->user()->jobApplications()->where('job_posting_id', $job->id)->first()->id ?? '' }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openWithdrawModal(this.form.dataset.applicationId)"
                                        class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors text-sm">
                                        Withdraw Application
                                    </button>
                                </form>

                            </div>
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
                                <svg id="descriptionIcon" class="w-4 h-4 transition-transform duration-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
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
                                                    {{ $relatedJob->partnerProfile->company_name ?? 'N/A' }} ·
                                                    {{ $relatedJob->location ?? 'Remote' }}</p>
                                            </div>
                                            <a href="{{ route('student.jobs.show', $relatedJob->id) }}"
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
                            @if ($job->partnerProfile->company_name)
                                <div>
                                    <div class="text-gray-500 text-xs font-medium mb-1">Company Name</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->company_name }}
                                    </div>
                                </div>
                            @endif

                            @if ($job->partnerProfile->industry)
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="text-gray-500 text-xs font-medium mb-1">Industry</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->industry }}</div>
                                </div>
                            @endif

                            @if ($job->partnerProfile->company_size)
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="text-gray-500 text-xs font-medium mb-1">Company Size</div>
                                    <div class="font-semibold text-gray-900">{{ $job->partnerProfile->company_size }}
                                    </div>
                                </div>
                            @endif

                            @if ($job->partnerProfile->founded_year)
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
                                {{ $job->partnerProfile->company_description ?? 'Information about the company is not available.' }}
                            </p>
                        </div>
                        @if ($job->partnerProfile->company_description && strlen($job->partnerProfile->company_description) > 300)
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
                        @if ($job->partnerProfile->company_website)
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
                    @if ($job->partnerProfile->contact_email || $job->partnerProfile->contact_phone || $job->partnerProfile->address)
                        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="space-y-3 text-sm">
                                @if ($job->partnerProfile->contact_email)
                                    <div>
                                        <div class="text-gray-500 text-xs font-medium mb-1">Email</div>
                                        <a href="mailto:{{ $job->partnerProfile->contact_email }}"
                                            class="text-primary hover:text-blue-700 break-all text-xs sm:text-sm font-medium">
                                            {{ $job->partnerProfile->contact_email }}
                                        </a>
                                    </div>
                                @endif
                                @if ($job->partnerProfile->contact_phone)
                                    <div class="@if ($job->partnerProfile->contact_email) border-t border-gray-200 pt-3 @endif">
                                        <div class="text-gray-500 text-xs font-medium mb-1">Phone</div>
                                        <a href="tel:{{ $job->partnerProfile->contact_phone }}"
                                            class="text-primary hover:text-blue-700 block break-all text-xs sm:text-sm font-medium">
                                            {{ $job->partnerProfile->contact_phone }}
                                        </a>
                                    </div>
                                @endif
                                @if ($job->partnerProfile->address)
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
    <!-- Application Modal -->
    <div id="applicationModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeApplicationModal()"></div>

            <!-- Modal -->
            <div
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 break-words line-clamp-2" id="modal-title">
                        Apply for {{ $job->title }}
                    </h3>
                    <button onclick="closeApplicationModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="applicationForm" action="{{ route('student.jobs.apply', $job->id) }}" method="POST"
                    enctype="multipart/form-data"
                    class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-96 sm:max-h-none overflow-y-auto">
                    @csrf

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Cover Letter -->
                    <div>
                        <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Letter
                            <span class="text-red-600">*</span>
                        </label>
                        <textarea id="cover_letter" name="cover_letter" rows="5" required
                            class="w-full px-3 py-2 border @error('cover_letter') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none"
                            placeholder="Tell us why you're interested and why you'd be a great fit..." value="{{ old('cover_letter') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                        @error('cover_letter')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18.101 12.93a1 1 0 00-1.414-1.414L9 18.586 4.707 14.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l9-9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Resume Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Resume / CV
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="space-y-2">
                            @if (auth()->user()->studentProfile && auth()->user()->studentProfile->resume_path)
                                <div
                                    class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="radio" name="resume_option" value="existing" id="existingResume"
                                        checked class="h-4 w-4 text-primary flex-shrink-0">
                                    <label for="existingResume" class="ml-3 flex-1 cursor-pointer min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Use Current Resume</p>
                                        <p class="text-xs text-gray-600 truncate">
                                            {{ basename(auth()->user()->studentProfile->resume_path) }}</p>
                                    </label>
                                </div>
                            @endif
                            <div
                                class="flex items-start p-3 bg-gray-50 border @error('resume_file') border-red-500 @else border-gray-200 @enderror rounded-lg">
                                <input type="radio" name="resume_option" value="upload" id="uploadResume"
                                    @if (!auth()->user()->studentProfile || !auth()->user()->studentProfile->resume_path) checked @endif
                                    class="mt-1 h-4 w-4 text-primary flex-shrink-0">
                                <label for="uploadResume" class="ml-3 flex-1 cursor-pointer min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Upload New Resume</p>
                                    <p class="text-xs text-gray-600">Max 5MB (PDF, DOC, DOCX)</p>
                                    <input type="file" name="resume_file" id="resumeFile" accept=".pdf,.doc,.docx"
                                        class="mt-2 w-full text-xs"
                                        onchange="document.getElementById('uploadResume').checked=true;">
                                </label>
                            </div>
                        </div>
                        @error('resume_file')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Additional Documents -->
                    <div>
                        <label for="additional_documents" class="block text-sm font-medium text-gray-700 mb-2">Additional
                            Documents (Optional)</label>
                        <p class="text-xs text-gray-600 mb-2">Portfolio, certificates, etc.</p>
                        <input type="file" name="additional_documents[]" id="additional_documents" multiple
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full text-xs @error('additional_documents') border-red-500 @endif"
                    >
                    <p class="text-xs text-gray-500 mt-1">Max 10MB each</p>
                    @error('additional_documents')
                        <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div class="bg-blue-50
                            border border-blue-200 rounded-lg p-3">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="confirmApplication" id="confirmApplication" required
                                class="mt-1 h-4 w-4 text-primary flex-shrink-0">
                            <span class="ml-2 text-xs sm:text-sm text-gray-700">I confirm that the information is accurate
                                and understand that false information may disqualify my application.</span>
                        </label>
                        @error('confirmApplication')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeApplicationModal()"
                            class="w-full sm:flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Confirmation Modal -->
    <div id="withdrawConfirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="withdraw-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeWithdrawModal()"></div>

            <!-- Modal -->
            <div
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                <!-- Icon -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mt-6 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>

                <!-- Modal Body -->
                <div class="px-4 sm:px-6 py-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2" id="withdraw-modal-title">
                        Withdraw Application?
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">
                        This action cannot be undone. Your application will be permanently deleted and you can apply again
                        if needed.
                    </p>

                    <!-- Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        <button type="button" onclick="closeWithdrawModal()"
                            class="w-full sm:flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="submitWithdraw()"
                            class="w-full sm:flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm transition-colors">
                            Yes, Withdraw
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let withdrawFormElement = null;

        function applyForJob() {
            document.getElementById('applicationModal').classList.remove('hidden');
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').classList.add('hidden');
            document.getElementById('applicationForm').reset();
        }

        function openWithdrawModal(applicationId) {
            withdrawFormElement = document.querySelector(`form[data-application-id="${applicationId}"]`);
            document.getElementById('withdrawConfirmModal').classList.remove('hidden');
        }

        function closeWithdrawModal() {
            document.getElementById('withdrawConfirmModal').classList.add('hidden');
            withdrawFormElement = null;
        }

        function submitWithdraw() {
            if (withdrawFormElement) {
                withdrawFormElement.submit();
            }
        }

        function toggleDescription() {
            const container = document.getElementById('descriptionContainer');
            const toggle = document.getElementById('descriptionToggle');
            const icon = document.getElementById('descriptionIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleRequirements() {
            const container = document.getElementById('requirementsContainer');
            const toggle = document.getElementById('requirementsToggle');
            const icon = document.getElementById('requirementsIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleBenefits() {
            const container = document.getElementById('benefitsContainer');
            const toggle = document.getElementById('benefitsToggle');
            const icon = document.getElementById('benefitsIcon');

            if (container.style.maxHeight === '400px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '400px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleCompanyDesc() {
            const container = document.getElementById('companyDescContainer');
            const toggle = document.getElementById('companyDescToggle');
            const icon = document.getElementById('companyDescIcon');

            if (container.style.maxHeight === '200px') {
                container.style.maxHeight = 'none';
                toggle.textContent = 'Read Less';
                icon.style.transform = 'rotate(180deg)';
            } else {
                container.style.maxHeight = '200px';
                toggle.textContent = 'Read More';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function shareJob(platform) {
            const jobTitle = "{{ $job->title }}";
            const jobUrl = window.location.href;
            const shareText = `Check out this job opportunity: ${jobTitle}`;

            let url;
            if (platform === 'facebook') {
                url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(jobUrl)}`;
            } else if (platform === 'twitter') {
                url =
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(jobUrl)}`;
            } else if (platform === 'linkedin') {
                url = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(jobUrl)}`;
            }

            if (url) {
                window.open(url, '_blank', 'width=600,height=400');
            }
        }

        // Close modal when clicking outside
        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApplicationModal();
            }
        });

        document.getElementById('withdrawConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWithdrawModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApplicationModal();
                closeWithdrawModal();
            }
        });
    </script>

@endsection
