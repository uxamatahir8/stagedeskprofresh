<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function index()
    {
        $title = 'Notifications';
        $query = $this->notificationService->scopedQueryForUser(Auth::user());

        if (request()->filled('category')) {
            $query->where('category', request('category'));
        }

        if (request()->filled('priority')) {
            $query->where('priority', (int) request('priority'));
        }

        if (request()->filled('status')) {
            if (request('status') === 'unread') {
                $query->where('is_read', false);
            } elseif (request('status') === 'read') {
                $query->where('is_read', true);
            }
        }

        $notifications = $query
            ->orderByDesc('priority')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = $this->notificationService->scopedQueryForUser(Auth::user())
            ->where('is_read', false)
            ->count();

        $categoryCounts = $this->notificationService->scopedQueryForUser(Auth::user())
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        return view('dashboard.pages.notifications.index', compact('title', 'notifications', 'unreadCount', 'categoryCounts'));
    }

    public function markAsRead(Notification $notification)
    {
        $allowed = $this->notificationService
            ->scopedQueryForUser(Auth::user())
            ->where('id', $notification->id)
            ->exists();

        if (!$allowed) {
            return abort(403, 'Unauthorized');
        }

        $notification->update(['is_read' => true]);
        ActivityLogger::info(
            'notification.read.single',
            'Notification marked as read',
            [
                'category' => 'notification',
                'action' => 'read',
                'target' => $notification,
            ]
        );

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $updated = $this->notificationService->scopedQueryForUser(Auth::user())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        ActivityLogger::info(
            'notification.read.all',
            'All notifications marked as read',
            [
                'category' => 'notification',
                'action' => 'read_all',
                'metadata' => ['count' => $updated],
            ]
        );

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function getUnread()
    {
        $notifications = $this->notificationService->scopedQueryForUser(Auth::user())
            ->where('is_read', false)
            ->orderByDesc('priority')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'category' => $notification->category,
                    'priority' => $notification->priority,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->category, $notification->type, $notification->title)
                ];
            });

        $count = $this->notificationService->scopedQueryForUser(Auth::user())
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
        $unreadCount = $this->notificationService->scopedQueryForUser(Auth::user())
            ->where('is_read', false)
            ->count();

        $latestNotifications = $this->notificationService->scopedQueryForUser(Auth::user())
            ->where('is_read', false)
            ->orderByDesc('priority')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'category' => $notification->category,
                    'priority' => $notification->priority,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->category, $notification->type, $notification->title)
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $latestNotifications
        ]);
    }

    private function getNotificationIcon(?string $category, ?string $type, ?string $title): string
    {
        $category = strtolower((string) $category);
        $type = strtolower((string) $type);
        $title = strtolower($title ?? '');

        if (str_contains($category, 'security') || str_contains($type, 'security')) {
            return 'shield-alert';
        }
        if (str_contains($category, 'auth')) {
            return 'user-check';
        }
        if (str_contains($category, 'email')) {
            return 'mail';
        }
        if (str_contains($category, 'payment')) {
            return 'wallet';
        }
        if (str_contains($category, 'booking')) {
            return 'calendar-check';
        }
        if (str_contains($category, 'review')) {
            return 'star';
        }
        if (str_contains($category, 'profile')) {
            return 'user-circle';
        }

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
        $allowed = $this->notificationService
            ->scopedQueryForUser(Auth::user())
            ->where('id', $notification->id)
            ->exists();

        if (!$allowed) {
            return abort(403, 'Unauthorized');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    public function destroyAll()
    {
        $this->notificationService->scopedQueryForUser(Auth::user())->delete();

        return redirect()->back()->with('success', 'All notifications deleted.');
    }
}
