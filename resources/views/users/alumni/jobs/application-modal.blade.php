<!-- Application Modal -->
<div id="applicationModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
            onclick="closeApplicationModal()"></div>

        <!-- Modal -->
        <div
            class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
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
                class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-[70vh] overflow-y-auto">
                @csrf

                <!-- ===== COVER LETTER SECTION ===== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Cover Letter
                        <span class="text-red-600">*</span>
                    </label>

                    <!-- Tab Buttons -->
                    <div class="flex gap-2 mb-4">
                        <button type="button" onclick="switchCoverLetterTab('write')"
                            id="writeTab"
                            class="px-4 py-2 text-sm font-medium rounded-lg border-2 border-primary text-primary bg-blue-50 transition-all">
                            ✍️ Write
                        </button>
                        <button type="button" onclick="switchCoverLetterTab('upload')"
                            id="uploadTab"
                            class="px-4 py-2 text-sm font-medium rounded-lg border-2 border-gray-300 text-gray-600 hover:border-gray-400 transition-all">
                            📄 Upload PDF
                        </button>
                    </div>

                    <!-- Write Cover Letter Option -->
                    <div id="writeSection" class="space-y-2">
                        <input type="radio" name="cover_letter_option" value="write" id="writeCoverLetter"
                            checked class="hidden">
                        <textarea id="cover_letter_text" name="cover_letter_text" rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none"
                            placeholder="Tell us why you're interested and why you'd be a great fit for this role..."
                            value="{{ old('cover_letter_text') }}"></textarea>
                        <p class="text-xs text-gray-500">Minimum 50 characters, maximum 5000 characters</p>
                        @error('cover_letter_text')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Upload Cover Letter Option -->
                    <div id="uploadSection" class="hidden space-y-2">
                        <input type="radio" name="cover_letter_option" value="upload" id="uploadCoverLetterFile"
                            class="hidden">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors cursor-pointer"
                            onclick="document.getElementById('coverLetterFile').click()">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-primary">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PDF only, max 5MB</p>
                        </div>
                        <input type="file" id="coverLetterFile" name="cover_letter_file" accept=".pdf"
                            class="hidden"
                            onchange="document.getElementById('uploadCoverLetterFile').checked=true; updateFileName('coverLetterFile', 'coverLetterName')">
                        <p id="coverLetterName" class="text-xs text-gray-600"></p>
                        @error('cover_letter_file')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- ===== RESUME SELECTION SECTION ===== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Resume / CV
                        <span class="text-red-600">*</span>
                    </label>

                    <div class="space-y-3">
                        <!-- Existing Resume Option -->
                        @if (auth()->user()->alumniProfile && auth()->user()->alumniProfile->resumes && is_array(auth()->user()->alumniProfile->resumes) && count(auth()->user()->alumniProfile->resumes) > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-2">From Your Profile</label>
                                <div class="space-y-2">
                                    @foreach (auth()->user()->alumniProfile->resumes as $index => $resume)
                                        <div
                                            class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                            <input type="radio" name="resume_option" value="existing"
                                                id="existingResume_{{ $index }}"
                                                data-resume-path="{{ $resume }}" checked
                                                class="h-4 w-4 text-primary flex-shrink-0"
                                                onchange="document.getElementById('existingResumeValue').value = this.dataset.resumePath;">
                                            <label for="existingResume_{{ $index }}"
                                                class="ml-3 flex-1 cursor-pointer min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ basename($resume) }}</p>
                                                <p class="text-xs text-gray-600">Saved in your profile</p>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">or</span>
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Resume Option -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">Upload New Resume</label>
                            <div class="flex items-center p-3 bg-blue-50 border-2 border-blue-200 rounded-lg">
                                <input type="radio" name="resume_option" value="upload" id="uploadResume"
                                    @if (!auth()->user()->alumniProfile || !auth()->user()->alumniProfile->resumes || count(auth()->user()->alumniProfile->resumes) === 0) checked @endif
                                    class="h-4 w-4 text-primary flex-shrink-0"
                                    onchange="document.getElementById('resumeFileInput').click();">
                                <label for="uploadResume" class="ml-3 flex-1 cursor-pointer min-w-0">
                                    <p class="text-sm font-medium text-gray-900">Upload New Resume</p>
                                    <p class="text-xs text-gray-600">PDF, DOC, DOCX (max 5MB)</p>
                                </label>
                            </div>
                            <input type="file" id="resumeFileInput" name="resume_file" accept=".pdf,.doc,.docx"
                                class="hidden"
                                onchange="document.getElementById('uploadResume').checked=true; updateFileName('resumeFileInput', 'resumeFileName')">
                            <p id="resumeFileName" class="text-xs text-gray-600 mt-2"></p>
                        </div>
                    </div>

                    <!-- Hidden field for existing resume value -->
                    <input type="hidden" id="existingResumeValue" name="existing_resume"
                        value="@if (auth()->user()->alumniProfile && auth()->user()->alumniProfile->resumes && count(auth()->user()->alumniProfile->resumes) > 0){{ auth()->user()->alumniProfile->resumes[0] }}@endif">

                    @error('resume_file')
                        <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    @error('existing_resume')
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

                <!-- Confirmation -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="confirmApplication" id="confirmApplication" required
                            class="mt-1 h-4 w-4 text-primary flex-shrink-0">
                        <span class="ml-2 text-xs sm:text-sm text-gray-700">I confirm that all information provided is
                            accurate and truthful. I understand that false or misleading information may result in
                            disqualification.</span>
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

<!-- JavaScript Functions -->
<script>
    // Switch between cover letter tabs
    function switchCoverLetterTab(tab) {
        const writeSection = document.getElementById('writeSection');
        const uploadSection = document.getElementById('uploadSection');
        const writeTab = document.getElementById('writeTab');
        const uploadTab = document.getElementById('uploadTab');
        const writeCoverLetter = document.getElementById('writeCoverLetter');
        const uploadCoverLetterFile = document.getElementById('uploadCoverLetterFile');

        if (tab === 'write') {
            writeSection.classList.remove('hidden');
            uploadSection.classList.add('hidden');
            writeTab.classList.add('border-primary', 'text-primary', 'bg-blue-50');
            writeTab.classList.remove('border-gray-300', 'text-gray-600');
            uploadTab.classList.remove('border-primary', 'text-primary', 'bg-blue-50');
            uploadTab.classList.add('border-gray-300', 'text-gray-600');
            writeCoverLetter.checked = true;
        } else {
            writeSection.classList.add('hidden');
            uploadSection.classList.remove('hidden');
            uploadTab.classList.add('border-primary', 'text-primary', 'bg-blue-50');
            uploadTab.classList.remove('border-gray-300', 'text-gray-600');
            writeTab.classList.remove('border-primary', 'text-primary', 'bg-blue-50');
            writeTab.classList.add('border-gray-300', 'text-gray-600');
            uploadCoverLetterFile.checked = true;
        }
    }

    // Update file name display
    function updateFileName(inputId, displayId) {
        const file = document.getElementById(inputId).files[0];
        const displayElement = document.getElementById(displayId);
        if (file) {
            displayElement.textContent = '✅ Selected: ' + file.name;
            displayElement.classList.add('text-green-600');
        } else {
            displayElement.textContent = '';
        }
    }

    // Handle resume option change
    document.querySelectorAll('input[name="resume_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'existing') {
                const resumePath = this.dataset.resumePath;
                document.getElementById('existingResumeValue').value = resumePath;
            }
        });
    });
</script>
