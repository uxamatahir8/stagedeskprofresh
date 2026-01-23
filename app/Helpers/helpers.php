<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasRole')) {
    /**
     * Check if the current user has any of the specified roles
     *
     * @param string ...$roles Role keys to check (e.g., 'master_admin', 'company_admin')
     * @return bool
     */
    function hasRole(...$roles): bool
    {
        $user = Auth::user();
        if (!$user || !$user->role) {
            return false;
        }
        return in_array($user->role->role_key, $roles);
    }
}

if (!function_exists('isMasterAdmin')) {
    /**
     * Check if the current user is a Master Admin
     *
     * @return bool
     */
    function isMasterAdmin(): bool
    {
        return hasRole('master_admin');
    }
}

if (!function_exists('isCompanyAdmin')) {
    /**
     * Check if the current user is a Company Admin
     *
     * @return bool
     */
    function isCompanyAdmin(): bool
    {
        return hasRole('company_admin');
    }
}

if (!function_exists('isArtist')) {
    /**
     * Check if the current user is an Artist
     *
     * @return bool
     */
    function isArtist(): bool
    {
        return hasRole('artist');
    }
}

if (!function_exists('isCustomer')) {
    /**
     * Check if the current user is a Customer
     *
     * @return bool
     */
    function isCustomer(): bool
    {
        return hasRole('customer');
    }
}

if (!function_exists('isAffiliate')) {
    /**
     * Check if the current user is an Affiliate
     *
     * @return bool
     */
    function isAffiliate(): bool
    {
        return hasRole('affiliate');
    }
}

if (!function_exists('canAccessCompanyResource')) {
    /**
     * Check if the current user can access a resource from a specific company
     *
     * @param int $companyId Company ID to check
     * @return bool
     */
    function canAccessCompanyResource(int $companyId): bool
    {
        if (isMasterAdmin()) {
            return true; // Master admin has access to all companies
        }

        $user = Auth::user();
        return $user && $user->company_id === $companyId;
    }
}

if (!function_exists('getCurrentUserCompanyId')) {
    /**
     * Get the current user's company ID
     *
     * @return int|null
     */
    function getCurrentUserCompanyId(): ?int
    {
        $user = Auth::user();
        return $user ? $user->company_id : null;
    }
}

if (!function_exists('settings')) {
    function settings()
    {
        return Settings::query()->pluck('value', 'key')->toArray();
    }
}

if (!function_exists('settings_get')) {
    function settings_get($key, $default = null)
    {
        $all = settings();
        return $all[$key] ?? $default;
    }
}
