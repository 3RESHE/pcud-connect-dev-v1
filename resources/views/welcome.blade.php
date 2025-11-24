<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PCU-DASMA Connect - Events, Jobs & News</title>
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
    <!-- Header/Navbar -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary">PCU-DASMA Connect</h1>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#events" class="text-gray-700 hover:text-primary transition">Events</a>
                    <a href="#jobs" class="text-gray-700 hover:text-primary transition">Jobs</a>
                    <a href="#news" class="text-gray-700 hover:text-primary transition">News</a>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition">Login</a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" id="mobileMenuBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="hidden md:hidden pb-4" id="mobileMenu">
                <a href="#events" class="block py-2 text-gray-700 hover:text-primary">Events</a>
                <a href="#jobs" class="block py-2 text-gray-700 hover:text-primary">Jobs</a>
                <a href="#news" class="block py-2 text-gray-700 hover:text-primary">News</a>
                <a href="{{ route('login') }}" class="block mt-2 px-4 py-2 bg-primary text-white rounded-lg text-center">Login</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl font-bold mb-4">Welcome to PCU-DASMA Connect</h2>
            <p class="text-xl text-blue-100 mb-8">Discover Events, Job Opportunities, and Latest News</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-gray-100 transition">
                    Join Now
                </a>
                <a href="#events" class="px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-primary transition">
                    Explore Events
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section id="events" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Featured Events</h2>
                <p class="text-lg text-gray-600">Join our upcoming events and connect with the community</p>
            </div>

            @if($featuredEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredEvents as $event)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden border border-gray-200">
                        <!-- Event Header -->
                        <div class="bg-gradient-to-r from-primary to-secondary text-white p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold mb-2">{{ $event->title }}</h3>
                                    <div class="flex items-center text-sm opacity-90">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h12a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $event->event_date->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-semibold">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Event Body -->
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($event->description, 150) }}
                            </p>

                            <div class="space-y-3 mb-6 text-sm text-gray-700">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $event->location ?? 'TBA' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $event->registrations()->count() ?? 0 }} Registered</span>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <a href="{{ route('login') }}" class="w-full block text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View All Events Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-semibold transition">
                        View All Events
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No featured events at the moment. Please check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <section id="jobs" class="py-16 sm:py-24 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Featured Job Opportunities</h2>
                <p class="text-lg text-gray-600">Explore career opportunities with our partner companies</p>
            </div>

            @if($featuredJobs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredJobs as $job)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden border border-gray-200">
                        <!-- Job Header -->
                        <div class="bg-gradient-to-r from-accent to-orange-400 text-white p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $job->title }}</h3>
                            <p class="text-sm opacity-90">{{ $job->partner->company_name ?? 'Partner Company' }}</p>
                        </div>

                        <!-- Job Body -->
                        <div class="p-6">
                            <div class="space-y-3 mb-6 text-sm">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $job->location ?? 'Multiple Locations' }}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.669 0-3.218.51-4.5 1.385A7.968 7.968 0 009 4.804z"></path>
                                    </svg>
                                    <span>{{ $job->employment_type ?? 'Full-time' }}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h12a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Deadline: {{ $job->application_deadline->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                                {{ Str::limit($job->description, 120) }}
                            </p>

                            <!-- CTA Button -->
                            <a href="{{ route('login') }}" class="w-full block text-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-orange-600 transition font-medium">
                                View Position
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View All Jobs Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border-2 border-accent text-accent hover:bg-accent hover:text-white rounded-lg font-semibold transition">
                        View All Jobs
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">No job opportunities at the moment. Please check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured News Section -->
    <section id="news" class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Latest News & Updates</h2>
                <p class="text-lg text-gray-600">Stay informed with the latest news from PCU-DASMA</p>
            </div>

            @if($featuredNews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredNews as $news)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden border border-gray-200">
                        <!-- News Image Placeholder -->
                        <div class="bg-gradient-to-br from-gray-200 to-gray-300 h-48 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- News Body -->
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h12a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $news->created_at->format('M d, Y') }}
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $news->title }}</h3>

                            <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                {{ Str::limit(strip_tags($news->content), 150) }}
                            </p>

                            <!-- CTA Button -->
                            <a href="{{ route('login') }}" class="inline-flex items-center text-primary hover:text-blue-700 font-semibold transition">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View All News Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-semibold transition">
                        View All News
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">No news at the moment. Please check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-lg text-blue-100 mb-8">Join thousands of students, alumni, and partners on PCU-DASMA Connect</p>
            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-white text-primary font-bold rounded-lg hover:bg-gray-100 transition">
                Login or Create Account
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-bold mb-4">PCU-DASMA Connect</h3>
                    <p class="text-sm">Connecting students, alumni, and partners for mutual growth and success.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Quick Links</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="#events" class="hover:text-white transition">Events</a></li>
                        <li><a href="#jobs" class="hover:text-white transition">Jobs</a></li>
                        <li><a href="#news" class="hover:text-white transition">News</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Resources</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="#" class="hover:text-white transition">Help & Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Contact</h4>
                    <ul class="text-sm space-y-2">
                        <li>Email: info@pcu.edu.ph</li>
                        <li>Phone: +63 (0) 2 XXX-XXXX</li>
                        <li>Address: PCU-DASMA Campus</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <p class="text-center text-sm">
                    &copy; 2025 PCU-DASMA Connect. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
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

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && !href.startsWith('#/')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>
