<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y h:i A') }}</div>
                    <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $log->user?->first_name ?? 'Unknown User' }}</div>
                    <div class="text-xs text-gray-500">{{ $log->ip_address }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $log->getActionDisplay() }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <span class="font-medium">{{ $log->getSubjectTypeDisplay() }}</span>
                    </div>
                    <div class="text-sm text-gray-500">{{ $log->getSubject()?->title ?? 'Record' }}</div>
                    @if($log->getChangedFieldsCount() > 0)
                        <div class="text-xs text-gray-400 mt-1">Changes: {{ $log->getChangedFieldsCount() }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $log->ip_address }}
                    <div class="text-xs text-gray-400">{{ $log->getBrowserInfo() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <button onclick="viewLogDetails({{ $log->id }})" class="text-primary hover:text-blue-700 text-xs">
                        View Details
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
