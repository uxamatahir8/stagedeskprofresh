<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Testimonials;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $title = 'Home';

        $monthly_packages = Package::where('duration_type', 'monthly')->get();
        $yearly_packages = Package::where('duration_type', 'yearly')->get();

        $blogs = Blog::with('category')->where('status', 'published')->latest()->take(6)->get();

        $testimonials = Testimonials::all();

        $totalBookings = BookingRequest::count();
        $completedBookings = BookingRequest::where('status', 'completed')->count();
        $activeCompanies = Company::where('status', 'active')->count();
        $activeArtists = User::whereHas('role', fn($q) => $q->where('role_key', 'artist'))->count();
        $activeSubscriptions = CompanySubscription::where('status', 'active')
            ->where('end_date', '>', now())
            ->count();
        $platformRevenue = (float) Payment::where('status', 'completed')
            ->where('type', 'subscription')
            ->sum('amount');

        $bookingsThisMonth = BookingRequest::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ])->count();
        $bookingsLastMonth = BookingRequest::whereBetween('created_at', [
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth(),
        ])->count();
        $bookingGrowthRate = $bookingsLastMonth > 0
            ? round((($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth) * 100, 1)
            : ($bookingsThisMonth > 0 ? 100.0 : 0.0);

        $completionRate = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0.0;

        $recentActivity = BookingRequest::with(['eventType', 'company'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function (BookingRequest $booking) {
                return [
                    'tracking_code' => $booking->tracking_code ?? $booking->id,
                    'event_type' => $booking->eventType->event_type ?? 'Event',
                    'company' => $booking->company->name ?? 'N/A',
                    'status' => $booking->status ?? 'pending',
                    'created_at' => $booking->created_at?->diffForHumans(),
                ];
            });

        $trendLabels = [];
        $trendValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trendLabels[] = $month->format('M');
            $trendValues[] = BookingRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $landingStats = [
            'active_companies' => $activeCompanies,
            'active_artists' => $activeArtists,
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'active_subscriptions' => $activeSubscriptions,
            'platform_revenue' => $platformRevenue,
            'bookings_this_month' => $bookingsThisMonth,
            'booking_growth_rate' => $bookingGrowthRate,
            'completion_rate' => $completionRate,
            'recent_activity' => $recentActivity,
            'trend_labels' => $trendLabels,
            'trend_values' => $trendValues,
        ];

        return view('website', compact('title', 'monthly_packages', 'yearly_packages', 'blogs', 'testimonials', 'landingStats'));
    }

    public function blogs($categorySlug = null)
    {
        $title = 'Insights & Updates';
        $query = Blog::with('category')->withCount('approvedComments')->where('status', 'published');

        $category = null;

        if ($categorySlug) {
            // Find category by slug
            $category = BlogCategory::where('name', $categorySlug)
                ->orWhereRaw('LOWER(name) = ?', [strtolower($categorySlug)])
                ->firstOrFail();

            // Filter blogs by that category
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('id', $category->id);
            });
        }

        $blogs = $query->paginate(6);

        return view('blogs', compact('title', 'blogs', 'category'));
    }
}
