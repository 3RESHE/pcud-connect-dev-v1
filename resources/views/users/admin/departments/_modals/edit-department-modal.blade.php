<!-- Edit Department Modal -->
<div id="editDepartmentModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Edit Department</h3>
                <button onclick="closeEditDepartmentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="editDepartmentForm" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="editId" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Department Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="editTitle"
                            required
                            placeholder="e.g., College of Computer Studies"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                        />
                        <span id="editTitleError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Department Code <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="code"
                            id="editCode"
                            required
                            placeholder="e.g., CCS"
                            maxlength="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary uppercase"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Must be unique (e.g., CAS, CBA, COED)
                        </p>
                        <span id="editCodeError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeEditDepartmentModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            id="editSubmitBtn"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
