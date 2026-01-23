<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CompanyScopeMiddleware
{
    /**
     * Handle an incoming request with company scope validation
     * Ensures that users can only access resources within their company
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Safety check: Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this resource.');
        }

        $user = auth()->user();

        // Safety check: Ensure user has a role
        if (!$user->role) {
            abort(403, 'Access denied: User role not assigned.');
        }

        // Master admin has access to everything - bypass company scope
        if ($user->role->role_key === 'master_admin') {
            return $next($request);
        }

        // Safety check: Company-scoped roles must have a company_id
        $companyScopedRoles = ['company_admin', 'artist'];
        if (in_array($user->role->role_key, $companyScopedRoles) && !$user->company_id) {
            abort(403, 'Access denied: Your account must be associated with a company.');
        }

        // Check resource-level company scope
        $this->validateResourceCompanyScope($request, $user);

        return $next($request);
    }

    /**
     * Validate that the requested resource belongs to the user's company
     */
    private function validateResourceCompanyScope(Request $request, $user): void
    {
        // Skip validation for non-company-scoped roles
        if (in_array($user->role->role_key, ['customer', 'affiliate'])) {
            return;
        }

        // Get resource from route parameters
        $resource = $request->route('artist')
                    ?? $request->route('booking')
                    ?? $request->route('user')
                    ?? $request->route('company')
                    ?? null;

        if (!$resource) {
            return; // No resource to validate
        }

        // Get company_id from the resource
        $resourceCompanyId = null;

        if (isset($resource->company_id)) {
            $resourceCompanyId = $resource->company_id;
        } elseif (method_exists($resource, 'company') && $resource->company) {
            $resourceCompanyId = $resource->company->id;
        }

        // Validate company scope
        if ($resourceCompanyId && $resourceCompanyId !== $user->company_id) {
            Log::warning('Company scope violation attempt', [
                'user_id' => $user->id,
                'user_company_id' => $user->company_id,
                'resource_company_id' => $resourceCompanyId,
                'resource_type' => get_class($resource),
                'resource_id' => $resource->id ?? null,
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);

            abort(403, 'Access denied: You do not have permission to access resources outside your company.');
        }
    }
}
