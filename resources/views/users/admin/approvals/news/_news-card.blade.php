<div class="news-card bg-white rounded-lg shadow-sm p-6 border-l-4 @if($article['status'] === 'pending') border-yellow-500 @elseif($article['status'] === 'published') border-blue-500 @elseif($article['status'] === 'rejected') border-red-500 @else border-green-500 @endif">
    <div class="flex justify-between items-start mb-4">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                <span class="px-2 py-1 rounded-full text-xs mr-3 font-medium @if($article['status'] === 'pending') bg-yellow-100 text-yellow-800 @elseif($article['status'] === 'published') bg-blue-100 text-blue-800 @elseif($article['status'] === 'rejected') bg-red-100 text-red-800 @else bg-green-100 text-green-800 @endif">
                    {{ ucfirst($article['status']) }}
                </span>
                @if($article['is_featured'] ?? false)
                    <span class="px-2 py-1 rounded-full text-xs mr-3 font-medium bg-purple-100 text-purple-800">Featured</span>
                @endif
                <span class="text-sm text-gray-500">{{ $article['submitted_date'] }}</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $article['title'] }}</h3>
            <div class="flex items-center text-sm text-gray-600 mb-4 space-x-6">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <strong>By:</strong> {{ $article['author'] }}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4v16a2 2 0 002 2h6a2 2 0 002-2V4M11 6v2M11 10v2"></path>
                    </svg>
                    <strong>Category:</strong> {{ $article['category'] }}
                </div>
            </div>
            <p class="text-gray-600 mb-4 line-clamp-2">{{ $article['content'] }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $article['tags']) as $tag)
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ trim($tag) }}</span>
                @endforeach
            </div>
        </div>
        <div class="ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
            @if($article['status'] === 'pending')
                <button onclick="openApproveNewsModal({{ $article['id'] }}, '{{ $article['title'] }}', '{{ $article['author'] }}', '{{ $article['category'] }}')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 text-center">
                    Approve & Publish
                </button>
                <button onclick="openRejectNewsModal({{ $article['id'] }}, '{{ $article['title'] }}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                    Reject
                </button>
            @elseif($article['status'] === 'published')
                <button onclick="viewArticle({{ $article['id'] }})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">
                    View Article
                </button>
                <button onclick="featureArticle({{ $article['id'] }}, !{{ $article['is_featured'] ?? false ? 'true' : 'false' }})" class="px-4 py-2 @if($article['is_featured'] ?? false) bg-gray-600 @else bg-indigo-600 @endif text-white text-sm rounded-md @if($article['is_featured'] ?? false) hover:bg-gray-700 @else hover:bg-indigo-700 @endif">
                    @if($article['is_featured'] ?? false)
                        Unfeature
                    @else
                        Feature
                    @endif
                </button>
                <button onclick="unpublishArticle({{ $article['id'] }})" class="px-4 py-2 border border-red-300 text-red-700 text-sm rounded-md hover:bg-red-50">
                    Unpublish
                </button>
            @else
                <button onclick="viewArticle({{ $article['id'] }})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">
                    View Article
                </button>
            @endif
        </div>
    </div>
    @if($article['status'] === 'rejected' && ($article['rejection_reason'] ?? null))
        <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
            <h4 class="font-medium text-red-800 mb-2">Rejection Reasons:</h4>
            <p class="text-sm text-red-700">{{ $article['rejection_reason'] }}</p>
        </div>
    @endif
</div>
