<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Department;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniProfileController extends Controller
{
    /**
     * Show user type selection page
     */
    public function selectType()
    {
        $user = auth()->user();
        $profile = AlumniProfile::where('user_id', $user->id)->first();

        if ($profile && ($profile->is_fresh_grad === true || $profile->is_fresh_grad === false)) {
            return redirect()->route('alumni.profile.edit');
        }

        return view('users.alumni.profile.select-type', compact('profile'));
    }

    /**
     * Set user type and redirect to appropriate form
     */
    public function setType(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_type' => 'required|in:fresh_grad,experienced',
            ]);

            $user = auth()->user();
            $profile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);

            $isFreshGrad = $validated['user_type'] === 'fresh_grad';
            $profile->update(['is_fresh_grad' => $isFreshGrad]);

            return response()->json([
                'success' => true,
                'message' => 'User type saved successfully',
                'redirect' => route('alumni.profile.edit'),
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
                'message' => 'Failed to save user type: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show alumni profile (Read-only)
     */
    public function show()
    {
        $user = auth()->user();
        $profile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);

        $experiences = Experience::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

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
        $profile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);

        if ($profile->is_fresh_grad === null) {
            return redirect()->route('alumni.profile.select-type');
        }

        // Get all active departments for the dropdown
        $departments = Department::orderBy('title', 'asc')->get();


        $experiences = Experience::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        $projects = Project::where('user_id', $user->id)
            ->where('user_type', 'alumni')
            ->orderByDesc('start_date')
            ->get();

        return view('users.alumni.profile.edit', compact('profile', 'departments', 'experiences', 'projects'));
    }

    /**
     * Update alumni profile
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            $profile = AlumniProfile::where('user_id', $user->id)->firstOrFail();
            $isFreshGrad = $profile->is_fresh_grad;

            $rules = [
                // Department Selection (Optional for alumni)
                'department_id' => 'nullable|exists:departments,id',

                // Personal Information (Required for both)
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'personal_email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'current_location' => 'required|string|max:255',

                // Academic Information (Required for both)
                'degree_program' => 'required|string|max:255',
                'graduation_year' => 'required|integer|min:1990|max:2099',
                'gwa' => 'nullable|numeric|min:0|max:4.00',
                'honors' => 'nullable|in:Cum Laude,Magna Cum Laude,Summa Cum Laude',
                'thesis_title' => 'nullable|string|max:500',

                // Skills & Competencies (Optional for both)
                'technical_skills' => 'nullable|string|max:1000',
                'soft_skills' => 'nullable|string|max:1000',
                'languages' => 'nullable|string|max:1000',

                // Social & Professional Links (Optional for both)
                'linkedin_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'portfolio_url' => 'nullable|url|max:255',

                // File uploads
                'resumes' => 'nullable|array|max:5',
                'resumes.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certifications' => 'nullable|array|max:5',
                'certifications.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ];

            if ($isFreshGrad) {
                $rules = array_merge($rules, [
                    'headline' => 'nullable|string|max:500',
                    'current_organization' => 'nullable|string|max:255',
                    'current_position' => 'nullable|string|max:255',
                    'current_industry' => 'nullable|string|max:255',
                    'willing_to_relocate' => 'nullable|boolean',
                    'professional_summary' => 'nullable|string|max:2000',
                ]);
            } else {
                $rules = array_merge($rules, [
                    'headline' => 'required|string|max:500',
                    'current_organization' => 'required|string|max:255',
                    'current_position' => 'required|string|max:255',
                    'current_industry' => 'nullable|string|max:255',
                    'willing_to_relocate' => 'nullable|boolean',
                    'professional_summary' => 'required|string|min:20|max:2000',
                ]);
            }

            $validated = $request->validate($rules);

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                    Storage::disk('public')->delete($profile->profile_photo);
                }
                $path = $request->file('profile_photo')->store('alumni-profiles', 'public');
                $validated['profile_photo'] = $path;
            }

            // Handle resume uploads
            if ($request->hasFile('resumes')) {
                $currentResumes = $profile->resumes ?? [];
                foreach ($request->file('resumes') as $resume) {
                    $path = $resume->store('alumni-resumes', 'public');
                    $currentResumes[] = $path;
                }
                $validated['resumes'] = $currentResumes;
            }

            // Handle certification uploads
            if ($request->hasFile('certifications')) {
                $currentCerts = $profile->certifications ?? [];
                foreach ($request->file('certifications') as $cert) {
                    $path = $cert->store('alumni-certifications', 'public');
                    $currentCerts[] = $path;
                }
                $validated['certifications'] = $currentCerts;
            }

            $profile->update($validated);

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

    /**
     * Delete resume file
     */
    public function deleteResume(Request $request)
    {
        try {
            $filePath = $request->input('file_path');
            $user = auth()->user();
            $profile = AlumniProfile::where('user_id', $user->id)->firstOrFail();

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $resumes = $profile->resumes ?? [];
            $resumes = array_filter($resumes, fn($r) => $r !== $filePath);
            $profile->update(['resumes' => array_values($resumes)]);

            return response()->json([
                'success' => true,
                'message' => 'Resume deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete resume: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete certification file
     */
    public function deleteCertification(Request $request)
    {
        try {
            $filePath = $request->input('file_path');
            $user = auth()->user();
            $profile = AlumniProfile::where('user_id', $user->id)->firstOrFail();

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $certs = $profile->certifications ?? [];
            $certs = array_filter($certs, fn($c) => $c !== $filePath);
            $profile->update(['certifications' => array_values($certs)]);

            return response()->json([
                'success' => true,
                'message' => 'Certification deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete certification: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change user type (Fresh Grad <-> Experienced)
     */
    public function changeType(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_type' => 'required|in:fresh_grad,experienced',
            ]);

            $user = auth()->user();
            $profile = AlumniProfile::where('user_id', $user->id)->firstOrFail();

            $isFreshGrad = $validated['user_type'] === 'fresh_grad';
            $profile->update(['is_fresh_grad' => $isFreshGrad]);

            return response()->json([
                'success' => true,
                'message' => 'Profile type changed successfully!',
                'redirect' => route('alumni.profile.edit'),
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
                'message' => 'Failed to change type: ' . $e->getMessage(),
            ], 500);
        }
    }
}
