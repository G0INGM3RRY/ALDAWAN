<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployerProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated employer users
        if (Auth::check() && Auth::user()->role === 'employer') {
            $user = Auth::user();
            $profile = $user->employerProfile;
            
            // Check if employer profile exists and has employer_type set
            if (!$profile || !$profile->employer_type) {
                // Allow access to profile completion routes
                $allowedRoutes = [
                    'employers.complete',
                    'employers.update',
                    'logout'
                ];
                
                if (!in_array($request->route()->getName(), $allowedRoutes)) {
                    return redirect()->route('employers.complete')
                        ->with('warning', 'Please complete your employer profile by selecting your employer type.');
                }
            }
        }
        
        return $next($request);
    }
}
