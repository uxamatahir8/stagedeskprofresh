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
        try {
            if (Schema::hasTable('settings')) {
                $settings = Settings::query()->pluck('value', 'key')->toArray();
                View::share('share', $settings);
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available or settings table doesn't exist
            // This prevents errors during migrations, testing, or initial setup
        }
    }
}
