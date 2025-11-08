<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated
        if ($request->user()) {
            // Check if password_changed_at is NULL (first login)
            if (is_null($request->user()->password_changed_at)) {
                // Allow these routes without redirecting
                if ($request->routeIs('password.change-first', 'password.update-first')) {
                    return $next($request);
                }

                // Redirect to password change page
                return redirect()->route('password.change-first')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
