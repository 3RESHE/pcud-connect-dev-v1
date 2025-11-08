@extends('layouts.partner')

@section('title', 'Job Posting Details - PCU-DASMA Connect')

@section('content')
<div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
    <div class="w-full max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">{{ $jobPosting->title }}</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">{{ $jobPosting->department }} • {{ $jobPosting->location }}</p>
            </div>
            <a href="{{ route('partner.job-postings.index') }}"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm text-center">
                ← Back to Postings
            </a>
        </div>

        <!-- Status Banner -->
        <div class="mb-6 sm:mb-8">
            <div class="@if($jobPosting->status === 'pending') bg-yellow-50 border-l-4 border-yellow-400 @elseif($jobPosting->status === 'approved') @if($jobPosting->sub_status === 'paused') bg-blue-50 border-l-4 border-blue-400 @else bg-green-50 border-l-4 border-green-400 @endif @elseif($jobPosting->status === 'rejected') bg-red-50 border-l-4 border-red-400 @else bg-gray-50 border-l-4 border-gray-400 @endif p-3 sm:p-4 rounded-r">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($jobPosting->status === 'pending')
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-yellow-800">
                                <strong>Pending Review:</strong> Your job posting is awaiting admin approval
                            </span>
                        @elseif($jobPosting->status === 'approved')
                            @if($jobPosting->sub_status === 'paused')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium text-blue-800">
                                    <strong>Paused:</strong> This posting is temporarily paused
                                </span>
                            @else
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium text-green-800">
                                    <strong>Active:</strong> Your job posting is live and accepting applications
                                </span>
                            @endif
                        @elseif($jobPosting->status === 'rejected')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-red-800">
                                <strong>Rejected:</strong> {{ $jobPosting->rejection_reason ?? 'No reason provided' }}
                            </span>
                        @else
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-gray-800">
                                <strong>Completed:</strong> This job posting has been closed
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 sm:mb-8">
            <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Basic Information</h3>
            </div>
            <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Job Type</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words capitalize">{{ str_replace('_', ' ', $jobPosting->job_type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Experience Level</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words capitalize">{{ $jobPosting->experience_level }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Department</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->department ?? $jobPosting->custom_department ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Work Setup</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words capitalize">{{ str_replace('_', ' ', $jobPosting->work_setup) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary & Timeline -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 sm:mb-8">
            <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Salary & Timeline</h3>
            </div>
            <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Salary Range</p>
                        <p class="text-sm sm:text-base font-medium text-green-600 mt-1 break-words">
                            @if($jobPosting->is_unpaid)
                                Unpaid
                            @else
                                @if($jobPosting->salary_min)
                                    ₱{{ number_format($jobPosting->salary_min) }}
                                @endif
                                @if($jobPosting->salary_min && $jobPosting->salary_max) - @endif
                                @if($jobPosting->salary_max)
                                    ₱{{ number_format($jobPosting->salary_max) }}
                                @endif
                                @if($jobPosting->salary_period)
                                    <span class="text-gray-600 text-xs">{{ $jobPosting->salary_period }}</span>
                                @endif
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Application Deadline</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->application_deadline->format('F j, Y') }}</p>
                    </div>
                    @if($jobPosting->preferred_start_date)
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Preferred Start Date</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->preferred_start_date->format('F j, Y') }}</p>
                        </div>
                    @endif
                    @if($jobPosting->duration_months)
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 break-words">Duration</p>
                            <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->duration_months }} month(s)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Job Description -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 sm:mb-8">
            <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Job Description</h3>
            </div>
            <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                <p class="text-sm sm:text-base text-gray-700 break-words whitespace-pre-wrap">{{ $jobPosting->description }}</p>
            </div>
        </div>

        <!-- Requirements -->
        @if($jobPosting->education_requirements || $jobPosting->experience_requirements || $jobPosting->technical_skills)
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 sm:mb-8">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Requirements</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                    @if($jobPosting->education_requirements)
                        <div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase mb-2">Education</p>
                            <p class="text-sm sm:text-base text-gray-700 break-words">{{ $jobPosting->education_requirements }}</p>
                        </div>
                    @endif
                    @if($jobPosting->experience_requirements)
                        <div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase mb-2">Experience</p>
                            <p class="text-sm sm:text-base text-gray-700 break-words">{{ $jobPosting->experience_requirements }}</p>
                        </div>
                    @endif
                    @if($jobPosting->technical_skills)
                        <div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase mb-2">Technical Skills</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($jobPosting->technical_skills as $skill)
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Additional Info -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 sm:mb-8">
            <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Additional Information</h3>
            </div>
            <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Positions Available</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->positions_available }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 break-words">Total Applications</p>
                        <p class="text-sm sm:text-base font-medium text-gray-900 mt-1 break-words">{{ $jobPosting->applications()->count() }}</p>
                    </div>
                </div>
                @if($jobPosting->application_process)
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase mb-2">Application Process</p>
                        <p class="text-sm sm:text-base text-gray-700 break-words">{{ $jobPosting->application_process }}</p>
                    </div>
                @endif
                @if($jobPosting->benefits)
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-gray-700 uppercase mb-2">Benefits & Perks</p>
                        <p class="text-sm sm:text-base text-gray-700 break-words">{{ $jobPosting->benefits }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                @if($jobPosting->status === 'approved')
                    <a href="{{ route('partner.job-postings.edit', $jobPosting->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm whitespace-nowrap text-center">
                        Edit Posting
                    </a>
                    @if($jobPosting->sub_status === 'paused')
                        <form action="{{ route('partner.job-postings.resume', $jobPosting->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs sm:text-sm whitespace-nowrap">
                                Resume Posting
                            </button>
                        </form>
                    @else
                        <form action="{{ route('partner.job-postings.pause', $jobPosting->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-xs sm:text-sm whitespace-nowrap">
                                Pause Posting
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('partner.job-postings.close', $jobPosting->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to close this job posting?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs sm:text-sm whitespace-nowrap">
                            Close Posting
                        </button>
                    </form>
                    <a href="{{ route('partner.job-postings.applications', $jobPosting->id) }}"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-xs sm:text-sm whitespace-nowrap text-center">
                        View Applications
                    </a>
                @elseif($jobPosting->status === 'rejected')
                    <a href="{{ route('partner.job-postings.edit', $jobPosting->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm whitespace-nowrap text-center">
                        Edit & Resubmit
                    </a>
                @endif
                <a href="{{ route('partner.job-postings.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-xs sm:text-sm whitespace-nowrap text-center">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
