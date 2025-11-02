<aside
    id="sidebar"
    class="bg-white w-64 min-h-screen shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 fixed z-50"
>
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-primary">PCU-DASMA Connect</a>
            <button
                id="closeSidebar"
                class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600"
                onclick="hideSidebar()"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm font-medium @if(Route::currentRouteName() === 'admin.dashboard') text-white bg-primary rounded-lg @else text-gray-700 rounded-lg hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 5 4-4 4 4"></path>
                </svg>
                Dashboard
            </a>

            <!-- Users -->
            <a
                href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium @if(Route::currentRouteName() === 'admin.users.index' || Route::currentRouteName() === 'admin.users.create' || Route::currentRouteName() === 'admin.users.edit') text-white bg-primary rounded-lg @else text-gray-700 rounded-lg hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 0 0-4-4h-1M9 20H4v-1a4 4 0 0 1 4-4h1m8-6a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-6 6h4" />
                </svg>
                Users
            </a>

            <!-- Departments -->
            <a
                href="#"
                class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                Departments
            </a>

            <!-- Approvals Dropdown -->
            <div class="space-y-1">
                <button
                    onclick="toggleDropdown('approvals')"
                    class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none"
                >
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approvals
                    </div>
                    <svg
                        id="approvals-arrow"
                        class="w-4 h-4 transform transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="approvals-menu" class="hidden ml-4 space-y-1">
                    <a
                        href="{{ route('admin.approvals.jobs') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        Job Post Approvals
                        <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">{{ $pending_jobs ?? 3 }}</span>
                    </a>
                    <a
                        href="{{ route('admin.approvals.news') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        News Approvals
                        <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">{{ $pending_news ?? 2 }}</span>
                    </a>
                    <a
                        href="{{ route('admin.approvals.events') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Event Approvals
                        <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">{{ $pending_events ?? 1 }}</span>
                    </a>
                </div>
            </div>

            <!-- Partnerships -->
            <a
                href="{{ route('admin.approvals.partnerships') }}"
                class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Partnerships
                <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full">{{ $pending_partnerships ?? 2 }}</span>
            </a>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>
        </nav>

        <!-- User Profile -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <img
                    class="h-8 w-8 rounded-full"
                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                    alt="Profile"
                />
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <div class="relative">
                    <button
                        onclick="toggleUserMenu()"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </button>
                    <div
                        id="userMenu"
                        class="hidden origin-bottom-right absolute bottom-0 right-0 mb-12 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                    >
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
