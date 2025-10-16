<?php

use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('/packages', [PackageController::class, 'index'])->name('packages');
    Route::get('/package/create', [PackageController::class, 'create'])->name('package.create');
    Route::post('/package', [PackageController::class, 'store'])->name('package.store');
    Route::get('/package/{package}/edit', [PackageController::class, 'edit'])->name('package.edit');
    Route::put('/package/{package}', [PackageController::class, 'update'])->name('package.update');
    Route::delete('/package/{package}', [PackageController::class, 'destroy'])->name('package.destroy');
});