<?php

namespace App\Http\Controllers;

use App\Models\ArtistWithdrawalRequest;
use App\Models\ArtistEarning;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtistWithdrawalController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    /**
     * Company admin: list withdrawal requests for their company.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role->role_key !== 'company_admin' && $user->role->role_key !== 'master_admin') {
            abort(403);
        }

        $companyId = $user->role->role_key === 'company_admin' ? $user->company_id : $request->query('company_id');

        $query = ArtistWithdrawalRequest::with(['artist.user', 'company'])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(15);
        $title = 'Artist Withdrawal Requests';

        return view('dashboard.pages.artist-withdrawals.index', compact('title', 'withdrawals'));
    }

    /**
     * Approve a withdrawal request (company admin).
     */
    public function approve(ArtistWithdrawalRequest $withdrawal)
    {
        $user = Auth::user();
        if ($user->role->role_key !== 'company_admin' && $user->role->role_key !== 'master_admin') {
            abort(403);
        }
        if ($user->role->role_key === 'company_admin' && (int) $withdrawal->company_id !== (int) $user->company_id) {
            abort(403);
        }
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $available = ArtistEarning::getAvailableBalanceForArtist($withdrawal->artist_id);
        if ($available < (float) $withdrawal->amount) {
            return back()->with('error', 'Artist available balance is insufficient for this withdrawal.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'processed_by' => $user->id,
            'processed_at' => now(),
        ]);

        $this->notificationService->createForUser(
            $withdrawal->artist->user_id,
            'Withdrawal Approved',
            'Your withdrawal request of ' . number_format($withdrawal->amount, 2) . ' has been approved. Payment will be processed by the company.',
            'withdrawal_approved',
            'financial',
            route('artist.earnings'),
            2,
            $withdrawal->artist->company_id,
            ['withdrawal_id' => $withdrawal->id]
        );

        return back()->with('success', 'Withdrawal request approved.');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, ArtistWithdrawalRequest $withdrawal)
    {
        $user = Auth::user();
        if ($user->role->role_key !== 'company_admin' && $user->role->role_key !== 'master_admin') {
            abort(403);
        }
        if ($user->role->role_key === 'company_admin' && (int) $withdrawal->company_id !== (int) $user->company_id) {
            abort(403);
        }
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $withdrawal->update([
            'status' => 'rejected',
            'processed_by' => $user->id,
            'processed_at' => now(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        $this->notificationService->createForUser(
            $withdrawal->artist->user_id,
            'Withdrawal Rejected',
            'Your withdrawal request of ' . number_format($withdrawal->amount, 2) . ' was rejected.' . ($request->input('admin_notes') ? ' Note: ' . $request->input('admin_notes') : ''),
            'withdrawal_rejected',
            'financial',
            route('artist.earnings'),
            2,
            $withdrawal->artist->company_id,
            ['withdrawal_id' => $withdrawal->id]
        );

        return back()->with('success', 'Withdrawal request rejected.');
    }

    /**
     * Mark withdrawal as paid (company admin paid the artist).
     */
    public function markPaid(Request $request, ArtistWithdrawalRequest $withdrawal)
    {
        $user = Auth::user();
        if ($user->role->role_key !== 'company_admin' && $user->role->role_key !== 'master_admin') {
            abort(403);
        }
        if ($user->role->role_key === 'company_admin' && (int) $withdrawal->company_id !== (int) $user->company_id) {
            abort(403);
        }
        if ($withdrawal->status !== 'approved') {
            return back()->with('error', 'Only approved withdrawals can be marked as paid.');
        }

        DB::transaction(function () use ($withdrawal, $user) {
            $withdrawal->update([
                'status' => 'paid',
                'processed_by' => $user->id,
                'processed_at' => now(),
            ]);

            $remaining = (float) $withdrawal->amount;
            $earnings = ArtistEarning::where('artist_id', $withdrawal->artist_id)
                ->where('status', 'available')
                ->orderBy('id')
                ->get();

            foreach ($earnings as $earning) {
                if ($remaining <= 0) {
                    break;
                }
                $available = (float) $earning->amount - (float) ($earning->amount_paid_out ?? 0);
                if ($available <= 0) {
                    continue;
                }
                $deduct = min($remaining, $available);
                $remaining -= $deduct;
                $newPaidOut = (float) ($earning->amount_paid_out ?? 0) + $deduct;
                $earning->update([
                    'amount_paid_out' => $newPaidOut,
                    'paid_out_at' => $newPaidOut >= (float) $earning->amount ? now() : null,
                    'status' => $newPaidOut >= (float) $earning->amount ? 'paid_out' : 'available',
                ]);
            }
        });

        $this->notificationService->createForUser(
            $withdrawal->artist->user_id,
            'Withdrawal Paid',
            'Your withdrawal of ' . number_format($withdrawal->amount, 2) . ' has been paid.',
            'withdrawal_paid',
            'financial',
            route('artist.earnings'),
            2,
            $withdrawal->artist->company_id,
            ['withdrawal_id' => $withdrawal->id]
        );

        return back()->with('success', 'Withdrawal marked as paid.');
    }
}
