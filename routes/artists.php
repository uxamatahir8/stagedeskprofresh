<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin,company_admin'])->group(function () {
    Route::resource('artists', ArtistController::class);
});

