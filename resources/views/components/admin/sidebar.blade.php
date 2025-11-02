<aside
    id="sidebar"
    class="bg-white w-64 min-h-screen shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static fixed z-50"
>
    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="flex items-center justify-between h-16 px-6 border-b">
            <h1 class="text-xl font-bold text-primary">PCUD Connect</h1>
            <!-- Close button for mobile -->
            <button
                id="closeSidebarBtn"
                class="lg:hidden text-gray-500 hover:text-gray-700"
                onclick="toggleSidebar()"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard Link -->
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg @if(Route::currentRouteName() === 'admin.dashboard') bg-blue-100 text-primary font-semibold @endif"
            >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                </svg>
                Dashboard
            </a>

            <!-- Sidebar Sections using @includes -->
            @include('components.admin.sidebar-links.users')
            @include('components.admin.sidebar-links.jobs')
            @include('components.admin.sidebar-links.events')
            @include('components.admin.sidebar-links.news')
            @include('components.admin.sidebar-links.partnerships')
            @include('components.admin.sidebar-links.analytics')
            @include('components.admin.sidebar-links.settings')
        </nav>

        <!-- Footer / Admin Info -->
        <div class="p-4 border-t">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">
                    {{ substr(auth()->user()->first_name, 0, 1) }}
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-gray-700">{{ auth()->user()->first_name }}</p>
                    <p class="text-gray-500 text-xs">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar Overlay for Mobile -->
<div
    id="sidebarOverlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
    onclick="toggleSidebar()"
></div>
