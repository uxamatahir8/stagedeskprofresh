<?php

namespace App\Http\Middleware;

use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuspiciousActivityDetector
{
    /**
     * Patterns that might indicate malicious activity
     */
    protected $suspiciousPatterns = [
        'sql' => ['union', 'select', 'insert', 'update', 'delete', 'drop', 'exec', '--', ';--'],
        'xss' => ['<script', 'javascript:', 'onerror=', 'onload=', 'eval(', 'alert('],
        'file_inclusion' => ['../', '....', '/etc/passwd', '/proc/self'],
        'command_injection' => ['&&', '||', ';', '|', '`', '$(']
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for suspicious patterns in request
        if ($this->detectSuspiciousInput($request)) {
            SecurityLog::logEvent(
                'suspicious_request',
                'Suspicious input pattern detected in request',
                'critical',
                auth()->id(),
                [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'input' => $this->sanitizeForLog($request->all())
                ]
            );

            // Optionally block the request
            // return response()->json(['error' => 'Suspicious activity detected'], 403);
        }

        // Check for suspicious user agent
        if ($this->isSuspiciousUserAgent($request->userAgent())) {
            SecurityLog::logEvent(
                'suspicious_user_agent',
                'Suspicious user agent detected',
                'warning',
                auth()->id(),
                ['user_agent' => $request->userAgent()]
            );
        }

        return $next($request);
    }

    /**
     * Detect suspicious input patterns
     */
    protected function detectSuspiciousInput(Request $request)
    {
        $inputString = strtolower(json_encode($request->all()));

        foreach ($this->suspiciousPatterns as $type => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($inputString, $pattern)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check for suspicious user agent
     */
    protected function isSuspiciousUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return true;
        }

        $suspiciousAgents = [
            'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget', 'python-requests'
        ];

        $lowerAgent = strtolower($userAgent);
        foreach ($suspiciousAgents as $agent) {
            if (str_contains($lowerAgent, $agent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitize data for logging (remove sensitive info)
     */
    protected function sanitizeForLog(array $data)
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'secret', 'api_key'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[REDACTED]';
            }
        }

        return $data;
    }
}
