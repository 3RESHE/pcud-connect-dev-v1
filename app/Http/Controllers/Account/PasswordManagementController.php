<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordManagementController extends Controller
{
    /**
     * Show change password form (First login)
     */
    public function showChangePassword()
    {
        $user = auth()->user();

        // Check if user has already changed password
        if (!is_null($user->password_changed_at)) {
            return redirect('/dashboard')
                ->with('info', 'You have already set your password.');
        }

        return view('account.change-password', [
            'isFirstLogin' => is_null($user->password_changed_at)
        ]);
    }

    /**
     * Update password (First login)
     */
    public function updateChangePassword(Request $request)
    {
        try {
            $user = auth()->user();

            // Check if already changed
            if (!is_null($user->password_changed_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already set your password.',
                ], 403);
            }

            // Validate password
            $validated = $request->validate([
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                ],
            ], [
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation does not match',
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain uppercase, lowercase, number, and special character',
            ]);

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now(),
            ]);

            // Log activity
            ActivityLogger::log(
                'password_changed',
                $user::class,
                $user->id,
                null,
                'User changed password on first login'
            );

            return response()->json([
                'success' => true,
                'message' => 'Password set successfully. Redirecting to dashboard...',
                'redirect' => '/dashboard'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show update password form (General password change)
     */
    public function showUpdatePassword()
    {
        return view('account.update-password');
    }

    /**
     * Update password (General - not first login)
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();

            // Validate
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                    'different:current_password',
                ],
            ], [
                'current_password.required' => 'Current password is required',
                'password.required' => 'New password is required',
                'password.confirmed' => 'Password confirmation does not match',
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain uppercase, lowercase, number, and special character',
                'password.different' => 'New password must be different from current password',
            ]);

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']],
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Log activity
            ActivityLogger::log(
                'password_changed',
                $user::class,
                $user->id,
                null,
                'User changed their password'
            );

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage(),
            ], 500);
        }
    }
}
