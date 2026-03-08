<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\ActivityLog;
use App\Models\EventType;
use App\Models\Payment;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Show concise, meaningful activity stream in dashboard
        $recentActivities = $this->getMeaningfulRecentActivities($roleKey, $user);
        $insights = $this->getRoleInsights($roleKey, $dateRange);
        $dashboardAlerts = $this->getDashboardAlerts($roleKey, $insights);
        $paymentInsights = $this->getPaymentInsights($roleKey);

        $topCompaniesByBookings = $this->getTopCompaniesByBookings($roleKey);
        $topArtistsByBookings = $this->getTopArtistsByBookings($roleKey);
        $bookingsByDayOfWeek = $this->getBookingsByDayOfWeek($roleKey);
        $upcomingEventsList = $this->getUpcomingEventsList($roleKey, 8);
        $revenueBreakdown = $this->getRevenueBreakdown($roleKey);

        return view('dashboard.pages.index_enhanced', compact(
            'title',
            'stats',
            'statistics',
            'recentBookings',
            'bookingStats',
            'eventTypeCounts',
            'unreadNotifications',
            'recentActivities',
            'insights',
            'dashboardAlerts',
            'paymentInsights',
            'filter',
            'dateRange',
            'topCompaniesByBookings',
            'topArtistsByBookings',
            'bookingsByDayOfWeek',
            'upcomingEventsList',
            'revenueBreakdown'
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
            // Master-admin finance reflects B2B subscription collection from companies.
            $stats['total_revenue'] = \App\Models\Payment::where('status', 'completed')
                ->where('type', 'subscription')
                ->sum('amount');
            $stats['monthly_revenue'] = \App\Models\Payment::where('status', 'completed')
                ->where('type', 'subscription')
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
        } elseif ($roleKey === 'artist') {
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
        } elseif ($roleKey === 'artist') {
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
        } elseif ($roleKey === 'artist') {
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
        } elseif ($roleKey === 'artist') {
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

    private function getMeaningfulRecentActivities(string $roleKey, User $user)
    {
        $query = ActivityLog::with('user')
            ->whereNotNull('description')
            ->where(function ($q) {
                $q->whereIn('action', ['created', 'updated', 'deleted'])
                    ->orWhereNotNull('event_key')
                    ->orWhereNotNull('model_type')
                    ->orWhereNotNull('target_type');
            })
            ->whereNotIn('action', ['login', 'logout', 'viewed', 'read'])
            ->where(function ($q) {
                $q->where('description', 'not like', '%login%')
                    ->where('description', 'not like', '%logout%');
            });

        if ($roleKey === 'company_admin' && $user->company_id) {
            $query->where(function ($q) use ($user) {
                $q->where('properties->company_id', $user->company_id)
                    ->orWhere('properties->shared_with_company_id', $user->company_id)
                    ->orWhere('properties->owner_company_id', $user->company_id)
                    ->orWhere(function ($sub) use ($user) {
                        $sub->where('model_type', Company::class)->where('model_id', $user->company_id);
                    });
            });
        } elseif ($roleKey === 'customer') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('properties->user_id', $user->id);
            });
        } elseif ($roleKey === 'artist') {
            $artistId = optional($user->artist)->id;
            if ($artistId) {
                $query->where(function ($q) use ($artistId, $user) {
                    $q->where('properties->artist_id', $artistId)
                        ->orWhere('user_id', $user->id);
                });
            } else {
                $query->where('user_id', $user->id);
            }
        }

        return $query->latest()->take(10)->get();
    }

    private function getRoleInsights(string $roleKey, array $dateRange): array
    {
        $bookingQuery = BookingRequest::query();

        if ($dateRange['start']) {
            $bookingQuery->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        if ($roleKey === 'company_admin') {
            $bookingQuery->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $bookingQuery->where('user_id', Auth::id());
        } elseif ($roleKey === 'artist') {
            $artistId = optional(Auth::user()->artist)->id;
            $bookingQuery->where('assigned_artist_id', $artistId ?: 0);
        } elseif ($roleKey === 'affiliate') {
            return [
                'status_labels' => [],
                'status_values' => [],
                'monthly_labels' => [],
                'monthly_values' => [],
                'kpis' => [],
            ];
        }

        $statusRows = (clone $bookingQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusOrder = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'rejected'];
        $statusLabels = [];
        $statusValues = [];
        foreach ($statusOrder as $status) {
            $statusLabels[] = ucfirst(str_replace('_', ' ', $status));
            $statusValues[] = (int) ($statusRows[$status] ?? 0);
        }

        $monthlyRows = (clone $bookingQuery)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-01') as month_key, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->startOfMonth();
            $key = $m->format('Y-m-01');
            $monthlyLabels[] = $m->format('M Y');
            $monthlyValues[] = (int) ($monthlyRows[$key]->total ?? 0);
        }

        $total = array_sum($statusValues);
        $completed = (int) ($statusRows['completed'] ?? 0);
        $cancelled = (int) ($statusRows['cancelled'] ?? 0);
        $upcoming = (clone $bookingQuery)->whereDate('event_date', '>=', today())->count();
        $overdue = (clone $bookingQuery)
            ->whereDate('event_date', '<', today())
            ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
            ->count();

        $kpis = [
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'cancellation_rate' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
            'upcoming_events' => $upcoming,
            'overdue_open' => $overdue,
        ];

        if ($roleKey === 'master_admin') {
            $revenueThisMonth = Payment::where('status', 'completed')
                ->where('type', 'subscription')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
            $kpis['revenue_this_month'] = (float) $revenueThisMonth;
        }

        return [
            'status_labels' => $statusLabels,
            'status_values' => $statusValues,
            'monthly_labels' => $monthlyLabels,
            'monthly_values' => $monthlyValues,
            'kpis' => $kpis,
        ];
    }

    private function getDashboardAlerts(string $roleKey, array $insights): array
    {
        $alerts = [];
        $kpis = $insights['kpis'] ?? [];
        $overdue = (int) ($kpis['overdue_open'] ?? 0);
        $cancelRate = (float) ($kpis['cancellation_rate'] ?? 0);
        $upcoming = (int) ($kpis['upcoming_events'] ?? 0);

        if ($overdue > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'alert-triangle',
                'title' => 'Overdue Open Bookings',
                'message' => $overdue . ' booking(s) are past event date and still open.',
            ];
        }

        if ($cancelRate >= 20) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'shield-alert',
                'title' => 'High Cancellation Rate',
                'message' => 'Cancellation rate is ' . number_format($cancelRate, 1) . '%. Review causes and follow-up.',
            ];
        }

        if ($upcoming >= 15) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'calendar-clock',
                'title' => 'Heavy Upcoming Schedule',
                'message' => $upcoming . ' upcoming events require resource readiness.',
            ];
        }

        if ($roleKey === 'master_admin' && empty($alerts)) {
            $alerts[] = [
                'type' => 'success',
                'icon' => 'badge-check',
                'title' => 'System Snapshot Healthy',
                'message' => 'No major operational alert detected in current period.',
            ];
        }

        return array_slice($alerts, 0, 3);
    }

    private function getPaymentInsights(string $roleKey): array
    {
        $query = Payment::query()->where('created_at', '>=', now()->subMonths(5)->startOfMonth());

        if ($roleKey === 'master_admin') {
            $query->where('type', 'subscription');
        } elseif ($roleKey === 'company_admin') {
            $query->where(function ($q) {
                $q->where('submitted_to_company_id', Auth::user()->company_id)
                    ->orWhereHas('user', function ($sub) {
                        $sub->where('company_id', Auth::user()->company_id);
                    });
            });
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::id());
        } elseif ($roleKey === 'artist' || $roleKey === 'affiliate') {
            return ['labels' => [], 'values' => [], 'status_labels' => [], 'status_values' => []];
        }

        $monthly = (clone $query)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-01') as month_key, SUM(amount) as total")
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $labels = [];
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->startOfMonth();
            $k = $m->format('Y-m-01');
            $labels[] = $m->format('M Y');
            $values[] = (float) ($monthly[$k]->total ?? 0);
        }

        $status = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => $labels,
            'values' => $values,
            'status_labels' => ['Pending', 'Completed', 'Failed'],
            'status_values' => [
                (int) ($status['pending'] ?? 0),
                (int) ($status['completed'] ?? 0),
                (int) ($status['failed'] ?? 0),
            ],
        ];
    }

    private function getTopCompaniesByBookings(string $roleKey)
    {
        if ($roleKey !== 'master_admin') {
            return collect();
        }
        return Company::query()
            ->withCount(['bookingRequests as bookings_count' => function ($q) {
                $q->where('created_at', '>=', now()->subMonths(6));
            }])
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();
    }

    private function getTopArtistsByBookings(string $roleKey)
    {
        $query = Artist::query()
            ->withCount(['bookings as bookings_count' => function ($q) {
                $q->where('created_at', '>=', now()->subMonths(6));
            }])
            ->with('user:id,name')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->limit(10);

        if ($roleKey === 'company_admin' && Auth::user()->company_id) {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey !== 'master_admin') {
            return collect();
        }

        return $query->get();
    }

    private function getBookingsByDayOfWeek(string $roleKey): array
    {
        $query = BookingRequest::query()
            ->selectRaw('DAYOFWEEK(created_at) as day_num, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(3))
            ->groupBy('day_num');

        if ($roleKey === 'company_admin') {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::id());
        } elseif ($roleKey === 'artist') {
            $artist = Artist::where('user_id', Auth::id())->first();
            $query->where('assigned_artist_id', $artist?->id ?? 0);
        } elseif ($roleKey === 'affiliate') {
            return [
                'labels' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'values' => [0, 0, 0, 0, 0, 0, 0],
            ];
        }

        $rows = $query->get()->keyBy('day_num');
        $labels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $values = [];
        for ($d = 1; $d <= 7; $d++) {
            $values[] = (int) ($rows->get($d)->total ?? 0);
        }
        return ['labels' => $labels, 'values' => $values];
    }

    private function getUpcomingEventsList(string $roleKey, int $limit = 8)
    {
        $query = BookingRequest::query()
            ->with(['user:id,name', 'eventType:id,event_type', 'company:id,name'])
            ->whereDate('event_date', '>=', today())
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->orderBy('event_date')
            ->limit($limit);

        if ($roleKey === 'company_admin') {
            $query->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', Auth::id());
        } elseif ($roleKey === 'artist') {
            $artist = Artist::where('user_id', Auth::id())->first();
            $query->where('assigned_artist_id', $artist?->id ?? 0);
        } elseif ($roleKey === 'affiliate') {
            return collect();
        }

        return $query->get();
    }

    private function getRevenueBreakdown(string $roleKey): array
    {
        if ($roleKey !== 'master_admin') {
            return ['labels' => [], 'values' => []];
        }
        $subscription = Payment::where('status', 'completed')
            ->where('type', 'subscription')
            ->where('created_at', '>=', now()->subMonths(12))
            ->sum('amount');
        $booking = Payment::where('status', 'completed')
            ->where('type', 'booking')
            ->where('created_at', '>=', now()->subMonths(12))
            ->sum('amount');
        return [
            'labels' => ['Subscriptions', 'Bookings'],
            'values' => [(float) $subscription, (float) $booking],
        ];
    }
}
