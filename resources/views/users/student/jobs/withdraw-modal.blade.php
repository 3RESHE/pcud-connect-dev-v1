    <!-- Withdraw Confirmation Modal -->
    <div id="withdrawConfirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="withdraw-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeWithdrawModal()"></div>

            <!-- Modal -->
            <div
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                <!-- Icon -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mt-6 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>

                <!-- Modal Body -->
                <div class="px-4 sm:px-6 py-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2" id="withdraw-modal-title">
                        Withdraw Application?
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">
                        This action cannot be undone. Your application will be permanently deleted and you can apply again
                        if needed.
                    </p>

                    <!-- Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        <button type="button" onclick="closeWithdrawModal()"
                            class="w-full sm:flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="submitWithdraw()"
                            class="w-full sm:flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm transition-colors">
                            Yes, Withdraw
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
