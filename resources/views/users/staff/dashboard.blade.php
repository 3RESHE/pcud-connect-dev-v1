@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-8">Staff Dashboard</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Total Events</div>
            <div class="text-3xl font-bold">{{ $total_events }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Published Events</div>
            <div class="text-3xl font-bold text-green-600">{{ $published_events }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Pending Review</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pending_events }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Total Articles</div>
            <div class="text-3xl font-bold">{{ $total_articles }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('staff.events.create') }}" class="bg-blue-600 text-white p-4 rounded text-center hover:bg-blue-700">
            Create Event
        </a>
        <a href="{{ route('staff.news.create') }}" class="bg-green-600 text-white p-4 rounded text-center hover:bg-green-700">
            Write Article
        </a>
    </div>

    <!-- Recent Events -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Recent Events</h2>
        <ul class="space-y-2">
            @foreach ($recent_events as $event)
                <li class="flex justify-between items-center border-b pb-2">
                    <div>
                        <div class="font-bold">{{ $event->title }}</div>
                        <div class="text-sm text-gray-600">{{ $event->event_date->format('M d, Y') }}</div>
                    </div>
                    <span class="text-sm px-2 py-1 rounded @if($event->status === 'published') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
