@extends('layouts.staff')

@section('title', 'Dashboard - PCU-DASMA Connect')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-8 text-white">
            <h1 class="text-4xl font-bold mb-2">Welcome back, {{ $user->first_name }}! 👋</h1>
            <p class="text-blue-100">Manage your news articles and track their performance</p>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Articles</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $total_articles }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Published</p>
                    <p class="text-3xl font-bold text-green-600">{{ $published_count }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pending_count }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Draft -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Drafts</p>
                    <p class="text-3xl font-bold text-gray-600">{{ $draft_count }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $approved_count }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.707 6.707a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414l3-3a1 1 0 011.414 0zm9.586 0a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414l3-3a1 1 0 011.414 0zm-9.586 9.586a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414l3-3a1 1 0 011.414 0zm9.586 0a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414l3-3a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rejected</p>
                    <p class="text-3xl font-bold text-red-600">{{ $rejected_count }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Featured Articles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-3">Featured Articles</h3>
            <div class="flex items-baseline">
                <p class="text-3xl font-bold text-purple-600">{{ $featured_articles }}</p>
                <span class="ml-2 text-sm text-gray-500">articles</span>
            </div>
            <p class="text-xs text-gray-500 mt-2">⭐ Highlighted content</p>
        </div>

        <!-- Quick Action -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm p-6 border border-blue-200">
            <h3 class="text-sm font-medium text-blue-900 mb-3">Quick Action</h3>
            <a href="{{ route('staff.news.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Article
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Articles Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Pending Review</h2>
                <a href="{{ route('staff.news.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
            </div>

            @forelse($pending_articles as $article)
                <div class="border-l-4 border-yellow-500 pl-4 pb-4 mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $article->summary ?? $article->getExcerpt(100) }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-gray-500">
                                    {{ $article->created_at->format('M d, Y') }}
                                </span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                            </div>
                        </div>
                        <a href="{{ route('staff.news.show', $article->id) }}"
                            class="ml-4 px-3 py-1 text-sm text-blue-600 hover:text-blue-700 font-medium">View</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No pending articles</p>
            @endforelse
        </div>

        <!-- Recently Published Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Recently Published</h2>
            </div>

            @forelse($recently_published as $article)
                <div class="border-l-4 border-green-500 pl-4 pb-4 mb-4 last:mb-0">
                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $article->title }}</h3>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Published</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No published articles yet</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Recent Articles</h2>
            <a href="{{ route('staff.news.index') }}"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3">Title</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3">Status</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3">Created</th>
                        <th class="text-left text-sm font-semibold text-gray-900 pb-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_articles as $article)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 text-sm text-gray-900 font-medium truncate max-w-xs">{{ $article->title }}</td>
                            <td class="py-3 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if ($article->status === 'published') bg-blue-100 text-blue-800
                                    @elseif($article->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($article->status === 'approved') bg-green-100 text-green-800
                                    @elseif($article->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $article->getStatusDisplay() }}
                                </span>
                            </td>
                            <td class="py-3 text-sm text-gray-600">{{ $article->created_at->format('M d, Y') }}</td>
                            <td class="py-3 text-sm">
                                <a href="{{ route('staff.news.show', $article->id) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">No articles yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
