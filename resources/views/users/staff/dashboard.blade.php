@extends('layouts.staff')

@section('title', 'Dashboard - PCU-DASMA Connect')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}!</h1>
        <p class="opacity-90">
            Manage your events and news content for the PCU-DASMA community
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Create Event -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-primary transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Create Event</h3>
            <p class="text-gray-600 mb-4">
                Plan and organize events for students, alumni, and community partners
            </p>
            <a
                href="{{ route('staff.events.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-blue-700"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Event
            </a>
        </div>
    </div>

    <!-- Create News -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-green-500 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Create News Article</h3>
            <p class="text-gray-600 mb-4">
                Share updates, announcements, and stories with your audience
            </p>
            <a
                href="{{ route('staff.news.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Article
            </a>
        </div>
    </div>

    <!-- View Calendar -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-purple-500 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Calendar</h3>
            <p class="text-gray-600 mb-4">
                View all your events in a calendar view and manage schedules
            </p>
            <a
                href="{{ route('staff.events.index') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                View Calendar
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Events</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_events ?? 0 }}</p>
                <p class="text-xs text-blue-600 mt-1">↑ This month</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $upcoming_events ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-1">Next 30 days</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Registrations</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_registrations ?? 0 }}</p>
                <p class="text-xs text-orange-600 mt-1">All time</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">News Articles</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_articles ?? 0 }}</p>
                <p class="text-xs text-purple-600 mt-1">Published</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Events -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Events</h3>
            <a href="{{ route('staff.events.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recent_events ?? [] as $event)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="@if($event->status === 'published' && $event->event_date > now()) bg-green-100 text-green-800 @elseif($event->status === 'published') bg-blue-100 text-blue-800 @else bg-yellow-100 text-yellow-800 @endif px-2 py-1 rounded-full text-xs mr-2">
                                    @if($event->status === 'published')
                                        @if($event->event_date > now())
                                            Upcoming
                                        @else
                                            Completed
                                        @endif
                                    @else
                                        Draft
                                    @endif
                                </span>
                                <span class="text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }}</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $event->title }}</h4>
                            <p class="text-gray-600 text-sm mb-2">{{ $event->location }}</p>
                            <p class="text-sm text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $event->registrations_count ?? 0 }} Registered
                            </p>
                        </div>
                        <a href="{{ route('staff.events.show', $event->id) }}" class="text-primary hover:text-blue-700 font-medium text-sm">
                            View →
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p>No events yet.</p>
                    <a href="{{ route('staff.events.create') }}" class="text-primary hover:underline">Create your first event</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent News -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent News Articles</h3>
            <a href="{{ route('staff.news.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recent_articles ?? [] as $article)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="@if($article->status === 'published') bg-green-100 text-green-800 @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800 @elseif($article->status === 'approved') bg-blue-100 text-blue-800 @else bg-red-100 text-red-800 @endif px-2 py-1 rounded-full text-xs mr-2">
                                    {{ ucfirst($article->status) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $article->created_at->format('M d, Y') }}</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $article->title }}</h4>
                            <p class="text-gray-600 text-sm mb-2">{{ $article->getExcerpt(100) }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="@if($article->category === 'university_updates') bg-blue-100 text-blue-800 @elseif($article->category === 'alumni_success') bg-yellow-100 text-yellow-800 @elseif($article->category === 'partnership_highlights') bg-emerald-100 text-emerald-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs">
                                    {{ $article->getCategoryDisplayName() }}
                                </span>
                                <span>
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $article->getViewsDisplay() }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('staff.news.edit', $article->id) }}" class="text-primary hover:text-blue-700 font-medium text-sm">
                            Edit →
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p>No articles yet.</p>
                    <a href="{{ route('staff.news.create') }}" class="text-primary hover:underline">Create your first article</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Analytics & Performance -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    <!-- Event Performance -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Performance</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-medium text-gray-900">Attendance Rate</span>
                    <span class="text-gray-600">{{ $attendance_rate ?? 85 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $attendance_rate ?? 85 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-medium text-gray-900">Capacity Usage</span>
                    <span class="text-gray-600">{{ $capacity_usage ?? 72 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $capacity_usage ?? 72 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Statistics -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Content Statistics</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Published Articles</span>
                <span class="font-semibold text-gray-900">{{ $published_articles ?? 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Pending Review</span>
                <span class="font-semibold text-gray-900 text-orange-600">{{ $pending_articles ?? 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Draft Articles</span>
                <span class="font-semibold text-gray-900 text-gray-500">{{ $draft_articles ?? 0 }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between items-center">
                <span class="text-gray-900 font-medium">Total Views</span>
                <span class="font-semibold text-primary text-lg">{{ $total_views ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
        <div class="space-y-2">
            <a href="{{ route('staff.events.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                My Events
            </a>
            <a href="{{ route('staff.news.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                My Articles
            </a>
            <a href="" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                My Profile
            </a>
            <a href="" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
            </a>
        </div>
    </div>
</div>
@endsection
