<!-- Users Section -->
<div class="space-y-1">
    <!-- Users Main Link -->
    <button
        onclick="toggleSubmenu('usersSubmenu')"
        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.users.index' ||
            Route::currentRouteName() === 'admin.users.create' ||
            Route::currentRouteName() === 'admin.users.edit' ||
            Route::currentRouteName() === 'admin.users.bulk-import-form')
            bg-blue-100 text-primary font-semibold
        @endif"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4.354L9.172 1.5M12 4.354l2.828-2.854m0 8.048L9.172 15.5M12 12.354l2.828 2.854"></path>
            </svg>
            Users
        </div>
        <svg class="w-4 h-4 transition-transform usersArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    <!-- Users Submenu -->
    <div id="usersSubmenu" class="pl-4 space-y-1 hidden">
        <a
            href="{{ route('admin.users.index') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.users.index') text-primary font-semibold @endif"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            View All Users
        </a>
        <a
            href="{{ route('admin.users.create') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.users.create') text-primary font-semibold @endif"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
            </svg>
            Create User
        </a>
        <a
            href="{{ route('admin.users.bulk-import-form') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.users.bulk-import-form') text-primary font-semibold @endif"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
            </svg>
            Bulk Import
        </a>
    </div>
</div>
