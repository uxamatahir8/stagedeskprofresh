<?php
namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\BookingCreated;
use App\Models\Notification;

class CreateBookingCreatedNotification
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
    public function handle(BookingCreated $event): void
    {
        //
        Notification::create([
            'user_id' => 1, // admin or target user
            'title'   => 'New Booking Created',
            'message' => sprintf(
                '%s added a booking for %s',
                $event->company->name,
                $event->booking->date
            ),
            'type'    => NotificationType::BOOKING_CREATED,
            'link'    => route('users'),
            'data'    => serialize([
                'booking_id' => $event->booking->id,
                'company_id' => $event->company->id,
            ]),
        ]);
    }
}
