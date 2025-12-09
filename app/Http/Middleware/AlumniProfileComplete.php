<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlumniProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get or create alumni profile
        $profile = $user->alumniProfile;

        if (!$profile) {
            // Redirect to profile edit if not created
            return redirect()->route('alumni.profile.edit')
                ->with('warning', 'Please complete your alumni profile to continue.');
        }

        // Check if profile is complete
        if (!$this->isProfileComplete($profile)) {
            // Calculate completion percentage
            $completion = $profile->getProfileCompletionPercentage();

            return redirect()->route('alumni.profile.edit')
                ->with('warning', "Your profile is only {$completion}% complete. Please complete all required fields.")
                ->with('completion_percentage', $completion);
        }

        return $next($request);
    }

    /**
     * Check if profile has all required fields completed
     */
    private function isProfileComplete($profile): bool
    {
        return 
               !empty($profile->personal_email) &&
               !empty($profile->phone);
    }
}
