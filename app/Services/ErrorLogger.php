<?php

namespace App\Services;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ErrorLogger
{
    /**
     * Log an error to the database
     */
    public static function log(
        \Throwable $exception,
        string $type = 'error',
        string $level = 'error',
        ?array $context = null
    ): ?ErrorLog {
        try {
            $errorLog = ErrorLog::create([
                'type' => $type,
                'level' => $level,
                'message' => $exception->getMessage(),
                'exception_class' => get_class($exception),
                'exception_message' => $exception->getMessage(),
                'stack_trace' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'request_data' => self::sanitizeRequestData(Request::all()),
                'user_id' => Auth::id(),
                'user_agent' => Request::userAgent(),
                'ip_address' => Request::ip(),
                'controller' => self::getController(),
                'action' => self::getAction(),
                'context' => $context,
            ]);

            // Also log to Laravel log
            \Log::error($exception->getMessage(), [
                'error_log_id' => $errorLog->id,
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return $errorLog;
        } catch (\Exception $e) {
            // Fallback to regular logging if database logging fails
            \Log::critical('Failed to log error to database: ' . $e->getMessage());
            \Log::error($exception->getMessage(), [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            return null;
        }
    }

    /**
     * Log a custom error message
     */
    public static function logMessage(
        string $message,
        string $type = 'error',
        string $level = 'error',
        ?array $context = null
    ): ?ErrorLog {
        try {
            return ErrorLog::create([
                'type' => $type,
                'level' => $level,
                'message' => $message,
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'request_data' => self::sanitizeRequestData(Request::all()),
                'user_id' => Auth::id(),
                'user_agent' => Request::userAgent(),
                'ip_address' => Request::ip(),
                'controller' => self::getController(),
                'action' => self::getAction(),
                'context' => $context,
            ]);
        } catch (\Exception $e) {
            \Log::critical('Failed to log message to database: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current controller name
     */
    private static function getController(): ?string
    {
        $route = Request::route();
        if ($route) {
            $action = $route->getActionName();
            if ($action && str_contains($action, '@')) {
                return explode('@', $action)[0];
            }
        }
        return null;
    }

    /**
     * Get current action name
     */
    private static function getAction(): ?string
    {
        $route = Request::route();
        if ($route) {
            $action = $route->getActionName();
            if ($action && str_contains($action, '@')) {
                return explode('@', $action)[1];
            }
        }
        return null;
    }

    /**
     * Sanitize request data (remove sensitive info)
     */
    private static function sanitizeRequestData(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_token',
            'secret',
            'credit_card',
            'card_number',
            'cvv',
            'ssn',
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Mark error as resolved
     */
    public static function resolve(int $errorLogId, ?string $notes = null): bool
    {
        try {
            $errorLog = ErrorLog::find($errorLogId);
            if ($errorLog) {
                $errorLog->update([
                    'is_resolved' => true,
                    'resolved_at' => now(),
                    'resolved_by' => Auth::id(),
                    'resolution_notes' => $notes,
                ]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to resolve error log: ' . $e->getMessage());
            return false;
        }
    }
}
