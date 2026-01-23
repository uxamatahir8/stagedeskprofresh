<?php

use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/clear-old', [ActivityLogController::class, 'clearOld'])->name('activity-logs.clear-old')
        ->middleware('role:master_admin');
});
