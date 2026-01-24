<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Add your model policies here
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
