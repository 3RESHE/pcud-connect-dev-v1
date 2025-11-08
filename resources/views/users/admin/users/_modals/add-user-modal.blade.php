<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
                <h3 class="text-xl font-semibold text-gray-900">Add New User</h3>
                <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="addUserForm" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="addFirstName" required placeholder="John"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <span id="addFirstNameError" class="text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="addLastName" required placeholder="Doe"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <span id="addLastNameError" class="text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" id="addMiddleName" placeholder="Optional"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name Suffix</label>
                            <input type="text" name="name_suffix" id="addNameSuffix" placeholder="Jr., Sr., etc."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="addEmail" required placeholder="john@example.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                        <span id="addEmailError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="addRole" required onchange="toggleDepartmentField()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="partner">Partner</option>
                            <option value="student">Student</option>
                            <option value="alumni">Alumni</option>
                        </select>
                        <span id="addRoleError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <div id="departmentFieldDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="addDepartmentId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                            <option value="">Select Department</option>
                        </select>
                        <span id="addDepartmentIdError" class="text-red-500 text-xs mt-1 hidden"></span>
                    </div>

                    <!-- Student ID Field (only for students) -->
                    <div id="studentIdFieldDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Student ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="student_id" id="addStudentId" placeholder="e.g., 202206642"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                        <span id="addStudentIdError" class="text-red-500 text-xs mt-1 hidden"></span>
                        <p class="text-xs text-gray-500 mt-1">Enter the student's ID number</p>
                    </div>


                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddUserModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="addSubmitBtn"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
