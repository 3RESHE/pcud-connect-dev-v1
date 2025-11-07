<?php

namespace App\Http\Controllers\Admin\Departments;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display all departments
     */
    public function index(): View
    {
        return view('users.admin.departments.index');
    }

    /**
     * Get all departments (AJAX)
     */
    public function getAll(): JsonResponse
    {
        try {
            $departments = Department::orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $departments->items(),
                'pagination' => [
                    'current_page' => $departments->currentPage(),
                    'total_pages' => $departments->lastPage(),
                    'total' => $departments->total(),
                    'per_page' => $departments->perPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch departments: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new department
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:departments,title',
                'code' => 'required|string|max:20|unique:departments,code',
            ], [
                'title.required' => 'Department name is required',
                'title.unique' => 'This department name already exists',
                'code.required' => 'Department code is required',
                'code.unique' => 'This department code already exists',
                'code.max' => 'Department code must not exceed 20 characters',
            ]);

            // Create department
            $department = Department::create([
                'title' => $validated['title'],
                'code' => strtoupper($validated['code']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'data' => $department,
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
                'message' => 'Failed to create department: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single department
     */
    public function show($id): JsonResponse
    {
        try {
            $department = Department::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $department,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch department: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a department
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $department = Department::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:departments,title,' . $id,
                'code' => 'required|string|max:20|unique:departments,code,' . $id,
            ], [
                'title.required' => 'Department name is required',
                'title.unique' => 'This department name already exists',
                'code.required' => 'Department code is required',
                'code.unique' => 'This department code already exists',
                'code.max' => 'Department code must not exceed 20 characters',
            ]);

            // Update department
            $department->update([
                'title' => $validated['title'],
                'code' => strtoupper($validated['code']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'data' => $department,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a department
     */
    public function destroy($id): JsonResponse
    {
        try {
            $department = Department::findOrFail($id);

            // Check if department has students
            $studentCount = $department->students()->count();
            if ($studentCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete department. It has {$studentCount} student(s) assigned.",
                ], 400);
            }

            // Delete department
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search departments
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q', '');

            $departments = Department::where('title', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->orderBy('title')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $departments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search departments: ' . $e->getMessage(),
            ], 500);
        }
    }
}
