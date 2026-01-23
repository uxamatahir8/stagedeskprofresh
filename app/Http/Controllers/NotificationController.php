<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $title = 'Notifications';
        $notifications = Notification::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard.pages.notifications.index', compact('title', 'notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        $notification->update(['is_read' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function getUnread()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $count = $notifications->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    public function destroyAll()
    {
        Notification::where('user_id', Auth::user()->id)->delete();

        return redirect()->back()->with('success', 'All notifications deleted.');
    }
}
