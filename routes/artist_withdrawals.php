<?php

use App\Http\Controllers\ArtistWithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin,company_admin'])->group(function () {
    Route::get('/artist-withdrawals', [ArtistWithdrawalController::class, 'index'])->name('artist-withdrawals.index');
    Route::post('/artist-withdrawals/{withdrawal}/approve', [ArtistWithdrawalController::class, 'approve'])->name('artist-withdrawals.approve');
    Route::post('/artist-withdrawals/{withdrawal}/reject', [ArtistWithdrawalController::class, 'reject'])->name('artist-withdrawals.reject');
    Route::post('/artist-withdrawals/{withdrawal}/mark-paid', [ArtistWithdrawalController::class, 'markPaid'])->name('artist-withdrawals.mark-paid');
});
