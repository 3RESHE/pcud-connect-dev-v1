@extends('layouts.alumni')

@section('title', 'Alumni Dashboard - PCU-DASMA Connect')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Welcome Section with Profile Completion -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 shadow-lg">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}! 👋</h1>
                    <p class="text-blue-100 mb-4">Class of {{ $alumniProfile?->graduation_year ?? 'Year Unknown' }} | {{ auth()->user()->department?->title ?? 'Department' }}</p>

                    <!-- Profile Completion Alert -->
                    @if (!$isProfileComplete && $profileCompletionPercentage < 100)
                        <div class="bg-blue-500/30 rounded-lg p-4 border border-blue-400/50">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold">Profile Completion: {{ $profileCompletionPercentage }}%</p>
                                <a href="{{ route('alumni.profile.edit') }}" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition">
                                    Complete Profile →
                                </a>
                            </div>
                            <div class="w-full bg-blue-400/30 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full transition-all" style="width: {{ $profileCompletionPercentage }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-500/30 rounded-lg p-3 border border-green-400/50 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">Your profile is complete!</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 1: KEY STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Applications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $applicationStats['total'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $applicationStats['approved'] }} approved</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Success Rate</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $applicationStats['success_rate'] }}%</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $applicationStats['approved'] }}/{{ $applicationStats['total'] }} approved</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414-1.414L13.586 7H12z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Experiences -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Experience</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $experienceCount }}</p>
                    <p class="text-xs text-gray-500 mt-2">Work entries added</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h-8m8 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Projects</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $projectCount }}</p>
                    <p class="text-xs text-gray-500 mt-2">Portfolio projects</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Registered Events -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Events</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $registeredEventsCount }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $upcomingRegistered }} upcoming</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: QUICK ACTIONS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('alumni.profile.edit') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition border-l-4 border-blue-600 group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Manage</p>
                    <p class="font-semibold text-gray-900">Edit Profile</p>
                </div>
            </div>
        </a>

        <a href="{{ route('alumni.jobs.index') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition border-l-4 border-green-600 group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Browse</p>
                    <p class="font-semibold text-gray-900">Job Postings</p>
                </div>
            </div>
        </a>

        <a href="{{ route('alumni.applications.index') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition border-l-4 border-purple-600 group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">View</p>
                    <p class="font-semibold text-gray-900">My Applications</p>
                </div>
            </div>
        </a>

        <a href="{{ route('events.index') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition border-l-4 border-orange-600 group">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Discover</p>
                    <p class="font-semibold text-gray-900">Events</p>
                </div>
            </div>
        </a>
    </div>

    <!-- SECTION 3: MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Applications (Left - 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                <a href="{{ route('alumni.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>

            <div class="space-y-3">
                @forelse ($recentApplications as $app)
                    <div class="flex items-start gap-4 pb-3 border-b last:border-b-0 hover:bg-gray-50 -mx-2 px-2 py-2 rounded transition">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-blue-600">
                            {{ substr($app->jobPosting->title, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $app->jobPosting->title }}</p>
                            <p class="text-sm text-gray-600">Applied {{ $app->created_at->diffForHumans() }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-1 rounded-full font-medium
                                    @if($app->status === 'approved') bg-green-100 text-green-800
                                    @elseif($app->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($app->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($app->status === 'contacted') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-8 text-gray-500">No applications yet. Start applying for jobs!</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events (Right - 1 col) -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Events</h2>
                <a href="{{ route('events.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>

            <div class="space-y-3">
                @forelse ($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}" class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-lg flex flex-col items-center justify-center font-semibold">
                            <span class="text-xs">{{ $event->event_date->format('M') }}</span>
                            <span class="text-lg leading-none">{{ $event->event_date->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate text-sm">{{ $event->title }}</p>
                            <p class="text-xs text-gray-600">{{ $event->event_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($event->event_format === 'virtual') 🌐 Online
                                @elseif($event->event_format === 'in_person') 📍 In-Person
                                @else 🔄 Hybrid @endif
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-center py-8 text-gray-500 text-sm">No upcoming events</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECTION 4: EXPERIENCE & PROJECTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Experiences -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Experience</h2>
                <a href="{{ route('alumni.profile.edit') }}" class="text-sm text-blue-600 hover:text-blue-800">Manage</a>
            </div>

            <div class="space-y-3">
                @forelse ($recentExperiences as $exp)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $exp->role_position }}</p>
                                <p class="text-sm text-gray-600">{{ $exp->organization }}</p>
                            </div>
                            @if($exp->is_current)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">Current</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $exp->start_date->format('M Y') }}@if($exp->end_date) - {{ $exp->end_date->format('M Y') }}@endif</p>
                    </div>
                @empty
                    <p class="text-center py-6 text-gray-500 text-sm">No experiences added yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                <a href="{{ route('alumni.profile.edit') }}" class="text-sm text-blue-600 hover:text-blue-800">Manage</a>
            </div>

            <div class="space-y-3">
                @forelse ($recentProjects as $project)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $project->title }}</p>
                            </div>
                            @if($project->url)
                                <a href="{{ $project->url }}" target="_blank" rel="noopener" class="text-xs text-blue-600 hover:text-blue-800">View →</a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $project->start_date->format('M Y') }}@if($project->end_date) - {{ $project->end_date->format('M Y') }}@endif</p>
                    </div>
                @empty
                    <p class="text-center py-6 text-gray-500 text-sm">No projects added yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECTION 5: FEATURED NEWS -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Latest News</h2>
            <a href="{{ route('news.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($latestNews as $news)
                <a href="{{ route('news.show', $news) }}" class="group cursor-pointer border border-gray-200 rounded-lg overflow-hidden hover:border-blue-400 hover:shadow-md transition">
                    @if($news->featured_image)
                        <img class="w-full h-40 object-cover group-hover:scale-105 transition-transform" src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" />
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">{{ ucfirst($news->category ?? 'News') }}</span>
                        <h3 class="font-semibold text-gray-900 mt-2 line-clamp-2 group-hover:text-blue-600 transition">{{ $news->title }}</h3>
                        <p class="text-xs text-gray-500 mt-2">{{ $news->created_at->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    <p>No news available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SECTION 6: JOB OPPORTUNITIES -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Available Opportunities</h2>
            <a href="{{ route('alumni.jobs.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Browse all</a>
        </div>

        <div class="space-y-3">
            @forelse ($recentJobs as $job)
                <a href="{{ route('alumni.jobs.show', $job) }}" class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition group">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0 text-sm">
                        {{ substr($job->title, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 group-hover:text-blue-600 truncate">{{ $job->title }}</p>
                        <p class="text-sm text-gray-600 truncate">{{ $job->location ?? 'Location TBA' }}</p>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                            <span>{{ ucfirst($job->job_type) }}</span>
                            <span>•</span>
                            <span>{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <span class="text-sm text-blue-600 font-medium group-hover:translate-x-1 transition">→</span>
                </a>
            @empty
                <p class="text-center py-8 text-gray-500">No job opportunities available at the moment.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
