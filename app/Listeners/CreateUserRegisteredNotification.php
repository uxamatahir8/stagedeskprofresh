<?php
namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\UserRegistered;
use App\Models\Notification;

class CreateUserRegisteredNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        //
        Notification::create([
            'user_id' => $event->user->id,
            'title'   => 'New User Registered',
            'message' => sprintf(
                '%s (%s) registered on %s',
                $event->user->name,
                $event->user->role,
                now()->format('d M Y H:i')
            ),
            'type'    => NotificationType::USER_REGISTERED,
            'data'    => serialize([
                'user_id' => $event->user->id,
                'role'    => $event->user->role,
            ]),
        ]);
    }
}
