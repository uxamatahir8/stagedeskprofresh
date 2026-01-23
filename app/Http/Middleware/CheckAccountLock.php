<?php

namespace App\Http\Middleware;

use App\Services\AuthSecurityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountLock
{
    protected $authSecurity;

    public function __construct(AuthSecurityService $authSecurity)
    {
        $this->authSecurity = $authSecurity;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $this->authSecurity->isAccountLocked($user)) {
            $minutes = $user->locked_until->diffInMinutes(now());

            Auth::logout();

            return redirect()->route('login')
                ->with('error', "Your account is locked due to multiple failed login attempts. Please try again in {$minutes} minutes.");
        }

        return $next($request);
    }
}
