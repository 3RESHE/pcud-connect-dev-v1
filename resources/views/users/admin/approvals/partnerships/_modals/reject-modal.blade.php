<!-- Reject Partnership Modal -->
<div id="rejectPartnershipModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Reject Partnership</h3>
                <button onclick="closeRejectPartnershipModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="rejectPartnershipForm" action="" method="">
                    <input type="hidden" name="partnership_id" id="rejectPartnershipId" />
                    <div class="space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-800">
                                <strong>Partnership:</strong> <span id="rejectPartnershipTitle"></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reasons <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="budget" id="budget" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="budget" class="ml-2 text-sm text-gray-600">Insufficient budget allocation</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="overlap" id="overlap" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="overlap" class="ml-2 text-sm text-gray-600">Overlap with existing programs</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="resources" id="resources" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="resources" class="ml-2 text-sm text-gray-600">Insufficient resources/capacity</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="alignment" id="alignment" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="alignment" class="ml-2 text-sm text-gray-600">Not aligned with mission</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="reasons" value="other" id="otherReason" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="otherReason" class="ml-2 text-sm text-gray-600">Other</label>
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
                                placeholder="Please provide detailed feedback and suggestions for the partner..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">This feedback will be communicated to the partner</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeRejectPartnershipModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            Reject Partnership
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
