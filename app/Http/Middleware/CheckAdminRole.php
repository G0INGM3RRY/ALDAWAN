<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user exists in admin_users table and is active
        $adminUser = DB::table('admin_users')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$adminUser) {
            // User is not an admin or not active
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
