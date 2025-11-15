@extends('layouts.admin')

@section('title', 'Event Management - PCU-DASMA Connect')

@section('content')
    <!-- Header Section -->
    <div class="mb-10">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Event Management</h1>
                <p class="text-gray-600 mt-2">Review and manage all event submissions across the platform</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pending_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $approved_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Published</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $published_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-orange-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Ongoing</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $ongoing_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-purple-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Completed</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $completed_count }}</p>
            </div>
            <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejected</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejected_count }}</p>
            </div>
        </div>
    </div>

    <!-- Featured Events Section -->
    @if($events->where('is_featured', true)->count())
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <svg class="w-8 h-8 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900">Featured Events</h2>
                <span class="ml-3 px-4 py-1 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">{{ $events->where('is_featured', true)->count() }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($events->where('is_featured', true)->take(4) as $event)
                    <a href="{{ route('admin.approvals.events.show', $event->id) }}"
                       class="group featured-event-card block">

                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-t-4 border-t-yellow-500 cursor-pointer">
                            <!-- Image Container -->
                            <div class="relative w-full h-40 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                @if($event->event_image)
                                    <img src="{{ asset($event->event_image) }}"
                                        alt="{{ $event->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-50 to-yellow-100">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-yellow-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-yellow-600 font-medium text-sm">No Image</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Featured Badge -->
                                <div class="absolute top-3 right-3 flex gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm
                                        @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($event->status === 'approved') bg-green-100 text-green-800
                                        @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($event->status === 'published') bg-blue-100 text-blue-800
                                        @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                                        @elseif($event->status === 'completed') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Container -->
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">
                                    {{ $event->title }}
                                </h3>

                                <div class="space-y-2 flex-grow text-sm">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <p class="text-gray-700 line-clamp-1 text-xs">{{ $event->creator->full_name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-700 text-xs">{{ $event->event_date?->format('M d, Y') }}</p>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        <p class="text-gray-700 capitalize text-xs">
                                            @if($event->event_format === 'inperson')
                                                In-Person
                                            @elseif($event->event_format === 'virtual')
                                                Virtual
                                            @else
                                                Hybrid
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-100 mt-3 text-center">
                                    <div class="text-blue-600 font-medium text-xs">View Details</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Search and Filter</h3>
        <form method="GET" action="{{ route('admin.approvals.events.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ $search_query }}" placeholder="Search by title, staff, venue..."
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer bg-white">
                    <option value="all" @if($current_status === 'all') selected @endif>All Status</option>
                    <option value="pending" @if($current_status === 'pending') selected @endif>Pending Review</option>
                    <option value="approved" @if($current_status === 'approved') selected @endif>Approved</option>
                    <option value="published" @if($current_status === 'published') selected @endif>Published</option>
                    <option value="ongoing" @if($current_status === 'ongoing') selected @endif>Ongoing</option>
                    <option value="completed" @if($current_status === 'completed') selected @endif>Completed</option>
                    <option value="rejected" @if($current_status === 'rejected') selected @endif>Rejected</option>
                </select>
            </div>

            <!-- Search Button -->
            <div>
                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- All Events Grid -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">All Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <a href="{{ route('admin.approvals.events.show', $event->id) }}"
                   class="group event-card block">

                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-b-4 cursor-pointer
                        @if($event->status === 'pending') border-b-yellow-500
                        @elseif($event->status === 'approved') border-b-green-500
                        @elseif($event->status === 'rejected') border-b-red-500
                        @elseif($event->status === 'published') border-b-blue-500
                        @elseif($event->status === 'ongoing') border-b-orange-500
                        @elseif($event->status === 'completed') border-b-purple-500
                        @else border-b-gray-300 @endif">

                        <!-- Image Container -->
                        <div class="relative w-full h-44 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if($event->event_image)
                                <img src="{{ asset($event->event_image) }}"
                                    alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-blue-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-blue-600 font-medium text-sm">No Image</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm bg-opacity-95
                                    @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($event->status === 'approved') bg-green-100 text-green-800
                                    @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($event->status === 'published') bg-blue-100 text-blue-800
                                    @elseif($event->status === 'ongoing') bg-orange-100 text-orange-800
                                    @elseif($event->status === 'completed') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>

                            <!-- Featured Indicator -->
                            @if($event->is_featured)
                                <div class="absolute bottom-3 right-3">
                                    <svg class="w-6 h-6 text-yellow-400 drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content Container -->
                        <div class="p-5 flex flex-col flex-grow">
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">
                                {{ $event->title }}
                            </h3>

                            <!-- Event Info Grid -->
                            <div class="space-y-3 flex-grow">
                                <!-- Created By Staff -->
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Created By</p>
                                        <p class="text-sm text-gray-900 font-medium">{{ $event->creator->full_name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Date & Time</p>
                                        <p class="text-sm text-gray-900 font-medium">{{ $event->event_date?->format('M d, Y') }} at {{ $event->start_time }}</p>
                                    </div>
                                </div>

                                <!-- Format -->
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Format</p>
                                        <p class="text-sm text-gray-900 font-medium capitalize">
                                            @if($event->event_format === 'inperson')
                                                In-Person
                                            @elseif($event->event_format === 'virtual')
                                                Virtual
                                            @else
                                                Hybrid
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Click to View Indicator -->
                            <div class="pt-4 border-t border-gray-100 mt-4">
                                <div class="flex items-center justify-center text-blue-600 font-medium text-sm group-hover:text-blue-700">
                                    <span>Click to view details</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow-md p-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Events Found</h3>
                    <p class="text-gray-600">No events match your search criteria</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
@endsection
