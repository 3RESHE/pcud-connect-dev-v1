@extends('layouts.admin')

@section('title', 'Bulk Import Users - PCU-DASMA Connect')
@section('page-title', 'Bulk Import Users')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bulk Import Users</h1>
        <p class="text-gray-600">Import multiple users at once using Excel or CSV files</p>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li><a href="{{ route('admin.users.index') }}" class="text-primary hover:text-blue-700">Users</a></li>
            <li><span class="text-gray-500 mx-2">/</span></li>
            <li class="text-gray-900">Bulk Import</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Import Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Upload File</h2>

                <form id="bulkImportForm" class="space-y-6">
                    @csrf

                    <!-- Upload Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            What type of users are you importing? <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="upload_type" value="students" required
                                    onchange="toggleDepartmentField()"
                                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                <span class="ml-3 text-gray-700">
                                    <span class="font-medium">Students</span>
                                    <span class="text-xs text-gray-500 block">Import student accounts (requires department
                                        selection)</span>
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="upload_type" value="alumni" onchange="toggleDepartmentField()"
                                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                <span class="ml-3 text-gray-700">
                                    <span class="font-medium">Alumni</span>
                                    <span class="text-xs text-gray-500 block">Import alumni accounts</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Department Selection (Only for Students) -->
                    <div id="departmentFieldDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="departmentId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">-- Choose Department --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                            @endforeach
                        </select>
                        <span id="departmentError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select File <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 py-10 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary transition"
                            id="dropZone">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-8l-3.172-3.172a4 4 0 00-5.656 0L28 20M9 20l3.172-3.172a4 4 0 015.656 0L20 20"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-primary cursor-pointer hover:text-blue-700">Click to
                                        upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">CSV, XLS, or XLSX (Max 10MB)</p>
                            </div>
                            <input type="file" name="csv_file" id="csvFile" accept=".csv,.xls,.xlsx" class="hidden"
                                required>
                        </div>
                        <span id="fileError" class="text-red-500 text-xs mt-1 hidden"></span>
                        <div id="filePreview" class="mt-3 hidden">
                            <div class="flex items-center space-x-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="fileName">File selected</span>
                                <button type="button" onclick="clearFile()"
                                    class="text-red-500 hover:text-red-700">Remove</button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                            <svg id="submitIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                            </svg>
                            <span id="submitText">Upload & Import</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions & Template Download -->
        <div class="space-y-6">
            <!-- Instructions -->
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <h3 class="font-semibold text-blue-900 mb-3">📋 Instructions</h3>
                <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                    <li>Select the type of users you're importing</li>
                    <li>If importing students, select the department</li>
                    <li>Prepare your file in Excel or CSV format</li>
                    <li>Upload the file and review the results</li>
                </ol>
            </div>

            <!-- File Format -->
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <h3 class="font-semibold text-green-900 mb-3">✅ File Format</h3>
                <p class="text-sm text-green-800 mb-3"><strong>Required columns:</strong></p>
                <ul class="text-xs text-green-800 space-y-1 font-mono bg-white p-2 rounded">
                    <li>• first_name</li>
                    <li>• last_name</li>
                    <li>• email</li>
                    <li>• student_id <span class="text-green-600">(students only)</span></li>
                </ul>
                <p class="text-xs text-green-800 mt-3"><strong>Optional columns:</strong></p>
                <ul class="text-xs text-green-800 space-y-1 font-mono">
                    <li>• middle_name</li>
                    <li>• name_suffix</li>
                </ul>
            </div>

            <!-- Download Template -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="font-semibold text-gray-900 mb-3">📥 Download Template</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.download-template', 'students') }}"
                        class="flex items-center px-3 py-2 text-sm text-primary hover:bg-blue-50 rounded transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                        </svg>
                        Students Template
                    </a>
                    <a href="{{ route('admin.users.download-template', 'alumni') }}"
                        class="flex items-center px-3 py-2 text-sm text-primary hover:bg-blue-50 rounded transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                        </svg>
                        Alumni Template
                    </a>
                </div>
            </div>


            <!-- Example Data -->
            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                <h3 class="font-semibold text-yellow-900 mb-3">💡 Example Data</h3>
                <p class="text-xs text-yellow-800 mb-2"><strong>Students:</strong></p>
                <code class="text-xs bg-white p-2 rounded block overflow-x-auto text-gray-600">
                    first_name,last_name,email,student_id<br>
                    Juan,Dela Cruz,juan@pcud.edu.ph,202209857<br>
                    Maria,Santos,maria@pcud.edu.ph,202209859
                </code>
            </div>
        </div>
    </div>

    <!-- Import Progress Modal -->
    <div id="progressModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl p-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-4">
                        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Importing Users</h3>
                    <p class="text-sm text-gray-500 mb-4">Please wait while we process your file...</p>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>

    <script src="{{ asset('js/admin/bulk-import.js') }}"></script>
@endsection
