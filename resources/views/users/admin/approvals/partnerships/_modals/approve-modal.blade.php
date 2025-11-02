<!-- Approve Partnership Modal -->
<div id="approvePartnershipModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Approve Partnership</h3>
                <button onclick="closeApprovePartnershipModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="approvePartnershipForm" action="" method="">
                    <input type="hidden" name="partnership_id" id="approvePartnershipId" />
                    <div class="space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800">
                                <strong>Partnership:</strong> <span id="approvePartnershipTitle"></span>
                            </p>
                            <p class="text-sm text-green-800 mt-2">
                                <strong>Category:</strong> <span id="approvePartnershipCategory"></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Expected Duration (months) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="duration"
                                required
                                min="1"
                                max="60"
                                placeholder="e.g., 12"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Approval Notes (Optional)
                            </label>
                            <textarea
                                name="approval_notes"
                                rows="3"
                                placeholder="Add any internal notes or conditions for this approval..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            ></textarea>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                ✓ This partnership will be marked as approved and the partner will be notified
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeApprovePartnershipModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        >
                            Approve Partnership
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
