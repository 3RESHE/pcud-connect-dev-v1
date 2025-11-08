<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AlumniProjectController extends Controller
{
    /**
     * Store a new project
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'project_title' => 'required|string|max:255',
                'project_desc' => 'required|string|min:20|max:2000',
                'project_url' => 'nullable|url|max:255',
                'project_start' => 'required|date',
                'project_end' => 'nullable|date|after_or_equal:project_start',
            ]);

            $project = Project::create([
                'user_id' => auth()->id(),
                'user_type' => 'alumni',
                'title' => $validated['project_title'],
                'description' => $validated['project_desc'],
                'url' => $validated['project_url'] ?? null,
                'start_date' => $validated['project_start'],
                'end_date' => $validated['project_end'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project added successfully!',
                'project' => $project,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Project store error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add project: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a project
     */
    public function update(Request $request, Project $project)
    {
        try {
            if ($project->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $validated = $request->validate([
                'project_title' => 'required|string|max:255',
                'project_desc' => 'required|string|min:20|max:2000',
                'project_url' => 'nullable|url|max:255',
                'project_start' => 'required|date',
                'project_end' => 'nullable|date|after_or_equal:project_start',
            ]);

            $project->update([
                'title' => $validated['project_title'],
                'description' => $validated['project_desc'],
                'url' => $validated['project_url'] ?? null,
                'start_date' => $validated['project_start'],
                'end_date' => $validated['project_end'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Project update error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a project
     */
    public function destroy(Project $project)
    {
        try {
            if ($project->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully!',
            ]);

        } catch (\Exception $e) {
            \Log::error('Project delete error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage(),
            ], 500);
        }
    }
}
