<!-- Reject News Modal -->
<div id="rejectNewsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Reject Article</h3>
                <button onclick="closeRejectNewsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="rejectNewsForm" action="" method="">
                    <input type="hidden" name="article_id" id="rejectArticleId" />
                    <div class="space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-800">
                                <strong>Article:</strong> <span id="rejectArticleTitle"></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reasons <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="incomplete" id="incomplete" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="incomplete" class="ml-2 text-sm text-gray-600">Incomplete content</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="inaccurate" id="inaccurate" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="inaccurate" class="ml-2 text-sm text-gray-600">Inaccurate or unverified information</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="inappropriate" id="inappropriate" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="inappropriate" class="ml-2 text-sm text-gray-600">Inappropriate content</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="plagiarism" id="plagiarism" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="plagiarism" class="ml-2 text-sm text-gray-600">Plagiarism concerns</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="other" id="other" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="other" class="ml-2 text-sm text-gray-600">Other</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Detailed Feedback <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="rejection_reason"
                                required
                                rows="4"
                                placeholder="Please provide detailed feedback for the author..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">This will be sent to the author with notification</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeRejectNewsModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            Reject Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
