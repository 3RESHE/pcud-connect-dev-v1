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
        @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y h:i A') }}</div>
                    <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $log->user?->first_name ?? 'Unknown' }} {{ $log->user?->last_name ?? 'User' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $log->ip_address }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $actionColors = [
                            'created' => 'green',
                            'updated' => 'blue',
                            'deleted' => 'red',
                            'approved' => 'green',
                            'rejected' => 'red',
                            'published' => 'green',
                            'archived' => 'yellow',
                            'restored' => 'blue',
                            'completed' => 'green',
                            'checked_in' => 'green',
                        ];
                        $color = $actionColors[$log->action] ?? 'gray';
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                        {{ $log->getActionDisplay() }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <span class="font-medium">{{ $log->getSubjectTypeDisplay() }}</span>
                    </div>
                    <div class="text-sm text-gray-500">{{ $log->getSubject()?->title ?? ' ' }}</div>
                    @if($log->getChangedFieldsCount() > 0)
                        <div class="text-xs text-gray-400 mt-1">Changes: {{ $log->getChangedFieldsCount() }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $log->ip_address }}
                    <div class="text-xs text-gray-400">{{ $log->getBrowserInfo() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <button onclick="viewLogDetails({{ $log->id }})" class="text-primary hover:text-blue-700 text-xs font-medium">
                        View Details
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p>No activity logs found</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
