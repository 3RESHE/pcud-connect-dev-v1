<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-lg w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Bulk Upload Users</h3>
                <button onclick="closeBulkUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Upload Instructions:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Upload a CSV file (.csv)</li>
                        <li>• First row should contain column headers</li>
                        <li>• For Students: Required columns: first_name, last_name, middle_name, name_suffix, email</li>
                        <li>• For Alumni: Required column: email</li>
                    </ul>
                </div>
                <div class="mb-4">
                    <a href="" onclick="downloadTemplate()" class="text-primary hover:underline text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-4-6V3"></path>
                        </svg>
                        Download CSV Template
                    </a>
                </div>
                <form id="bulkUploadForm" action="" method="">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Type <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="upload_type"
                                onchange="toggleBulkUploadFields()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                required
                            >
                                <option value="">Select Upload Type</option>
                                <option value="students">Students</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>
                        <div id="studentDepartmentField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="department_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            >
                                <option value="">Select Department</option>
                                <option value="1">College of Arts and Sciences</option>
                                <option value="2">College of Business and Accountancy</option>
                                <option value="3">College of Criminal Justice</option>
                                <option value="4">College of Engineering and Technology</option>
                                <option value="5">College of Education</option>
                                <option value="6">College of Informatics</option>
                                <option value="7">College of Hospitality and Tourism Management</option>
                                <option value="8">College of Nursing and Health Sciences</option>
                                <option value="9">College of Social Work</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select CSV File <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="file"
                                name="csv_file"
                                accept=".csv"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700"
                            />
                        </div>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="sendBulkCredentials"
                                name="send_credentials"
                                checked
                                disabled
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                            />
                            <label for="sendBulkCredentials" class="ml-2 block text-sm text-gray-700">
                                Send login credentials to all users via email
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeBulkUploadModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        >
                            Upload Users
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
