    <!-- Application Modal -->
    <div id="applicationModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeApplicationModal()"></div>

            <!-- Modal -->
            <div
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 break-words line-clamp-2" id="modal-title">
                        Apply for {{ $job->title }}
                    </h3>
                    <button onclick="closeApplicationModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="applicationForm" action="{{ route('alumni.jobs.apply', $job->id) }}" method="POST"
                    enctype="multipart/form-data"
                    class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-96 sm:max-h-none overflow-y-auto">
                    @csrf

                    <!-- Cover Letter -->
                    <div>
                        <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Letter
                            <span class="text-red-600">*</span>
                        </label>
                        <textarea id="cover_letter" name="cover_letter" rows="5" required
                            class="w-full px-3 py-2 border @error('cover_letter') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none"
                            placeholder="Tell us why you're interested and why you'd be a great fit..." value="{{ old('cover_letter') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                        @error('cover_letter')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18.101 12.93a1 1 0 00-1.414-1.414L9 18.586 4.707 14.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l9-9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Resume Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Resume / CV
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="space-y-2">
                            @if (auth()->user()->studentProfile && auth()->user()->studentProfile->resume_path)
                                <div
                                    class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="radio" name="resume_option" value="existing" id="existingResume"
                                        checked class="h-4 w-4 text-primary flex-shrink-0">
                                    <label for="existingResume" class="ml-3 flex-1 cursor-pointer min-w-0">
                                        <p class="text-sm font-medium text-gray-900">Use Current Resume</p>
                                        <p class="text-xs text-gray-600 truncate">
                                            {{ basename(auth()->user()->studentProfile->resume_path) }}</p>
                                    </label>
                                </div>
                            @endif
                            <div
                                class="flex items-start p-3 bg-gray-50 border @error('resume_file') border-red-500 @else border-gray-200 @enderror rounded-lg">
                                <input type="radio" name="resume_option" value="upload" id="uploadResume"
                                    @if (!auth()->user()->studentProfile || !auth()->user()->studentProfile->resume_path) checked @endif
                                    class="mt-1 h-4 w-4 text-primary flex-shrink-0">
                                <label for="uploadResume" class="ml-3 flex-1 cursor-pointer min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Upload New Resume</p>
                                    <p class="text-xs text-gray-600">Max 5MB (PDF, DOC, DOCX)</p>
                                    <input type="file" name="resume_file" id="resumeFile" accept=".pdf,.doc,.docx"
                                        class="mt-2 w-full text-xs"
                                        onchange="document.getElementById('uploadResume').checked=true;">
                                </label>
                            </div>
                        </div>
                        @error('resume_file')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirmation -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="confirmApplication" id="confirmApplication" required
                                class="mt-1 h-4 w-4 text-primary flex-shrink-0">
                            <span class="ml-2 text-xs sm:text-sm text-gray-700">I confirm that the information is accurate
                                and understand that false information may disqualify my application.</span>
                        </label>
                        @error('confirmApplication')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeApplicationModal()"
                            class="w-full sm:flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
