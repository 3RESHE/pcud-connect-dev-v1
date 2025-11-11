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
        <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Events
        </a>
    </div>

    <!-- Event Header Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl overflow-hidden mb-8 shadow-lg">
        <div class="px-8 py-16">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            @if ($event->event_format === 'inperson')
                                In-Person
                            @elseif($event->event_format === 'virtual')
                                Virtual
                            @else
                                Hybrid
                            @endif
                        </span>
                        @if ($event->is_featured)
                            <span
                                class="bg-yellow-500 text-yellow-900 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>
                </div>
                @if ($isRegistered)
                    <div class="bg-green-500 bg-opacity-20 border border-green-300 rounded-lg px-4 py-3 text-right">
                        <p class="text-green-100 text-sm mb-1">Registration Status</p>
                        <p class="text-white font-bold">Registered</p>
                    </div>
                @endif
            </div>
            <p class="text-blue-100">By <span class="font-semibold">{{ $event->creator->full_name }}</span></p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="container mx-auto px-4 mb-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Event Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Overview -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                <p class="text-gray-700 leading-relaxed mb-6">{{ $event->description }}</p>

                @if ($event->event_tags)
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $event->event_tags) as $tag)
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Details</h2>
                <div class="grid grid-cols-2 gap-6">
                    <!-- Date & Time -->
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Start Date & Time</p>
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ $event->event_date->format('F d, Y') }} at
                                {{ $event->formatted_start_time }}</span>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Duration</p>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ $event->formatted_start_time }} -
                                {{ $event->formatted_end_time }}</span>
                        </div>
                    </div>

                    <!-- Organizer -->
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Organized By</p>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ $event->creator->full_name }}</span>
                        </div>
                    </div>

                    <!-- Capacity -->
                    @if ($event->max_attendees)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Capacity</p>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM4.318 20H4a2 2 0 01-2-2v-2a6 6 0 0112 0v2a2 2 0 01-2 2h-.682">
                                    </path>
                                </svg>
                                <span class="font-semibold text-gray-900">{{ $registrationCount }} /
                                    {{ $event->max_attendees }} Attendees</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $capacityPercent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">{{ $capacityPercent }}% Capacity</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location/Venue -->
            @if (in_array($event->event_format, ['inperson', 'hybrid']))
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Venue</h2>
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $event->venue_name ?? 'TBA' }}</p>
                            @if ($event->venue_address)
                                <p class="text-gray-600 text-sm">{{ $event->venue_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Virtual Details -->
            @if (in_array($event->event_format, ['virtual', 'hybrid']))
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Virtual Information</h2>
                    <p class="text-gray-700 mb-4"><span class="font-semibold">Platform:</span>
                        {{ $event->platform === 'other' ? $event->custom_platform : ucfirst($event->platform) }}</p>
                    @if ($event->meeting_link)
                        <a href="{{ $event->meeting_link }}" target="_blank"
                            class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Join Meeting
                        </a>
                    @endif
                </div>
            @endif

            <!-- Registration Requirements -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Registration Info</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Registration Required</span>
                        <span
                            class="font-semibold text-gray-900">{{ $event->registration_required ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Walk-in Allowed</span>
                        <span class="font-semibold text-gray-900">{{ $event->walkin_allowed ? 'Yes' : 'No' }}</span>
                    </div>
                    @if ($event->registration_deadline)
                        <div class="flex justify-between">
                            <span class="text-gray-700">Registration Deadline</span>
                            <span
                                class="font-semibold text-gray-900">{{ $event->registration_deadline->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Registration Card -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Registration</h3>

                @if ($isRegistered)
                    <!-- Already Registered -->
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center text-green-800 mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-semibold">You're Registered!</p>
                        </div>
                        <p class="text-sm text-green-700">Registration Date:
                            {{ $registration->registration_date->format('M d, Y') }}</p>
                    </div>

                    <!-- Unregister Form -->
                    <form action="{{ route('events.unregister', $event->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors"
                            onclick="return confirm('Are you sure you want to unregister from this event?')">
                            Unregister
                        </button>
                    </form>
                @else
                    <!-- Check if event has capacity -->
                    @if ($event->max_attendees && $registrationCount >= $event->max_attendees)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 font-semibold">Event Full</p>
                            <p class="text-sm text-red-700">This event has reached maximum capacity</p>
                        </div>
                    @else
                        <!-- Registration Form -->
                        <form action="{{ route('events.register', $event->id) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Dietary Restriction -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dietary Restrictions</label>
                                <textarea name="dietary_restriction" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="Any dietary restrictions? (optional)"></textarea>
                            </div>

                            <!-- Special Requirements -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                                <textarea name="special_requirements" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="Any special needs or accommodations? (optional)"></textarea>
                            </div>

                            <!-- Terms Agreement -->
                            <div class="flex items-start">
                                <input type="checkbox" name="agree_terms" id="agree_terms" required
                                    class="mt-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <label for="agree_terms" class="ml-2 text-sm text-gray-600">
                                    I agree to the event terms and conditions
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Register Now
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Similar Events -->
            @if ($similarEvents->count())
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Similar Events</h3>
                    <div class="space-y-4">
                        @foreach ($similarEvents as $similar)
                            <a href="{{ route('events.show', $similar->id) }}"
                                class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($similar->title, 50) }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $similar->event_date->format('M d, Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg">
            <p class="font-semibold">{{ $errors->first() }}</p>
        </div>
    @endif
@endsection
