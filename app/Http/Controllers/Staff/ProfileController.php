<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show staff profile
     */
    public function show()
    {
        $user = auth()->user();

        return view('users.staff.profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Edit staff profile
     */
    public function edit()
    {
        $user = auth()->user();

        return view('users.staff.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update staff profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'staff_department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50|unique:staff_profiles,employee_id,' . auth()->id() . ',user_id',
        ]);

        try {
            $user = auth()->user();

            // ✅ Update User table
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            // ✅ Update or create StaffProfile
            $user->staffProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $request->phone,
                    'staff_department' => $request->staff_department,
                    'position' => $request->position,
                    'employee_id' => $request->employee_id,
                ]
            );

            return redirect()->route('staff.profile')
                ->with('success', '✓ Profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
}
