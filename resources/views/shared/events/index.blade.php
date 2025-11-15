@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Discover Events')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-12 mb-8">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2">Discover Events</h1>
            <p class="text-blue-100">Find and register for amazing events happening at PCUD</p>
        </div>

        <!-- My Registrations Button -->
        @if($stats['registered'] > 0)
            <a href="{{ route('events.myRegistrations') }}" class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition-colors shadow-md">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                My Registrations ({{ $stats['registered'] }})
            </a>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="container mx-auto px-4 mb-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-gray-400 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Events</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Upcoming</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-purple-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Past Events</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['past'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Your Registrations</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['registered'] }}</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="container mx-auto px-4 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('events.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Search Input -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <svg class="absolute left-4 top-10 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search events by title, description, or organizer..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Event Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                    <select name="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer bg-white">
                        <option value="">All Types</option>
                        <option value="inperson" @if($currentType === 'inperson') selected @endif>In-Person</option>
                        <option value="virtual" @if($currentType === 'virtual') selected @endif>Virtual</option>
                        <option value="hybrid" @if($currentType === 'hybrid') selected @endif>Hybrid</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        Search
                    </button>
                </div>
            </div>

            <!-- Clear Filters Link -->
            <div class="text-center mt-3">
                <a href="{{ route('events.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Clear All Filters
                </a>
            </div>
        </form>
    </div>
</div>

<!-- ✅ HELPER FUNCTION: Get Event Status Badge -->
@php
    $getEventStatusBadge = function($event) {
        if ($event->status === 'completed') {
            return [
                'label' => 'Completed Event',
                'color' => 'bg-gray-600',
                'textColor' => 'text-gray-900',
                'bgColor' => 'bg-gray-100',
                'icon' => '✓'
            ];
        } elseif ($event->status === 'ongoing') {
            return [
                'label' => 'Ongoing Event',
                'color' => 'bg-orange-600',
                'textColor' => 'text-orange-900',
                'bgColor' => 'bg-orange-100',
                'icon' => '●'
            ];
        } else {
            // published
            return [
                'label' => 'Upcoming Event',
                'color' => 'bg-green-600',
                'textColor' => 'text-green-900',
                'bgColor' => 'bg-green-100',
                'icon' => '→'
            ];
        }
    };
@endphp

<!-- Featured Events Section -->
@php
    $featuredEvents = $events->where('is_featured', true)->take(4);
@endphp

@if($featuredEvents->count() > 0)
    <div class="container mx-auto px-4 mb-12">
        <div class="flex items-center mb-6">
            <svg class="w-8 h-8 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900">Featured Events</h2>
            <span class="ml-3 px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">{{ $featuredEvents->count() }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredEvents as $event)
                @php
                    $badgeInfo = $getEventStatusBadge($event);
                @endphp
                <a href="{{ route('events.show', $event->id) }}" class="group block">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-t-4 border-t-yellow-500 cursor-pointer">
                        <!-- Image Container -->
                        <div class="relative w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if($event->event_image)
                                <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-50 to-yellow-100">
                                    <svg class="w-12 h-12 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status and Featured Badges -->
                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <!-- Featured Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Featured
                                </span>

                                <!-- ✅ Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeInfo['bgColor'] }} {{ $badgeInfo['textColor'] }}">
                                    {{ $badgeInfo['label'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Container -->
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">
                                {{ $event->title }}
                            </h3>

                            <div class="space-y-2 flex-grow text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <p class="text-gray-700 line-clamp-1">{{ $event->creator->full_name ?? 'N/A' }}</p>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-700">
                                        @if($event->is_multiday && $event->end_date)
                                            {{ $event->event_date?->format('M d') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                        @else
                                            {{ $event->event_date?->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <p class="text-gray-700 capitalize">
                                        @if($event->event_format === 'inperson') In-Person
                                        @elseif($event->event_format === 'virtual') Virtual
                                        @else Hybrid @endif
                                    </p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-100 mt-3">
                                <div class="flex items-center justify-between">
                                    @if(in_array($event->id, $registeredEventIds))
                                        <span class="flex items-center text-green-600 text-xs font-bold">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Registered
                                        </span>
                                    @else
                                        <span></span>
                                    @endif
                                    <svg class="w-4 h-4 text-blue-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

<!-- All Events Section -->
<div class="container mx-auto px-4 mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">All Events</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($events as $event)
            @php
                $badgeInfo = $getEventStatusBadge($event);
            @endphp
            <a href="{{ route('events.show', $event->id) }}" class="group block">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden h-full flex flex-col border-t-4 border-t-blue-500 cursor-pointer">
                    <!-- Image Container -->
                    <div class="relative w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                        @if($event->event_image)
                            <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- ✅ Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeInfo['bgColor'] }} {{ $badgeInfo['textColor'] }}">
                                {{ $badgeInfo['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Container -->
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-base font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">
                            {{ $event->title }}
                        </h3>

                        <div class="space-y-2 flex-grow text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p class="text-gray-700 line-clamp-1">{{ $event->creator->full_name ?? 'N/A' }}</p>
                            </div>

                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-700">
                                    @if($event->is_multiday && $event->end_date)
                                        {{ $event->event_date?->format('M d') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                    @else
                                        {{ $event->event_date?->format('M d, Y') }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <p class="text-gray-700 capitalize">
                                    @if($event->event_format === 'inperson') In-Person
                                    @elseif($event->event_format === 'virtual') Virtual
                                    @else Hybrid @endif
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 mt-3">
                            <div class="flex items-center justify-between">
                                @if(in_array($event->id, $registeredEventIds))
                                    <span class="flex items-center text-green-600 text-xs font-bold">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Registered
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                <svg class="w-4 h-4 text-blue-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <!-- Empty State -->
            <div class="col-span-full bg-white rounded-lg shadow-md p-16 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-600">No events match your search criteria. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>

@endsection
