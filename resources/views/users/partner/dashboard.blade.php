@extends('layouts.partner')

@section('title', 'Dashboard - PCU-DASMA Connect')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">Partner Dashboard</h1>
        <p class="opacity-90">
            Welcome back! Manage your job postings and partnership proposals
            with PCU-DASMA.
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Post Job Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-primary transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Post a Job</h3>
            <p class="text-gray-600 mb-4">Create job postings or internship opportunities for PCU-DASMA students and alumni.</p>
            <a href="{{ route('partner.job-postings.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Job Post
            </a>
        </div>
    </div>

    <!-- Partnership Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-green-500 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Partnership Proposal</h3>
            <p class="text-gray-600 mb-4">Submit proposals for internship programs, research collaborations, or training partnerships.</p>
            <a href="{{ route('partner.partnerships.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Submit Proposal
            </a>
        </div>
    </div>

    <!-- News Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-purple-500 transition-colors">
        <div class="text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Create News Article</h3>
            <p class="text-gray-600 mb-4">Write and publish news articles to share updates, announcements, or stories with your audience.</p>
            <a href="{{ route('partner.news.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Publish Article
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Job Postings</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $active_jobs ?? 5 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Partnerships</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $active_partnerships ?? 2 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Applications</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_applications ?? 142 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_reviews ?? 3 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Job Postings -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Job Postings</h3>
            <a href="{{ route('partner.job-postings.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recent_jobs ?? [] as $job)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs mr-2">Active</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs mr-2">{{ $job->job_type }}</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $job->title }}</h4>
                            <p class="text-gray-600 mb-2">{{ $job->location }} • ₱{{ number_format($job->salary_min) }} - ₱{{ number_format($job->salary_max) }}</p>
                            <p class="text-sm text-gray-500">{{ $job->applications_count ?? 0 }} applications • {{ $job->views_count ?? 0 }} views</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No job postings yet. <a href="{{ route('partner.job-postings.create') }}" class="text-primary hover:underline">Create one now</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Partnership Proposals -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Partnership Proposals</h3>
            <a href="{{ route('partner.partnerships.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recent_partnerships ?? [] as $partnership)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="@if($partnership->status === 'approved') bg-green-100 text-green-800 @elseif($partnership->status === 'pending') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif px-2 py-1 rounded-full text-xs mr-2">{{ ucfirst($partnership->status) }}</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs mr-2">{{ $partnership->type }}</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $partnership->title }}</h4>
                            <p class="text-gray-600 mb-2">{{ $partnership->description }}</p>
                            <p class="text-sm text-gray-500">{{ $partnership->slots ?? 0 }} slots available</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No partnership proposals yet. <a href="{{ route('partner.partnerships.create') }}" class="text-primary hover:underline">Create one now</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
