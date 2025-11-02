<nav class="bg-white shadow-sm border-b h-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left Side: Menu Toggle + Title -->
            <div class="flex items-center space-x-4">
                <button
                    id="toggleSidebarBtn"
                    class="lg:hidden text-gray-500 hover:text-gray-700"
                    onclick="toggleSidebar()"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            </div>

            <!-- Right Side: User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                </button>

                <!-- User Dropdown Menu -->
                <div class="relative group">
                    <button
                        id="userMenuBtn"
                        class="flex items-center space-x-2 text-gray-700 hover:text-gray-900"
                        onclick="toggleUserMenu()"
                    >
                        <img
                            src="https://via.placeholder.com/32"
                            alt="User Avatar"
                            class="w-8 h-8 rounded-full"
                        />
                        <span class="text-sm font-semibold">{{ auth()->user()->first_name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        id="userMenu"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-50"
                    >
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                            My Profile
                        </a>
                        <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Settings
                        </a>
                        <hr class="my-2" />
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
