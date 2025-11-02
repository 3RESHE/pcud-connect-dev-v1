<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated user's account is still active (is_active = true).
     * If inactive, logs them out and redirects to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated
        if ($request->user()) {
            // Check if user is active
            if (!$request->user()->is_active) {
                // Log the user out
                Auth::logout();

                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect to login with message
                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated. Please contact administration.');
            }
        }

        return $next($request);
    }
}
