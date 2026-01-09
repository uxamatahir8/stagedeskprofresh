<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {

            if (!Auth::check()) {
                return;
            }

            $notifications = Notification::where('user_id', Auth::id())
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            $view->with([
                'topbarNotifications' => $notifications,
                'unreadNotificationCount' => $unreadCount,
            ]);
        });
    }
}
