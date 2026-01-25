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

            if (! Auth::check()) {
                return;
            }

            $userId = Auth::user()->id;
            $roleKey = Auth::user()->role->role_key;

            // Master admin sees all notifications
            if ($roleKey === 'master_admin') {
                $notifications = Notification::orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $unreadCount = Notification::where('is_read', false)->count();
            } else {
                // Other users see only their own notifications
                $notifications = Notification::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $unreadCount = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'topbarNotifications'     => $notifications,
                'unreadNotificationCount' => $unreadCount,
            ]);
        });
    }
}
