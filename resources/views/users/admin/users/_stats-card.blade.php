<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
                @if($icon === 'users')
                    <svg class="w-5 h-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 0 0-4-4h-1M9 20H4v-1a4 4 0 0 1 4-4h1m8-6a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-6 6h4"></path>
                    </svg>
                @elseif($icon === 'document')
                    <svg class="w-5 h-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 016.5 17H20"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path>
                    </svg>
                @elseif($icon === 'building')
                    <svg class="w-5 h-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                @elseif($icon === 'shield')
                    <svg class="w-5 h-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($icon === 'cog')
                    <svg class="w-5 h-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                @endif
            </div>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>
