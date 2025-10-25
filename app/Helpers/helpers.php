<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasRole')) {
    function hasRole(...$roles)
    {
        $user = Auth::user();
        return $user && in_array($user->role->role_key, $roles);
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
