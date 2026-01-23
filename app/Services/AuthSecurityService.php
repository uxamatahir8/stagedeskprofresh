<?php

namespace App\Services;

use App\Models\LoginAttempt;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthSecurityService
{
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_DURATION = 30; // minutes
    const PASSWORD_HISTORY_COUNT = 5; // Number of previous passwords to check

    /**
     * Record login attempt
     */
    public function recordLoginAttempt($email, $successful, $userId = null, $failureReason = null)
    {
        LoginAttempt::create([
            'user_id' => $userId,
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => $successful,
            'failure_reason' => $failureReason,
            'attempted_at' => now()
        ]);

        if (!$successful && $userId) {
            $this->incrementFailedAttempts($userId);
        }
    }

    /**
     * Check if account is locked
     */
    public function isAccountLocked(User $user)
    {
        if ($user->locked_until && $user->locked_until->isFuture()) {
            return true;
        }

        // Unlock if lockout period has passed
        if ($user->locked_until && $user->locked_until->isPast()) {
            $user->update([
                'locked_until' => null,
                'failed_login_attempts' => 0
            ]);
            return false;
        }

        return false;
    }

    /**
     * Increment failed login attempts and lock if necessary
     */
    protected function incrementFailedAttempts($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        $attempts = $user->failed_login_attempts + 1;
        $updates = ['failed_login_attempts' => $attempts];

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $updates['locked_until'] = now()->addMinutes(self::LOCKOUT_DURATION);

            SecurityLog::logEvent(
                'account_locked',
                "Account locked due to {$attempts} failed login attempts",
                'critical',
                $userId,
                ['ip_address' => request()->ip(), 'attempts' => $attempts]
            );
        }

        $user->update($updates);
    }

    /**
     * Reset failed attempts on successful login
     */
    public function resetFailedAttempts(User $user)
    {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);

        SecurityLog::logEvent(
            'login_success',
            'User logged in successfully',
            'info',
            $user->id,
            ['ip_address' => request()->ip()]
        );
    }

    /**
     * Check if password was used recently
     */
    public function isPasswordReused(User $user, $newPassword)
    {
        $passwordHistory = $user->passwordHistory()
            ->orderBy('created_at', 'desc')
            ->take(self::PASSWORD_HISTORY_COUNT)
            ->get();

        foreach ($passwordHistory as $history) {
            if (Hash::check($newPassword, $history->password_hash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save password to history
     */
    public function savePasswordHistory(User $user, $passwordHash)
    {
        $user->passwordHistory()->create([
            'password_hash' => $passwordHash,
            'created_at' => now()
        ]);

        // Keep only last N passwords
        $oldPasswords = $user->passwordHistory()
            ->orderBy('created_at', 'desc')
            ->skip(self::PASSWORD_HISTORY_COUNT)
            ->pluck('id');

        if ($oldPasswords->count() > 0) {
            $user->passwordHistory()->whereIn('id', $oldPasswords)->delete();
        }
    }

    /**
     * Detect suspicious activity
     */
    public function detectSuspiciousActivity(User $user)
    {
        $recentIps = LoginAttempt::where('user_id', $user->id)
            ->successful()
            ->where('attempted_at', '>=', now()->subHours(24))
            ->distinct('ip_address')
            ->count();

        // Multiple IPs in short time = suspicious
        if ($recentIps >= 3) {
            SecurityLog::logEvent(
                'suspicious_activity',
                'Multiple IP addresses detected in 24 hours',
                'warning',
                $user->id,
                ['ip_count' => $recentIps]
            );
            return true;
        }

        // Check for unusual login time
        $hourOfDay = now()->hour;
        if ($hourOfDay >= 2 && $hourOfDay <= 5) {
            SecurityLog::logEvent(
                'unusual_login_time',
                'Login detected during unusual hours (2 AM - 5 AM)',
                'warning',
                $user->id,
                ['hour' => $hourOfDay]
            );
        }

        return false;
    }

    /**
     * Check rate limiting
     */
    public function checkRateLimit($key, $maxAttempts = 5, $decayMinutes = 1)
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return [
                'limited' => true,
                'retry_after' => $seconds
            ];
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return [
            'limited' => false,
            'attempts_left' => $maxAttempts - RateLimiter::attempts($key)
        ];
    }

    /**
     * Clear rate limiter
     */
    public function clearRateLimit($key)
    {
        RateLimiter::clear($key);
    }
}
