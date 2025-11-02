<!-- Approve Event Modal -->
<div id="approveEventModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Approve Event</h3>
                <button onclick="closeApproveEventModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="approveEventForm" action="" method="">
                    <input type="hidden" name="event_id" id="approveEventId" />
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Event Title:</strong> <span id="approveEventTitle"></span>
                            </p>
                            <p class="text-sm text-blue-800 mt-2">
                                <strong>Staff Member:</strong> <span id="approveEventStaff"></span>
                            </p>
                            <p class="text-sm text-blue-800 mt-2">
                                <strong>Date & Time:</strong> <span id="approveEventDateTime"></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Make This a Featured Event? (Optional)
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="isFeatured" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="isFeatured" class="ml-2 text-sm text-gray-600">
                                    Display this event prominently on the platform
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Admin Notes (Optional)
                            </label>
                            <textarea
                                name="admin_notes"
                                rows="3"
                                placeholder="Add any internal notes about this approval..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            ></textarea>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800">
                                ✓ This event will be published immediately and visible to all users for registration
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeApproveEventModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        >
                            Approve & Publish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
