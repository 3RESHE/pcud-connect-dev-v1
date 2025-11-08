<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentProfileComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Check if user is a student
        if ($user->role !== 'student') {
            return $next($request);
        }

        // Check if student has completed basic profile
        $profile = $user->studentProfile;

        if (!$profile || is_null($profile->headline) || is_null($profile->phone)) {
            // Redirect to profile edit with message
            return redirect()
                ->route('student.profile.edit')
                ->with('warning', 'Please complete your basic profile information first to explore opportunities.');
        }

        return $next($request);
    }
}
