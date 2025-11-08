<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Experience;
use App\Models\Project;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentProfileController extends Controller
{
    /**
     * Display student profile (read-only view).
     */
    public function show()
    {
        $user = auth()->user();
        $profile = $user->studentProfile;
        $experiences = $user->experiences()->latest()->get();
        $projects = $user->projects()->latest()->get();

        return view('users.student.profile.show', compact('user', 'profile', 'experiences', 'projects'));
    }

    /**
     * Show edit profile form.
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        // Auto-create profile if doesn't exist
        if (!$profile) {
            $profile = $user->studentProfile()->create([
                'student_id' => $user->student_id,
            ]);
        }

        $experiences = $user->experiences()->latest()->get();
        $projects = $user->projects()->latest()->get();

        return view('users.student.profile.edit', compact('user', 'profile', 'experiences', 'projects'));
    }

    /**
     * Update student profile.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $profile = $user->studentProfile;

            // Validate profile data
            $validated = $request->validate([
                // Profile Photo
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

                // StudentProfile fields
                'headline' => 'nullable|string|max:255',
                'personal_email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'emergency_contact' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:500',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
                'linkedin_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'portfolio_url' => 'nullable|url|max:255',
                'technical_skills' => 'nullable|string|max:1000',
                'soft_skills' => 'nullable|string|max:1000',
                'certifications' => 'nullable|string|max:1000',
                'languages' => 'nullable|string|max:500',
                'hobbies' => 'nullable|string|max:500',
                'resume_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                if ($profile->profile_photo && Storage::exists($profile->profile_photo)) {
                    Storage::delete($profile->profile_photo);
                }
                $path = $request->file('profile_photo')->store('student-photos', 'public');
                $validated['profile_photo'] = $path;
            }

            // Handle resume upload
            if ($request->hasFile('resume_path')) {
                if ($profile->resume_path && Storage::exists($profile->resume_path)) {
                    Storage::delete($profile->resume_path);
                }
                $path = $request->file('resume_path')->store('student-resumes', 'public');
                $validated['resume_path'] = $path;
            }

            // Update profile
            $profile->update($validated);

            // Log activity
            ActivityLogger::log(
                'profile_updated',
                StudentProfile::class,
                $profile->id,
                $validated,
                "Student updated profile"
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Profile updated successfully!',
                'redirect' => route('student.profile.show'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Student profile update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage(),
            ], 500);
        }
    }
}
