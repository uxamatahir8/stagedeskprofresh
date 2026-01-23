<?php

use App\Http\Controllers\Artist\ArtistPortalController;
use Illuminate\Support\Facades\Route;

/**
 * Artist Portal Routes
 * Accessible only to users with artist role
 */
Route::middleware(['auth', 'role:artist'])->prefix('artist')->name('artist.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ArtistPortalController::class, 'dashboard'])->name('dashboard');

    // My Bookings
    Route::get('/bookings', [ArtistPortalController::class, 'myBookings'])->name('bookings');
    Route::get('/bookings/{booking}', [ArtistPortalController::class, 'bookingDetails'])->name('bookings.details');

    // Booking Actions
    Route::post('/bookings/{booking}/accept', [ArtistPortalController::class, 'acceptBooking'])->name('bookings.accept');
    Route::post('/bookings/{booking}/reject', [ArtistPortalController::class, 'rejectBooking'])->name('bookings.reject');
    Route::post('/bookings/{booking}/complete', [ArtistPortalController::class, 'markBookingCompleted'])->name('bookings.complete');

    // Earnings
    Route::get('/earnings', [ArtistPortalController::class, 'earnings'])->name('earnings');

    // Reviews
    Route::get('/reviews', [ArtistPortalController::class, 'reviews'])->name('reviews');

    // Availability
    Route::get('/availability', [ArtistPortalController::class, 'availability'])->name('availability');
    Route::post('/availability', [ArtistPortalController::class, 'updateAvailability'])->name('availability.update');

    // Profile
    Route::get('/profile', [ArtistPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [ArtistPortalController::class, 'updateProfile'])->name('profile.update');
});
