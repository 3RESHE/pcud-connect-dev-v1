<?php

namespace App\Http\Controllers\Admin\Departments;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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
            $departments = Department::withCount('students')
                ->orderBy('created_at', 'desc')
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

            // Log activity
            ActivityLogger::logCreate($department, "Created department: {$department->title}");

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
            $department = Department::withCount('students')->findOrFail($id);

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

            // Store old values for activity log
            $oldValues = $department->only(['title', 'code']);

            // Update department
            $department->update([
                'title' => $validated['title'],
                'code' => strtoupper($validated['code']),
            ]);

            // Log activity
            ActivityLogger::log(
                action: 'updated',
                subjectType: Department::class,
                subjectId: $department->id,
                properties: [
                    'old' => $oldValues,
                    'new' => $department->only(['title', 'code']),
                ],
                description: "Updated department: {$department->title}"
            );

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

            // Store data for activity log before deletion
            $departmentData = $department->toArray();

            // Delete department
            $department->delete();

            // Log activity
            ActivityLogger::log(
                action: 'deleted',
                subjectType: Department::class,
                subjectId: $id,
                properties: [
                    'data' => $departmentData,
                ],
                description: "Deleted department: {$departmentData['title']}"
            );

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
                ->withCount('students')
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

    /**
     * ✅ NEW: Export students by department
     */
    public function exportStudents(Request $request, $departmentId)
    {
        try {
            $department = Department::with(['students' => function ($query) {
                $query->with('studentProfile');
            }])->findOrFail($departmentId);

            $fileName = "students-{$department->code}-" . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new DepartmentStudentsExport($department),
                $fileName
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Department not found');
        } catch (\Exception $e) {
            \Log::error('Export students failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export students: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Export all departments with student count summary
     */
    public function exportAll(Request $request)
    {
        try {
            $fileName = "departments-summary-" . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new DepartmentsSummaryExport(),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Export departments failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export departments: ' . $e->getMessage());
        }
    }
}

/**
 * ✅ NEW: Export class for students in a specific department
 */
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DepartmentStudentsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $department;

    public function __construct($department)
    {
        $this->department = $department;
    }

    public function array(): array
    {
        $data = [];

        // Add department header
        $data[] = ['DEPARTMENT: ' . $this->department->title . ' (' . $this->department->code . ')'];
        $data[] = [];
        $data[] = ['Total Students: ' . $this->department->students()->count()];
        $data[] = ['Export Date: ' . now()->format('M d, Y H:i A')];
        $data[] = [];

        // Add student data
        foreach ($this->department->students as $student) {
            $profile = $student->studentProfile;

            $data[] = [
                $student->first_name,
                $student->last_name,
                $student->email,
                $profile?->student_id ?? 'N/A',
                $profile?->headline ?? 'N/A',
                $profile?->phone ?? 'N/A',
                $profile?->personal_email ?? 'N/A',
                $student->is_active ? 'Active' : 'Inactive',
                $student->created_at->format('M d, Y'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Student ID',
            'Headline',
            'Phone',
            'Personal Email',
            'Status',
            'Created At',
        ];
    }
}

/**
 * ✅ NEW: Export class for all departments with student count summary
 */
class DepartmentsSummaryExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        $data = [];

        // Add header section
        $data[] = ['DEPARTMENTS SUMMARY REPORT'];
        $data[] = ['Export Date: ' . now()->format('M d, Y H:i A')];
        $data[] = [];

        // Get departments with student count
        $departments = Department::withCount('students')
            ->orderBy('title', 'asc')
            ->get();

        $totalStudents = 0;

        // Add department data
        foreach ($departments as $dept) {
            $studentCount = $dept->students_count ?? 0;
            $totalStudents += $studentCount;

            $data[] = [
                $dept->code,
                $dept->title,
                $studentCount,
                $studentCount > 0 ? 'Active' : 'No Students',
                $dept->created_at->format('M d, Y'),
            ];
        }

        // Add summary row
        $data[] = [];
        $data[] = ['TOTAL', '', $totalStudents, '', ''];

        return $data;
    }

    public function headings(): array
    {
        return [
            'Department Code',
            'Department Name',
            'Student Count',
            'Status',
            'Created At',
        ];
    }
}
