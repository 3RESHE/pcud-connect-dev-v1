<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Desktop Navigation -->
            <div class="flex items-center">
                <a href="{{ route('staff.dashboard') }}" class="text-xl font-bold text-primary">
                    PCU-DASMA Connect
                </a>
                <!-- Desktop Navigation Menu -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a
                        href="{{ route('staff.dashboard') }}"
                        class="@if(Route::currentRouteName() === 'staff.dashboard') text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary border-b-2 border-transparent @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200"
                    >
                        Dashboard
                    </a>
                    <a
                        href="{{ route('staff.events.index') }}"
                        class="@if(str_contains(Route::currentRouteName(), 'staff.events')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary border-b-2 border-transparent @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200"
                    >
                        Events
                    </a>
                    <a
                        href="{{ route('news.index') }}"
                        class="@if(str_contains(Route::currentRouteName(), 'staff.news')) text-primary border-b-2 border-primary @else text-gray-700 hover:text-primary border-b-2 border-transparent @endif px-1 pt-1 pb-4 text-sm font-medium transition-colors duration-200"
                    >
                        News
                    </a>
                </div>
            </div>

            <!-- Desktop User Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <div class="relative">
                    <button
                        onclick="toggleUserMenu()"
                        class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-200"
                        aria-label="Open user menu"
                        aria-haspopup="true"
                    >
                        <img
                            class="h-8 w-8 rounded-full"
                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                            alt="Profile"
                        />
                        <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        id="userMenu"
                        class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 transform transition-all duration-200"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <div class="py-1">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                <p class="text-xs text-gray-500">Staff Member</p>
                            </div>
                            <a
                                href="{{ route('staff.profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                role="menuitem"
                            >
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Staff Profile
                            </a>
                            <a
                                href=""
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                role="menuitem"
                            >
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                            <div class="border-t"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button
                                    type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                                    role="menuitem"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button
                    onclick="toggleMobileMenu()"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary transition-all duration-200"
                    aria-label="Open main menu"
                    aria-expanded="false"
                >
                    <svg
                        id="hamburgerIcon"
                        class="block h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        id="closeIcon"
                        class="hidden h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            id="mobileMenu"
            class="md:hidden hidden transition-all duration-300 ease-in-out"
        >
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 bg-white">
                <a
                    href="{{ route('staff.dashboard') }}"
                    class="@if(Route::currentRouteName() === 'staff.dashboard') text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                >
                    Dashboard
                </a>
                <a
                    href="{{ route('staff.events.index') }}"
                    class="@if(str_contains(Route::currentRouteName(), 'staff.events')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                >
                    Events
                </a>
                <a
                    href="{{ route('news.index') }}"
                    class="@if(str_contains(Route::currentRouteName(), 'staff.news')) text-primary bg-primary bg-opacity-10 @else text-gray-700 hover:text-primary hover:bg-gray-100 @endif block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                >
                    News
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200 bg-white">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img
                            class="h-10 w-10 rounded-full"
                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23374151' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                            alt="Profile"
                        />
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-gray-900">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </div>
                        <div class="text-sm font-medium leading-none text-gray-500 mt-1">
                            Staff Member
                        </div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a
                        href="{{ route('staff.profile') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200"
                    >
                        Staff Profile
                    </a>
                    <a
                        href=""
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200"
                    >
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button
                            type="submit"
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition-colors duration-200"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
