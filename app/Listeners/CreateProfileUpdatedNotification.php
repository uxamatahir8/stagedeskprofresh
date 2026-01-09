<?php
namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\ProfileUpdated;
use App\Models\Notification;

class CreateProfileUpdatedNotification
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
    public function handle(ProfileUpdated $event): void
    {
        Notification::create([
            'user_id' => $event->user->id,
            'title'   => 'Profile Updated',
            'message' => $event->user->name . ' updated their profile.',
            'type'    => NotificationType::PROFILE_UPDATED,
            'data'    => serialize([
                'user_id' => $event->user->id,
            ]),
        ]);
    }
}
