<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniProfileController extends Controller
{
    /**
     * Show alumni profile (Read-only)
     */
    public function show()
    {
        $user = auth()->user();

        // Get or create alumni profile
        $profile = AlumniProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );

        // Get experiences
        $experiences = Experience::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        // Get projects
        $projects = Project::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('start_date')
            ->get();

        return view('users.alumni.profile.show', compact('profile', 'experiences', 'projects'));
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $user = auth()->user();

        // Get or create alumni profile
        $profile = AlumniProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );

        // Get experiences
        $experiences = Experience::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        // Get projects
        $projects = Project::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('start_date')
            ->get();

        return view('users.alumni.profile.edit', compact('profile', 'experiences', 'projects'));
    }

    /**
     * Update alumni profile
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            // Validate
            $validated = $request->validate([
                // Personal Information
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'headline' => 'required|string|max:500',
                'personal_email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'current_location' => 'required|string|max:255',

                // Academic Information
                'degree_program' => 'required|string|max:255',
                'graduation_year' => 'required|integer|min:1990|max:2099',
                'gwa' => 'nullable|numeric|min:0|max:4.00',
                'honors' => 'nullable|in:Cum Laude,Magna Cum Laude,Summa Cum Laude',
                'thesis_title' => 'nullable|string|max:500',

                // Professional Information
                'current_organization' => 'required|string|max:255',
                'current_position' => 'required|string|max:255',
                'current_industry' => 'nullable|string|max:255',
                'willing_to_relocate' => 'nullable|boolean',
                'professional_summary' => 'required|string|min:20|max:2000',

                // Skills & Competencies
                'technical_skills' => 'nullable|string|max:1000',
                'soft_skills' => 'nullable|string|max:1000',
                'certifications' => 'nullable|string|max:1000',
                'languages' => 'nullable|string|max:1000',

                // Social & Professional Links
                'linkedin_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'portfolio_url' => 'nullable|url|max:255',
            ]);

            // Get or create profile
            $profile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }

                // Store new photo
                $path = $request->file('profile_photo')->store('alumni-profiles', 'public');
                $validated['profile_photo'] = $path;
            }

            // Update profile
            $profile->update($validated);

            // Check if profile is now complete
            if ($profile->isProfileComplete()) {
                $profile->markAsComplete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'redirect' => route('alumni.profile.show'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Alumni profile update error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage(),
            ], 500);
        }
    }
}
