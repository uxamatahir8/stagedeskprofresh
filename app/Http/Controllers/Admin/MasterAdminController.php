<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\Artist;
use App\Models\CompanySubscription;
use App\Models\ActivityLog;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterAdminController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardStatisticsService $dashboardService)
    {
        $this->middleware('role:master_admin');
        $this->dashboardService = $dashboardService;
    }

    /**
     * Master Admin Dashboard
     */
    public function dashboard()
    {
        $title = 'Master Admin Dashboard';

        // System-wide statistics
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_customers' => User::whereHas('role', fn($q) => $q->where('role_key', 'customer'))->count(),
            'total_artists' => Artist::count(),
            'total_bookings' => BookingRequest::count(),
            'pending_bookings' => BookingRequest::where('status', 'pending')->count(),
            'confirmed_bookings' => BookingRequest::where('status', 'confirmed')->count(),
            'completed_bookings' => BookingRequest::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'active_subscriptions' => CompanySubscription::where('status', 'active')
                ->where('end_date', '>', now())
                ->count(),
        ];

        // Recent activities
        $recentCompanies = Company::latest()->take(5)->get();
        $recentUsers = User::with('role')->latest()->take(10)->get();
        $recentBookings = BookingRequest::with(['user', 'company', 'eventType', 'assignedArtist'])
            ->latest()
            ->take(10)
            ->get();
        $recentPayments = Payment::with(['user', 'bookingRequest'])
            ->latest()
            ->take(10)
            ->get();

        // System health
        $systemHealth = [
            'database_size' => $this->getDatabaseSize(),
            'total_records' => $this->getTotalRecords(),
            'disk_usage' => $this->getDiskUsage(),
        ];

        // Charts data
        $monthlyStats = $this->getMonthlyStatistics();
        $topCompanies = $this->getTopCompanies();
        $topArtists = $this->getTopArtists();

        return view('dashboard.pages.admin.master-dashboard', compact(
            'title',
            'stats',
            'recentCompanies',
            'recentUsers',
            'recentBookings',
            'recentPayments',
            'systemHealth',
            'monthlyStats',
            'topCompanies',
            'topArtists'
        ));
    }

    /**
     * System Overview
     */
    public function systemOverview()
    {
        $title = 'System Overview';

        $overview = [
            'users_by_role' => User::select('role_id', DB::raw('count(*) as count'))
                ->groupBy('role_id')
                ->with('role')
                ->get(),
            'bookings_by_status' => BookingRequest::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'companies_by_status' => Company::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'payments_by_status' => Payment::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'subscriptions_by_status' => CompanySubscription::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
        ];

        return view('dashboard.pages.admin.system-overview', compact('title', 'overview'));
    }

    /**
     * Verify Payment
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:completed,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => $request->status,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'admin_notes' => $request->notes
            ]);

            ActivityLog::log(
                'updated',
                $payment,
                'Payment ' . $request->status . ' by admin',
                ['payment_id' => $payment->id, 'amount' => $payment->amount]
            );

            DB::commit();

            return back()->with('success', 'Payment ' . $request->status . ' successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * System Activity Logs
     */
    public function activityLogs(Request $request)
    {
        $title = 'System Activity Logs';

        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

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

        return view('dashboard.pages.admin.activity-logs', compact('title', 'logs', 'actions'));
    }

    /**
     * Helper Methods
     */
    private function getDatabaseSize()
    {
        try {
            $result = DB::select("SELECT
                SUM(data_length + index_length) / 1024 / 1024 AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = DATABASE()");
            return round($result[0]->size_mb ?? 0, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getTotalRecords()
    {
        return User::count() +
               Company::count() +
               BookingRequest::count() +
               Payment::count() +
               Artist::count();
    }

    private function getDiskUsage()
    {
        try {
            $path = storage_path();
            $total = disk_total_space($path);
            $free = disk_free_space($path);
            $used = $total - $free;
            $percentage = round(($used / $total) * 100, 2);
            return $percentage . '%';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getMonthlyStatistics()
    {
        $months = [];
        $bookings = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $bookings[] = BookingRequest::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $revenue[] = Payment::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        return [
            'months' => $months,
            'bookings' => $bookings,
            'revenue' => $revenue
        ];
    }

    private function getTopCompanies($limit = 10)
    {
        return Company::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take($limit)
            ->get();
    }

    private function getTopArtists($limit = 10)
    {
        return Artist::with('user')
            ->withCount(['assignedBookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('assigned_bookings_count', 'desc')
            ->take($limit)
            ->get();
    }
}
