<!-- Events Section -->
<div class="space-y-1">
    <button
        onclick="toggleSubmenu('eventsSubmenu')"
        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.approvals.events')
            bg-blue-100 text-primary font-semibold
        @endif"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Events
        </div>
        <svg class="w-4 h-4 transition-transform eventsArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    <div id="eventsSubmenu" class="pl-4 space-y-1 hidden">
        <a
            href="{{ route('admin.approvals.events') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.approvals.events') text-primary font-semibold @endif"
        >
            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
            Pending Events
        </a>
    </div>
</div>
