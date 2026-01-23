<?php

use App\Http\Controllers\Customer\CustomerPortalController;
use Illuminate\Support\Facades\Route;

/**
 * Customer Portal Routes
 * Accessible only to users with customer role
 */
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');

    // My Bookings
    Route::get('/bookings', [CustomerPortalController::class, 'myBookings'])->name('bookings');
    Route::get('/bookings/{booking}', [CustomerPortalController::class, 'bookingDetails'])->name('bookings.details');

    // Create Booking
    Route::get('/bookings/create', [CustomerPortalController::class, 'createBooking'])->name('bookings.create');

    // Cancel Booking
    Route::post('/bookings/{booking}/cancel', [CustomerPortalController::class, 'cancelBooking'])->name('bookings.cancel');

    // My Payments
    Route::get('/payments', [CustomerPortalController::class, 'myPayments'])->name('payments');

    // Reviews
    Route::post('/reviews', [CustomerPortalController::class, 'submitReview'])->name('reviews.submit');
    Route::get('/reviews', [CustomerPortalController::class, 'myReviews'])->name('reviews');

    // Profile
    Route::get('/profile', [CustomerPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');
});
