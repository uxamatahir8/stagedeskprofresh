<?php

use Illuminate\Support\Facades\Route;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth')->get('/test/create-notification', function () {
    $user = Auth::user();

    $notification = Notification::create([
        'user_id' => $user->id,
        'title' => 'Test Booking Notification',
        'message' => 'A new booking request has been received for your review.',
        'link' => route('dashboard'),
        'is_read' => false
    ]);

    return redirect()->route('notifications.index')->with('success', 'Test notification created!');
})->name('test.notification');
