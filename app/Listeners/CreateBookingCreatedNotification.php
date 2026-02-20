<?php

namespace App\Listeners;

use App\Constants\NotificationType;
use App\Events\BookingCreated;
use App\Services\NotificationService;
use App\Models\User;

class CreateBookingCreatedNotification
{
    public function __construct(private NotificationService $notificationService)
    {
    }

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

        // Notify company admins for this company
        $companyAdminIds = User::query()
            ->where('company_id', $company->id)
            ->whereHas('role', fn ($q) => $q->where('role_key', 'company_admin'))
            ->pluck('id');

        foreach ($companyAdminIds as $userId) {
            $this->notificationService->createForUser(
                (int) $userId,
                'New Booking Created',
                $message,
                NotificationType::BOOKING_CREATED,
                'booking',
                $link,
                3,
                $company->id,
                [
                    'booking_id' => $booking->id,
                    'company_id' => $company->id,
                ]
            );
        }

        // If no company admins, notify first master admin so the booking is not missed
        if ($companyAdminIds->isEmpty()) {
            $masterAdmin = User::query()
                ->whereHas('role', fn ($q) => $q->where('role_key', 'master_admin'))
                ->first();
            if ($masterAdmin) {
                $this->notificationService->createForUser(
                    $masterAdmin->id,
                    'New Booking Created',
                    $message,
                    NotificationType::BOOKING_CREATED,
                    'booking',
                    $link,
                    3,
                    $company->id,
                    [
                        'booking_id' => $booking->id,
                        'company_id' => $company->id,
                    ]
                );
            }
        }
    }
}
