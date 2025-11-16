<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\StudentProfile;
use App\Helpers\ActivityLogger;
use App\Mail\UserCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    /**
     * Display user management page
     */
    public function index(): View
    {
        return view('users.admin.users.index');
    }

    /**
     * Get all users (AJAX)
     */
    public function getAll(Request $request): JsonResponse
    {
        try {
            $query = User::with(['department', 'studentProfile'])
                ->orderBy('created_at', 'desc');

            // Filter by role
            if ($request->filled('role') && $request->role !== 'all') {
                $query->where('role', $request->role);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'total_pages' => $users->lastPage(),
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'partner' => User::where('role', 'partner')->count(),
                'student' => User::where('role', 'student')->count(),
                'alumni' => User::where('role', 'alumni')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
            ], 500);
        }
    }

    /**
     * Store a new user
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation rules
            $rules = [
                'role' => 'required|in:admin,staff,partner,student,alumni',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'name_suffix' => 'nullable|string|max:20',
                'email' => 'required|email|unique:users,email',
            ];

            // Students must have department and student_id
            if ($request->role === 'student') {
                $rules['department_id'] = 'required|exists:departments,id';
                $rules['student_id'] = 'required|string|max:20|unique:student_profiles,student_id';
            }

            $validated = $request->validate($rules, [
                'student_id.required' => 'Student ID is required for students',
                'student_id.unique' => 'This Student ID already exists',
            ]);

            // Generate temporary password
            $tempPassword = Str::random(12);

            // Create user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'name_suffix' => $validated['name_suffix'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($tempPassword),
                'role' => $validated['role'],
                'department_id' => $validated['department_id'] ?? null,
                'is_active' => true,
                'password_changed_at' => null, // Force password change on first login
            ]);

            // Create profile based on role
            $this->createUserProfile($user, $validated);

            // Log activity
            ActivityLogger::logCreate($user, "Created {$user->role} account: {$user->full_name}");

            // Send credentials email
            try {
                Mail::to($user->email)->send(new UserCredentialsMail($user, $tempPassword));
            } catch (\Exception $e) {
                \Log::error('Failed to send credentials email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully. Credentials sent via email.',
                'data' => $user,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single user
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with(['department', 'studentProfile'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update a user
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Validation rules
            $rules = [
                'role' => 'required|in:admin,staff,partner,student,alumni',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'name_suffix' => 'nullable|string|max:20',
                'email' => 'required|email|unique:users,email,' . $id,
                'is_active' => 'required|boolean',
            ];

            // Students must have department
            if ($request->role === 'student') {
                $rules['department_id'] = 'required|exists:departments,id';
            }

            $validated = $request->validate($rules);

            // Store old values for logging
            $oldValues = $user->only(['first_name', 'last_name', 'email', 'role', 'is_active']);

            // Update user
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'name_suffix' => $validated['name_suffix'] ?? null,
                'email' => $validated['email'],
                'role' => $validated['role'],
                'department_id' => $validated['department_id'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Log activity
            ActivityLogger::logUpdate($user, $oldValues, "Updated user: {$user->full_name}");

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account',
                ], 400);
            }

            $userData = $user->toArray();
            $user->delete();

            // Log activity
            ActivityLogger::logDelete($user, "Deleted {$user->role} account: {$user->full_name}");

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display bulk import form
     */
    public function bulkImportForm(): View
    {
        return view('users.admin.users.bulk-import', [
            'departments' => Department::orderBy('title')->get(),
        ]);
    }

    /**
     * Bulk import users
     */
    public function bulkImport(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'upload_type' => 'required|in:students,alumni',
                'csv_file' => 'required|file|mimes:csv,xlsx,xls',
            ]);

            if ($request->upload_type === 'students') {
                $request->validate([
                    'department_id' => 'required|exists:departments,id',
                ]);
            }

            $file = $request->file('csv_file');
            $uploadType = $request->upload_type;
            $departmentId = $request->department_id ?? null;

            // Import users
            $import = new UsersImport($uploadType, $departmentId);
            Excel::import($import, $file);

            // Get imported users count
            $importedCount = $import->getImportedCount();

            // Log activity
            ActivityLogger::log(
                'bulk_import',
                User::class,
                null,
                ['count' => $importedCount, 'type' => $uploadType],
                "Bulk imported {$importedCount} {$uploadType}"
            );

            return response()->json([
                'success' => true,
                'message' => "{$importedCount} users imported successfully. Credentials sent via email.",
                'count' => $importedCount,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create user profile based on role
     */
    private function createUserProfile(User $user, array $validated): void
    {
        switch ($user->role) {
            case 'admin':
                $user->adminProfile()->create([]);
                break;
            case 'staff':
                $user->staffProfile()->create([]);
                break;
            case 'partner':
                // Create empty profile - partner will fill it later
                $user->partnerProfile()->create([]);
                break;
            case 'student':
                $user->studentProfile()->create([
                    'student_id' => $validated['student_id'],
                ]);
                break;
            case 'alumni':
                $user->alumniProfile()->create([]);
                break;
        }
    }

    /**
     * Download import template
     */
    /**
     * Download import template
     */
    public function downloadTemplate($type)
    {
        try {
            // Validate type
            if (!in_array($type, ['students', 'alumni'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid template type',
                ], 400);
            }

            $filename = "{$type}-import-template-" . date('Y-m-d') . ".csv";

            if ($type === 'students') {
                $headers = ['first_name', 'last_name', 'middle_name', 'name_suffix', 'email', 'student_id'];
                $rows = [
                    ['Juan', 'Dela Cruz', 'Carlos', 'Jr.', 'test123@pcu.edu.ph', '202206402'],
                    ['Marj Celine', 'San Jose', 'Abera', '', 'test333@pcu.edu.ph', '202206406'],
                    ['Antonio', 'Reyes', 'Manuel', 'Sr.', 'test123@pcud.edu.ph', '202206408'],
                ];
            } else {
                $headers = ['first_name', 'last_name', 'middle_name', 'name_suffix', 'email'];
                $rows = [
                    ['Pedro', 'Gonzales', 'Ramos', '', 'pedro.gonzales@example.com'],
                    ['Rosa', 'Martinez', '', '', 'rosa.martinez@example.com'],
                    ['Carlos', 'Lopez', 'Juan', 'Jr.', 'carlos.lopez@example.com'],
                ];
            }

            $callback = function () use ($headers, $rows) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                "Content-Type" => "text/csv; charset=utf-8",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download template: ' . $e->getMessage(),
            ], 500);
        }
    }
}
