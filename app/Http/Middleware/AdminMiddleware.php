<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Checks the user is:
     *   1. Authenticated
     *   2. Has admin role/flag on the users table
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in at all → redirect to admin login
        if (!Auth::check()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $user = Auth::user();

        // Check admin flag — supports both common column conventions.
        // Uses 'is_admin' (bool) OR 'role' (string) column on users table.
        // Adjust the condition below to match YOUR users table column.
        $isAdmin = match(true) {
            isset($user->role)              => in_array($user->role, ['admin', 'super_admin']),
            default                         => false,
        };

        if (!$isAdmin) {
            // Logged in but not an admin → log them out and reject
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Access denied. You do not have admin privileges.');
        }

        return $next($request);
    }
}