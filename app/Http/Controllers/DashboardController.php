<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\EventType;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardStatisticsService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $title = 'Dashboard';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Get comprehensive stats based on user role
        $statistics = $this->dashboardService->getStatisticsForRole();

        // Legacy stats for backward compatibility
        $stats = $this->getStats($roleKey);

        // Get recent bookings
        $recentBookings = $this->getRecentBookings($roleKey, 5);

        // Get booking stats for charts
        $bookingStats = $this->getBookingStats($roleKey);

        // Get event type counts
        $eventTypeCounts = $this->getEventTypeCounts($roleKey);

        return view('dashboard.pages.index', compact(
            'title',
            'stats',
            'statistics',
            'recentBookings',
            'bookingStats',
            'eventTypeCounts'
        ));
    }

    private function getStats($roleKey)
    {
        $stats = [];

        if ($roleKey === 'master_admin') {
            $stats['total_bookings'] = BookingRequest::count();
            $stats['total_users'] = User::count();
            $stats['total_companies'] = Company::count();
            $stats['total_event_types'] = EventType::count();
        } elseif ($roleKey === 'company_admin') {
            $company_id = Auth::user()->company_id;
            $stats['total_bookings'] = BookingRequest::whereHas('user', function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            })->count();
            $stats['total_users'] = User::where('company_id', $company_id)
                ->whereHas('role', function ($q) {
                    $q->where('role_key', 'customer');
                })->count();
            $stats['total_companies'] = 1;
            $stats['total_event_types'] = EventType::count();
        } else {
            $stats['total_bookings'] = BookingRequest::where('user_id', Auth::user()->id)->count();
            $stats['total_users'] = 1;
            $stats['total_companies'] = 0;
            $stats['total_event_types'] = 0;
        }

        return $stats;
    }

    private function getRecentBookings($roleKey, $limit = 5)
    {
        $query = BookingRequest::with(['user', 'eventType']);

        if ($roleKey === 'company_admin') {
            $query->whereHas('user', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    private function getBookingStats($roleKey)
    {
        $query = BookingRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7);

        if ($roleKey === 'company_admin') {
            $query->whereHas('user', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        }

        $stats = $query->get()->reverse()->values();

        return [
            'dates' => $stats->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))->toArray(),
            'counts' => $stats->pluck('count')->toArray(),
        ];
    }

    private function getEventTypeCounts($roleKey)
    {
        $query = BookingRequest::with('eventType')
            ->selectRaw('event_type_id, COUNT(*) as count')
            ->groupBy('event_type_id');

        if ($roleKey === 'company_admin') {
            $query->whereHas('user', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        }

        $data = $query->get();

        $eventTypeCounts = [];
        foreach ($data as $item) {
            $eventTypeCounts[$item->eventType->event_type ?? 'Unknown'] = $item->count;
        }

        // If no data, return default values
        if (empty($eventTypeCounts)) {
            $eventTypeCounts = ['Wedding' => 45, 'Corporate' => 30, 'Birthday' => 25];
        }

        return $eventTypeCounts;
    }
}
