<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Models\SharedArtist;
use App\Policies\BookingRequestPolicy;
use App\Policies\SharedArtistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        BookingRequest::class => BookingRequestPolicy::class,
        SharedArtist::class => SharedArtistPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
