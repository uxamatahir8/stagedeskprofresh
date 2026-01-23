<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // List all bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Show form to create a new booking
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');

    // Store a new booking
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Show a specific booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Show form to edit a booking
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');

    // Update a specific booking
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');

    // Delete a specific booking
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});
