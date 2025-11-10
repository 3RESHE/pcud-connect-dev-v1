@extends('layouts.partner')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('partner.job-postings.show', $jobPosting) }}"
                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Back to Job
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Applications</h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base break-words">{{ $jobPosting->title }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards - Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <!-- Total -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-100 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-2a4 4 0 00-8 0v2a2 2 0 002 2h4a2 2 0 002-2z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-yellow-100 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Contacted -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Contacted</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['contacted'] }}</p>
                    </div>
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-purple-100 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                        </path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                    </div>
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-green-100 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Rejected</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                    </div>
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-red-100 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search Applicant</label>
                    <input type="text" id="searchInput" placeholder="Name or email..."
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select id="statusFilter"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="contacted">Contacted</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select id="sortFilter"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition text-sm">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="name">Name (A-Z)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Applications Table - Responsive -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Applicant
                                </th>
                                <th
                                    class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Type
                                </th>
                                <th
                                    class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="hidden lg:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Applied
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    View
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($applications as $application)
                                <tr class="hover:bg-gray-50 transition-colors application-row"
                                    data-status="{{ $application->status }}"
                                    data-name="{{ strtolower($application->applicant->name) }}">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                {{ substr($application->applicant->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $application->applicant->name }}</p>
                                                <p class="text-xs sm:text-sm text-gray-500 truncate">
                                                    {{ $application->applicant->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full
                                        @if ($application->applicant_type === 'student') bg-blue-100 text-blue-800
                                        @else
                                            bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($application->applicant_type) }}
                                        </span>
                                    </td>
                                    <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full status-badge
                                        @if ($application->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($application->status === 'contacted')
                                            bg-purple-100 text-purple-800
                                        @elseif($application->status === 'approved')
                                            bg-green-100 text-green-800
                                        @elseif($application->status === 'rejected')
                                            bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </td>
                                    <td
                                        class="hidden lg:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-600">
                                        {{ $application->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('partner.applications.show', $application) }}"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                            title="View Application">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-white px-4 sm:px-6 py-4 border-t border-gray-200 overflow-x-auto">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="p-8 sm:p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <p class="mt-4 text-base sm:text-lg text-gray-600">No applications yet</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Applicants will appear here once they apply.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keyup', filterApplications);
            document.getElementById('statusFilter').addEventListener('change', filterApplications);
        });

        function filterApplications() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            document.querySelectorAll('.application-row').forEach(row => {
                const name = row.dataset.name;
                const status = row.dataset.status;
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg
        ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
@endsection
