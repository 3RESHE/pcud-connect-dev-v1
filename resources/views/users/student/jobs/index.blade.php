@extends('layouts.student')

@section('title', 'Job Opportunities - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Job Opportunities</h1>
        <p class="text-gray-600">
            Discover career opportunities from registered partner organizations tailored for PCU-DASMA students and alumni
        </p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form action="{{ route('student.jobs.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search job titles, companies, skills, or keywords..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                </div>

                <!-- Location Filter -->
                <div class="lg:w-48">
                    <select
                        name="location"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <option value="">All Locations</option>
                        <option value="makati" {{ request('location') == 'makati' ? 'selected' : '' }}>Makati City</option>
                        <option value="bgc" {{ request('location') == 'bgc' ? 'selected' : '' }}>BGC, Taguig</option>
                        <option value="ortigas" {{ request('location') == 'ortigas' ? 'selected' : '' }}>Ortigas Center</option>
                        <option value="quezon" {{ request('location') == 'quezon' ? 'selected' : '' }}>Quezon City</option>
                        <option value="remote" {{ request('location') == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ request('location') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <!-- Job Type Filter -->
                <div class="lg:w-48">
                    <select
                        name="job_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <option value="">All Types</option>
                        <option value="full_time" {{ request('job_type') == 'full_time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part_time" {{ request('job_type') == 'part_time' ? 'selected' : '' }}>Part-time</option>
                        <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="freelance" {{ request('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="project" {{ request('job_type') == 'project' ? 'selected' : '' }}>Project-based</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button
                    type="submit"
                    class="lg:w-auto w-full px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results Header -->
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-600">
            Showing <span class="font-semibold">{{ $jobs->count() }}</span> of <span class="font-semibold">{{ $jobs->total() }}</span> job opportunities
        </p>
        <div class="flex items-center space-x-2">
            <label for="sort-by" class="text-sm text-gray-600">Sort by:</label>
            <form method="GET" class="inline">
                <select
                    name="sort"
                    onchange="this.form.submit()"
                    class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="space-y-6">
        @forelse($jobs as $job)
            <!-- Job Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex items-center mb-2 flex-wrap gap-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst(str_replace('_', '-', $job->job_type)) }}
                            </span>
                        </div>

                        <!-- Job Title -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>

                        <!-- Job Details -->
                        <div class="flex items-center text-sm text-gray-600 mb-3 flex-wrap gap-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium">{{ $job->company }}</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ $job->location }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                @if($job->salary_min && $job->salary_max)
                                    ₱{{ number_format($job->salary_min) }} - ₱{{ number_format($job->salary_max) }}/month
                                @else
                                    Salary negotiable
                                @endif
                            </span>
                        </div>

                        <!-- Job Description -->
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {{ strip_tags($job->description) }}
                        </p>

                        <!-- Job Meta -->
                        <div class="flex items-center space-x-4 text-sm text-gray-500 flex-wrap">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Posted {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- View Button -->
                    <div class="ml-6 flex flex-col space-y-2">
                        <a
                            href="{{ route('student.jobs.show', $job->id) }}"
                            class="px-6 py-2 bg-primary text-white text-sm rounded-lg hover:bg-blue-700 text-center font-medium whitespace-nowrap"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- No Results -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No job opportunities found</h3>
                <p class="text-gray-600">Try adjusting your search criteria or filters</p>
                <a href="{{ route('student.jobs.index') }}" class="mt-4 inline-block px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                    View All Jobs
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($jobs->hasPages())
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
