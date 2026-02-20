<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $title = 'Activity Logs';
        $user = Auth::user();

        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        $roleKey = $user->role->role_key;
        if ($roleKey === 'master_admin') {
            // See all logs
        } elseif ($roleKey === 'company_admin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_key')) {
            $query->where('event_key', 'like', '%' . $request->event_key . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        $actions = ActivityLog::distinct()->pluck('action');
        $categories = ActivityLog::query()->select('category')->distinct()->pluck('category');
        $severities = ActivityLog::query()->select('severity')->distinct()->pluck('severity');
        $statuses = ActivityLog::query()->select('status')->distinct()->pluck('status')->filter();

        $flowGroups = $logs->getCollection()
            ->groupBy(function ($log) {
                return $log->correlation_key ?: ('request:' . ($log->request_id ?? $log->id));
            })
            ->map(function ($group, $key) {
                return [
                    'key' => $key,
                    'count' => $group->count(),
                    'first_at' => optional($group->last())->created_at,
                    'last_at' => optional($group->first())->created_at,
                    'items' => $group->take(6),
                ];
            })
            ->values();

        return view('dashboard.pages.activity-logs.index', compact(
            'title',
            'logs',
            'actions',
            'categories',
            'severities',
            'statuses',
            'flowGroups'
        ));
    }
    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        if ($roleKey === 'company_admin') {
            if (!$activityLog->user || $activityLog->user->company_id !== $user->company_id) {
                abort(403, 'You can only view activity logs for your company.');
            }
        } elseif ($roleKey !== 'master_admin' && $activityLog->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $title = 'Activity Log Details';
        return view('dashboard.pages.activity-logs.show', compact('title', 'activityLog'));
    }

    /**
     * Clear old activity logs
     */
    public function clearOld(Request $request)
    {
        return redirect()->back()->with('info', 'Activity log cleanup is disabled. Logs are retained permanently.');
    }
}
