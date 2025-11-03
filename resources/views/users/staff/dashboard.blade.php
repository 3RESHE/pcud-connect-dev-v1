@extends('layouts.staff')

@section('title', 'Staff Dashboard - PCU-DASMA Connect')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->first_name }}! 👋</h1>
    <p class="text-gray-600">Here's what's happening with your events and news today</p>
</div>

<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- My Events Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">My Events</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_events ?? 9 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $pending_events ?? 3 }} pending review</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('staff.events.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all events →</a>
        </div>
    </div>

    <!-- Published Events Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Published Events</p>
                <p class="text-3xl font-bold text-gray-900">{{ $published_events ?? 3 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $upcoming_events ?? 2 }} upcoming</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('staff.events.index') }}?filter=published" class="text-sm text-green-600 hover:text-green-700 font-medium">View published →</a>
        </div>
    </div>

    <!-- My News Articles Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">My News Articles</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_news ?? 7 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $pending_news ?? 2 }} pending approval</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View all news →</a>
        </div>
    </div>

    <!-- Total Registrations Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Event Registrations</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_registrations ?? 156 }}</p>
                <p class="text-xs text-gray-500 mt-1">Across all events</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">+12% from last month</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="mb-4 md:mb-0">
            <h2 class="text-xl font-bold text-white mb-2">Quick Actions</h2>
            <p class="text-blue-100">Create and manage your content</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('staff.events.create') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 flex items-center justify-center transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Event
            </a>
            <a href="" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-400 flex items-center justify-center transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create News
            </a>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Upcoming Events</h2>
            <a href="{{ route('staff.events.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
        </div>
        <div class="space-y-4">
            @forelse($upcoming_events_list ?? [] as $event)
                <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $event->event_date->format('M j, Y \a\t g:i A') }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-xs text-gray-500">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $event->registrations_count ?? 0 }} registered
                            </span>
                            <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">Published</span>
                        </div>
                    </div>
                    <a href="{{ route('staff.events.show', $event->id) }}" class="flex-shrink-0 text-blue-600 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No upcoming events</p>
                    <a href="{{ route('staff.events.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">Create your first event</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Pending Approvals</h2>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">{{ $pending_items ?? 5 }} items</span>
        </div>
        <div class="space-y-4">
            @forelse($pending_items_list ?? [] as $item)
                <div class="flex items-start space-x-4 p-4 rounded-lg border border-yellow-200 bg-yellow-50">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            @if($item->type === 'event')
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->title }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ ucfirst($item->type) }} • Submitted {{ $item->created_at->diffForHumans() }}</p>
                        <p class="text-xs text-yellow-700 mt-2">Awaiting admin review</p>
                    </div>
                    <a href="{{ $item->type === 'event' ? route('staff.events.show', $item->id) : route('staff.news.show', $item->id) }}" class="flex-shrink-0 text-yellow-600 hover:text-yellow-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No pending approvals</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent News Articles -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">Recent News Articles</h2>
        <a href="" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($recent_news ?? [] as $news)
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                @if($news->featured_image)
                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                        <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="@if($news->status === 'published') bg-green-100 text-green-800 @elseif($news->status === 'pending') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif px-2 py-1 rounded-full text-xs font-medium">
                            {{ ucfirst($news->status) }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $news->created_at->format('M j') }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">{{ $news->title }}</h3>
                    <p class="text-xs text-gray-600 line-clamp-2">{{ $news->getExcerpt(80) }}</p>
                    <a href="{{ route('staff.news.show', $news->id) }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium mt-3 inline-block">Read more →</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No news articles yet</p>
                <a href="" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">Create your first article</a>
            </div>
        @endforelse
    </div>
</div>

<!-- Activity Timeline -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
    </div>
    <div class="space-y-4">
        @forelse($recent_activities ?? [] as $activity)
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-8 h-8 @if($activity->type === 'event_created') bg-blue-100 @elseif($activity->type === 'event_approved') bg-green-100 @elseif($activity->type === 'news_published') bg-purple-100 @else bg-gray-100 @endif rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 @if($activity->type === 'event_created') text-blue-600 @elseif($activity->type === 'event_approved') text-green-600 @elseif($activity->type === 'news_published') text-purple-600 @else text-gray-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-4">No recent activity</p>
        @endforelse
    </div>
</div>
@endsection
