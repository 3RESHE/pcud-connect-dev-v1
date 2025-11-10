@extends('layouts.student')

@section('title', 'Student Dashboard - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6 shadow-sm">
            <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}!</h1>
            <p class="opacity-90">Stay connected with your university and discover new opportunities.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- New Jobs -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h-8m8 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">New Jobs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newJobsCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Upcoming Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $upcomingEventsCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Latest News -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Latest News</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $latestNewsCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Event Registrations -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">My Registrations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $eventRegistrationsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured News Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Latest News</h2>
            <a href="{{ route('news.index') }}" class="text-primary hover:text-blue-700 font-medium flex items-center gap-1">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestNews as $article)
                <!-- News Card -->
                <a href="{{ route('student.news.show', $article->id) }}" class="group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    @if($article->featured_image)
                        <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" />
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif
                    <div class="p-5">
                        <span class="
                            @if($article->category == 'university') bg-blue-100 text-blue-800
                            @elseif($article->category == 'alumni') bg-yellow-100 text-yellow-800
                            @elseif($article->category == 'event') bg-indigo-100 text-indigo-800
                            @elseif($article->category == 'partnership') bg-emerald-100 text-emerald-800
                            @else bg-teal-100 text-teal-800
                            @endif
                            px-2 py-1 rounded-full text-xs font-medium
                        ">
                            {{ ucfirst(str_replace('_', ' ', $article->category)) }}
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900 mt-3 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ strip_tags($article->content) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $article->created_at->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-3 bg-white rounded-lg shadow-sm p-6 text-center">
                    <p class="text-gray-500">No news articles available.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Job Postings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Job Opportunities</h3>
                        <a href="{{ route('student.jobs.index') }}" class="text-sm text-primary hover:underline">View all</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentJobs as $job)
                        <!-- Job Item -->
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $job->title }}</h4>
                                    <p class="text-gray-600 mb-2">{{ $job->company }}</p>
                                    <div class="flex items-center text-sm text-gray-500 flex-wrap gap-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            {{ $job->location }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</span>
                                        <span>•</span>
                                        <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('student.jobs.show', $job->id) }}" class="ml-4 px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors whitespace-nowrap">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No job opportunities available.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Upcoming Events -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                        <a href="{{ route('student.events.index') }}" class="text-sm text-primary hover:underline">View all</a>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($upcomingEvents as $event)
                        <!-- Event Item -->
                        <a href="{{ route('student.events.show', $event->id) }}" class="flex items-start cursor-pointer hover:bg-gray-50 -mx-3 px-3 py-2 rounded-lg transition-colors">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary text-white rounded-lg flex flex-col items-center justify-center font-semibold">
                                <span class="text-xs">{{ $event->event_date->format('M') }}</span>
                                <span class="text-lg">{{ $event->event_date->format('d') }}</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-medium text-gray-900 line-clamp-1">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $event->event_date->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($event->event_format === 'virtual')
                                        🌐 Virtual
                                    @elseif($event->event_format === 'in_person')
                                        📍 In-Person
                                    @else
                                        🔄 Hybrid
                                    @endif
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 text-sm">No upcoming events.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('student.profile.show') }}" class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-gray-900">Update Profile</span>
                        </div>
                    </a>
                    <a href="{{ route('student.jobs.index') }}" class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h-8m8 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6"></path>
                            </svg>
                            <span class="font-medium text-gray-900">Browse Jobs</span>
                        </div>
                    </a>
                    <a href="{{ route('student.events.index') }}" class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-900">View Events</span>
                        </div>
                    </a>
                    <a href="{{ route('news.index') }}" class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <span class="font-medium text-gray-900">Read News</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
