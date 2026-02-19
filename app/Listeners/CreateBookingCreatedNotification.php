<?php

namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\BookingCreated;
use App\Models\Notification;
use App\Models\User;

class CreateBookingCreatedNotification
{
    /**
     * Handle the event: notify company admins (and master admins) about the new booking.
     */
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;
        $company = $event->company;
        $eventDate = $booking->event_date ? \Carbon\Carbon::parse($booking->event_date)->format('M j, Y') : 'TBD';
        $customerName = trim($booking->name . ' ' . $booking->surname);
        $message = sprintf(
            'New booking for %s on %s (%s)',
            $customerName,
            $eventDate,
            $company->name
        );
        $link = route('bookings.show', $booking);
        $data = serialize([
            'booking_id' => $booking->id,
            'company_id' => $company->id,
        ]);

        // Notify company admins for this company
        $companyAdminIds = User::query()
            ->where('company_id', $company->id)
            ->whereHas('role', fn ($q) => $q->where('role_key', 'company_admin'))
            ->pluck('id');

        foreach ($companyAdminIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title'   => 'New Booking Created',
                'message' => $message,
                'type'    => NotificationType::BOOKING_CREATED,
                'link'    => $link,
                'data'    => $data,
            ]);
        }

        // If no company admins, notify first master admin so the booking is not missed
        if ($companyAdminIds->isEmpty()) {
            $masterAdmin = User::query()
                ->whereHas('role', fn ($q) => $q->where('role_key', 'master_admin'))
                ->first();
            if ($masterAdmin) {
                Notification::create([
                    'user_id' => $masterAdmin->id,
                    'title'   => 'New Booking Created',
                    'message' => $message,
                    'type'    => NotificationType::BOOKING_CREATED,
                    'link'    => $link,
                    'data'    => $data,
                ]);
            }
        }
    }
}
