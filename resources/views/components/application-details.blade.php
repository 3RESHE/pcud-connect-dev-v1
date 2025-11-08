<div class="space-y-6">
    <!-- Applicant Info -->
    <div class="border-b pb-6">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">Applicant Information</h4>
        <div class="space-y-2 text-sm">
            <div>
                <p class="text-gray-600">Full Name</p>
                <p class="text-gray-900 font-medium">{{ $application->alumni->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Email</p>
                <p class="text-gray-900 font-medium break-words">{{ $application->alumni->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Phone</p>
                <p class="text-gray-900 font-medium">{{ $application->alumni->phone ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Cover Letter -->
    @if($application->cover_letter)
        <div class="border-b pb-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Cover Letter</h4>
            <p class="text-sm text-gray-700 whitespace-pre-wrap break-words">{{ $application->cover_letter }}</p>
        </div>
    @endif

    <!-- Application Status -->
    <div class="border-b pb-6">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">Status</h4>
        <span class="px-3 py-1 rounded-full text-sm font-medium
            @if($application->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($application->status === 'reviewed') bg-blue-100 text-blue-800
            @elseif($application->status === 'approved') bg-green-100 text-green-800
            @elseif($application->status === 'rejected') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif">
            {{ ucfirst($application->status) }}
        </span>
    </div>

    <!-- Application Date -->
    <div>
        <h4 class="text-sm font-semibold text-gray-900 mb-3">Applied On</h4>
        <p class="text-sm text-gray-700">{{ $application->created_at->format('F d, Y - g:i A') }}</p>
    </div>

    @if($application->rejection_reason)
        <div class="bg-red-50 border border-red-200 rounded p-3">
            <p class="text-sm text-red-800"><strong>Rejection Reason:</strong> {{ $application->rejection_reason }}</p>
        </div>
    @endif
</div>
