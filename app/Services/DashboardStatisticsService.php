<?php

namespace App\Services;

use App\Models\Artist;
use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    public function getStatisticsForRole(): array
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        return match ($roleKey) {
            'master_admin' => $this->getMasterAdminStatistics(),
            'company_admin' => $this->getCompanyAdminStatistics(),
            'artist', 'dj' => $this->getArtistStatistics(),
            'customer' => $this->getCustomerStatistics(),
            'affiliate' => $this->getAffiliateStatistics(),
            default => [],
        };
    }

    private function getMasterAdminStatistics(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        return [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_artists' => Artist::count(),
            'total_customers' => User::whereHas('role', function ($query) {
                $query->where('role_key', 'customer');
            })->count(),
            'total_bookings' => BookingRequest::count(),
            'pending_bookings' => BookingRequest::whereNull('status')->orWhere('status', 'pending')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('amount'),
            'yearly_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startOfYear)
                ->sum('amount'),
            'active_subscriptions' => CompanySubscription::where('status', 'active')
                ->where('end_date', '>', $now)
                ->count(),
            'expiring_subscriptions' => CompanySubscription::where('status', 'active')
                ->whereBetween('end_date', [$now, $now->copy()->addDays(30)])
                ->count(),
            'recent_bookings' => BookingRequest::with(['user', 'eventType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'monthly_bookings_chart' => $this->getMonthlyBookingsData(),
            'revenue_chart' => $this->getMonthlyRevenueData(),
        ];
    }

    private function getCompanyAdminStatistics(): array
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        return [
            'total_artists' => Artist::where('company_id', $companyId)->count(),
            'active_artists' => Artist::where('company_id', $companyId)
                ->whereHas('user', function ($query) {
                    $query->whereNotNull('email_verified_at');
                })->count(),
            'total_bookings' => BookingRequest::where('company_id', $companyId)->count(),
            'pending_bookings' => BookingRequest::where('company_id', $companyId)
                ->whereNull('status')
                ->orWhere('status', 'pending')
                ->count(),
            'completed_bookings' => BookingRequest::where('company_id', $companyId)
                ->where('status', 'completed')
                ->count(),
            'monthly_bookings' => BookingRequest::where('company_id', $companyId)
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'subscription_status' => CompanySubscription::where('company_id', $companyId)
                ->where('status', 'active')
                ->orderBy('end_date', 'desc')
                ->first(),
            'recent_bookings' => BookingRequest::where('company_id', $companyId)
                ->with(['user', 'eventType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'top_artists' => Artist::where('company_id', $companyId)
                ->orderBy('rating', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    private function getArtistStatistics(): array
    {
        $user = Auth::user();
        $artist = Artist::where('user_id', $user->id)->first();

        if (!$artist) {
            return [];
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        return [
            'total_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)->count(),
            'pending_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where(function($q) {
                    $q->whereNull('status')->orWhere('status', 'pending');
                })->count(),
            'confirmed_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('status', 'confirmed')
                ->count(),
            'completed_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('status', 'completed')
                ->count(),
            'monthly_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'average_rating' => round($artist->rating ?? 0, 1),
            'total_earnings' => DB::table('payments')
                ->join('booking_requests', 'payments.booking_requests_id', '=', 'booking_requests.id')
                ->where('booking_requests.assigned_artist_id', $artist->id)
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
            'monthly_earnings' => DB::table('payments')
                ->join('booking_requests', 'payments.booking_requests_id', '=', 'booking_requests.id')
                ->where('booking_requests.assigned_artist_id', $artist->id)
                ->where('payments.status', 'completed')
                ->where('payments.created_at', '>=', $startOfMonth)
                ->sum('payments.amount'),
            'profile_completion' => $this->calculateProfileCompletion($artist),
            'recent_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->with(['user', 'eventType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    private function getCustomerStatistics(): array
    {
        $user = Auth::user();
        $now = Carbon::now();

        return [
            'total_bookings' => BookingRequest::where('user_id', $user->id)->count(),
            'pending_bookings' => BookingRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', null])
                ->count(),
            'confirmed_bookings' => BookingRequest::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count(),
            'completed_bookings' => BookingRequest::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'upcoming_events' => BookingRequest::where('user_id', $user->id)
                ->where('event_date', '>=', $now)
                ->orderBy('event_date', 'asc')
                ->limit(5)
                ->get(),
            'recent_bookings' => BookingRequest::where('user_id', $user->id)
                ->with(['eventType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    private function getMonthlyBookingsData(): array
    {
        $data = BookingRequest::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'values' => $data->pluck('count')->toArray(),
        ];
    }

    private function getMonthlyRevenueData(): array
    {
        $data = Payment::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'values' => $data->pluck('total')->toArray(),
        ];
    }

    private function calculateProfileCompletion(Artist $artist): int
    {
        $fields = [
            'stage_name' => !empty($artist->stage_name),
            'bio' => !empty($artist->bio),
            'experience_years' => !empty($artist->experience_years),
            'genres' => !empty($artist->genres),
            'specialization' => !empty($artist->specialization),
            'image' => !empty($artist->image),
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);

        return round(($completed / $total) * 100);
    }

    private function getAffiliateStatistics(): array
    {
        $user = Auth::user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        return [
            'total_referrals' => User::where('referred_by', $user->id)->count(),
            'active_referrals' => User::where('referred_by', $user->id)
                ->whereNotNull('email_verified_at')
                ->count(),
            'pending_referrals' => User::where('referred_by', $user->id)
                ->whereNull('email_verified_at')
                ->count(),
            'monthly_referrals' => User::where('referred_by', $user->id)
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'total_commissions' => \App\Models\AffiliateComission::where('affiliate_id', $user->id)
                ->sum('commission_amount'),
            'pending_commissions' => \App\Models\AffiliateComission::where('affiliate_id', $user->id)
                ->where('status', 'pending')
                ->sum('commission_amount'),
            'paid_commissions' => \App\Models\AffiliateComission::where('affiliate_id', $user->id)
                ->where('status', 'paid')
                ->sum('commission_amount'),
            'monthly_commissions' => \App\Models\AffiliateComission::where('affiliate_id', $user->id)
                ->where('created_at', '>=', $startOfMonth)
                ->sum('commission_amount'),
            'recent_referrals' => User::where('referred_by', $user->id)
                ->with('role')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'conversion_rate' => $this->calculateConversionRate($user->id),
        ];
    }

    private function calculateConversionRate($affiliateId): float
    {
        $totalReferrals = User::where('referred_by', $affiliateId)->count();
        if ($totalReferrals === 0) {
            return 0;
        }

        $activeReferrals = User::where('referred_by', $affiliateId)
            ->whereNotNull('email_verified_at')
            ->count();

        return round(($activeReferrals / $totalReferrals) * 100, 2);
    }
}
