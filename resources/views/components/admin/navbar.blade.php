<!-- Top Header -->
<header class="bg-white shadow-sm border-b lg:hidden">
    <div class="flex items-center justify-between px-4 py-3">
        <button
            id="openSidebar"
            class="p-2 rounded-md text-gray-400 hover:text-gray-600"
            onclick="showSidebar()"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-xl font-bold text-primary">@yield('page-title', 'Admin Dashboard')</h1>
        <div class="w-10"></div>
        <!-- Spacer -->
    </div>
</header>
