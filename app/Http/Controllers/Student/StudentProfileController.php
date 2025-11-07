<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentProfileController extends Controller
{
    /**
     * Display student profile form.
     */
    public function index(): View
    {
        try {
            $user = auth()->user();

            return view('users.student.profile.index', [
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Student profile index error: ' . $e->getMessage());
            abort(500, 'An error occurred.');
        }
    }

    /**
     * Update student profile.
     */
    public function update(): RedirectResponse
    {
        try {
            $user = auth()->user();

            $validated = request()->validate([
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:500',
                'location' => 'nullable|string|max:255',
                'field_of_study' => 'nullable|string|max:255',
                'skills' => 'nullable|string|max:1000',
                'linkedin_url' => 'nullable|url|max:255',
                'github_url' => 'nullable|url|max:255',
                'portfolio_url' => 'nullable|url|max:255',
            ]);

            // Handle profile photo
            if (request()->hasFile('profile_photo')) {
                try {
                    if ($user->profile_photo) {
                        Storage::disk('public')->delete($user->profile_photo);
                    }

                    $path = request()->file('profile_photo')->store('profile-photos', 'public');
                    $validated['profile_photo'] = $path;
                } catch (\Exception $e) {
                    \Log::error('Profile photo upload error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error uploading photo.');
                }
            }

            $user->update($validated);

            return redirect()->route('student.profile.index')
                ->with('success', 'Profile updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Student profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }
}
