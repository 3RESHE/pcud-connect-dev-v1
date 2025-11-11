@php
    $layout = match (auth()->user()->role) {
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', $event->title . ' - Event Details')

@section('content')
<!-- Back Button -->
<div class="container mx-auto px-4 mb-6 mt-6">
    <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Events
    </a>
</div>

<!-- Success Alert Banner -->
@if($isRegistered)
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-r-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-medium text-green-800">Registered Successfully!</h3>
                    <p class="mt-2 text-sm text-green-700">
                        You are registered for this event. Check your email for confirmation and updates.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Event Hero Section -->
<div class="relative w-full h-96 bg-gradient-to-br from-blue-50 to-gray-100 overflow-hidden mb-8">
    @if($event->event_image)
        <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-500"></div>
    @endif

    <!-- Hero Content -->
    <div class="absolute inset-0 flex flex-col justify-end">
        <div class="container mx-auto px-4 pb-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">{{ $event->title }}</h1>
                    <p class="text-blue-50 text-lg drop-shadow">Organized by <span class="font-semibold">{{ $event->creator->full_name }}</span></p>
                </div>

                <!-- Status Badges -->
                <div class="flex flex-col gap-2">
                    @if($event->is_featured)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-400 text-yellow-900 shadow-lg w-fit">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Featured
                        </span>
                    @endif
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white text-blue-600 shadow-lg w-fit">
                        @if($event->event_format === 'inperson') In-Person
                        @elseif($event->event_format === 'virtual') Virtual
                        @else Hybrid @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="container mx-auto px-4 mb-8 flex justify-end">
    <div class="flex space-x-4">
        @if($isRegistered && $registration->created_at->toDateString() >= now()->toDateString())
            <form action="{{ route('events.unregister', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your registration?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-8 py-3 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg font-medium flex items-center text-base transition-colors">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel Registration
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Main Content Grid -->
<div class="container mx-auto px-4 mb-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Event Information -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Event Information
            </h2>
            <div class="mb-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-1">Description</p>
                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                </div>
            </div>

            @if($event->event_tags)
                <div class="flex flex-wrap gap-3 mt-6">
                    @foreach(explode(',', $event->event_tags) as $tag)
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">#{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Date & Time Section -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Date & Time
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Start Date -->
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-base font-medium text-gray-700">Start Date</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ $event->event_date->format('F d, Y') }}</p>
                </div>

                <!-- Time -->
                <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-base font-medium text-gray-700">Time</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ $event->start_time }} - {{ $event->end_time }}</p>
                </div>

                <!-- Capacity -->
                <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 12a2 2 0 11-4 0 2 2 0 014 0zM13 16a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-base font-medium text-gray-700">Attendees</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ $registrationCount }}/{{ $event->max_attendees ?? '∞' }}</p>
                </div>
            </div>
        </div>

        <!-- Location & Venue -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Location & Format
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(in_array($event->event_format, ['inperson', 'hybrid']))
                    <div>
                        <p class="text-base text-gray-500 mb-2 font-medium">📍 Venue</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->venue_name ?? 'TBA' }}</p>
                    </div>
                    @if($event->venue_address)
                        <div>
                            <p class="text-base text-gray-500 mb-2 font-medium">📮 Address</p>
                            <p class="text-gray-700 text-lg">{{ $event->venue_address }}</p>
                        </div>
                    @endif
                @endif

                @if(in_array($event->event_format, ['virtual', 'hybrid']))
                    <div>
                        <p class="text-base text-gray-500 mb-2 font-medium">🌐 Platform</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $event->platform === 'other' ? $event->custom_platform : ucfirst($event->platform) }}</p>
                    </div>
                    @if($event->meeting_link)
                        <div>
                            <p class="text-base text-gray-500 mb-2 font-medium">🔗 Meeting Link</p>
                            <a href="{{ $event->meeting_link }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.757 15.657a1 1 0 00-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707zM2 10a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.757 4.343a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707z"></path>
                                </svg>
                                Join Meeting
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-8">
        <!-- Registration Card -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 top-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Register</h3>

            @if($isRegistered)
                <!-- Already Registered -->
                <div class="mb-6 p-4 bg-green-50 border-2 border-green-300 rounded-lg">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-bold text-green-800">You're Registered!</p>
                    </div>
                    <p class="text-sm text-green-700">Registered on {{ $registration->created_at->format('M d, Y') }}</p>
                </div>
            @else
                <!-- Registration Form or Full Message -->
                @if($event->max_attendees && $registrationCount >= $event->max_attendees)
                    <div class="p-4 bg-red-50 border-2 border-red-300 rounded-lg text-center">
                        <p class="text-red-800 font-bold text-lg">❌ Event Full</p>
                        <p class="text-sm text-red-700 mt-1">This event has reached capacity</p>
                    </div>
                @else
                    <form action="{{ route('events.register', $event->id) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Terms Checkbox -->
                        <div class="flex items-start p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <input type="checkbox" name="agree_terms" id="agree_terms" required
                                class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                            <label for="agree_terms" class="ml-3 text-sm text-gray-700">
                                I agree to the <span class="font-semibold">event terms and conditions</span>
                            </label>
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-colors shadow-md">
                            Register Now
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <!-- Event Status -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h3 class="font-bold text-gray-900 text-xl mb-6">Event Status</h3>
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-500 mb-1 font-medium">Status</p>
                    <p class="font-semibold text-gray-900 text-lg capitalize">{{ $event->status }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-500 mb-1 font-medium">Registrations</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $registrationCount }}/{{ $event->max_attendees ?? '∞' }}</p>
                    @if($event->max_attendees)
                        <div class="w-full bg-gray-300 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $capacityPercent }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <h3 class="font-bold text-gray-900 text-xl mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Contact Information
            </h3>
            <div class="space-y-6">
                <div>
                    <p class="text-base text-gray-500">Contact Person</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $event->creator->full_name }}</p>
                </div>
                @if($event->creator->email)
                    <div>
                        <p class="text-base text-gray-500">Contact Email</p>
                        <a href="mailto:{{ $event->creator->email }}" class="text-blue-600 hover:underline text-lg">{{ $event->creator->email }}</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Similar Events -->
        @if($similarEvents->count())
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Similar Events</h3>
                <div class="space-y-3">
                    @foreach($similarEvents as $similar)
                        <a href="{{ route('events.show', $similar->id) }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all group">
                            <p class="font-semibold text-gray-900 group-hover:text-blue-600 text-sm line-clamp-2">{{ $similar->title }}</p>
                            <p class="text-xs text-gray-500 mt-2">📅 {{ $similar->event_date->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Error Messages -->
@if ($errors->any())
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center z-50">
        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <p class="font-semibold">{{ $errors->first() }}</p>
    </div>
@endif

@endsection
