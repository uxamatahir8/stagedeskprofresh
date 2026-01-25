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

    public function dashboard(Request $request)
    {
        $title = 'Dashboard';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Get filter parameters
        $filter = $request->get('filter', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set date range based on filter
        $dateRange = $this->getDateRange($filter, $startDate, $endDate);

        // Get comprehensive stats based on user role
        $statistics = $this->dashboardService->getStatisticsForRole();

        // Legacy stats for backward compatibility with date filter
        $stats = $this->getStats($roleKey, $dateRange);

        // Get recent bookings
        $recentBookings = $this->getRecentBookings($roleKey, 5, $dateRange);

        // Get booking stats for charts
        $bookingStats = $this->getBookingStats($roleKey, $dateRange);

        // Get event type counts
        $eventTypeCounts = $this->getEventTypeCounts($roleKey, $dateRange);

        // Get unread notifications count
        $unreadNotifications = \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('dashboard.pages.index_enhanced', compact(
            'title',
            'stats',
            'statistics',
            'recentBookings',
            'bookingStats',
            'eventTypeCounts',
            'unreadNotifications',
            'filter',
            'dateRange'
        ));
    }

    private function getDateRange($filter, $startDate = null, $endDate = null)
    {
        $range = ['start' => null, 'end' => now()];

        switch ($filter) {
            case 'today':
                $range['start'] = now()->startOfDay();
                $range['end'] = now()->endOfDay();
                break;
            case 'this_week':
                $range['start'] = now()->startOfWeek();
                $range['end'] = now()->endOfWeek();
                break;
            case 'this_month':
                $range['start'] = now()->startOfMonth();
                $range['end'] = now()->endOfMonth();
                break;
            case 'this_year':
                $range['start'] = now()->startOfYear();
                $range['end'] = now()->endOfYear();
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $range['start'] = \Carbon\Carbon::parse($startDate)->startOfDay();
                    $range['end'] = \Carbon\Carbon::parse($endDate)->endOfDay();
                }
                break;
            default:
                $range['start'] = now()->startOfMonth();
                $range['end'] = now()->endOfMonth();
        }

        return $range;
    }

    private function getStats($roleKey, $dateRange = null)
    {
        $stats = [];

        if ($roleKey === 'master_admin') {
            $stats['total_bookings'] = BookingRequest::count();
            $stats['pending_bookings'] = BookingRequest::whereNull('status')->orWhere('status', 'pending')->count();
            $stats['completed_bookings'] = BookingRequest::where('status', 'completed')->count();
            $stats['total_users'] = User::count();
            $stats['total_companies'] = Company::count();
            $stats['active_companies'] = Company::where('status', 'active')->count();
            $stats['total_event_types'] = EventType::count();
            $stats['total_revenue'] = \App\Models\Payment::where('status', 'completed')->sum('amount');
            $stats['monthly_revenue'] = \App\Models\Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
        } elseif ($roleKey === 'company_admin') {
            $company_id = Auth::user()->company_id;
            $stats['total_bookings'] = BookingRequest::where('company_id', $company_id)->count();
            $stats['pending_bookings'] = BookingRequest::where('company_id', $company_id)
                ->where(function($q) {
                    $q->whereNull('status')->orWhere('status', 'pending');
                })->count();
            $stats['completed_bookings'] = BookingRequest::where('company_id', $company_id)
                ->where('status', 'completed')->count();
            $stats['total_users'] = User::where('company_id', $company_id)
                ->whereHas('role', function ($q) {
                    $q->where('role_key', 'customer');
                })->count();
            $stats['total_artists'] = \App\Models\Artist::where('company_id', $company_id)->count();
            $stats['active_artists'] = \App\Models\Artist::where('company_id', $company_id)
                ->whereHas('user', function($q) {
                    $q->whereNotNull('email_verified_at');
                })->count();
            $stats['total_companies'] = 1;
            $stats['total_event_types'] = EventType::count();
        } elseif (in_array($roleKey, ['artist', 'dj'])) {
            $artist = \App\Models\Artist::where('user_id', Auth::user()->id)->first();
            if ($artist) {
                // Count bookings where this artist is assigned
                $stats['total_bookings'] = BookingRequest::where('assigned_artist_id', $artist->id)->count();
                $stats['pending_bookings'] = BookingRequest::where('assigned_artist_id', $artist->id)
                    ->where(function($q) {
                        $q->whereNull('status')->orWhere('status', 'pending');
                    })->count();
                $stats['completed_bookings'] = BookingRequest::where('assigned_artist_id', $artist->id)
                    ->where('status', 'completed')->count();
                $stats['confirmed_bookings'] = BookingRequest::where('assigned_artist_id', $artist->id)
                    ->where('status', 'confirmed')->count();
                $stats['average_rating'] = round($artist->rating ?? 0, 1);
                $stats['total_earnings'] = \Illuminate\Support\Facades\DB::table('payments')
                    ->join('booking_requests', 'payments.booking_requests_id', '=', 'booking_requests.id')
                    ->where('booking_requests.assigned_artist_id', $artist->id)
                    ->where('payments.status', 'completed')
                    ->sum('payments.amount');
            }
            $stats['total_users'] = 1;
            $stats['total_companies'] = 0;
            $stats['total_event_types'] = 0;
        } elseif ($roleKey === 'affiliate') {
            // Affiliate dashboard stats
            $stats['total_referrals'] = User::where('referred_by', Auth::id())->count();
            $stats['active_referrals'] = User::where('referred_by', Auth::id())
                ->whereNotNull('email_verified_at')->count();
            $stats['total_commissions'] = \App\Models\AffiliateComission::where('affiliate_id', Auth::id())->sum('commission_amount');
            $stats['pending_commissions'] = \App\Models\AffiliateComission::where('affiliate_id', Auth::id())
                ->where('status', 'pending')->sum('commission_amount');
            $stats['paid_commissions'] = \App\Models\AffiliateComission::where('affiliate_id', Auth::id())
                ->where('status', 'paid')->sum('commission_amount');
            $stats['total_bookings'] = 0;
            $stats['pending_bookings'] = 0;
            $stats['completed_bookings'] = 0;
            $stats['total_users'] = 1;
            $stats['total_companies'] = 0;
            $stats['total_event_types'] = 0;
        } else {
            // Customer dashboard stats
            $stats['total_bookings'] = BookingRequest::where('user_id', Auth::user()->id)->count();
            $stats['pending_bookings'] = BookingRequest::where('user_id', Auth::user()->id)
                ->where(function($q) {
                    $q->whereNull('status')->orWhere('status', 'pending');
                })->count();
            $stats['completed_bookings'] = BookingRequest::where('user_id', Auth::user()->id)
                ->where('status', 'completed')->count();
            $stats['cancelled_bookings'] = BookingRequest::where('user_id', Auth::user()->id)
                ->where('status', 'cancelled')->count();
            $stats['total_users'] = 1;
            $stats['total_companies'] = 0;
            $stats['total_event_types'] = 0;
        }

        return $stats;
    }

    private function getRecentBookings($roleKey, $limit = 5, $dateRange = null)
    {
        $query = BookingRequest::with(['user', 'eventType', 'assignedArtist']);

        if ($dateRange && $dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        if ($roleKey === 'company_admin') {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        } elseif (in_array($roleKey, ['artist', 'dj'])) {
            // Artists see bookings assigned to them
            $artist = \App\Models\Artist::where('user_id', Auth::user()->id)->first();
            if ($artist) {
                $query->where('assigned_artist_id', $artist->id);
            } else {
                // If no artist profile, show nothing
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleKey === 'affiliate') {
            // Affiliates don't see bookings in dashboard
            $query->whereRaw('1 = 0');
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    private function getBookingStats($roleKey, $dateRange = null)
    {
        $query = BookingRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7);

        if ($dateRange && $dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        if ($roleKey === 'company_admin') {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        } elseif (in_array($roleKey, ['artist', 'dj'])) {
            $artist = \App\Models\Artist::where('user_id', Auth::user()->id)->first();
            if ($artist) {
                $query->where('assigned_artist_id', $artist->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleKey === 'affiliate') {
            $query->whereRaw('1 = 0');
        }

        $stats = $query->get()->reverse()->values();

        return [
            'dates' => $stats->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))->toArray(),
            'counts' => $stats->pluck('count')->toArray(),
        ];
    }

    private function getEventTypeCounts($roleKey, $dateRange = null)
    {
        $query = BookingRequest::with('eventType')
            ->selectRaw('event_type_id, COUNT(*) as count')
            ->groupBy('event_type_id');

        if ($dateRange && $dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        if ($roleKey === 'company_admin') {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::user()->id);
        } elseif (in_array($roleKey, ['artist', 'dj'])) {
            $artist = \App\Models\Artist::where('user_id', Auth::user()->id)->first();
            if ($artist) {
                $query->where('assigned_artist_id', $artist->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleKey === 'affiliate') {
            $query->whereRaw('1 = 0');
        }

        $data = $query->get();

        $eventTypeCounts = [];
        foreach ($data as $item) {
            $eventTypeCounts[$item->eventType->event_type ?? 'Unknown'] = $item->count;
        }

        // If no data, return empty for roles without bookings
        if (empty($eventTypeCounts) && !in_array($roleKey, ['affiliate'])) {
            $eventTypeCounts = ['No Data' => 0];
        }

        return $eventTypeCounts;
    }
}
