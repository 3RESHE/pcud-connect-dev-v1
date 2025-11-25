<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PCU-DASMA Connect - Digital Platform for External Affairs, Partnerships and Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#1E40AF",
                        secondary: "#3B82F6",
                        accent: "#F59E0B",
                    },
                },
            },
        };
    </script>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-primary">PCU-DASMA Connect</h1>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-gray-700 hover:text-primary transition">About</a>
                    <a href="#events" class="text-gray-700 hover:text-primary transition">Events</a>
                    <a href="#jobs" class="text-gray-700 hover:text-primary transition">Jobs</a>
                    <a href="#news" class="text-gray-700 hover:text-primary transition">News</a>
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition">Login</a>
                </div>

                <button class="md:hidden text-gray-700" id="mobileMenuBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="hidden md:hidden pb-4" id="mobileMenu">
                <a href="#about" class="block py-2 text-gray-700 hover:text-primary">About</a>
                <a href="#events" class="block py-2 text-gray-700 hover:text-primary">Events</a>
                <a href="#jobs" class="block py-2 text-gray-700 hover:text-primary">Jobs</a>
                <a href="#news" class="block py-2 text-gray-700 hover:text-primary">News</a>
                <a href="{{ route('login') }}"
                    class="block mt-2 px-4 py-2 bg-primary text-white rounded-lg text-center">Login</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary via-blue-700 to-secondary text-white overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0YzYuNjI3IDAgMTItNS4zNzMgMTItMTJTNDIuNjI3LTEwIDM2LTEwIDI0LTQuNjI3IDI0IDJzNS4zNzMgMTIgMTIgMTJ6TTEyIDM4YzYuNjI3IDAgMTItNS4zNzMgMTItMTJTMTguNjI3IDE0IDEyIDE0IDAgMTkuMzczIDAgMjZzNS4zNzMgMTIgMTIgMTJ6bTI0IDI0YzYuNjI3IDAgMTItNS4zNzMgMTItMTJzLTUuMzczLTEyLTEyLTEyLTEyIDUuMzczLTEyIDEyIDUuMzczIDEyIDEyIDEyeiIvPjwvZz48L2c+PC9zdmc+')] opacity-20">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-32">
            <div class="text-center">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    Welcome to <span class="text-yellow-300">PCU-DASMA</span> Connect
                </h2>
                <p class="text-xl sm:text-2xl text-blue-100 mb-4 max-w-3xl mx-auto">
                    Digital Platform for External Affairs, Partnerships and Alumni
                </p>
                <p class="text-lg text-blue-200 mb-10 max-w-2xl mx-auto">
                    Discover Events, Job Opportunities, and Latest News - Connect with our vibrant community
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-white text-primary font-bold rounded-lg hover:bg-gray-100 transition shadow-lg transform hover:scale-105">
                        Get Started
                    </a>
                    <a href="#about"
                        class="px-8 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-primary transition transform hover:scale-105">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">About PCU-DASMA Connect</h2>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    A comprehensive digital platform designed to strengthen the relationship between Philippine
                    Christian University - Dasmariñas, its alumni, partners, and students.
                </p>
            </div>

            <div class="max-w-4xl mx-auto mt-16">
                <!-- Our Mission -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Our Mission</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        PCU-DASMA Connect serves as the central hub for alumni engagement, career opportunities,
                        university updates, and partnership coordination. We bridge the gap between the university, its
                        graduates, and external partners while fostering meaningful collaborations.
                    </p>

                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700">Connect alumni and students with verified job opportunities
                                from partner organizations</span>
                        </li>
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700">Keep alumni informed about campus events, reunions, and
                                celebrations</span>
                        </li>
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700">Share university updates, alumni achievements, and
                                partnership success stories</span>
                        </li>
                        <li class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700">Streamline partnership management and external
                                collaborations</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <!-- Featured Events Section -->
    <section id="events" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span
                    class="inline-block px-4 py-1 bg-primary bg-opacity-10 text-primary rounded-full text-sm font-semibold mb-4">
                    UPCOMING EVENTS
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Featured Events</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Join our upcoming events and connect with the community
                </p>
            </div>

            @if ($featuredEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($featuredEvents as $event)
                        <div
                            class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
                            <!-- Event Image -->
                            <div class="relative h-52 overflow-hidden bg-gradient-to-br from-primary to-secondary">
                                @if ($event->event_image)
                                    <img src="{{ asset($event->event_image) }}" alt="{{ $event->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-40" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Featured Badge -->
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold shadow-lg flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                                <!-- Status Badge -->
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-3 py-1 bg-white bg-opacity-90 text-primary rounded-full text-xs font-semibold capitalize">
                                        {{ $event->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Event Content -->
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $event->event_date->format('M d, Y') }}
                                </div>

                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-primary transition">
                                    {{ $event->title }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit($event->description, 120) }}
                                </p>

                                <div class="space-y-2 mb-6">
                                    @if ($event->venue_name)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-4 h-4 mr-2 text-primary" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="truncate">{{ $event->venue_name }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-primary" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('login') }}"
                                    class="block w-full text-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition font-semibold group-hover:shadow-lg">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-8 py-4 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-bold transition shadow-md hover:shadow-xl transform hover:scale-105">
                        View All Events
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No featured events at the moment</p>
                    <p class="text-gray-400 text-sm mt-2">Check back soon for upcoming events!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <section id="jobs" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span
                    class="inline-block px-4 py-1 bg-accent bg-opacity-10 text-accent rounded-full text-sm font-semibold mb-4">
                    CAREER OPPORTUNITIES
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Featured Job Opportunities</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Explore career opportunities with our partner companies
                </p>
            </div>

            @if ($featuredJobs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($featuredJobs as $job)
                        <div
                            class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
                            <!-- Job Header -->
                            <div class="relative bg-gradient-to-r from-accent to-orange-500 text-white p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3
                                        class="text-xl font-bold flex-1 pr-2 line-clamp-2 group-hover:scale-105 transition">
                                        {{ $job->title }}
                                    </h3>
                                    <span
                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold shadow-lg flex items-center flex-shrink-0">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                                <p class="text-sm opacity-90 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $job->partner->company_name ?? 'Partner Company' }}
                                </p>
                            </div>

                            <!-- Job Content -->
                            <div class="p-6">
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-accent flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="truncate">{{ $job->location ?? 'Multiple Locations' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-accent flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.669 0-3.218.51-4.5 1.385A7.968 7.968 0 009 4.804z">
                                            </path>
                                        </svg>
                                        <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type ?? 'Full-time')) }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-accent flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Deadline: {{ $job->application_deadline->format('M d, Y') }}</span>
                                    </div>
                                    @if ($job->salary_min && $job->salary_max)
                                        <div class="flex items-center text-sm font-semibold text-green-600">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span>₱{{ number_format($job->salary_min) }} -
                                                ₱{{ number_format($job->salary_max) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                    {{ Str::limit($job->description, 120) }}
                                </p>

                                <a href="{{ route('login') }}"
                                    class="block w-full text-center px-4 py-3 bg-accent text-white rounded-lg hover:bg-orange-600 transition font-semibold group-hover:shadow-lg">
                                    View Position
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-8 py-4 border-2 border-accent text-accent hover:bg-accent hover:text-white rounded-lg font-bold transition shadow-md hover:shadow-xl transform hover:scale-105">
                        View All Jobs
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-xl shadow-sm">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No featured job opportunities at the moment</p>
                    <p class="text-gray-400 text-sm mt-2">Check back soon for new positions!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured News Section -->
    <section id="news" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span
                    class="inline-block px-4 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                    LATEST UPDATES
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Latest News & Updates</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Stay informed with the latest news from PCU-DASMA
                </p>
            </div>

            @if ($featuredNews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($featuredNews as $news)
                        <div
                            class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
                            <!-- News Image -->
                            <div class="relative h-52 overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300">
                                @if ($news->featured_image)
                                    <img src="{{ asset('storage/' . $news->featured_image) }}"
                                        alt="{{ $news->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Featured Badge -->
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold shadow-lg flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            </div>

                            <!-- News Content -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $news->created_at->format('M d, Y') }}
                                    </div>
                                </div>

                                @if ($news->category)
                                    <span
                                        class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold mb-3">
                                        {{ ucfirst(str_replace('_', ' ', $news->category)) }}
                                    </span>
                                @endif

                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-primary transition">
                                    {{ $news->title }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                    {{ Str::limit($news->summary ?? strip_tags($news->content), 150) }}
                                </p>

                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center text-primary hover:text-blue-700 font-semibold transition group-hover:gap-2">
                                    Read More
                                    <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-8 py-4 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-bold transition shadow-md hover:shadow-xl transform hover:scale-105">
                        View All News
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No featured news at the moment</p>
                    <p class="text-gray-400 text-sm mt-2">Check back soon for latest updates!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative bg-gradient-to-r from-primary to-secondary text-white py-20 overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0YzYuNjI3IDAgMTItNS4zNzMgMTItMTJTNDIuNjI3LTEwIDM2LTEwIDI0LTQuNjI3IDI0IDJzNS4zNzMgMTIgMTIgMTJ6TTEyIDM4YzYuNjI3IDAgMTItNS4zNzMgMTItMTJTMTguNjI3IDE0IDEyIDE0IDAgMTkuMzczIDAgMjZzNS4zNzMgMTIgMTIgMTJ6bTI0IDI0YzYuNjI3IDAgMTItNS4zNzMgMTItMTJzLTUuMzczLTEyLTEyLTEyLTEyIDUuMzczLTEyIDEyIDUuMzczIDEyIDEyIDEyeiIvPjwvZz48L2c+PC9zdmc+')] opacity-20">
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of students, alumni, and partners on PCU-DASMA Connect
            </p>
            <a href="{{ route('login') }}"
                class="inline-block px-8 py-4 bg-white text-primary font-bold rounded-lg hover:bg-gray-100 transition shadow-xl transform hover:scale-105">
                Login or Create Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">PCU-DASMA Connect</h3>
                    <p class="text-sm text-gray-400">
                        Digital Platform for External Affairs, Partnerships and Alumni
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#about" class="hover:text-white transition">About</a></li>
                        <li><a href="#events" class="hover:text-white transition">Events</a></li>
                        <li><a href="#jobs" class="hover:text-white transition">Jobs</a></li>
                        <li><a href="#news" class="hover:text-white transition">News</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="#" class="hover:text-white transition">Help & Support</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            <span>info@pcu.edu.ph</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                </path>
                            </svg>
                            <span>+63 (0) 2 XXX-XXXX</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>PCU-DASMA Campus</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <p class="text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} PCU-DASMA Connect. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu & Smooth Scroll Script -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking on a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && !href.startsWith('#/')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>
