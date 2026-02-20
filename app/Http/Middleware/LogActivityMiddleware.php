<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        if (!$request->attributes->has('request_id')) {
            $request->attributes->set('request_id', (string) Str::uuid());
        }

        $response = $next($request);

        // Only log for authenticated users
        if (Auth::check()) {
            // Determine action based on HTTP method and route
            $action = $this->determineAction($request);

            // Skip logging for certain routes
            if (!$this->shouldSkipLogging($request)) {
                $statusCode = $response->getStatusCode();
                $severity = $statusCode >= 500 ? 'error' : ($statusCode >= 400 ? 'warning' : 'info');

                ActivityLogger::write(
                    $severity,
                    'request.' . strtolower($request->method()) . '.' . $action,
                    $statusCode >= 400 ? 'failed' : 'success',
                    $this->getDescription($request),
                    [
                        'category' => 'system',
                        'action' => $action,
                        'status' => $statusCode >= 400 ? 'failed' : 'success',
                        'method' => $request->method(),
                        'url' => $request->fullUrl(),
                        'route' => $request->route()?->getName(),
                        'context' => [
                            'status_code' => $statusCode,
                        ],
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
            'notifications.refresh',
        ];

        $routeName = $request->route()?->getName();

        return in_array($routeName, $skipRoutes) ||
               str_contains($request->path(), 'api/') ||
               str_contains($request->path(), '_debugbar');
    }
}
