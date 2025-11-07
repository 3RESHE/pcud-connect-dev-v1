<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PCU-DASMA Connect - Alumni')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        secondary: '#3B82F6',
                        accent: '#F59E0B',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Desktop Navigation -->
                <div class="flex items-center">
                    <a href="{{ route('alumni.dashboard') }}" class="text-xl font-bold text-primary">
                        PCU-DASMA Connect
                    </a>
                    <!-- Desktop Navigation Menu -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('alumni.dashboard') }}"
                           class="@if(request()->routeIs('alumni.dashboard')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('alumni.jobs.index') }}"
                           class="@if(request()->routeIs('alumni.jobs.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Job Opportunities
                        </a>
                        <a href="{{ route('alumni.events.index') }}"
                           class="@if(request()->routeIs('alumni.events.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Events
                        </a>
                        <a href="{{ route('alumni.news.index') }}"
                           class="@if(request()->routeIs('alumni.news.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            News
                        </a>
                        <a href="{{ route('alumni.profile.index') }}"
                           class="@if(request()->routeIs('alumni.profile.*')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200">
                            Profile
                        </a>
                    </div>
                </div>

                <!-- Desktop User Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200" aria-label="Open user menu">
                            <img class="h-8 w-8 rounded-full" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Profile" />
                        </button>
                        <div id="userMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('alumni.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">👤 Profile</a>
                                <a href="{{ route('alumni.profile.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">⚙️ Settings</a>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">🚪 Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100">
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
            <div id="mobileMenu" class="md:hidden hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                    <a href="{{ route('alumni.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">Dashboard</a>
                    <a href="{{ route('alumni.jobs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">Job Opportunities</a>
                    <a href="{{ route('alumni.events.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">Events</a>
                    <a href="{{ route('alumni.news.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">News</a>
                    <a href="{{ route('alumni.profile.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">Profile</a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Profile" />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('alumni.profile.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">👤 Profile</a>
                        <a href="{{ route('alumni.profile.settings') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">⚙️ Settings</a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <p class="text-center text-gray-500 text-sm">© 2025 PCU-DASMA Connect. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById("mobileMenu");
            const hamburgerIcon = document.getElementById("hamburgerIcon");
            const closeIcon = document.getElementById("closeIcon");
            mobileMenu.classList.toggle("hidden");
            hamburgerIcon.classList.toggle("hidden");
            closeIcon.classList.toggle("hidden");
        }

        function toggleUserMenu() {
            const menu = document.getElementById("userMenu");
            menu.classList.toggle("hidden");
        }

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
