@extends('layouts.partner')

@section('title', 'View Job - ' . $jobPosting->title . ' - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-4 gap-4">
            <a href="{{ route('partner.job-postings.index') }}" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $jobPosting->title }}</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">
                    @if($jobPosting->status === 'pending')
                        Review your pending job posting submission
                    @elseif($jobPosting->status === 'approved')
                        @if($jobPosting->sub_status === 'paused')
                            This job posting is currently paused
                        @else
                            Manage your approved job posting
                        @endif
                    @elseif($jobPosting->status === 'rejected')
                        Review your rejected job posting
                    @else
                        Review your completed job posting
                    @endif
                </p>

                <!-- Badges - Responsive -->
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    @if($jobPosting->status === 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Pending</span>
                    @elseif($jobPosting->status === 'approved')
                        @if($jobPosting->sub_status === 'paused')
                            <span class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Paused</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Approved</span>
                        @endif
                    @elseif($jobPosting->status === 'rejected')
                        <span class="bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Rejected</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Completed</span>
                    @endif
                    <span class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">{{ $jobPosting->getJobTypeDisplay() }}</span>
                    <span class="bg-gray-100 text-gray-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">{{ $jobPosting->getExperienceLevelDisplay() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Alerts -->
    @if($jobPosting->status === 'pending')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="min-w-0">
                    <h4 class="font-semibold text-yellow-800">Awaiting Admin Review</h4>
                    <p class="text-sm text-yellow-700 mt-1 break-words">
                        This job posting was submitted {{ $jobPosting->created_at->diffForHumans() }} and is pending administrative approval. You can edit or withdraw the posting until a decision is made.
                    </p>
                </div>
            </div>
        </div>
    @elseif($jobPosting->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <div class="min-w-0">
                    <h4 class="font-semibold text-red-800">Job Posting Rejected</h4>
                    <p class="text-sm text-red-700 mt-1 break-words">
                        This job posting was rejected on {{ $jobPosting->updated_at->format('F d, Y - g:i A') }}. Reason: {{ $jobPosting->rejection_reason ?? 'No reason provided' }}. Please review and revise the posting or contact the admin for further details.
                    </p>
                </div>
            </div>
        </div>
    @elseif($jobPosting->status === 'approved')
        <div class="@if($jobPosting->sub_status === 'paused') bg-blue-50 border-blue-200 @else bg-green-50 border-green-200 @endif border rounded-lg p-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                <svg class="w-5 h-5 @if($jobPosting->sub_status === 'paused') text-blue-600 @else text-green-600 @endif flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="min-w-0">
                    <h4 class="font-semibold @if($jobPosting->sub_status === 'paused') text-blue-800 @else text-green-800 @endif">
                        Job Posting @if($jobPosting->sub_status === 'paused') Paused @else Approved @endif
                    </h4>
                    <p class="text-sm @if($jobPosting->sub_status === 'paused') text-blue-700 @else text-green-700 @endif mt-1 break-words">
                        @if($jobPosting->sub_status === 'paused')
                            This job posting is currently paused and not visible to new applicants. You can resume it at any time.
                        @else
                            This job posting was approved on {{ $jobPosting->updated_at->format('F d, Y - g:i A') }} and is now live for applicants. You can view applications, edit, pause, or close the posting as needed.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="min-w-0">
                    <h4 class="font-semibold text-gray-800">Job Posting Completed</h4>
                    <p class="text-sm text-gray-700 mt-1 break-words">
                        This job posting was completed on {{ $jobPosting->closed_at ? $jobPosting->closed_at->format('F d, Y - g:i A') : $jobPosting->updated_at->format('F d, Y - g:i A') }}. You can still view applications received during its active period.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Two-column Layout - Responsive -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Job Content -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Job Description</h2>
                <div class="space-y-4 min-w-0">
                    <p class="text-gray-700 text-sm sm:text-base leading-relaxed break-words whitespace-pre-wrap">{{ $jobPosting->description }}</p>

                    @php
                        // Decode technical_skills properly
                        $skills = is_string($jobPosting->technical_skills)
                            ? json_decode($jobPosting->technical_skills, true)
                            : ($jobPosting->technical_skills ?? []);
                        $skills = is_array($skills) ? $skills : [];
                        $hasRequirements = $jobPosting->education_requirements || $jobPosting->experience_requirements || !empty($skills);
                    @endphp

                    @if($hasRequirements)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Requirements</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    @if($jobPosting->education_requirements)
                                        <h4 class="text-sm sm:text-base font-medium text-gray-900 mb-3">Education & Experience</h4>
                                        <div class="text-gray-700 text-sm sm:text-base space-y-1 break-words whitespace-pre-wrap">{{ $jobPosting->education_requirements }}</div>
                                    @endif

                                    @if($jobPosting->experience_requirements)
                                        <h4 class="text-sm sm:text-base font-medium text-gray-900 mb-3 mt-4 sm:mt-0">Additional Experience</h4>
                                        <div class="text-gray-700 text-sm sm:text-base space-y-1 break-words whitespace-pre-wrap">{{ $jobPosting->experience_requirements }}</div>
                                    @endif
                                </div>

                                @if(!empty($skills))
                                    <div>
                                        <h4 class="text-sm sm:text-base font-medium text-gray-900 mb-3">Technical Skills</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($skills as $skill)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded break-words">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($jobPosting->benefits)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Benefits & Perks</h3>
                            <div class="text-gray-700 text-sm sm:text-base break-words whitespace-pre-wrap">{{ $jobPosting->benefits }}</div>
                        </div>
                    @endif

                    @if($jobPosting->application_process)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Application Process</h3>
                            <div class="text-gray-700 text-sm sm:text-base break-words whitespace-pre-wrap">{{ $jobPosting->application_process }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Timeline -->
            @if(in_array($jobPosting->status, ['pending', 'rejected', 'approved', 'completed']))
                <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-lg font-semibold text-gray-900 mb-4">Review Timeline</h2>
                    <div class="space-y-4 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-3 h-3 bg-green-400 rounded-full flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Job Submitted</p>
                                    <p class="text-xs text-gray-500 break-words">{{ $jobPosting->created_at->format('F d, Y - g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($jobPosting->status === 'pending')
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Pending Admin Review</p>
                                        <p class="text-xs text-gray-500">Current status</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                                <p class="text-sm text-gray-500">Awaiting Decision</p>
                            </div>
                        @elseif($jobPosting->status === 'rejected')
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-3 h-3 bg-red-400 rounded-full flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-red-900">Rejected by Admin</p>
                                        <p class="text-xs text-gray-500 break-words">{{ $jobPosting->updated_at->format('F d, Y - g:i A') }}</p>
                                        @if($jobPosting->rejection_reason)
                                            <p class="text-xs text-gray-600 mt-1 break-words">Reason: {{ $jobPosting->rejection_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($jobPosting->status === 'approved')
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-3 h-3 bg-green-400 rounded-full flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Approved by Admin</p>
                                        <p class="text-xs text-gray-500 break-words">{{ $jobPosting->updated_at->format('F d, Y - g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-3 h-3 bg-green-400 rounded-full flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Approved by Admin</p>
                                        <p class="text-xs text-gray-500 break-words">{{ $jobPosting->updated_at->format('F d, Y - g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Completed</p>
                                        <p class="text-xs text-gray-500 break-words">{{ $jobPosting->closed_at ? $jobPosting->closed_at->format('F d, Y - g:i A') : $jobPosting->updated_at->format('F d, Y - g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2 sm:space-y-3">
                    @if($jobPosting->status === 'approved')
                        <a href="{{ route('partner.job-postings.applications', $jobPosting->id) }}"
                            class="block w-full px-4 py-2 bg-primary text-white text-sm text-center rounded-md hover:bg-blue-700 transition-colors duration-200">
                            View Applications
                        </a>
                        <a href="{{ route('partner.job-postings.edit', $jobPosting->id) }}"
                            class="block w-full px-4 py-2 bg-primary text-white text-sm text-center rounded-md hover:bg-blue-700 transition-colors duration-200">
                            Edit
                        </a>

                        @if($jobPosting->sub_status === 'paused')
                            <button onclick="openResumeModal()"
                                class="w-full px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700 transition-colors duration-200">
                                Resume
                            </button>
                        @else
                            <button onclick="openPauseModal()"
                                class="w-full px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700 transition-colors duration-200">
                                Pause
                            </button>
                        @endif

                        <button onclick="openCloseModal()"
                            class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                            Close Job
                        </button>
                    @elseif($jobPosting->status === 'pending' || $jobPosting->status === 'rejected')
                        <a href="{{ route('partner.job-postings.edit', $jobPosting->id) }}"
                            class="block w-full px-4 py-2 bg-primary text-white text-sm text-center rounded-md hover:bg-blue-700 transition-colors duration-200">
                            Edit {{ $jobPosting->status === 'rejected' ? '& Resubmit' : '' }}
                        </a>

                        @if($jobPosting->status === 'pending')
                            <button onclick="openWithdrawModal()"
                                class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                Withdraw
                            </button>
                        @endif
                    @else
                        <a href="{{ route('partner.job-postings.applications', $jobPosting->id) }}"
                            class="block w-full px-4 py-2 bg-primary text-white text-sm text-center rounded-md hover:bg-blue-700 transition-colors duration-200">
                            View Applications
                        </a>
                    @endif
                </div>
            </div>

            <!-- Job Details -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h2>
                <div class="space-y-3 min-w-0">
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Job Type</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->getJobTypeDisplay() }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Department</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->department ?? 'N/A' }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Experience Level</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->getExperienceLevelDisplay() }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Work Setup</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->getWorkSetupDisplay() }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Location</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->location ?? 'Remote' }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Compensation</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->getSalaryRangeDisplay() }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Positions Available</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->positions_available }}</p>
                    </div>
                    <div class="break-words">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Application Deadline</p>
                        <p class="text-sm text-gray-600">{{ $jobPosting->application_deadline->format('F d, Y') }}</p>
                    </div>
                    @if($jobPosting->status === 'approved')
                        <div class="break-words">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Total Applications</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->applications()->count() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Confirmation Modal -->
    <div id="withdrawModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeWithdrawModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <div class="px-4 sm:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto sm:mx-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="text-center sm:text-left flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900">Withdraw Job Posting</h3>
                            <p class="text-sm text-gray-500 mt-2 break-words">
                                Are you sure you want to withdraw this job posting? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <button onclick="closeWithdrawModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </button>
                    <form id="withdrawForm" action="{{ route('partner.job-postings.destroy', $jobPosting->id) }}" method="POST" class="inline w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">
                            Withdraw Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pause/Resume Confirmation Modal -->
    <div id="pauseModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePauseModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <div class="px-4 sm:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mx-auto sm:mx-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6"></path>
                            </svg>
                        </div>
                        <div class="text-center sm:text-left flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900" id="pauseModalTitle">Pause Job Posting</h3>
                            <p class="text-sm text-gray-500 mt-2 break-words" id="pauseModalMessage">
                                Are you sure you want to pause this job posting? It will no longer be visible to new applicants.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <button onclick="closePauseModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </button>
                    <form id="pauseForm" action="{{ route('partner.job-postings.pause', $jobPosting->id) }}" method="POST" class="inline w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 text-sm font-medium" id="pauseConfirmButton">
                            Pause Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Job Confirmation Modal -->
    <div id="closeModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCloseModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <div class="px-4 sm:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto sm:mx-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="text-center sm:text-left flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900">Close Job Posting</h3>
                            <p class="text-sm text-gray-500 mt-2 break-words">
                                Are you sure you want to close this job posting? It will no longer accept applications.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <button onclick="closeCloseModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </button>
                    <form action="{{ route('partner.job-postings.close', $jobPosting->id) }}" method="POST" class="inline w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">
                            Close Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Withdraw modal functions
function openWithdrawModal() {
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
}

// Pause modal functions
function openPauseModal() {
    const form = document.getElementById('pauseForm');
    form.action = '{{ route("partner.job-postings.pause", $jobPosting->id) }}';
    document.getElementById('pauseModalTitle').textContent = 'Pause Job Posting';
    document.getElementById('pauseModalMessage').textContent = 'Are you sure you want to pause this job posting? It will no longer be visible to new applicants.';
    document.getElementById('pauseConfirmButton').textContent = 'Pause Job';
    document.getElementById('pauseModal').classList.remove('hidden');
}

function openResumeModal() {
    const form = document.getElementById('pauseForm');
    form.action = '{{ route("partner.job-postings.resume", $jobPosting->id) }}';
    document.getElementById('pauseModalTitle').textContent = 'Resume Job Posting';
    document.getElementById('pauseModalMessage').textContent = 'Are you sure you want to resume this job posting? It will be visible to new applicants again.';
    document.getElementById('pauseConfirmButton').textContent = 'Resume Job';
    document.getElementById('pauseModal').classList.remove('hidden');
}

function closePauseModal() {
    document.getElementById('pauseModal').classList.add('hidden');
}

// Close modal functions
function openCloseModal() {
    document.getElementById('closeModal').classList.remove('hidden');
}

function closeCloseModal() {
    document.getElementById('closeModal').classList.add('hidden');
}

// Close modals on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeWithdrawModal();
        closePauseModal();
        closeCloseModal();
    }
});
</script>
@endsection
