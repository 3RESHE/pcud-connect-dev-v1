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
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(Route::currentRouteName() === 'admin.dashboard') text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 5 4-4 4 4"></path>
                </svg>
                Dashboard
            </a>

            <!-- Users -->
            <a
                href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(in_array(Route::currentRouteName(), ['admin.users.index', 'admin.users.create', 'admin.users.edit', 'admin.users.bulk-import-form'])) text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 0 0-4-4h-1M9 20H4v-1a4 4 0 0 1 4-4h1m8-6a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-6 6h4" />
                </svg>
                Users
            </a>

            <!-- Departments -->
            <a
                href="{{ route('admin.departments.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(in_array(Route::currentRouteName(), ['admin.departments.index', 'admin.departments.create', 'admin.departments.edit'])) text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
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
                    class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium rounded-lg transition @if(Route::currentRouteName() === 'admin.approvals.jobs.index' || Route::currentRouteName() === 'admin.approvals.events.index' || Route::currentRouteName() === 'admin.approvals.news.index') text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif focus:outline-none"
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
                    <!-- Job Post Approvals -->
                    <a
                        href="{{ route('admin.approvals.jobs.index') }}"
                        class="flex items-center px-4 py-2 text-sm rounded-lg transition @if(Route::currentRouteName() === 'admin.approvals.jobs.index') text-white bg-primary/70 @else text-gray-600 hover:bg-gray-100 @endif"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        Job Approvals
                        @php
                            $pending_jobs = \App\Models\JobPosting::where('status', 'pending')->count();
                        @endphp
                        @if($pending_jobs > 0)
                            <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $pending_jobs }}</span>
                        @endif
                    </a>

                    <!-- News Approvals -->
                    <a
                        href="{{ route('admin.approvals.news.index') }}"
                        class="flex items-center px-4 py-2 text-sm rounded-lg transition @if(Route::currentRouteName() === 'admin.approvals.news.index') text-white bg-primary/70 @else text-gray-600 hover:bg-gray-100 @endif"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        News Approvals
                        @php
                            $pending_news = \App\Models\NewsArticle::where('status', 'pending')->count();
                        @endphp
                        @if($pending_news > 0)
                            <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $pending_news }}</span>
                        @endif
                    </a>

                    <!-- Event Approvals -->
                    <a
                        href="{{ route('admin.approvals.events.index') }}"
                        class="flex items-center px-4 py-2 text-sm rounded-lg transition @if(Route::currentRouteName() === 'admin.approvals.events.index') text-white bg-primary/70 @else text-gray-600 hover:bg-gray-100 @endif"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Event Approvals
                        @php
                            $pending_events = \App\Models\Event::where('status', 'pending')->count();
                        @endphp
                        @if($pending_events > 0)
                            <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $pending_events }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Partnerships -->
            <a
                href="{{ route('admin.approvals.partnerships.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(Route::currentRouteName() === 'admin.approvals.partnerships.index') text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Partnerships
                @php
                    $pending_partnerships = \App\Models\Partnership::whereIn('status', ['submitted', 'under_review'])->count();
                @endphp
                @if($pending_partnerships > 0)
                    <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $pending_partnerships }}</span>
                @endif
            </a>

            <!-- Activity Logs -->
            <a
                href="{{ route('admin.activity-logs') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(Route::currentRouteName() === 'admin.activity-logs') text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Activity Logs
            </a>

            <!-- Reports -->
            <a
                href="{{ route('admin.reports') }}"
                class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition @if(Route::currentRouteName() === 'admin.reports') text-white bg-primary @else text-gray-700 hover:bg-gray-100 @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Reports
            </a>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>
        </nav>

        <!-- User Profile Section -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1 min-w-0">
                    <img
                        class="h-8 w-8 rounded-full flex-shrink-0"
                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                        alt="Profile"
                    />
                    <div class="ml-3 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->first_name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="relative ml-2 flex-shrink-0">
                    <button
                        onclick="toggleUserMenu(event)"
                        class="p-1 rounded-md text-gray-400 hover:text-gray-600 focus:outline-none"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H9.5V.5h1v1zm0 17H9.5v-1h1v1zm8-8.5v1h-1v-1h1zm-17 0v1H.5v-1h1zm14.5-5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 11a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-11-11a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 11a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                        </svg>
                    </button>
                    <!-- User Menu Dropdown - FIXED: Added overflow-visible and fixed positioning -->
                    <div
                        id="userMenu"
                        class="hidden absolute bottom-full right-0 mb-2 w-56 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50"
                        style="min-width: 220px;"
                    >
                        <div class="py-1">
                            <!-- Profile Link -->
                            {{-- <a
                                href="{{ route('admin.profile') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Admin Profile
                            </a> --}}

                            <!-- Settings Link -->
                            <a
                                href="{{ url('/profile') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>

                            <!-- Divider -->
                            <div class="border-t border-gray-200"></div>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                                    onclick="return confirm('Are you sure you want to logout?')"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
