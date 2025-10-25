<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (Schema::hasTable('settings')) {

            $settings = Settings::query()->pluck('value', 'key')->toArray();
            View::share('share', $settings);
        }
    }
}
