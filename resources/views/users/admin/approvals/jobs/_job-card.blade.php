<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border-l-4 @if($job['status'] === 'pending') border-yellow-400 @elseif($job['status'] === 'approved') border-green-400 @else border-red-400 @endif">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $job['title'] }}</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($job['status'] === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($job['status'] === 'approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($job['status']) }}
                    </span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($job['job_type']) }}
                    </span>
                </div>
                <p class="text-gray-600"><strong>{{ $job['company'] }}</strong> • {{ $job['location'] }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Submitted {{ $job['submitted_date'] }}</p>
            </div>
        </div>

        <!-- Salary and Details -->
        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
            <div>
                <p class="text-gray-600">Salary Range</p>
                <p class="font-semibold text-gray-900">₱{{ number_format($job['salary_min']) }} - ₱{{ number_format($job['salary_max']) }}</p>
            </div>
            <div>
                <p class="text-gray-600">Employment Type</p>
                <p class="font-semibold text-gray-900">{{ $job['job_type'] }}</p>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <p class="text-gray-700 text-sm line-clamp-2">{{ $job['description'] }}</p>
        </div>

        <!-- Requirements -->
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-900 mb-2">Key Requirements</p>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $job['requirements']) as $req)
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ trim($req) }}</span>
                @endforeach
            </div>
        </div>

        <!-- Skills -->
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-900 mb-2">Required Skills</p>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $job['skills']) as $skill)
                    <span class="px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">{{ trim($skill) }}</span>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 pt-4 border-t">
            @if($job['status'] === 'pending')
                <button onclick="previewJob({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </button>
                <button onclick="openApproveModal({{ $job['id'] }}, '{{ $job['title'] }}', '{{ $job['company'] }}')" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve & Publish
                </button>
                <button onclick="openRejectModal({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject
                </button>
            @elseif($job['status'] === 'approved')
                <button onclick="viewPublished({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Published
                </button>
                <button onclick="featureJob({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Feature Job
                </button>
                <button onclick="unpublishJob({{ $job['id'] }})" class="px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Unpublish
                </button>
            @else
                <button onclick="previewJob({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </button>
                <button onclick="reactivateJob({{ $job['id'] }})" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Reactivate
                </button>
            @endif
        </div>
    </div>
</div>
