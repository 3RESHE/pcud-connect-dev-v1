@extends('layouts.staff')

@section('title', 'Edit News Article - PCU-DASMA Connect')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div class="flex items-center">
        <a href="{{ route('staff.news.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit News Article</h1>
            <p class="text-gray-600">Update and resubmit your news article</p>
        </div>
    </div>
    <div class="flex items-center space-x-3">
        <button onclick="previewArticle()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
            Preview
        </button>
    </div>
</div>

<!-- Article Form -->
<form action="{{ route('staff.news.update', $article->id) }}" method="POST" enctype="multipart/form-data" id="newsForm" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- Basic Information -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Article Title <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    required
                    value="{{ old('title', $article->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-lg @error('title') border-red-500 @enderror"
                    placeholder="Enter your article title..."
                />
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Keep it concise and engaging (recommended: under 100 characters)</p>
            </div>

            <!-- Category, Author, Event Date -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="category"
                        name="category"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary @error('category') border-red-500 @enderror"
                    >
                        <option value="">Select Category</option>
                        <option value="university_updates" {{ old('category', $article->category) == 'university_updates' ? 'selected' : '' }}>University Update</option>
                        <option value="alumni_success" {{ old('category', $article->category) == 'alumni_success' ? 'selected' : '' }}>Alumni Success</option>
                        <option value="campus_events" {{ old('category', $article->category) == 'campus_events' ? 'selected' : '' }}>Campus Events</option>
                        <option value="partnership_highlights" {{ old('category', $article->category) == 'partnership_highlights' ? 'selected' : '' }}>Partnership Success</option>
                        <option value="general" {{ old('category', $article->category) == 'general' ? 'selected' : '' }}>General News</option>
                    </select>
                    @error('category')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                    <input
                        type="text"
                        id="author"
                        name="author"
                        value="{{ $article->creator->first_name }} {{ $article->creator->last_name }}"
                        readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed"
                    />
                </div>

                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Event Date <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="event_date"
                        name="event_date"
                        required
                        value="{{ old('event_date', $article->event_date ? $article->event_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary @error('event_date') border-red-500 @enderror"
                    />
                    @error('event_date')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Date the event took place or will take place</p>
                </div>
            </div>

            <!-- Partnership With -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="partnership_with" class="block text-sm font-medium text-gray-700 mb-1">Partnership With</label>
                    <input
                        type="text"
                        id="partnership_with"
                        name="partnership_with"
                        value="{{ old('partnership_with', $article->partnership_with) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="e.g., Clean Seas Alliance, Local Barangay"
                    />
                    <p class="text-sm text-gray-500 mt-1">Specify partner organizations or individuals</p>
                </div>

                <div>
                    <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                    <input
                        type="date"
                        id="publish_date"
                        name="publish_date"
                        readonly
                        value="{{ $article->published_at ? $article->published_at->format('Y-m-d') : '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed"
                    />
                    <p class="text-sm text-gray-500 mt-1">Set by admin upon approval</p>
                </div>
            </div>

            <!-- Summary -->
            <div>
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">
                    Article Summary <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="summary"
                    name="summary"
                    rows="3"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary @error('summary') border-red-500 @enderror"
                    placeholder="Brief summary that will appear in the article preview and social media shares..."
                >{{ old('summary', $article->summary) }}</textarea>
                @error('summary')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">This summary will be used for previews and social media sharing (recommended: 150-200 characters)</p>
            </div>

            <!-- Featured Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>

                <!-- Existing Image Preview -->
                @if($article->featured_image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($article->featured_image) }}" alt="Current featured image" class="h-32 w-auto rounded-md border border-gray-300">
                            <input type="hidden" name="keep_existing_image" id="keepExistingImage" value="1">
                        </div>
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                <span>Upload a new file</span>
                                <input id="featured_image" name="featured_image" type="file" accept="image/*" class="sr-only" onchange="handleImageUpload(event)" />
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        @if($article->featured_image)
                            <p class="text-xs text-gray-500">(Leave empty to keep current image)</p>
                        @endif
                    </div>
                </div>
                @error('featured_image')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <div id="imagePreview" class="hidden mt-4">
                    <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                    <img id="previewImg" class="h-48 w-full object-cover rounded-md" />
                    <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-700">Remove new image</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Editor -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Article Content</h2>

        <!-- Editor Toolbar -->
        <div class="border border-gray-300 rounded-t-md p-2 bg-gray-50">
            <div class="flex items-center space-x-1 flex-wrap">
                <button type="button" onclick="formatText('bold')" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-200" title="Bold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 4v12h4.5a3.5 3.5 0 001.409-6.706A3 3 0 0010.5 4H6zM8 6h2.5a1 1 0 110 2H8V6zm0 4h3.5a1.5 1.5 0 110 3H8v-3z"></path>
                    </svg>
                </button>
                <button type="button" onclick="formatText('italic')" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-200" title="Italic">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.5 4a.5.5 0 01.5.5V6h2a.5.5 0 010 1h-.5l-1.5 6H11a.5.5 0 010 1H9a.5.5 0 01-.5-.5V12H6a.5.5 0 010-1h.5l1.5-6H6a.5.5 0 010-1h2V4.5a.5.5 0 01.5-.5z"></path>
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300"></div>
                <button type="button" onclick="insertHeading()" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-200" title="Heading">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h2a1 1 0 011 1v4h6V4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1v-4H7v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    </svg>
                </button>
                <button type="button" onclick="insertList()" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-200" title="Bullet List">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 000 2h14a1 1 0 100-2H3zM3 8a1 1 0 000 2h14a1 1 0 100-2H3zM3 12a1 1 0 100 2h14a1 1 0 100-2H3z"></path>
                    </svg>
                </button>
                <button type="button" onclick="insertQuote()" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-200" title="Quote">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 8a3 3 0 100-6 3 3 0 000 6zm2-3a2 2 0 11-4 0 2 2 0 014 0zm4 8a5 5 0 11-10 0h10z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content Textarea -->
        <textarea
            id="content"
            name="content"
            rows="12"
            required
            class="w-full px-3 py-3 border-l border-r border-b border-gray-300 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary resize-none @error('content') border-red-500 @enderror"
            placeholder="Write your full article content here..."
        >{{ old('content', $article->content) }}</textarea>
        @error('content')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror

        <div class="mt-2 flex justify-between text-sm text-gray-500">
            <span>Use the toolbar above to format your content</span>
            <span id="wordCount">0 words</span>
        </div>
    </div>

    <!-- Additional Options -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Options</h2>

        <div class="space-y-4">
            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                    Tags <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="tags"
                    name="tags"
                    required
                    value="{{ old('tags', $article->getTagsString()) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary @error('tags') border-red-500 @enderror"
                    placeholder="e.g., university, event, community, partnership (separate with commas)"
                />
                @error('tags')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Tags help categorize and make your article discoverable</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('staff.news.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <div class="space-x-3">
            <button type="submit" name="action" value="draft" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Save as Draft
            </button>
            <button type="submit" name="action" value="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                @if($article->status === 'rejected')
                    Update & Resubmit
                @else
                    Update Article
                @endif
            </button>
        </div>
    </div>
</form>

<!-- Preview Modal (Same as Create) -->
<div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 max-h-[80vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Article Preview</h2>
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="previewContent" class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Title</h3>
                    <p id="previewTitle" class="text-gray-700"></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Category</h3>
                    <p id="previewCategory" class="text-gray-700"></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Event Date</h3>
                    <p id="previewEventDate" class="text-gray-700"></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Partnership With</h3>
                    <p id="previewPartnership" class="text-gray-700"></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
                    <p id="previewSummary" class="text-gray-700"></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Content</h3>
                    <div id="previewContentBody" class="text-gray-700 prose max-w-none"></div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    <p id="previewTags" class="text-gray-700"></p>
                </div>
                <div id="previewImageContainer" class="hidden">
                    <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>
                    <img id="previewModalImg" class="mt-2 h-48 w-full object-cover rounded-md" />
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closePreviewModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/staff/news-form.js') }}"></script>
<script>
    // Pre-populate existing image in preview modal
    @if($article->featured_image)
        document.addEventListener('DOMContentLoaded', function() {
            const previewModalImg = document.getElementById('previewModalImg');
            previewModalImg.src = "{{ Storage::url($article->featured_image) }}";
        });
    @endif
</script>
@endsection
