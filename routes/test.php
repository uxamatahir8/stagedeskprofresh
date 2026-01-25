<?php

use Illuminate\Support\Facades\Route;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth')->get('/test/create-notification', function () {
    $user = Auth::user();

    // Create different types of test notifications
    $notifications = [
        [
            'title' => 'New Booking Request',
            'message' => 'A new booking request has been received for your review.',
            'type' => 'booking',
            'link' => route('dashboard')
        ],
        [
            'title' => 'Payment Received',
            'message' => 'Payment of $500 has been received for Booking #1234.',
            'type' => 'payment',
            'link' => route('dashboard')
        ],
        [
            'title' => 'New Message',
            'message' => 'You have a new message from a customer.',
            'type' => 'message',
            'link' => route('dashboard')
        ],
        [
            'title' => 'New Review',
            'message' => 'You received a 5-star review from a customer.',
            'type' => 'review',
            'link' => route('dashboard')
        ],
        [
            'title' => 'System Update',
            'message' => 'The system has been updated successfully.',
            'type' => 'general',
            'link' => null
        ]
    ];

    // Create all test notifications
    foreach ($notifications as $notificationData) {
        Notification::create([
            'user_id' => $user->id,
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'type' => $notificationData['type'],
            'link' => $notificationData['link'],
            'is_read' => false
        ]);
    }

    return redirect()->route('notifications.index')->with('success', count($notifications) . ' test notifications created!');
});
