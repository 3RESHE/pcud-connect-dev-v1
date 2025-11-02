<!-- Partnerships Section -->
<div class="space-y-1">
    <button onclick="toggleSubmenu('partnershipsSubmenu')"
        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if (Route::currentRouteName() === 'admin.approvals.partnerships') bg-blue-100 text-primary font-semibold @endif">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 20H9m8-4v2m0-11V9m0 0a1 1 0 10-2 0 1 1 0 002 0zm0 0a1 1 0 10-2 0 1 1 0 002 0z">
                </path>
            </svg>
            Partnerships
        </div>
        <svg class="w-4 h-4 transition-transform partnershipsArrow" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    <div id="partnershipsSubmenu" class="pl-4 space-y-1 hidden">
        <a href="{{ route('admin.approvals.partnerships') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if (Route::currentRouteName() === 'admin.approvals.partnerships') text-primary font-semibold @endif">
            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
            Pending Proposals
        </a>
    </div>
</div>
