<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class ErrorHandlerService
{
    /**
     * Handle and log exceptions
     */
    public static function handle(Exception $exception, string $context = 'general'): void
    {
        Log::error("Error in {$context}", [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => Auth::id(),
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Get user-friendly error message
     */
    public static function getUserMessage(Exception $exception): string
    {
        if (config('app.debug')) {
            return $exception->getMessage();
        }

        return match (get_class($exception)) {
            'Illuminate\Database\QueryException' => 'A database error occurred. Please try again.',
            'Illuminate\Auth\AuthenticationException' => 'You must be logged in to perform this action.',
            'Illuminate\Validation\ValidationException' => 'The provided data was invalid.',
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => 'The requested resource was not found.',
            'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException' => 'This action is not allowed.',
            default => 'An error occurred. Please try again later.',
        };
    }

    /**
     * Check if error should be reported
     */
    public static function shouldReport(Exception $exception): bool
    {
        $dontReport = [
            'Illuminate\Auth\AuthenticationException',
            'Illuminate\Validation\ValidationException',
            'Symfony\Component\HttpKernel\Exception\HttpException',
        ];

        return !in_array(get_class($exception), $dontReport);
    }
}
