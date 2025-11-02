@extends('layouts.app')

@section('title', 'Partner Dashboard')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-8">Partner Dashboard</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Total Jobs</div>
            <div class="text-3xl font-bold">{{ $total_jobs }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Published</div>
            <div class="text-3xl font-bold text-green-600">{{ $published_jobs }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Pending</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pending_jobs }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Applications</div>
            <div class="text-3xl font-bold">{{ $total_applications }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Pending Apps</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pending_applications }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('partner.jobs.create') }}" class="bg-blue-600 text-white p-4 rounded text-center hover:bg-blue-700">
            Post Job
        </a>
        <a href="{{ route('partner.partnerships.create') }}" class="bg-green-600 text-white p-4 rounded text-center hover:bg-green-700">
            Submit Partnership
        </a>
    </div>

    <!-- Recent Jobs -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Recent Job Postings</h2>
        <ul class="space-y-2">
            @foreach ($recent_jobs as $job)
                <li class="flex justify-between items-center border-b pb-2">
                    <div>
                        <div class="font-bold">{{ $job->title }}</div>
                        <div class="text-sm text-gray-600">Deadline: {{ $job->application_deadline->format('M d, Y') }}</div>
                    </div>
                    <span class="text-sm px-2 py-1 rounded @if($job->status === 'published') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($job->status) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
