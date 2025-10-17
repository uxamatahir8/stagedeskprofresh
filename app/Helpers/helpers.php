<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasRole')) {
    function hasRole(...$roles)
    {
        $user = Auth::user();
        return $user && in_array($user->role->role_key, $roles);
    }
}
