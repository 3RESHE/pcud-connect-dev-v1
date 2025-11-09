@extends('layouts.student')

@section('title', 'Job Opportunities - PCU-DASMA Connect')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header with View Applications Button -->
        <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Job Opportunities</h1>
                <p class="text-gray-600">
                    Discover career opportunities from registered partner organizations tailored for PCU-DASMA students and alumni
                </p>
            </div>
            <!-- ✅ VIEW APPLICATIONS BUTTON -->
            <a href="{{ route('student.applications.index') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors whitespace-nowrap text-center">
                View Applications
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-8">
            <form action="{{ route('student.jobs.index') }}" method="GET">
                <!-- Search Bar Row -->
                <div class="mb-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            name="search"
                            id="job-search"
                            placeholder="Search job titles, companies, skills, or keywords..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                </div>

                <!-- Filters Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                    <!-- Location Filter -->
                    <div>
                        <select name="location" id="location-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                            <option value="">All Locations</option>
                            <option value="makati" @if(request('location') === 'makati') selected @endif>Makati City</option>
                            <option value="bgc" @if(request('location') === 'bgc') selected @endif>BGC, Taguig</option>
                            <option value="ortigas" @if(request('location') === 'ortigas') selected @endif>Ortigas Center</option>
                            <option value="quezon" @if(request('location') === 'quezon') selected @endif>Quezon City</option>
                            <option value="remote" @if(request('location') === 'remote') selected @endif>Remote</option>
                            <option value="hybrid" @if(request('location') === 'hybrid') selected @endif>Hybrid</option>
                        </select>
                    </div>

                    <!-- Job Type Filter -->
                    <div>
                        <select name="job_type" id="job-type-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                            <option value="">All Types</option>
                            <option value="fulltime" @if(request('job_type') === 'fulltime') selected @endif>Full-time</option>
                            <option value="parttime" @if(request('job_type') === 'parttime') selected @endif>Part-time</option>
                            <option value="internship" @if(request('job_type') === 'internship') selected @endif>Internship</option>
                            <option value="other" @if(request('job_type') === 'other') selected @endif>Contract/Project</option>
                        </select>
                    </div>

                    <!-- Experience Level Filter -->
                    <div>
                        <select name="experience_level" id="experience-level-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                            <option value="">All Experience Levels</option>
                            <option value="entry" @if(request('experience_level') === 'entry') selected @endif>Entry Level</option>
                            <option value="mid" @if(request('experience_level') === 'mid') selected @endif>Mid Level</option>
                            <option value="senior" @if(request('experience_level') === 'senior') selected @endif>Senior Level</option>
                            <option value="lead" @if(request('experience_level') === 'lead') selected @endif>Lead/Manager</option>
                            <option value="student" @if(request('experience_level') === 'student') selected @endif>Student/Fresh Graduate</option>
                        </select>
                    </div>

                    <!-- Department Filter -->
                    <div>
                        <select name="department" id="department-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                            <option value="">All Departments</option>
                            <option value="it" @if(request('department') === 'it') selected @endif>Information Technology</option>
                            <option value="engineering" @if(request('department') === 'engineering') selected @endif>Engineering</option>
                            <option value="marketing" @if(request('department') === 'marketing') selected @endif>Marketing</option>
                            <option value="sales" @if(request('department') === 'sales') selected @endif>Sales</option>
                            <option value="hr" @if(request('department') === 'hr') selected @endif>Human Resources</option>
                            <option value="finance" @if(request('department') === 'finance') selected @endif>Finance</option>
                            <option value="operations" @if(request('department') === 'operations') selected @endif>Operations</option>
                            <option value="customer_service" @if(request('department') === 'customer_service') selected @endif>Customer Service</option>
                            <option value="other" @if(request('department') === 'other') selected @endif>Other</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="col-span-1 sm:col-span-2 lg:col-span-1 px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary font-medium">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Job Type Quick Filters -->
        <div class="mb-8 overflow-x-auto">
            <div class="flex gap-2 pb-2">
                <a href="{{ route('student.jobs.index') }}" class="@if(!request()->filled('job_type')) bg-primary text-white @else bg-white border border-gray-300 text-gray-700 @endif px-4 py-2 rounded-full text-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
                    All Jobs
                </a>
                <a href="{{ route('student.jobs.index', ['job_type' => 'fulltime']) }}" class="@if(request('job_type') === 'fulltime') bg-primary text-white @else bg-white border border-gray-300 text-gray-700 @endif px-4 py-2 rounded-full text-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
                    Full-time
                </a>
                <a href="{{ route('student.jobs.index', ['job_type' => 'parttime']) }}" class="@if(request('job_type') === 'parttime') bg-primary text-white @else bg-white border border-gray-300 text-gray-700 @endif px-4 py-2 rounded-full text-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
                    Part-time
                </a>
                <a href="{{ route('student.jobs.index', ['job_type' => 'internship']) }}" class="@if(request('job_type') === 'internship') bg-primary text-white @else bg-white border border-gray-300 text-gray-700 @endif px-4 py-2 rounded-full text-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
                    Internships
                </a>
                <a href="{{ route('student.jobs.index', ['job_type' => 'other']) }}" class="@if(request('job_type') === 'other') bg-primary text-white @else bg-white border border-gray-300 text-gray-700 @endif px-4 py-2 rounded-full text-sm hover:bg-gray-50 transition-colors whitespace-nowrap">
                    Contract/Project
                </a>
            </div>
        </div>

        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <p class="text-gray-600">
                Showing <span class="font-semibold">{{ $jobs->total() }}</span> job opportunities
            </p>
            <div class="flex items-center space-x-2">
                <label for="sort-by" class="text-sm text-gray-600 whitespace-nowrap">Sort by:</label>
                <select id="sort-by" onchange="window.location.href=this.value" class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="{{ route('student.jobs.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" @if(request('sort') === 'newest' || !request('sort')) selected @endif>Newest First</option>
                    <option value="{{ route('student.jobs.index', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}" @if(request('sort') === 'oldest') selected @endif>Oldest First</option>
                    <option value="{{ route('student.jobs.index', array_merge(request()->except('sort'), ['sort' => 'salary-high'])) }}" @if(request('sort') === 'salary-high') selected @endif>Salary Range</option>
                </select>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="space-y-6">
            @forelse($jobs as $job)
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 @if($job->is_featured) border-l-4 border-blue-500 @endif hover:shadow-md transition-shadow">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center mb-3 gap-2">
                        @if($job->is_featured)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Featured</span>
                        @endif
                        <span class="@if($job->job_type === 'fulltime') bg-green-100 text-green-800 @elseif($job->job_type === 'parttime') bg-blue-100 text-blue-800 @elseif($job->job_type === 'internship') bg-purple-100 text-purple-800 @else bg-orange-100 text-orange-800 @endif px-2 py-1 rounded-full text-xs font-medium">
                            {{ $job->getJobTypeDisplay() }}
                        </span>
                        @if(auth()->check() && $job->applications()->where('applicant_id', auth()->id())->exists())
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Applied</span>
                        @endif
                    </div>

                    <!-- Job Title -->
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 break-words">{{ $job->title }}</h3>

                    <!-- Job Info - Responsive Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-4 text-sm text-gray-600">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium break-words">{{ $job->partnerProfile->company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="break-words">{{ $job->location ?? 'Remote' }}</span>
                        </div>
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-semibold text-green-600 break-words">{{ $job->getSalaryRangeDisplay() }}</span>
                        </div>
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            <span class="break-words">{{ $job->department ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Description with proper truncation -->
                    <p class="text-gray-600 mb-4 text-sm sm:text-base line-clamp-3 break-words">{{ $job->description }}</p>

                    <!-- Job Meta -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500 mb-4">
                        <div class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Posted {{ $job->created_at->diffForHumans() }}
                        </div>
                        <div class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $job->applications()->count() }} applicants
                        </div>
                    </div>

                    <!-- Action Button - Always visible -->
                    <div class="flex justify-start">
                        <a href="{{ route('student.jobs.show', $job->id) }}" class="w-full sm:w-auto px-6 py-2 bg-primary text-white text-sm rounded-lg hover:bg-blue-700 text-center font-medium transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-8 sm:p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No job opportunities found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search filters or check back later for new opportunities</p>
                    <a href="{{ route('student.jobs.index') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Clear Filters & Search Again
                    </a>
                </div>
            @endforelse

            @if($jobs->hasMorePages())
                <!-- Pagination -->
                <div class="text-center py-8">
                    {{ $jobs->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
