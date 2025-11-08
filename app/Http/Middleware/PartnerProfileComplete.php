<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PartnerProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is a partner
        if ($user->role !== 'partner') {
            return $next($request);
        }

        // Check if partner has a complete profile
        $profile = $user->partnerProfile;

        if (!$profile || is_null($profile->company_name)) {
            // Redirect to profile edit with message
            return redirect()
                ->route('partner.profile.edit')
                ->with('warning', 'Please complete your company profile first to access this feature.');
        }

        return $next($request);
    }
}
