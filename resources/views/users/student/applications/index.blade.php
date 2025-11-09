@extends('layouts.student')

@section('title', 'My Applications - PCU-DASMA Connect')
@section('page-title', 'My Job Applications')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">


            <!-- Header Section -->
            <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Job Applications</h1>
                    <p class="text-gray-600">Track and manage all your job applications</p>
                </div>
                <a href="{{ route('student.jobs.index') }}"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors whitespace-nowrap text-center">
                    Apply for Jobs
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Pending Applications -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Pending</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $statuses['pending'] }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Approved Applications -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Approved</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $statuses['approved'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Rejected Applications -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Rejected</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $statuses['rejected'] }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <form action="{{ route('student.applications.index') }}" method="GET">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <input type="text" name="search" placeholder="Search by job title or company..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select name="status"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">All Status</option>
                                <option value="pending" @if (request('status') === 'pending') selected @endif>Pending</option>
                                <option value="approved" @if (request('status') === 'approved') selected @endif>Approved</option>
                                <option value="rejected" @if (request('status') === 'rejected') selected @endif>Rejected</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors whitespace-nowrap">
                            Search
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('student.applications.index') }}"
                            class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium transition-colors whitespace-nowrap text-center">
                            Clear
                        </a>
                    </div>
                </form>
            </div>


            <!-- Applications List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Application Timeline</h3>
                </div>

                @forelse($applications as $application)
                    <div class="border-b border-gray-200 last:border-b-0">
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <!-- Application Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-3">
                                <div class="flex-1">
                                    <!-- Job Title -->
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $application->jobPosting?->title ?? 'Job Posting' }}
                                    </h4>
                                    <!-- Company Name -->
                                    <p class="text-gray-600 text-sm">
                                        <span class="font-medium">Company:</span>
                                        {{ $application->jobPosting?->partnerProfile?->company_name ?? 'N/A' }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <div>
                                    @if ($application->status === 'pending')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Pending Review
                                        </span>
                                    @elseif($application->status === 'approved')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($application->status === 'rejected')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Application Details -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4 text-sm text-gray-600">
                                <!-- Job Type -->
                                <div>
                                    <p class="text-gray-500 text-xs uppercase font-semibold mb-1">Job Type</p>
                                    <p class="font-medium">{{ ucfirst($application->jobPosting?->job_type ?? 'N/A') }}</p>
                                </div>

                                <!-- Location -->
                                <div>
                                    <p class="text-gray-500 text-xs uppercase font-semibold mb-1">Location</p>
                                    <p class="font-medium">{{ $application->jobPosting?->location ?? 'Remote' }}</p>
                                </div>

                                <!-- Applied Date -->
                                <div>
                                    <p class="text-gray-500 text-xs uppercase font-semibold mb-1">Applied On</p>
                                    <p class="font-medium">{{ $application->created_at->format('M d, Y') }}</p>
                                </div>

                                <!-- Status Update -->
                                <div>
                                    <p class="text-gray-500 text-xs uppercase font-semibold mb-1">Status Update</p>
                                    <p class="font-medium">{{ $application->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Application Meta -->
                            <div
                                class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                                @if ($application->jobPosting?->salary_min && $application->jobPosting?->salary_max)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <span class="font-semibold text-green-600">
                                            PHP {{ number_format($application->jobPosting->salary_min) }} - PHP
                                            {{ number_format($application->jobPosting->salary_max) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Application ID: {{ $application->id }}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                @if ($application->jobPosting)
                                    <a href="{{ route('student.applications.show', $application->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-blue-700 font-medium transition-colors">
                                        View Application Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No applications yet</h3>
                        <p class="text-gray-600 mb-6">Start exploring job opportunities and submit your first application
                        </p>
                        <a href="{{ route('student.jobs.index') }}"
                            class="inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                            Browse Jobs
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($applications->hasPages())
                <div class="mt-8">
                    {{ $applications->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
@endsection
