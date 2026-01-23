<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request with role-based access control
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed role keys (e.g., 'master_admin', 'company_admin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Safety check: Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this resource.');
        }

        $user = Auth::user();

        // Safety check: Ensure user has a role assigned
        if (!$user->role) {
            abort(403, 'Access denied: User role not assigned. Please contact administrator.');
        }

        $roleKey = $user->role->role_key;

        // Check if user's role is in the list of allowed roles
        if (in_array($roleKey, $roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt
        \Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $roleKey,
            'required_roles' => $roles,
            'url' => $request->fullUrl(),
            'ip' => $request->ip()
        ]);

        abort(403, 'Unauthorized: Your role (' . ($user->role->name ?? 'Unknown') . ') does not have permission to access this resource.');
    }
}