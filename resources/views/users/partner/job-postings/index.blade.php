@extends('layouts.partner')

@section('title', 'Job Postings - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
    <div class="mb-4 sm:mb-0">
        <h1 class="text-3xl font-bold text-gray-900">Job Postings & Opportunities</h1>
        <p class="text-gray-600">Manage your job postings and internship opportunities</p>
    </div>
    <a
        href="{{ route('partner.job-postings.create') }}"
        class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center sm:justify-start transition-colors duration-200"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        New Job Posting
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Approved (Active/Paused)</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $approved_count ?? 2 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Applications</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_applications ?? 50 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending Review</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 3 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $rejected_count ?? 2 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <input
        type="text"
        id="searchInput"
        placeholder="Search by job title or department..."
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
        oninput="searchPostings()"
    />
</div>

<!-- Filter Tabs -->
<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
            <button
                onclick="filterPostings('all')"
                class="job-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="true"
            >
                All ({{ $total_jobs ?? 9 }})
            </button>
            <button
                onclick="filterPostings('approved')"
                class="job-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Approved ({{ $approved_count ?? 2 }})
            </button>
            <button
                onclick="filterPostings('pending')"
                class="job-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Pending ({{ $pending_count ?? 3 }})
            </button>
            <button
                onclick="filterPostings('rejected')"
                class="job-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Rejected ({{ $rejected_count ?? 2 }})
            </button>
            <button
                onclick="filterPostings('completed')"
                class="job-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                role="tab"
                aria-selected="false"
            >
                Completed ({{ $completed_count ?? 2 }})
            </button>
        </nav>
    </div>
</div>

<!-- Job Postings List -->
<div id="postingsContainer" class="space-y-6">
    @forelse($jobs as $job)
        <div
            class="bg-white rounded-lg shadow-sm p-6 border-l-4 @if($job->status === 'pending') border-yellow-500 @elseif($job->status === 'approved') @if($job->sub_status === 'paused') border-blue-500 @else border-green-500 @endif @elseif($job->status === 'rejected') border-red-500 @else border-gray-500 @endif status-{{ $job->status }}"
            data-status="{{ $job->status }}"
            data-id="{{ $job->id }}"
        >
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center mb-2">
                        <span class="@if($job->status === 'pending') bg-yellow-100 text-yellow-800 @elseif($job->status === 'approved') @if($job->sub_status === 'paused') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif @elseif($job->status === 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs mr-3 mb-1">
                            @if($job->status === 'approved')
                                Approved ({{ ucfirst($job->sub_status) }})
                            @else
                                {{ ucfirst($job->status) }}
                            @endif
                        </span>
                        <span class="@if($job->job_type === 'Full-time') bg-blue-100 text-blue-800 @elseif($job->job_type === 'Part-time') bg-green-100 text-green-800 @else bg-purple-100 text-purple-800 @endif px-2 py-1 rounded-full text-xs mr-3 mb-1">
                            {{ $job->job_type }}
                        </span>
                        <span class="text-sm text-gray-500 mb-1">
                            @if($job->status === 'pending')
                                Submitted {{ $job->created_at->format('F j, Y') }}
                            @elseif($job->status === 'approved')
                                Posted {{ $job->created_at->format('F j, Y') }} • Expires {{ $job->expiry_date->format('F j, Y') }}
                            @elseif($job->status === 'completed')
                                Completed {{ $job->closed_at->format('F j, Y') }} • Posted {{ $job->created_at->format('F j, Y') }}
                            @else
                                {{ $job->created_at->format('F j, Y') }}
                            @endif
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>
                    <p class="text-gray-600 mb-3">{{ $job->department }} • {{ $job->location }} • {{ $job->work_arrangement }}</p>
                    <p class="text-lg font-semibold text-green-600 mb-3">₱{{ number_format($job->salary_min) }} - ₱{{ number_format($job->salary_max) }} monthly</p>

                    @if($job->status === 'pending')
                        <p class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded">
                            This posting is currently being reviewed by our administrators. You'll receive an email notification once it's approved.
                        </p>
                    @elseif($job->status === 'approved')
                        <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500">
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ $job->applications_count ?? 0 }} applications
                            </div>
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $job->positions_available ?? 0 }} positions available
                            </div>
                        </div>
                    @elseif($job->status === 'completed')
                        <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500">
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ $job->applications_count ?? 0 }} applications
                            </div>
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $job->positions_filled ?? 0 }} positions filled
                            </div>
                        </div>
                    @elseif($job->status === 'rejected')
                        <p class="text-sm text-red-700 bg-red-50 p-3 rounded">
                            This posting was rejected by administrators. Please review the feedback and edit to resubmit.
                        </p>
                    @endif
                </div>

                <div class="mt-4 lg:mt-0 lg:ml-4 flex flex-col space-y-2">
                    @if($job->status === 'pending')
                        <a href="{{ route('partner.job-postings.show', $job->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>
                    @elseif($job->status === 'approved')
                        <a href="{{ route('partner.job-postings.show', $job->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>
                        <a href="" class="px-4 py-2 bg-secondary text-white text-sm rounded-md hover:bg-blue-500 transition-colors duration-200 text-center">
                            View Applications
                        </a>
                        <a href="{{ route('partner.job-postings.edit', $job->id) }}" class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center">
                            Edit Posting
                        </a>
                        @if($job->sub_status === 'paused')
                            <button onclick="resumePosting({{ $job->id }})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors duration-200">
                                Resume Posting
                            </button>
                        @else
                            <button onclick="pausePosting({{ $job->id }})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors duration-200">
                                Pause Posting
                            </button>
                        @endif
                        <button onclick="closePosting({{ $job->id }})" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                            Close Posting
                        </button>
                    @elseif($job->status === 'rejected')
                        <a href="{{ route('partner.job-postings.show', $job->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>
                        <a href="{{ route('partner.job-postings.edit', $job->id) }}" class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center">
                            Edit Posting
                        </a>
                    @else
                        <a href="{{ route('partner.job-postings.show', $job->id) }}" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>
                        <a href="" class="px-4 py-2 bg-secondary text-white text-sm rounded-md hover:bg-blue-500 transition-colors duration-200 text-center">
                            View Applications
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Job Postings</h3>
            <p class="text-gray-600 mb-6">You haven't created any job postings yet. Start by creating your first posting!</p>
            <a href="{{ route('partner.job-postings.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Job Posting
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($jobs->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $jobs->links() }}
    </div>
@endif

<!-- Close Confirmation Modal -->
<div id="closeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Close Job Posting</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to close this job posting? It will be moved to the Completed tab and become view-only.</p>
        <div class="flex justify-end space-x-4">
            <button
                id="cancelCloseBtn"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200"
            >
                Cancel
            </button>
            <button
                id="confirmCloseBtn"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
            >
                Close Job
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/partner/job-postings.js') }}"></script>
@endsection
