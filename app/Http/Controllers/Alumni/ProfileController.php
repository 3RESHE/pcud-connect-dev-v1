<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the alumni profile edit form.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $user = auth()->user();

            return view('users.alumni.profile.index', [
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Profile index error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading your profile.');
        }
    }

    /**
     * Update the alumni profile.
     *
     * @return RedirectResponse
     */
    public function update(): RedirectResponse
    {
        try {
            $user = auth()->user();

            // ===== VALIDATION =====
            $validated = request()->validate([
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'headline' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'suffix' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:500',
                'location' => 'nullable|string|max:255',
                'degree' => 'required|string|in:bachelor,master,associate,certificate,other',
                'field_of_study' => 'required|string|max:255',
                'graduation_year' => 'required|integer|min:1950|max:' . now()->year,
                'honors' => 'nullable|string|max:500',
                'current_position' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'industry' => 'nullable|string|in:technology,finance,healthcare,education,retail,manufacturing,other',
                'skills' => 'nullable|string|max:1000',
                'linkedin_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'portfolio_url' => 'nullable|url|max:255',
            ]);

            // ===== HANDLE PROFILE PHOTO UPLOAD =====
            if (request()->hasFile('profile_photo')) {
                try {
                    // Delete old photo if exists
                    if ($user->profile_photo) {
                        Storage::disk('public')->delete($user->profile_photo);
                    }

                    // Store new photo
                    $path = request()->file('profile_photo')->store('profile-photos', 'public');
                    $validated['profile_photo'] = $path;
                } catch (\Exception $e) {
                    \Log::error('Error uploading profile photo: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error uploading profile photo. Please try again.');
                }
            }

            // ===== UPDATE USER =====
            $user->update($validated);

            return redirect()->route('alumni.profile.index')
                ->with('success', 'Profile updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your profile.');
        }
    }
}
