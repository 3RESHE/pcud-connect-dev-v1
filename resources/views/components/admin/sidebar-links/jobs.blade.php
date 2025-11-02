<!-- Jobs Section -->
<div class="space-y-1">
    <!-- Jobs Main Link -->
    <button
        onclick="toggleSubmenu('jobsSubmenu')"
        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg
        @if(Route::currentRouteName() === 'admin.approvals.jobs')
            bg-blue-100 text-primary font-semibold
        @endif"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4m0 0L14 6m2-2l2 2M9 20h6m-6 0a9 9 0 010-18 9 9 0 010 18z"></path>
            </svg>
            Job Proposals
        </div>
        <svg class="w-4 h-4 transition-transform jobsArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    <!-- Jobs Submenu -->
    <div id="jobsSubmenu" class="pl-4 space-y-1 hidden">
        <a
            href="{{ route('admin.approvals.jobs') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg
            @if(Route::currentRouteName() === 'admin.approvals.jobs') text-primary font-semibold @endif"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2 4 4 0 00-4 4v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 000 2 2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
            </svg>
            Pending Review
        </a>
        <a
            href="{{ route('admin.approvals.jobs') }}?status=approved"
            class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg"
        >
            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            Approved
        </a>
    </div>
</div>
