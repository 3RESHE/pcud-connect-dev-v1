<!-- News Section -->
<div class="space-y-1">
    <button
        onclick="toggleSubmenu('newsSubmenu')"
        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.approvals.news')
            bg-blue-100 text-primary font-semibold
        @endif"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2v-6a2 2 0 012-2h.344c.603 0 1.191.25 1.591.662.4.412.609 1.01.609 1.643v3.695"></path>
            </svg>
            News & Articles
        </div>
        <svg class="w-4 h-4 transition-transform newsArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    <div id="newsSubmenu" class="pl-4 space-y-1 hidden">
        <a
            href="{{ route('admin.approvals.news') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.approvals.news') text-primary font-semibold @endif"
        >
            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
            Pending Articles
        </a>
    </div>
</div>
