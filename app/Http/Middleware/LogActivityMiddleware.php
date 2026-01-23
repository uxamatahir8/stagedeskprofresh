<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users
        if (Auth::check()) {
            // Determine action based on HTTP method and route
            $action = $this->determineAction($request);

            // Skip logging for certain routes
            if (!$this->shouldSkipLogging($request)) {
                ActivityLog::log(
                    $action,
                    null,
                    $this->getDescription($request),
                    [
                        'method' => $request->method(),
                        'url' => $request->fullUrl(),
                        'route' => $request->route()?->getName(),
                    ]
                );
            }
        }

        return $response;
    }

    private function determineAction(Request $request): string
    {
        $method = $request->method();

        return match($method) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access',
        };
    }

    private function getDescription(Request $request): string
    {
        $routeName = $request->route()?->getName() ?? 'unknown';
        $method = $request->method();

        return "User performed {$method} request to {$routeName}";
    }

    private function shouldSkipLogging(Request $request): bool
    {
        $skipRoutes = [
            'notifications.unread',
            'notifications.read',
        ];

        $routeName = $request->route()?->getName();

        return in_array($routeName, $skipRoutes) ||
               str_contains($request->path(), 'api/') ||
               str_contains($request->path(), '_debugbar');
    }
}
