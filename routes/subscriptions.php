<?php

use App\Http\Controllers\CompanySubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {
    Route::resource('subscriptions', CompanySubscriptionController::class);
});
