@extends('layouts.staff')

@section('title', 'Dashboard - PCUD-Connect')

@section('content')
    <!-- Welcome Section with Enhanced Design -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 rounded-xl shadow-lg p-8 text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2">Welcome back, {{ $user->first_name }}! 👋</h1>
                <p class="text-blue-100 text-lg">Manage your content, track engagement, and monitor your impact on the community</p>

                <!-- Quick Stats in Welcome Card -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all cursor-pointer">
                        <div class="text-3xl font-bold">{{ $quickStats['total_content'] }}</div>
                        <div class="text-sm text-blue-100 mt-1">Total Content Created</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all cursor-pointer">
                        <div class="text-3xl font-bold">{{ $quickStats['total_published'] }}</div>
                        <div class="text-sm text-blue-100 mt-1">Published Items</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all cursor-pointer">
                        <div class="text-3xl font-bold">{{ $quickStats['total_pending'] }}</div>
                        <div class="text-sm text-blue-100 mt-1">Awaiting Review</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all cursor-pointer">
                        <div class="text-3xl font-bold">{{ $quickStats['total_registrations'] }}</div>
                        <div class="text-sm text-blue-100 mt-1">Total Registrations</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">⚡ Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('staff.news.create') }}" class="bg-white hover:bg-blue-50 rounded-xl shadow-sm border border-gray-200 p-6 text-center transition-all hover:shadow-md group">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Create Article</h3>
                <p class="text-xs text-gray-600">Write a new news article</p>
            </a>

            <a href="{{ route('staff.events.create') }}" class="bg-white hover:bg-purple-50 rounded-xl shadow-sm border border-gray-200 p-6 text-center transition-all hover:shadow-md group">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Create Event</h3>
                <p class="text-xs text-gray-600">Schedule a new event</p>
            </a>

            <a href="{{ route('staff.news.index') }}" class="bg-white hover:bg-green-50 rounded-xl shadow-sm border border-gray-200 p-6 text-center transition-all hover:shadow-md group">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">View Articles</h3>
                <p class="text-xs text-gray-600">Manage your articles</p>
            </a>

            <a href="{{ route('staff.events.index') }}" class="bg-white hover:bg-orange-50 rounded-xl shadow-sm border border-gray-200 p-6 text-center transition-all hover:shadow-md group">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">View Events</h3>
                <p class="text-xs text-gray-600">Manage your events</p>
            </a>
        </div>
    </div>

    <!-- Comparison Metrics (This Month vs Last Month) -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">📈 Monthly Performance Comparison</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Articles Comparison -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Articles Published</h3>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-3 mb-3">
                    <div class="text-4xl font-bold text-gray-900">{{ $comparisonMetrics['articles_this_month'] }}</div>
                    <div class="text-sm text-gray-600 mb-2">this month</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($comparisonMetrics['articles_change'] > 0)
                        <span class="text-green-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +{{ $comparisonMetrics['articles_change'] }}%
                        </span>
                    @elseif($comparisonMetrics['articles_change'] < 0)
                        <span class="text-red-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $comparisonMetrics['articles_change'] }}%
                        </span>
                    @else
                        <span class="text-gray-600 font-semibold text-sm">No change</span>
                    @endif
                    <span class="text-xs text-gray-500">vs last month</span>
                </div>
            </div>

            <!-- Events Comparison -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Events Published</h3>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-3 mb-3">
                    <div class="text-4xl font-bold text-gray-900">{{ $comparisonMetrics['events_this_month'] }}</div>
                    <div class="text-sm text-gray-600 mb-2">this month</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($comparisonMetrics['events_change'] > 0)
                        <span class="text-green-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +{{ $comparisonMetrics['events_change'] }}%
                        </span>
                    @elseif($comparisonMetrics['events_change'] < 0)
                        <span class="text-red-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $comparisonMetrics['events_change'] }}%
                        </span>
                    @else
                        <span class="text-gray-600 font-semibold text-sm">No change</span>
                    @endif
                    <span class="text-xs text-gray-500">vs last month</span>
                </div>
            </div>

            <!-- Registrations Comparison -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Event Registrations</h3>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-3 mb-3">
                    <div class="text-4xl font-bold text-gray-900">{{ $comparisonMetrics['registrations_this_month'] }}</div>
                    <div class="text-sm text-gray-600 mb-2">this month</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($comparisonMetrics['registrations_change'] > 0)
                        <span class="text-green-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +{{ $comparisonMetrics['registrations_change'] }}%
                        </span>
                    @elseif($comparisonMetrics['registrations_change'] < 0)
                        <span class="text-red-600 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $comparisonMetrics['registrations_change'] }}%
                        </span>
                    @else
                        <span class="text-gray-600 font-semibold text-sm">No change</span>
                    @endif
                    <span class="text-xs text-gray-500">vs last month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Health & Engagement Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Content Health Score -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">📊 Content Health Score</h2>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="text-5xl font-bold text-gray-900">{{ $contentHealth['health_score'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Overall Health Score</div>
                </div>
                <div class="relative w-24 h-24">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none"
                            stroke="#e5e7eb"
                            stroke-width="3"
                        />
                        <path
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none"
                            stroke="{{ $contentHealth['health_score'] >= 70 ? '#10b981' : ($contentHealth['health_score'] >= 50 ? '#f59e0b' : '#ef4444') }}"
                            stroke-width="3"
                            stroke-dasharray="{{ $contentHealth['health_score'] }}, 100"
                        />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-700">{{ $contentHealth['health_score'] }}%</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="text-2xl font-bold text-green-700">{{ $contentHealth['publish_rate'] }}%</div>
                    <div class="text-xs text-gray-600 mt-1">Publish Rate</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="text-2xl font-bold text-red-700">{{ $contentHealth['rejection_rate'] }}%</div>
                    <div class="text-xs text-gray-600 mt-1">Rejection Rate</div>
                </div>
            </div>
        </div>

        <!-- Engagement Analytics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">🎯 Engagement Analytics</h2>
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Avg Registrations/Event</span>
                        <span class="text-2xl font-bold text-gray-900">{{ $engagementAnalytics['avg_registrations_per_event'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($engagementAnalytics['avg_registrations_per_event'] * 2, 100) }}%"></div>
                    </div>
                </div>
                <div class="border-b border-gray-200 pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Overall Attendance Rate</span>
                        <span class="text-2xl font-bold text-gray-900">{{ $engagementAnalytics['overall_attendance_rate'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $engagementAnalytics['overall_attendance_rate'] }}%"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="text-2xl font-bold text-blue-700">{{ $engagementAnalytics['total_registrations'] }}</div>
                        <div class="text-xs text-gray-600 mt-1">Total Registrations</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="text-2xl font-bold text-green-700">{{ $engagementAnalytics['total_attended'] }}</div>
                        <div class="text-xs text-gray-600 mt-1">Total Attended</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trend Charts (Last 30 Days) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Articles Created Trend -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">📰 Articles Created (Last 30 Days)</h2>
                <select id="articles-trend-period" class="text-sm border-gray-300 rounded-lg px-3 py-1">
                    <option value="30" selected>Last 30 days</option>
                    <option value="7">Last 7 days</option>
                    <option value="14">Last 14 days</option>
                </select>
            </div>
            <canvas id="articlesChart" height="250"></canvas>
        </div>

        <!-- Events Created Trend -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">📅 Events Created (Last 30 Days)</h2>
                <select id="events-trend-period" class="text-sm border-gray-300 rounded-lg px-3 py-1">
                    <option value="30" selected>Last 30 days</option>
                    <option value="7">Last 7 days</option>
                    <option value="14">Last 14 days</option>
                </select>
            </div>
            <canvas id="eventsChart" height="250"></canvas>
        </div>
    </div>

    <!-- News Articles Stats Grid -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">📰 News Articles Overview</h2>
            <a href="{{ route('staff.news.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Article
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <!-- Total Articles -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $newsStats['total_articles'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Total</p>
            </div>

            <!-- Published -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $newsStats['published_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Published</p>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $newsStats['pending_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Pending</p>
            </div>

            <!-- Draft -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-600">{{ $newsStats['draft_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Drafts</p>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ $newsStats['approved_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Approved</p>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-red-600">{{ $newsStats['rejected_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Rejected</p>
            </div>

            <!-- Featured -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-xl">⭐</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ $newsStats['featured_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Featured</p>
            </div>
        </div>
    </div>

    <!-- Events Stats Grid -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">📅 Events Overview</h2>
            <a href="{{ route('staff.events.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition-colors text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Event
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <!-- Total Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $eventsStats['total_events'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Total</p>
            </div>

            <!-- Published Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $eventsStats['published_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Published</p>
            </div>

            <!-- Ongoing Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ $eventsStats['ongoing_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Ongoing</p>
            </div>

            <!-- Pending Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $eventsStats['pending_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Pending</p>
            </div>

            <!-- Draft Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-600">{{ $eventsStats['draft_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Drafts</p>
            </div>

            <!-- Approved Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ $eventsStats['approved_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Approved</p>
            </div>

            <!-- Completed Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-indigo-600">{{ $eventsStats['completed_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Completed</p>
            </div>

            <!-- Featured Events -->
            <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-200 hover:shadow-md transition-all cursor-pointer hover:scale-105">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <span class="text-xl">⭐</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ $eventsStats['featured_count'] }}</p>
                <p class="text-xs text-gray-600 mt-1">Featured</p>
            </div>
        </div>
    </div>

    <!-- Ongoing & Upcoming Events Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Ongoing Events with Live Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    Ongoing Events
                </h2>
                <a href="{{ route('staff.events.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All</a>
            </div>

            @forelse($ongoingEvents as $event)
                <div class="border-l-4 border-orange-500 pl-4 pb-5 mb-5 last:mb-0 hover:bg-orange-50 p-3 rounded-r-lg transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </span>
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-semibold flex items-center gap-1">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-600 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-600"></span>
                                    </span>
                                    LIVE NOW
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-700">Registered</span>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-700">{{ $event->registrations_count }}</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-700">Attended</span>
                                    </div>
                                    <div class="text-2xl font-bold text-green-700">{{ $event->attended_count }}</div>
                                </div>
                            </div>
                            @if($event->registrations_count > 0)
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Attendance Rate</span>
                                        <span class="font-semibold">{{ $event->attendance_rate }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $event->attendance_rate }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('staff.events.attendance.mark', $event->id) }}" class="ml-4 px-4 py-2 text-sm bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium shadow-sm transition-colors whitespace-nowrap">
                            Mark Attendance
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No ongoing events</p>
                    <p class="text-gray-400 text-sm mt-1">Events that are currently happening will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">📅 Upcoming Events (Next 7 Days)</h2>
                <a href="{{ route('staff.events.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All</a>
            </div>

            @forelse($upcomingEvents as $event)
                <div class="border-l-4 border-blue-500 pl-4 pb-5 mb-5 last:mb-0 hover:bg-blue-50 p-3 rounded-r-lg transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1
                                    @if($event->status === 'published') bg-green-100 text-green-800 border border-green-200
                                    @elseif($event->status === 'approved') bg-blue-100 text-blue-800 border border-blue-200
                                    @endif
                                    text-xs rounded-full font-medium">
                                    {{ ucfirst($event->status) }}
                                </span>
                                @if($event->event_format)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full border border-gray-200 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $event->event_format)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('staff.events.show', $event->id) }}" class="ml-4 px-4 py-2 text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 font-medium rounded-lg border border-blue-200 transition-colors whitespace-nowrap">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No upcoming events</p>
                    <p class="text-gray-400 text-sm mt-1">Events scheduled for the next 7 days will appear here</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pending Items Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pending Articles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    ⏳ Pending Articles
                    @if($pendingNews->count() > 0)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold">{{ $pendingNews->count() }}</span>
                    @endif
                </h2>
                <a href="{{ route('staff.news.index') }}?status=pending" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
            </div>

            @forelse($pendingNews as $article)
                <div class="border-l-4 border-yellow-500 pl-4 pb-4 mb-4 last:mb-0 hover:bg-yellow-50 p-3 rounded-r-lg transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $article->summary ?? Str::limit(strip_tags($article->content), 100) }}</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $article->created_at->format('M d, Y') }}
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-500">
                                    Submitted {{ $article->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('staff.news.show', $article->id) }}" class="ml-4 px-3 py-1 text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 font-medium rounded-lg border border-blue-200 transition-colors whitespace-nowrap">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No pending articles</p>
                    <p class="text-gray-400 text-sm mt-1">Articles awaiting review will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Pending Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    ⏳ Pending Events
                    @if($pendingEvents->count() > 0)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold">{{ $pendingEvents->count() }}</span>
                    @endif
                </h2>
                <a href="{{ route('staff.events.index') }}?status=pending" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All</a>
            </div>

            @forelse($pendingEvents as $event)
                <div class="border-l-4 border-yellow-500 pl-4 pb-4 mb-4 last:mb-0 hover:bg-yellow-50 p-3 rounded-r-lg transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $event->description ? Str::limit($event->description, 100) : 'No description provided' }}</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-500">
                                    Submitted {{ $event->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('staff.events.show', $event->id) }}" class="ml-4 px-3 py-1 text-sm text-purple-600 hover:text-purple-700 hover:bg-purple-50 font-medium rounded-lg border border-purple-200 transition-colors whitespace-nowrap">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No pending events</p>
                    <p class="text-gray-400 text-sm mt-1">Events awaiting review will appear here</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recently Published & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recently Published Articles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">✅ Recently Published</h2>
                <a href="{{ route('staff.news.index') }}?status=published" class="text-sm text-green-600 hover:text-green-700 font-medium">View All</a>
            </div>

            @forelse($recentlyPublished as $article)
                <div class="border-l-4 border-green-500 pl-4 pb-4 mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $article->title }}</h3>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Published</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs text-gray-500">{{ $article->published_at ? $article->published_at->format('M d, Y h:i A') : 'Date not available' }}</p>
                                @if($article->is_featured)
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">⭐ Featured</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('staff.news.show', $article->id) }}" class="ml-4 px-3 py-1 text-sm text-blue-600 hover:text-blue-700 font-medium">View</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No published articles yet</p>
                    <p class="text-gray-400 text-sm mt-1">Your published articles will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Recent Activity Log -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">🕒 Recent Activity</h2>
            </div>

            <div class="space-y-4">
                @forelse($recentActivity as $log)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full
                                @if($log->color === 'green') bg-green-100 text-green-600
                                @elseif($log->color === 'blue') bg-blue-100 text-blue-600
                                @elseif($log->color === 'yellow') bg-yellow-100 text-yellow-600
                                @elseif($log->color === 'red') bg-red-100 text-red-600
                                @elseif($log->color === 'purple') bg-purple-100 text-purple-600
                                @else bg-gray-100 text-gray-600
                                @endif
                                flex items-center justify-center text-sm">
                                {{ $log->icon }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">{{ $log->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-sm">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Articles Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">📝 Recent Articles Activity</h2>
            <a href="{{ route('staff.news.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3 px-2">Title</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3 px-2">Category</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3 px-2">Status</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3 px-2">Created</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3 px-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentArticles as $article)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-2 text-sm text-gray-900 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="line-clamp-1">{{ $article->title }}</span>
                                    @if($article->is_featured)
                                        <span class="text-purple-600 flex-shrink-0">⭐</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-2 text-sm">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $article->category)) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if ($article->status === 'published') bg-green-100 text-green-800 border border-green-200
                                    @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif($article->status === 'approved') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($article->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-sm text-gray-600">
                                <div>{{ $article->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $article->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-3 px-2 text-sm">
                                <a href="{{ route('staff.news.show', $article->id) }}" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No articles yet</p>
                                <p class="text-gray-400 text-sm mt-1">Create your first article to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Articles Trend Chart Data
    const articlesTrendData = @json($articlesTrend);

    // Events Trend Chart Data
    const eventsTrendData = @json($eventsTrend);

    // Chart.js Configuration
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 10
                    },
                    maxRotation: 45,
                    minRotation: 45
                },
                grid: {
                    display: false
                }
            }
        }
    };

    // Articles Chart
    const articlesCtx = document.getElementById('articlesChart').getContext('2d');
    const articlesChart = new Chart(articlesCtx, {
        type: 'line',
        data: {
            labels: articlesTrendData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Articles Created',
                data: articlesTrendData.map(item => item.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: chartOptions
    });

    // Events Chart
    const eventsCtx = document.getElementById('eventsChart').getContext('2d');
    const eventsChart = new Chart(eventsCtx, {
        type: 'line',
        data: {
            labels: eventsTrendData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Events Created',
                data: eventsTrendData.map(item => item.count),
                borderColor: 'rgb(168, 85, 247)',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(168, 85, 247)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgb(168, 85, 247)',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: chartOptions
    });

    // Optional: Period change handlers (for future AJAX updates)
    document.getElementById('articles-trend-period')?.addEventListener('change', function() {
        // Implement AJAX call to update chart data based on selected period
        console.log('Articles period changed to:', this.value);
    });

    document.getElementById('events-trend-period')?.addEventListener('change', function() {
        // Implement AJAX call to update chart data based on selected period
        console.log('Events period changed to:', this.value);
    });
</script>
@endpush
