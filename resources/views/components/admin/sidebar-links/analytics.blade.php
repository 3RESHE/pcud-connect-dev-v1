<!-- Analytics Section -->
<div class="space-y-1">
    <a
        href="{{ route('admin.activity-logs') }}"
        class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.activity-logs') bg-blue-100 text-primary font-semibold @endif"
    >
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Activity Logs
    </a>

    <a
        href="{{ route('admin.reports') }}"
        class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.reports') bg-blue-100 text-primary font-semibold @endif"
    >
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Reports
    </a>
</div>
