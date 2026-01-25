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
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->title)
                ];
            });

        $count = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    public function refresh()
    {
        $unreadCount = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->count();

        $latestNotifications = Notification::where('user_id', Auth::user()->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->title)
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $latestNotifications
        ]);
    }

    private function getNotificationIcon($title)
    {
        $title = strtolower($title ?? '');

        if (str_contains($title, 'booking')) {
            return 'calendar';
        } elseif (str_contains($title, 'payment')) {
            return 'dollar-sign';
        } elseif (str_contains($title, 'message')) {
            return 'message-circle';
        } elseif (str_contains($title, 'review')) {
            return 'star';
        }

        return 'bell';
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
