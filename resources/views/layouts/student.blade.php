<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Student Dashboard - PCU-DASMA Connect')</title>
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
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Desktop Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('student.dashboard') }}" class="text-xl font-bold text-primary">
                        PCU-DASMA Connect
                    </a>
                    <!-- Desktop Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('student.dashboard') }}" class="@if(request()->routeIs('student.dashboard')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('student.jobs.index') }}" class="@if(request()->routeIs('student.jobs.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Job Opportunities
                        </a>
                        <a href="{{ route('student.events.index') }}" class="@if(request()->routeIs('student.events.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Events
                        </a>
                        <a href="{{ route('student.news.index') }}" class="@if(request()->routeIs('student.news.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            News
                        </a>
                        <a href="{{ route('student.profile.show') }}" class="@if(request()->routeIs('student.profile.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Profile
                        </a>
                    </div>
                </div>

                <!-- Desktop User Menu - ENHANCED DROPDOWN -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200" aria-label="Open user menu" aria-haspopup="true">
                            <div class="flex items-center space-x-2 px-3 py-2 rounded-full hover:bg-gray-100">
                                @if(auth()->user()->profile_photo)
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" />
                                @else
                                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userMenu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 transform transition-all duration-200" role="menu" aria-orientation="vertical">
                            <!-- User Info Section -->
                            <div class="px-4 py-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    @if(auth()->user()->profile_photo)
                                        <img class="h-12 w-12 rounded-full object-cover flex-shrink-0" src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" />
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('student.profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200" role="menuitem">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200" role="menuitem">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>
                                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors duration-200" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <svg class="h-5 w-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary transition-all duration-200" aria-label="Open main menu" aria-expanded="false">
                        <svg id="hamburgerIcon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="closeIcon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden transition-all duration-300 ease-in-out">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 bg-white">
                    <a href="{{ route('student.dashboard') }}" class="@if(request()->routeIs('student.dashboard')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Dashboard</a>
                    <a href="{{ route('student.jobs.index') }}" class="@if(request()->routeIs('student.jobs.*')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Job Opportunities</a>
                    <a href="{{ route('student.events.index') }}" class="@if(request()->routeIs('student.events.*')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Events</a>
                    <a href="{{ route('student.news.index') }}" class="@if(request()->routeIs('student.news.*')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">News</a>
                    <a href="{{ route('student.profile.show') }}" class="@if(request()->routeIs('student.profile.*')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Profile</a>
                </div>

                <!-- Mobile User Profile Section - ENHANCED -->
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            @if(auth()->user()->profile_photo)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" />
                            @else
                                <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-semibold leading-none text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                            <div class="text-sm font-medium leading-none text-gray-500 mt-1">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('student.profile.show') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('logout') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-red-700 hover:text-red-900 hover:bg-red-50 transition-colors duration-200" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <script>
        // Mobile menu toggle function
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById("mobileMenu");
            const hamburgerIcon = document.getElementById("hamburgerIcon");
            const closeIcon = document.getElementById("closeIcon");

            mobileMenu.classList.toggle("hidden");
            hamburgerIcon.classList.toggle("hidden");
            closeIcon.classList.toggle("hidden");

            const button = document.querySelector('[onclick="toggleMobileMenu()"]');
            const isExpanded = !mobileMenu.classList.contains("hidden");
            button.setAttribute("aria-expanded", isExpanded);
        }

        // User menu toggle function
        function toggleUserMenu() {
            const menu = document.getElementById("userMenu");
            menu.classList.toggle("hidden");
        }

        // Close user menu when clicking outside
        document.addEventListener("click", function (event) {
            const userMenu = document.getElementById("userMenu");
            const button = event.target.closest('button[onclick="toggleUserMenu()"]');

            if (!button && !userMenu.contains(event.target)) {
                userMenu.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
