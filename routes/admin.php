<?php

use App\Http\Controllers\Admin\MasterAdminController;
use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\Admin\ArtistSharingController;
use Illuminate\Support\Facades\Route;

/**
 * Master Admin Routes
 * Accessible only to users with master_admin role
 */
Route::middleware(['auth', 'role:master_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [MasterAdminController::class, 'dashboard'])->name('dashboard');

    // System Overview
    Route::get('/system-overview', [MasterAdminController::class, 'systemOverview'])->name('system-overview');

    // Payment Verification
    Route::post('/verify-payment/{payment}', [MasterAdminController::class, 'verifyPayment'])->name('verify-payment');

    // Activity Logs
    Route::get('/activity-logs', [MasterAdminController::class, 'activityLogs'])->name('activity-logs');
});

/**
 * Company Admin Routes
 * Accessible only to users with company_admin role
 */
Route::middleware(['auth', 'role:company_admin', 'company.scope'])->prefix('company-admin')->name('company.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard'])->name('dashboard');

    // Staff Management
    Route::get('/staff', [CompanyAdminController::class, 'staff'])->name('staff');

    // Artists Management
    Route::get('/artists', [CompanyAdminController::class, 'artists'])->name('artists');

    // Bookings Management
    Route::get('/bookings', [CompanyAdminController::class, 'bookings'])->name('bookings');

    // Assign Artist to Booking
    Route::post('/bookings/{booking}/assign-artist', [CompanyAdminController::class, 'assignArtistToBooking'])->name('bookings.assign-artist');

    // Company Settings
    Route::get('/settings', [CompanyAdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [CompanyAdminController::class, 'updateSettings'])->name('settings.update');

    // Artist Sharing
    Route::get('/artist-sharing', [ArtistSharingController::class, 'index'])->name('artist-sharing');
    Route::post('/artist-sharing/share', [ArtistSharingController::class, 'shareArtist'])->name('artist-sharing.share');
    Route::post('/artist-sharing/{sharedArtist}/accept', [ArtistSharingController::class, 'acceptShare'])->name('artist-sharing.accept');
    Route::post('/artist-sharing/{sharedArtist}/reject', [ArtistSharingController::class, 'rejectShare'])->name('artist-sharing.reject');
    Route::post('/artist-sharing/{sharedArtist}/revoke', [ArtistSharingController::class, 'revokeShare'])->name('artist-sharing.revoke');
});
