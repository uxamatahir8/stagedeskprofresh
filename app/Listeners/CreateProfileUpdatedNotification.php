<?php
namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\ProfileUpdated;
use App\Services\NotificationService;

class CreateProfileUpdatedNotification
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ProfileUpdated $event): void
    {
        $this->notificationService->createForUser(
            $event->user->id,
            'Profile Updated',
            $event->user->name . ' updated their profile.',
            NotificationType::PROFILE_UPDATED,
            'profile',
            null,
            1,
            $event->user->company_id,
            ['user_id' => $event->user->id]
        );
    }
}
