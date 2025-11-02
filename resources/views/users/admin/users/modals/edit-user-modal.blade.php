<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Edit User Account</h3>
                <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="editUserForm" action="" method="">
                    <input type="hidden" name="id" />
                    <div class="space-y-4">
                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Account Type <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="editUserRole"
                                name="role"
                                onchange="toggleEditRoleFields()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                required
                            >
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="partner">Partner</option>
                                <option value="alumni">Alumni</option>
                                <option value="student">Student</option>
                            </select>
                        </div>

                        <!-- Name fields -->
                        <div id="editNameFields">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="first_name"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="last_name"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input
                                    type="text"
                                    name="middle_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name Suffix</label>
                                <input
                                    type="text"
                                    name="name_suffix"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Jr., Sr., III"
                                />
                            </div>
                        </div>

                        <!-- Department (only for student) -->
                        <div id="editDepartmentField" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="department_id"
                                required
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

                        <!-- Email Field -->
                        <div id="editEmailField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                            />
                            <p id="editEmailNote" class="hidden text-xs text-gray-500 mt-1">Must use @pcu.edu.ph domain</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                required
                            >
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button
                            type="button"
                            onclick="closeEditUserModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
