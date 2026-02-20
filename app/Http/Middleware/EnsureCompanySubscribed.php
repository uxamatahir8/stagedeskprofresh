<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanySubscribed
{
    /**
     * Routes that company_admin can access without a verified subscription (to complete subscription/payment flow).
     */
    protected array $allowedWithoutSubscription = [
        'packages.choose',
        'packages.choose.store',
        'subscriptions.index',
        'subscriptions.create',
        'subscriptions.store',
        'subscriptions.show',
        'payments.index',
        'payments.create',
        'payments.store',
        'payments.show',
        'payment-methods.index',
        'payment-methods.create',
        'payment-methods.store',
        'payment-methods.edit',
        'payment-methods.update',
        'payment-methods.destroy',
        'logout',
        'change-password',
        'update-password',
    ];

    /**
     * Handle an incoming request: redirect company_admin to choose package if not subscribed and payment not verified.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user || $user->role->role_key !== 'company_admin') {
            return $next($request);
        }

        $company = $user->company;
        if (!$company) {
            return redirect()->route('packages.choose')
                ->with('error', 'Your account is not linked to a company. Please contact support.');
        }

        if ($company->hasVerifiedSubscription()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, $this->allowedWithoutSubscription, true)) {
            return $next($request);
        }

        return redirect()->route('packages.choose')
            ->with('info', 'Please choose a subscription package and complete payment to access the dashboard.');
    }
}
