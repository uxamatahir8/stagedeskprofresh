<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            foreach (glob(base_path('routes/*.php')) as $file) {
                $filename = basename($file);

                if (in_array($filename, ['api.php', 'console.php'])) {
                    continue;
                }

                if (str_starts_with($filename, 'api')) {
                    Route::middleware('api')->prefix('api')->group($file);
                } else {
                    Route::middleware('web')->group($file);
                }
            }
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->append(\App\Http\Middleware\SuspiciousActivityDetector::class);

        // Route middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'company.scope' => \App\Http\Middleware\CompanyScopeMiddleware::class,
            'account.lock' => \App\Http\Middleware\CheckAccountLock::class,
        ]);

        // Add account lock check to web middleware group
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckAccountLock::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
