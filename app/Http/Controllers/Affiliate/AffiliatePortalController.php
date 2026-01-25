<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AffiliatePortalController extends Controller
{
    /**
     * Affiliate Dashboard
     */
    public function dashboard()
    {
        $title = 'Affiliate Dashboard';
        $user = Auth::user();

        // Get affiliate stats
        $stats = [
            'total_referrals' => User::where('referred_by', $user->id)->count() +
                               Company::where('referred_by', $user->id)->count(),
            'user_referrals' => User::where('referred_by', $user->id)->count(),
            'company_referrals' => Company::where('referred_by', $user->id)->count(),
            'total_commissions' => $this->getTotalCommissions($user->id),
            'pending_commissions' => $this->getPendingCommissions($user->id),
            'paid_commissions' => $this->getPaidCommissions($user->id),
            'this_month_commissions' => $this->getMonthCommissions($user->id),
        ];

        // Recent referrals
        $recentUserReferrals = User::where('referred_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentCompanyReferrals = Company::where('referred_by', $user->id)
            ->with('owner')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly commission chart
        $monthlyCommissions = $this->getMonthlyCommissions($user->id);

        // Get or create referral code
        if (!$user->referral_code) {
            $user->update([
                'referral_code' => $this->generateReferralCode()
            ]);
        }

        return view('dashboard.pages.affiliate.dashboard', compact(
            'title',
            'stats',
            'recentUserReferrals',
            'recentCompanyReferrals',
            'monthlyCommissions'
        ));
    }

    /**
     * My Referrals
     */
    public function referrals(Request $request)
    {
        $title = 'My Referrals';
        $user = Auth::user();

        $type = $request->get('type', 'all'); // all, users, companies

        $userReferrals = collect();
        $companyReferrals = collect();

        if (in_array($type, ['all', 'users'])) {
            $userReferrals = User::where('referred_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'users_page');
        }

        if (in_array($type, ['all', 'companies'])) {
            $companyReferrals = Company::where('referred_by', $user->id)
                ->with('owner')
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'companies_page');
        }

        return view('dashboard.pages.affiliate.referrals', compact(
            'title',
            'userReferrals',
            'companyReferrals',
            'type'
        ));
    }

    /**
     * My Commissions
     */
    public function commissions(Request $request)
    {
        $title = 'My Commissions';
        $user = Auth::user();

        // In a full implementation, you would have a commissions table
        // For now, we'll calculate from payments linked to referred users/companies
        $commissions = $this->getDetailedCommissions($user->id, $request);

        $summary = [
            'total_earned' => $this->getTotalCommissions($user->id),
            'pending' => $this->getPendingCommissions($user->id),
            'paid' => $this->getPaidCommissions($user->id),
            'available_for_withdrawal' => $this->getPaidCommissions($user->id),
        ];

        return view('dashboard.pages.affiliate.commissions', compact('title', 'commissions', 'summary'));
    }

    /**
     * Request Payout
     */
    public function requestPayout(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:50', // Minimum payout amount
            'payment_method' => 'required|in:bank_transfer,paypal,crypto',
            'payment_details' => 'required|string|max:500'
        ]);

        $availableBalance = $this->getPaidCommissions($user->id);

        if ($validated['amount'] > $availableBalance) {
            return back()->with('error', 'Requested amount exceeds available balance');
        }

        DB::beginTransaction();
        try {
            // In a full implementation, create payout request record
            // For now, we'll log the activity
            ActivityLog::log(
                'created',
                $user,
                'Payout request submitted',
                [
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending'
                ]
            );

            DB::commit();

            return back()->with('success', 'Payout request submitted successfully! We will process it within 3-5 business days.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit payout request: ' . $e->getMessage());
        }
    }

    /**
     * Referral Links
     */
    public function referralLinks()
    {
        $title = 'Referral Links';
        $user = Auth::user();

        // Ensure user has referral code
        if (!$user->referral_code) {
            $user->update([
                'referral_code' => $this->generateReferralCode()
            ]);
        }

        $baseUrl = url('/');
        $referralCode = $user->referral_code;

        $links = [
            'general' => $baseUrl . '/register?ref=' . $referralCode,
            'company' => $baseUrl . '/company/register?ref=' . $referralCode,
            'customer' => $baseUrl . '/register?ref=' . $referralCode . '&type=customer',
            'artist' => $baseUrl . '/register?ref=' . $referralCode . '&type=artist',
        ];

        return view('dashboard.pages.affiliate.referral-links', compact('title', 'links', 'referralCode'));
    }

    /**
     * Generate Referral Link
     */
    public function generateReferralLink(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'link_type' => 'required|in:general,company,customer,artist'
        ]);

        // In a full implementation, store custom campaign links
        $baseUrl = url('/');
        $referralCode = $user->referral_code;
        $campaign = Str::slug($validated['campaign_name']);

        $linkTypes = [
            'general' => '/register',
            'company' => '/company/register',
            'customer' => '/register?type=customer',
            'artist' => '/register?type=artist',
        ];

        $link = $baseUrl . $linkTypes[$validated['link_type']] .
                (str_contains($linkTypes[$validated['link_type']], '?') ? '&' : '?') .
                'ref=' . $referralCode . '&campaign=' . $campaign;

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }

    /**
     * Marketing Materials
     */
    public function marketingMaterials()
    {
        $title = 'Marketing Materials';
        $user = Auth::user();

        // In a full implementation, provide downloadable marketing materials
        $materials = [
            'banners' => [
                ['name' => '728x90 Banner', 'size' => '728x90', 'file' => 'banner-728x90.png'],
                ['name' => '300x250 Banner', 'size' => '300x250', 'file' => 'banner-300x250.png'],
                ['name' => '160x600 Skyscraper', 'size' => '160x600', 'file' => 'banner-160x600.png'],
            ],
            'email_templates' => [
                ['name' => 'General Invitation', 'description' => 'Invite users to join the platform'],
                ['name' => 'Company Invitation', 'description' => 'Targeted at event companies'],
                ['name' => 'Artist Invitation', 'description' => 'Targeted at DJs and artists'],
            ],
            'social_media' => [
                ['platform' => 'Facebook', 'type' => 'Post Template'],
                ['platform' => 'Instagram', 'type' => 'Story Template'],
                ['platform' => 'Twitter', 'type' => 'Tweet Template'],
            ]
        ];

        return view('dashboard.pages.affiliate.marketing-materials', compact('title', 'materials'));
    }

    /**
     * My Profile
     */
    public function profile()
    {
        $title = 'My Profile';
        $user = Auth::user();

        return view('dashboard.pages.affiliate.profile', compact('title', 'user'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|in:bank_transfer,paypal,crypto',
            'payment_details' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $user->update($validated);

            ActivityLog::log(
                'updated',
                $user,
                'Affiliate profile updated',
                ['user_id' => $user->id]
            );

            DB::commit();

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Performance Report
     */
    public function performanceReport(Request $request)
    {
        $title = 'Performance Report';
        $user = Auth::user();

        $period = $request->get('period', 'month'); // month, quarter, year

        $report = [
            'referrals' => [
                'total' => User::where('referred_by', $user->id)->count() +
                          Company::where('referred_by', $user->id)->count(),
                'users' => User::where('referred_by', $user->id)->count(),
                'companies' => Company::where('referred_by', $user->id)->count(),
                'active' => $this->getActiveReferrals($user->id),
            ],
            'commissions' => [
                'total' => $this->getTotalCommissions($user->id),
                'average_per_referral' => $this->getAverageCommissionPerReferral($user->id),
                'highest_earning_referral' => $this->getHighestEarningReferral($user->id),
            ],
            'conversion' => [
                'clicks' => 0, // Would track link clicks
                'registrations' => User::where('referred_by', $user->id)->count(),
                'conversion_rate' => 0, // Would calculate from clicks
            ]
        ];

        $monthlyData = $this->getMonthlyPerformanceData($user->id);

        return view('dashboard.pages.affiliate.performance-report', compact('title', 'report', 'monthlyData', 'period'));
    }

    // ============ HELPER METHODS ============

    private function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    private function getTotalCommissions($userId)
    {
        // Calculate from payments of referred users/companies
        // In a full implementation, use a commissions table
        return Payment::whereIn('user_id', function($query) use ($userId) {
                $query->select('id')
                    ->from('users')
                    ->where('referred_by', $userId);
            })
            ->where('status', 'completed')
            ->sum('amount') * 0.10; // 10% commission rate
    }

    private function getPendingCommissions($userId)
    {
        return Payment::whereIn('user_id', function($query) use ($userId) {
                $query->select('id')
                    ->from('users')
                    ->where('referred_by', $userId);
            })
            ->where('status', 'pending')
            ->sum('amount') * 0.10;
    }

    private function getPaidCommissions($userId)
    {
        return $this->getTotalCommissions($userId);
    }

    private function getMonthCommissions($userId)
    {
        return Payment::whereIn('user_id', function($query) use ($userId) {
                $query->select('id')
                    ->from('users')
                    ->where('referred_by', $userId);
            })
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount') * 0.10;
    }

    private function getMonthlyCommissions($userId)
    {
        $months = [];
        $commissions = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $commissions[] = Payment::whereIn('user_id', function($query) use ($userId) {
                    $query->select('id')
                        ->from('users')
                        ->where('referred_by', $userId);
                })
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount') * 0.10;
        }

        return [
            'months' => $months,
            'commissions' => $commissions
        ];
    }

    private function getDetailedCommissions($userId, $request)
    {
        // Simplified version - in full implementation, use dedicated commissions table
        $payments = Payment::whereIn('user_id', function($query) use ($userId) {
                $query->select('id')
                    ->from('users')
                    ->where('referred_by', $userId);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $payments;
    }

    private function getActiveReferrals($userId)
    {
        // Users/companies who made at least one booking/payment
        return User::where('referred_by', $userId)
            ->whereHas('bookings')
            ->count();
    }

    private function getAverageCommissionPerReferral($userId)
    {
        $totalCommissions = $this->getTotalCommissions($userId);
        $totalReferrals = User::where('referred_by', $userId)->count() +
                         Company::where('referred_by', $userId)->count();

        return $totalReferrals > 0 ? $totalCommissions / $totalReferrals : 0;
    }

    private function getHighestEarningReferral($userId)
    {
        $user = User::where('referred_by', $userId)
            ->withCount(['payments' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('payments_count', 'desc')
            ->first();

        return $user ? $user->name : 'N/A';
    }

    private function getMonthlyPerformanceData($userId)
    {
        $months = [];
        $referrals = [];
        $commissions = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $referrals[] = User::where('referred_by', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $commissions[] = Payment::whereIn('user_id', function($query) use ($userId) {
                    $query->select('id')
                        ->from('users')
                        ->where('referred_by', $userId);
                })
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount') * 0.10;
        }

        return [
            'months' => $months,
            'referrals' => $referrals,
            'commissions' => $commissions
        ];
    }
}
