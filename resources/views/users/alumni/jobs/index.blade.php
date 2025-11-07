@extends('layouts.alumni')

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
        <form method="GET" action="{{ route('alumni.jobs.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-center space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search job titles, companies, skills, or keywords..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" />
                    </div>
                </div>

                <!-- Location Filter -->
                <div class="lg:w-48">
                    <select name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">All Locations</option>
                        <option value="makati" {{ request('location') == 'makati' ? 'selected' : '' }}>Makati City</option>
                        <option value="bgc" {{ request('location') == 'bgc' ? 'selected' : '' }}>BGC, Taguig</option>
                        <option value="ortigas" {{ request('location') == 'ortigas' ? 'selected' : '' }}>Ortigas Center</option>
                        <option value="quezon" {{ request('location') == 'quezon' ? 'selected' : '' }}>Quezon City</option>
                        <option value="remote" {{ request('location') == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>

                <!-- Job Type Filter -->
                <div class="lg:w-48">
                    <select name="job_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">All Types</option>
                        <option value="full_time" {{ request('job_type') == 'full_time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part_time" {{ request('job_type') == 'part_time' ? 'selected' : '' }}>Part-time</option>
                        <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" class="lg:w-auto w-full px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Job Type Quick Filters -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('alumni.jobs.index') }}" class="px-4 py-2 {{ !request('job_type') ? 'bg-primary text-white' : 'bg-white border border-gray-300 text-gray-700' }} rounded-full text-sm hover:bg-primary hover:text-white transition">
                All Jobs
            </a>
            <a href="{{ route('alumni.jobs.index', ['job_type' => 'full_time']) }}" class="px-4 py-2 {{ request('job_type') == 'full_time' ? 'bg-primary text-white' : 'bg-white border border-gray-300 text-gray-700' }} rounded-full text-sm hover:bg-primary hover:text-white transition">
                Full-time
            </a>
            <a href="{{ route('alumni.jobs.index', ['job_type' => 'part_time']) }}" class="px-4 py-2 {{ request('job_type') == 'part_time' ? 'bg-primary text-white' : 'bg-white border border-gray-300 text-gray-700' }} rounded-full text-sm hover:bg-primary hover:text-white transition">
                Part-time
            </a>
            <a href="{{ route('alumni.jobs.index', ['job_type' => 'internship']) }}" class="px-4 py-2 {{ request('job_type') == 'internship' ? 'bg-primary text-white' : 'bg-white border border-gray-300 text-gray-700' }} rounded-full text-sm hover:bg-primary hover:text-white transition">
                Internships
            </a>
            <a href="{{ route('alumni.jobs.index', ['job_type' => 'contract']) }}" class="px-4 py-2 {{ request('job_type') == 'contract' ? 'bg-primary text-white' : 'bg-white border border-gray-300 text-gray-700' }} rounded-full text-sm hover:bg-primary hover:text-white transition">
                Contract/Project
            </a>
        </div>
    </div>

    <!-- Results Header -->
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-600">
            Showing <span class="font-semibold">{{ $jobs->total() }}</span> job opportunities
        </p>
        <div class="flex items-center space-x-2">
            <label for="sort-by" class="text-sm text-gray-600">Sort by:</label>
            <select id="sort-by" onchange="window.location.href = this.value" class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="{{ route('alumni.jobs.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest First</option>
                <option value="{{ route('alumni.jobs.index', array_merge(request()->all(), ['sort' => 'company'])) }}" {{ request('sort') == 'company' ? 'selected' : '' }}>Company Name</option>
            </select>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="space-y-6">
        @forelse($jobs as $job)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ ucfirst(str_replace('_', '-', $job->job_type)) }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-3 flex-wrap gap-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium">{{ $job->company }}</span>
                            <span class="mx-2">•</span>
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $job->location ?? 'Location TBA' }}
                        </div>

                        <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($job->description), 150) }}</p>

                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Posted {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="ml-6 flex flex-col space-y-2">
                        <a href="{{ route('alumni.jobs.show', $job->id) }}" class="px-6 py-2 bg-primary text-white text-sm rounded-lg hover:bg-blue-700 text-center font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h-8m8 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6"></path>
                </svg>
                <p class="text-gray-500 text-lg">No job opportunities found.</p>
                <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or search criteria.</p>
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
