<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // List all bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Show form to create a new booking (not for artists)
    Route::get('/bookings/create', [BookingController::class, 'create'])
        ->name('bookings.create')
        ->middleware('role:master_admin,company_admin,customer');

    // Store a new booking (not for artists)
    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store')
        ->middleware('role:master_admin,company_admin,customer');

    // Show a specific booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Show form to edit a booking (not for artists)
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])
        ->name('bookings.edit')
        ->middleware('role:master_admin,company_admin,customer');

    // Update a specific booking (not for artists)
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])
        ->name('bookings.update')
        ->middleware('role:master_admin,company_admin,customer');

    // Delete a specific booking (Admin only)
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
        ->name('bookings.destroy')
        ->middleware('role:master_admin,company_admin');

    // Assign artist to booking (Admin only)
    Route::post('/bookings/{booking}/assign-artist', [BookingController::class, 'assignArtist'])
        ->name('bookings.assign-artist')
        ->middleware('role:master_admin,company_admin');

    // Mark booking as completed
    Route::post('/bookings/{booking}/mark-completed', [BookingController::class, 'markCompleted'])
        ->name('bookings.mark-completed')
        ->middleware('role:master_admin,company_admin,artist');

    // Cancel booking
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel')
        ->middleware('role:master_admin,company_admin,customer');
});
