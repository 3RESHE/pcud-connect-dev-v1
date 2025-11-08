<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerProfile;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show partner profile
     */
    public function show()
    {
        $user = auth()->user();
        $profile = $user->partnerProfile;

        return view('users.partner.profile.show', [
            'profile' => $profile,
            'user' => $user,
            'isIncomplete' => is_null($profile) || is_null($profile->company_name),
        ]);
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->partnerProfile;

        return view('users.partner.profile.edit', [
            'profile' => $profile,
            'user' => $user,
        ]);
    }

    /**
     * Update partner profile
     */
    /**
     * Update partner profile
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            // Check if user has profile, if not create it
            $profile = $user->partnerProfile;
            if (!$profile) {
                $profile = $user->partnerProfile()->create();
            }

            // Validate input
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'industry' => 'required|string|max:100',
                'company_size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'description' => 'required|string|min:50|max:2000',
                'contact_person' => 'required|string|max:255',
                'contact_title' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'website' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'company_name.required' => 'Company name is required',
                'description.required' => 'Company description is required',
                'description.min' => 'Description must be at least 50 characters',
                'contact_person.required' => 'Contact person name is required',
                'company_logo.image' => 'Logo must be an image file',
                'company_logo.mimes' => 'Logo must be a JPEG, PNG, JPG, or GIF file',
                'company_logo.max' => 'Logo must not exceed 2MB',
            ]);

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                if ($profile->company_logo && Storage::exists($profile->company_logo)) {
                    Storage::delete($profile->company_logo);
                }

                // Store new logo
                $logo = $request->file('company_logo');
                $path = $logo->store('partner-logos', 'public');
                $validated['company_logo'] = $path;
            }

            // Update profile
            $profile->update($validated);

            // Log activity
            ActivityLogger::log(
                'profile_updated',
                PartnerProfile::class,
                $profile->id,
                $validated,
                "Partner updated company profile: {$validated['company_name']}"
            );

            return response()->json([
                'success' => true,
                'message' => '✅ Profile updated successfully!',
                'redirect' => route('partner.profile.show'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed - please check all required fields',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Partner Profile Update Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
