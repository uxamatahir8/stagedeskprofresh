<?php

use App\Http\Controllers\CompanySubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('/subscriptions', [CompanySubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create/{id?}', [CompanySubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [CompanySubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}', [CompanySubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::get('/subscriptions/{subscription}/edit', [CompanySubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('/subscriptions/{subscription}', [CompanySubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [CompanySubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Legacy route for backward compatibility
    Route::get('subscription/create/{id?}', [CompanySubscriptionController::class, 'create'])->name('subscription.create');
});
