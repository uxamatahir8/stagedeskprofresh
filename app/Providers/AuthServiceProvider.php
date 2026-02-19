<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Policies\BookingRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        BookingRequest::class => BookingRequestPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
