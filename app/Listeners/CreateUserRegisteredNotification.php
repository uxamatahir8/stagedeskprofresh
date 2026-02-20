<?php
namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\UserRegistered;
use App\Services\NotificationService;

class CreateUserRegisteredNotification
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->notificationService->createForUser(
            $event->user->id,
            'New User Registered',
            sprintf(
                '%s registered on %s',
                $event->user->name,
                now()->format('d M Y H:i')
            ),
            NotificationType::USER_REGISTERED,
            'auth',
            null,
            2,
            $event->user->company_id,
            [
                'user_id' => $event->user->id,
                'role_id' => $event->user->role_id,
            ]
        );
    }
}
