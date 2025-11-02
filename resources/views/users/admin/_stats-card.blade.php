<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900">{{ $value }}</p>
        </div>
        <div class="p-3 rounded-full bg-{{ $color }}-100">
            @if($icon === 'users')
                <svg class="w-6 h-6 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4.354L9.172 1.5M12 4.354l2.828-2.854m0 8.048L9.172 15.5M12 12.354l2.828 2.854"></path>
                </svg>
            @elseif($icon === 'check-circle')
                <svg class="w-6 h-6 text-{{ $color }}-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            @elseif($icon === 'clock')
                <svg class="w-6 h-6 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @elseif($icon === 'briefcase')
                <svg class="w-6 h-6 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4m0 0L14 6m2-2l2 2M9 20h6m-6 0a9 9 0 010-18 9 9 0 010 18z"></path>
                </svg>
            @endif
        </div>
    </div>
</div>
