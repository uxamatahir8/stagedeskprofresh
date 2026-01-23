<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\Artist;
use App\Models\ActivityLog;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyAdminController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardStatisticsService $dashboardService)
    {
        $this->middleware('role:company_admin');
        $this->middleware('company.scope');
        $this->dashboardService = $dashboardService;
    }

    /**
     * Company Admin Dashboard
     */
    public function dashboard()
    {
        $title = 'Company Admin Dashboard';
        $user = Auth::user();
        $companyId = $user->company_id;

        // Safety check
        if (!$companyId) {
            abort(403, 'You must be associated with a company');
        }

        $company = Company::with('subscription')->findOrFail($companyId);

        // Company statistics
        $stats = [
            'total_artists' => Artist::where('company_id', $companyId)->count(),
            'active_artists' => Artist::where('company_id', $companyId)
                ->whereHas('user', fn($q) => $q->where('status', 'active'))
                ->count(),
            'total_customers' => User::where('company_id', $companyId)
                ->whereHas('role', fn($q) => $q->where('role_key', 'customer'))
                ->count(),
            'total_bookings' => BookingRequest::where('company_id', $companyId)->count(),
            'pending_bookings' => BookingRequest::where('company_id', $companyId)
                ->where('status', 'pending')
                ->count(),
            'confirmed_bookings' => BookingRequest::where('company_id', $companyId)
                ->where('status', 'confirmed')
                ->count(),
            'completed_bookings' => BookingRequest::where('company_id', $companyId)
                ->where('status', 'completed')
                ->count(),
            'total_revenue' => Payment::whereHas('bookingRequest', fn($q) => $q->where('company_id', $companyId))
                ->where('status', 'completed')
                ->sum('amount'),
            'monthly_bookings' => BookingRequest::where('company_id', $companyId)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        // Recent activities
        $recentBookings = BookingRequest::where('company_id', $companyId)
            ->with(['user', 'eventType', 'assignedArtist'])
            ->latest()
            ->take(10)
            ->get();

        $recentArtists = Artist::where('company_id', $companyId)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $topArtists = Artist::where('company_id', $companyId)
            ->with('user')
            ->withCount(['assignedBookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('assigned_bookings_count', 'desc')
            ->take(5)
            ->get();

        // Subscription info
        $subscription = $company->subscription()->active()->first();

        // Monthly stats chart
        $monthlyStats = $this->getMonthlyCompanyStatistics($companyId);

        return view('dashboard.pages.company.dashboard', compact(
            'title',
            'company',
            'stats',
            'recentBookings',
            'recentArtists',
            'topArtists',
            'subscription',
            'monthlyStats'
        ));
    }

    /**
     * Company Staff Management
     */
    public function staff()
    {
        $title = 'Company Staff';
        $companyId = Auth::user()->company_id;

        $staff = User::where('company_id', $companyId)
            ->with('role')
            ->whereHas('role', function($q) {
                $q->whereIn('role_key', ['company_admin', 'artist', 'customer']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.pages.company.staff', compact('title', 'staff'));
    }

    /**
     * Company Artists Management
     */
    public function artists()
    {
        $title = 'Company Artists';
        $companyId = Auth::user()->company_id;

        $artists = Artist::where('company_id', $companyId)
            ->with(['user', 'assignedBookings'])
            ->withCount(['assignedBookings as completed_count' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.pages.company.artists', compact('title', 'artists'));
    }

    /**
     * Company Bookings
     */
    public function bookings(Request $request)
    {
        $title = 'Company Bookings';
        $companyId = Auth::user()->company_id;

        $query = BookingRequest::where('company_id', $companyId)
            ->with(['user', 'eventType', 'assignedArtist']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Unassigned bookings (need artist assignment)
        $unassignedBookings = BookingRequest::where('company_id', $companyId)
            ->whereNull('assigned_artist_id')
            ->where('status', 'pending')
            ->count();

        return view('dashboard.pages.company.bookings', compact('title', 'bookings', 'unassignedBookings'));
    }

    /**
     * Assign Artist to Booking
     */
    public function assignArtistToBooking(Request $request, BookingRequest $booking)
    {
        $companyId = Auth::user()->company_id;

        // Safety check: verify booking belongs to company
        if ($booking->company_id !== $companyId) {
            abort(403, 'This booking does not belong to your company');
        }

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'company_notes' => 'nullable|string|max:1000'
        ]);

        // Verify artist belongs to company
        $artist = Artist::where('id', $request->artist_id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $booking->update([
                'assigned_artist_id' => $artist->id,
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'company_notes' => $request->company_notes
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Artist assigned to booking by company admin',
                ['artist_id' => $artist->id, 'booking_id' => $booking->id]
            );

            DB::commit();

            return back()->with('success', 'Artist assigned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign artist: ' . $e->getMessage());
        }
    }

    /**
     * Company Settings
     */
    public function settings()
    {
        $title = 'Company Settings';
        $company = Company::findOrFail(Auth::user()->company_id);

        return view('dashboard.pages.company.settings', compact('title', 'company'));
    }

    /**
     * Update Company Settings
     */
    public function updateSettings(Request $request)
    {
        $company = Company::findOrFail(Auth::user()->company_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('company-logos', 'public');
                $validated['logo'] = $logoPath;
            }

            $company->update($validated);

            ActivityLog::log(
                'updated',
                $company,
                'Company settings updated',
                ['company_id' => $company->id]
            );

            DB::commit();

            return back()->with('success', 'Company settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Helper Methods
     */
    private function getMonthlyCompanyStatistics($companyId)
    {
        $months = [];
        $bookings = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $bookings[] = BookingRequest::where('company_id', $companyId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $revenue[] = Payment::whereHas('bookingRequest', fn($q) => $q->where('company_id', $companyId))
                ->where('status', 'completed')
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
}
