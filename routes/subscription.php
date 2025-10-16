<?php

use App\Http\Controllers\CompanySubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('/subscriptions', [CompanySubscriptionController::class, 'index'])->name('subscriptions');
    Route::get('subscription/create/{id?}', [CompanySubscriptionController::class, 'create'])
        ->name('subscription.create');


    Route::post('/subscription/store', [CompanySubscriptionController::class, 'store'])->name('subscription.store');
});