@extends('layouts.staff')

@section('title', 'Job Details - PCU-DASMA Connect')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('staff.jobs.index') }}" class="inline-flex items-center text-base text-gray-600 hover:text-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Jobs
            </a>
        </div>

        <!-- Job Details Container -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <!-- Header Section -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <!-- Badges -->
                <div class="flex flex-wrap items-center mb-4 gap-2">
                    @if($job->is_featured)
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                    @endif
                    <span class="@if($job->job_type === 'fulltime') bg-green-100 text-green-800 @elseif($job->job_type === 'parttime') bg-blue-100 text-blue-800 @elseif($job->job_type === 'internship') bg-purple-100 text-purple-800 @else bg-orange-100 text-orange-800 @endif px-3 py-1 rounded-full text-sm font-medium">
                        {{ $job->getJobTypeDisplay() ?? 'Job' }}
                    </span>
                </div>

                <!-- Job Title -->
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $job->title }}</h1>

                <!-- Key Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Department</p>
                        <p class="font-semibold text-gray-900">{{ $job->department ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Location</p>
                        <p class="font-semibold text-gray-900">{{ $job->location ?? 'Remote' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Posted</p>
                        <p class="font-semibold text-gray-900">{{ $job->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Applicants</p>
                        <p class="font-semibold text-gray-900">{{ $job->applications()->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="space-y-8">
                <!-- Salary & Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-blue-600 text-sm font-medium mb-1">Salary Range</p>
                        <p class="text-lg font-bold text-gray-900">{{ $job->getSalaryRangeDisplay() ?? 'Negotiable' }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <p class="text-green-600 text-sm font-medium mb-1">Experience Level</p>
                        <p class="text-lg font-bold text-gray-900 capitalize">{{ $job->experience_level ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                        <p class="text-purple-600 text-sm font-medium mb-1">Work Setup</p>
                        <p class="text-lg font-bold text-gray-900 capitalize">{{ $job->work_setup ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Job Description -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ $job->description ?? 'No description provided.' }}
                    </div>
                </div>

                <!-- Requirements -->
                @if($job->requirements)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $job->requirements }}
                        </div>
                    </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Benefits</h2>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $job->benefits }}
                        </div>
                    </div>
                @endif

                <!-- Application Deadline -->
                @if($job->application_deadline)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-900 mb-1">Application Deadline</h3>
                                <p class="text-yellow-800">
                                    {{ \Carbon\Carbon::parse($job->application_deadline)->format('F j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contact Information -->
                @if($job->contact_email || $job->contact_phone || $job->contact_person)
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h2>
                        <div class="space-y-3">
                            @if($job->contact_person)
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Contact Person</p>
                                    <p class="font-semibold text-gray-900">{{ $job->contact_person }}</p>
                                </div>
                            @endif
                            @if($job->contact_email)
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Email</p>
                                    <a href="mailto:{{ $job->contact_email }}" class="text-primary hover:underline font-medium break-all">
                                        {{ $job->contact_email }}
                                    </a>
                                </div>
                            @endif
                            @if($job->contact_phone)
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Phone</p>
                                    <a href="tel:{{ $job->contact_phone }}" class="text-primary hover:underline font-medium">
                                        {{ $job->contact_phone }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Staff Information Note -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Staff Information</p>
                            <p class="text-xs text-blue-800 mt-1">You are viewing this job for informational purposes only. To apply, please use your alumni or student account.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
