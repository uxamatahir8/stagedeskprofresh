<?php

namespace App\Policies;

use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, BookingRequest $booking): bool
    {
        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'customer' => $booking->user_id === $user->id,
            'artist' => $booking->assigned_artist_id === optional($user->artist)->id,
            default => false,
        };
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, BookingRequest $booking): bool
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return false;
        }

        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'customer' => $booking->user_id === $user->id && $booking->status === 'pending',
            default => false,
        };
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, BookingRequest $booking): bool
    {
        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            default => false,
        };
    }

    /**
     * Determine whether the user can assign an artist to the booking.
     */
    public function assignArtist(User $user, BookingRequest $booking): bool
    {
        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            default => false,
        };
    }

    /**
     * Determine whether the user can mark the booking as completed.
     */
    public function markCompleted(User $user, BookingRequest $booking): bool
    {
        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'artist' => $booking->assigned_artist_id === optional($user->artist)->id,
            default => false,
        };
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, BookingRequest $booking): bool
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return false;
        }

        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'customer' => $booking->user_id === $user->id,
            default => false,
        };
    }
}
