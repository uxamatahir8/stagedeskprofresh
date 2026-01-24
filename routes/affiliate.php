<?php

use App\Http\Controllers\Affiliate\AffiliatePortalController;
use Illuminate\Support\Facades\Route;

/**
 * Affiliate Portal Routes
 * Accessible only to users with affiliate role
 */
Route::middleware(['auth', 'role:affiliate'])->prefix('affiliate')->name('affiliate.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AffiliatePortalController::class, 'dashboard'])->name('dashboard');

    // My Referrals
    Route::get('/referrals', [AffiliatePortalController::class, 'referrals'])->name('referrals');

    // My Commissions
    Route::get('/commissions', [AffiliatePortalController::class, 'commissions'])->name('commissions');

    // Request Payout
    Route::post('/payout', [AffiliatePortalController::class, 'requestPayout'])->name('payout.request');

    // Referral Links
    Route::get('/referral-links', [AffiliatePortalController::class, 'referralLinks'])->name('referral-links');
    Route::post('/referral-links', [AffiliatePortalController::class, 'generateReferralLink'])->name('referral-links.generate');

    // Marketing Materials
    Route::get('/marketing-materials', [AffiliatePortalController::class, 'marketingMaterials'])->name('marketing-materials');

    // Performance Report
    Route::get('/performance', [AffiliatePortalController::class, 'performanceReport'])->name('performance');

    // Profile
    Route::get('/profile', [AffiliatePortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [AffiliatePortalController::class, 'updateProfile'])->name('profile.update');
});
