<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Redirects authenticated users to their role-specific dashboard.
     * Used for public pages that should only be accessible to guests.
     *
     * Middleware usage:
     * Route::get('/', [WelcomeController::class, 'index'])
     *     ->middleware('guest');
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect to role-specific dashboard
                return $this->redirectToDashboard($user->role);
            }
        }

        return $next($request);
    }

    /**
     * Redirect user to their role-specific dashboard
     */
    private function redirectToDashboard(string $role): Response
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'partner' => redirect()->route('partner.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'alumni' => redirect()->route('alumni.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
