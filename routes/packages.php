<?php

use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('/packages', [PackageController::class, 'index'])->name('packages');
    Route::get('/package/create', [PackageController::class, 'create'])->name('package.create');
});