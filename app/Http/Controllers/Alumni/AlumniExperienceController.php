<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class AlumniExperienceController extends Controller
{
    /**
     * Store a new experience
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'experience_role' => 'required|string|max:255',
                'experience_org' => 'required|string|max:255',
                'experience_type' => 'required|in:internship,part_time,volunteer,full_time,freelance',
                'experience_start' => 'required|date',
                'experience_end' => 'nullable|date|after_or_equal:experience_start',
                'experience_desc' => 'required|string|min:20|max:2000',
                'experience_location' => 'nullable|string|max:255',
            ]);

            $experience = Experience::create([
                'user_id' => auth()->id(),
                'user_type' => 'alumni',
                'role_position' => $validated['experience_role'],
                'organization' => $validated['experience_org'],
                'experience_type' => $validated['experience_type'],
                'start_date' => $validated['experience_start'],
                'end_date' => $validated['experience_end'] ?? null,
                'location' => $validated['experience_location'] ?? null,
                'description' => $validated['experience_desc'],
                'is_current' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Experience added successfully!',
                'experience' => $experience,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Experience store error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add experience: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an experience
     */
    public function update(Request $request, Experience $experience)
    {
        try {
            if ($experience->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $validated = $request->validate([
                'experience_role' => 'required|string|max:255',
                'experience_org' => 'required|string|max:255',
                'experience_type' => 'required|in:internship,part_time,volunteer,full_time,freelance',
                'experience_start' => 'required|date',
                'experience_end' => 'nullable|date|after_or_equal:experience_start',
                'experience_desc' => 'required|string|min:20|max:2000',
                'experience_location' => 'nullable|string|max:255',
            ]);

            $experience->update([
                'role_position' => $validated['experience_role'],
                'organization' => $validated['experience_org'],
                'experience_type' => $validated['experience_type'],
                'start_date' => $validated['experience_start'],
                'end_date' => $validated['experience_end'] ?? null,
                'location' => $validated['experience_location'] ?? null,
                'description' => $validated['experience_desc'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Experience updated successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Experience update error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update experience: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an experience
     */
    public function destroy(Experience $experience)
    {
        try {
            if ($experience->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $experience->delete();

            return response()->json([
                'success' => true,
                'message' => 'Experience deleted successfully!',
            ]);

        } catch (\Exception $e) {
            \Log::error('Experience delete error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete experience: ' . $e->getMessage(),
            ], 500);
        }
    }
}
