<?php
namespace App\Providers;

use App\Services\NotificationService;
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

            /** @var NotificationService $notificationService */
            $notificationService = app(NotificationService::class);
            $scoped = $notificationService->scopedQueryForUser(Auth::user());

            $notifications = (clone $scoped)
                ->orderByDesc('priority')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            $unreadCount = (clone $scoped)
                ->where('is_read', false)
                ->count();

            $view->with([
                'topbarNotifications'     => $notifications,
                'unreadNotificationCount' => $unreadCount,
            ]);
        });
    }
}
