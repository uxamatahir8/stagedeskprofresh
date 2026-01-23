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

        // Filter by user role
        if ($user->role->role_key !== 'master_admin') {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
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

        return view('dashboard.pages.activity-logs.index', compact('title', 'logs', 'actions'));
    }

    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $title = 'Activity Log Details';
        return view('dashboard.pages.activity-logs.show', compact('title', 'activityLog'));
    }

    /**
     * Clear old activity logs
     */
    public function clearOld(Request $request)
    {
        $days = $request->input('days', 90);

        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->back()->with('success', "Activity logs older than {$days} days have been cleared.");
    }
}
