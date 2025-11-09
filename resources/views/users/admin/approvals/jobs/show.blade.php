@extends('layouts.admin')

@section('title', $jobPosting->title . ' - Job Details - PCU-DASMA Connect')

@section('content')
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.approvals.jobs.index') }}"
                    class="text-gray-400 hover:text-gray-600 mr-4 focus:outline-none focus:ring-2 focus:ring-primary"
                    aria-label="Back to job postings">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 flex-1">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 break-words">{{ $jobPosting->title }}</h1>
                        <p class="text-gray-600 mt-2">Job posting details and management</p>
                    </div>
                    <div class="flex items-center space-x-2 mt-2 sm:mt-0 flex-wrap gap-2">
                        @if ($jobPosting->is_featured)
                            <span
                                class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                        @endif
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium
                        @if ($jobPosting->status === 'approved') bg-green-100 text-green-800
                        @elseif($jobPosting->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($jobPosting->status === 'rejected') bg-red-100 text-red-800 @endif">
                            {{ ucfirst($jobPosting->status) }}
                        </span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $jobPosting->getJobTypeDisplay() }}</span>
                        <span
                            class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">{{ $jobPosting->getExperienceLevelDisplay() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if ($jobPosting->status === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-800">Job Post Approved & Published</h4>
                        <p class="text-sm text-green-700">
                            This job posting was approved on
                            {{ $jobPosting->approved_at ? $jobPosting->approved_at->format('F d, Y') : 'N/A' }}.
                            You can unpublish or feature the posting if needed.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($jobPosting->status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-yellow-800">Pending Review</h4>
                        <p class="text-sm text-yellow-700">This job posting is waiting for admin approval.</p>
                    </div>
                </div>
            </div>
        @elseif($jobPosting->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-800">Job Post Rejected</h4>
                        <p class="text-sm text-red-700">{{ $jobPosting->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Two-column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Job Content -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Description</h2>
                    <div class="prose max-w-none space-y-4">
                        <p class="text-gray-700 whitespace-pre-wrap break-words">{{ $jobPosting->description }}</p>

                        @php
                            $skills = is_string($jobPosting->technical_skills)
                                ? json_decode($jobPosting->technical_skills, true)
                                : ($jobPosting->technical_skills ?? []);
                            $skills = is_array($skills) ? $skills : [];
                        @endphp

                        @if(!empty($skills))
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Technical Skills Required</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($skills as $skill)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-lg break-words inline-block">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($jobPosting->education_requirements)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Education Requirements</h3>
                                <p class="text-gray-700 whitespace-pre-wrap break-words">{{ $jobPosting->education_requirements }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->experience_requirements)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Experience Requirements</h3>
                                <p class="text-gray-700 whitespace-pre-wrap break-words">{{ $jobPosting->experience_requirements }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->benefits)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Benefits & Perks</h3>
                                <p class="text-gray-700 whitespace-pre-wrap break-words">{{ $jobPosting->benefits }}</p>
                            </div>
                        @endif

                        @if ($jobPosting->application_process)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Application Process</h3>
                                <p class="text-gray-700 whitespace-pre-wrap break-words">{{ $jobPosting->application_process }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Review Timeline -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Timeline</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full"></div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Job Submitted</p>
                                <p class="text-xs text-gray-500">{{ $jobPosting->created_at->format('F d, Y - g:i A') }}
                                </p>
                            </div>
                        </div>

                        @if ($jobPosting->status === 'approved')
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Job Approved</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $jobPosting->approved_at ? $jobPosting->approved_at->format('F d, Y - g:i A') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Job Published</p>
                                    <p class="text-xs text-gray-500">Current status</p>
                                </div>
                            </div>
                        @elseif($jobPosting->status === 'rejected')
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Job Rejected</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $jobPosting->rejected_at ? $jobPosting->rejected_at->format('F d, Y - g:i A') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Review Actions -->
                @if ($jobPosting->status === 'pending')
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Actions</h2>
                        <div class="space-y-3">
                            <button type="button" onclick="openApproveModal()"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 font-medium text-sm transition-colors">
                                Approve & Publish
                            </button>
                            <button type="button" onclick="openRejectModal()"
                                class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 font-medium text-sm transition-colors">
                                Reject
                            </button>
                        </div>
                    </div>
                @elseif($jobPosting->status === 'approved')
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Post Actions</h2>
                        <div class="space-y-3">
                            <!-- ✅ Feature Button with Confirmation Modal -->
                            <button type="button" id="featureBtn" onclick="openFeatureModal()"
                                class="w-full px-4 py-2 border border-yellow-300 text-yellow-700 rounded-md hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 flex items-center justify-center font-medium text-sm transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                {{ $jobPosting->is_featured ? 'Unfeature Job' : 'Feature Job' }}
                            </button>
                            <button type="button" onclick="openUnpublishModal()"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 font-medium text-sm transition-colors">
                                Unpublish Job
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Company Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Company Name</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->partnerProfile->company_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Contact Person</p>
                            <p class="text-sm text-gray-600">
                                {{ $jobPosting->partnerProfile->contact_person ?? ($jobPosting->partner->getFullNameAttribute() ?? 'N/A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <p class="text-sm text-gray-600 break-words">{{ $jobPosting->partner->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Phone</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->partnerProfile->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Partnership Status</p>
                            <span class="text-sm text-gray-600 px-2 py-1 bg-green-100 text-green-800 rounded">Active Partner</span>
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Job Type</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->getJobTypeDisplay() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Department</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->department ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Experience Level</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->getExperienceLevelDisplay() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Work Setup</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->getWorkSetupDisplay() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->location ?? 'Remote' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Compensation</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->getSalaryRangeDisplay() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Positions Available</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->positions_available }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Application Deadline</p>
                            <p class="text-sm text-gray-600">{{ $jobPosting->application_deadline->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeApproveModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <form action="{{ route('admin.approvals.jobs.approve', $jobPosting->id) }}" method="POST">
                    @csrf
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Approve Job Posting</h3>
                        <p class="text-sm text-gray-600">
                            Are you sure you want to approve and publish this job posting? It will become visible to all alumni immediately.
                        </p>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeApproveModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                            Approve & Publish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <form action="{{ route('admin.approvals.jobs.reject', $jobPosting->id) }}" method="POST">
                    @csrf
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Job Posting</h3>

                        <div class="text-left">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason <span class="text-red-500">*</span>
                            </label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                placeholder="Explain why this job posting is being rejected..."></textarea>
                            @error('rejection_reason')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ✅ Feature/Unfeature Confirmation Modal (NEW) -->
    <div id="featureModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeFeatureModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <form id="featureForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2" id="featureModalTitle">Feature Job Posting</h3>
                        <p class="text-sm text-gray-600" id="featureModalMessage">
                            Are you sure you want to feature this job posting? Featured jobs are displayed prominently on the platform.
                        </p>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeFeatureModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm font-medium" id="featureConfirmBtn">
                            Feature Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Unpublish Modal -->
    <div id="unpublishModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeUnpublishModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <form id="unpublishForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Unpublish Job Posting</h3>
                        <p class="text-sm text-gray-600 mb-4">Please provide a reason for unpublishing this job posting.
                        </p>

                        <div class="text-left">
                            <textarea name="unpublish_reason" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                placeholder="Enter reason for unpublishing"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeUnpublishModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">
                            Unpublish Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal() {
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }

        // ✅ Feature Modal Functions (NEW)
        function openFeatureModal() {
            const isFeatured = {{ $jobPosting->is_featured ? 'true' : 'false' }};
            const form = document.getElementById('featureForm');
            const title = document.getElementById('featureModalTitle');
            const message = document.getElementById('featureModalMessage');
            const button = document.getElementById('featureConfirmBtn');

            if (isFeatured) {
                form.action = '{{ route("admin.jobs.unfeature", $jobPosting->id) }}';
                title.textContent = 'Unfeature Job Posting';
                message.textContent = 'Are you sure you want to unfeature this job posting? It will no longer be displayed prominently.';
                button.textContent = 'Unfeature Job';
            } else {
                form.action = '{{ route("admin.jobs.feature", $jobPosting->id) }}';
                title.textContent = 'Feature Job Posting';
                message.textContent = 'Are you sure you want to feature this job posting? Featured jobs are displayed prominently on the platform.';
                button.textContent = 'Feature Job';
            }

            document.getElementById('featureModal').classList.remove('hidden');
        }

        function closeFeatureModal() {
            document.getElementById('featureModal').classList.add('hidden');
        }

        function openUnpublishModal() {
            document.getElementById('unpublishForm').action = '{{ route("admin.jobs.unpublish", $jobPosting->id) }}';
            document.getElementById('unpublishModal').classList.remove('hidden');
        }

        function closeUnpublishModal() {
            document.getElementById('unpublishModal').classList.add('hidden');
        }

        // Close modals on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
                closeFeatureModal();
                closeUnpublishModal();
            }
        });
    </script>
@endsection
