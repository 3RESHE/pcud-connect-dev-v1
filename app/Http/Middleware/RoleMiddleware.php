<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware usage:
     * Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
     *     ->middleware('role:admin');
     *
     * Multiple roles:
     * ->middleware('role:admin,staff')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // If user is not authenticated, redirect to login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user's role is in the allowed roles
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // User doesn't have the required role
        return abort(403, 'Unauthorized - insufficient permissions for this resource.');
    }
}
