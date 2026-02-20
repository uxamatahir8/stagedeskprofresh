<?php

namespace App\Http\Controllers;

use App\Mail\PaymentStatusUpdatedNotification;
use App\Mail\PaymentSubmittedNotification;
use App\Models\Payment;
use App\Models\BookingRequest;
use App\Models\CompanySubscription;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function index()
    {
        $title = 'Payments';
        $roleKey = Auth::user()->role->role_key;

        $paymentsQuery = Payment::with(['user', 'bookingRequest', 'subscription']);

        if ($roleKey === 'customer') {
            $paymentsQuery->where('user_id', Auth::user()->id);
        } elseif ($roleKey === 'company_admin') {
            $paymentsQuery->whereHas('user', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        } elseif ($roleKey === 'artist') {
            $artist = Auth::user()->artist;
            if ($artist) {
                $paymentsQuery->whereHas('bookingRequest', function ($q) use ($artist) {
                    $q->where('assigned_artist_id', $artist->id);
                });
            } else {
                $paymentsQuery->whereRaw('1 = 0'); // No artist profile: show none
            }
        }

        $payments = $paymentsQuery->orderBy('created_at', 'desc')->paginate(15);

        return view('dashboard.pages.payments.index', compact('title', 'payments'));
    }

    public function create()
    {
        $title = 'Add Payment';
        $mode = 'create';

        $bookingRequests = BookingRequest::where('user_id', Auth::user()->id)
            ->whereDoesntHave('payments', function ($q) {
                $q->where('status', 'completed');
            })
            ->get();

        $subscriptions = CompanySubscription::where('company_id', Auth::user()->company_id)
            ->get();

        return view('dashboard.pages.payments.manage', compact('title', 'mode', 'bookingRequests', 'subscriptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_requests_id'      => 'nullable|exists:booking_requests,id',
            'subscription_id'          => 'nullable|exists:company_subscriptions,id',
            'amount'                   => 'required|numeric|min:0.01',
            'currency'                 => 'required|string|size:3',
            'payment_method'           => 'required|in:credit_card,debit_card,bank_transfer,paypal,stripe',
            'transaction_id'           => 'nullable|string|max:255|unique:payments',
            'type'                     => 'required|in:booking,subscription',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::user()->id;
        $validated['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('payments', 'public');
        }

        $payment = Payment::create($validated);
        ActivityLogger::success(
            'payment.create.success',
            'Payment submitted successfully',
            [
                'category' => 'payment',
                'action' => 'create',
                'target' => $payment,
                'user_id' => Auth::id(),
            ]
        );

        $this->sendPaymentSubmissionNotifications($payment, Auth::user());
        $this->createPaymentSubmissionInAppNotifications($payment, Auth::user());

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully. Awaiting verification.');
    }

    public function show(Payment $payment)
    {
        $roleKey = Auth::user()->role->role_key;

        // Check authorization based on role
        if ($roleKey === 'customer' && $payment->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin') {
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only view payments from your company');
            }
        }

        if ($roleKey === 'artist') {
            $artist = Auth::user()->artist;
            if (!$artist || !$payment->bookingRequest || $payment->bookingRequest->assigned_artist_id !== $artist->id) {
                return abort(403, 'Unauthorized - You can only view payments for your assigned bookings');
            }
        }

        $title = 'Payment Details';

        return view('dashboard.pages.payments.show', compact('title', 'payment'));
    }

    public function edit(Payment $payment)
    {
        $roleKey = Auth::user()->role->role_key;

        // Check authorization based on role
        if ($roleKey === 'customer' && $payment->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin') {
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only edit payments from your company');
            }
        }

        if ($roleKey === 'artist') {
            $artist = Auth::user()->artist;
            if (!$artist || !$payment->bookingRequest || $payment->bookingRequest->assigned_artist_id !== $artist->id) {
                return abort(403, 'Unauthorized - You can only edit payments for your assigned bookings');
            }
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')->with('error', 'Cannot edit processed payments.');
        }

        $title = 'Edit Payment';
        $mode = 'edit';

        $bookingRequests = BookingRequest::where('user_id', Auth::user()->id)->get();
        $subscriptions = CompanySubscription::where('company_id', Auth::user()->company_id)->get();

        return view('dashboard.pages.payments.manage', compact('title', 'mode', 'payment', 'bookingRequests', 'subscriptions'));
    }

    public function update(Request $request, Payment $payment)
    {
        $roleKey = Auth::user()->role->role_key;

        // Check authorization based on role
        if ($roleKey === 'customer' && $payment->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin') {
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only update payments from your company');
            }
        }

        if ($roleKey === 'artist') {
            $artist = Auth::user()->artist;
            if (!$artist || !$payment->bookingRequest || $payment->bookingRequest->assigned_artist_id !== $artist->id) {
                return abort(403, 'Unauthorized - You can only update payments for your assigned bookings');
            }
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')->with('error', 'Cannot update processed payments.');
        }

        $validated = $request->validate([
            'booking_requests_id'      => 'nullable|exists:booking_requests,id',
            'subscription_id'          => 'nullable|exists:company_subscriptions,id',
            'amount'                   => 'required|numeric|min:0.01',
            'currency'                 => 'required|string|size:3',
            'payment_method'           => 'required|in:credit_card,debit_card,bank_transfer,paypal,stripe',
            'transaction_id'           => 'nullable|string|max:255|unique:payments,transaction_id,' . $payment->id,
            'type'                     => 'required|in:booking,subscription',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('payments', 'public');
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $roleKey = Auth::user()->role->role_key;
        if ($roleKey === 'master_admin') {
            // allow
        } elseif ($roleKey === 'customer' && $payment->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        } elseif ($roleKey === 'company_admin') {
            if (!$payment->user || $payment->user->company_id !== Auth::user()->company_id) {
                return abort(403, 'Unauthorized');
            }
        } elseif ($roleKey === 'artist') {
            $artist = Auth::user()->artist;
            if (!$artist || !$payment->bookingRequest || $payment->bookingRequest->assigned_artist_id !== $artist->id) {
                return abort(403, 'Unauthorized');
            }
        } else {
            return abort(403, 'Unauthorized');
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')->with('error', 'Cannot delete processed payments.');
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        if (Auth::user()->role->role_key !== 'master_admin') {
            return abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:completed,rejected',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $payment->update(['status' => $validated['status']]);
        ActivityLogger::info(
            'payment.verify.updated',
            'Payment status updated by master admin',
            [
                'category' => 'payment',
                'action' => 'verify',
                'target' => $payment,
                'metadata' => ['status' => $validated['status']],
            ]
        );

        if ($validated['status'] === 'completed') {
            $this->sendPaymentStatusConfirmationToCompanyAdmins($payment, $validated['status'], $validated['notes'] ?? null);
        }
        $this->createPaymentVerificationNotifications($payment, $validated['status']);

        return redirect()->route('payments.index')->with('success', 'Payment status updated to ' . $validated['status']);
    }

    private function sendPaymentSubmissionNotifications(Payment $payment, User $submittedBy): void
    {
        $submittedBy->loadMissing('role');
        $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'user.company']);
        $roleKey = optional($submittedBy->role)->role_key;

        try {
            if ($roleKey === 'customer' && $payment->type === 'booking' && $payment->bookingRequest) {
                $this->notifyMasterAdminsOnSubmission($payment, $submittedBy);
                $this->notifyCompanyAdminsOnBookingPayment($payment, $submittedBy);
                return;
            }

            if ($roleKey === 'company_admin' && $payment->type === 'subscription') {
                $this->notifyMasterAdminsOnSubmission($payment, $submittedBy);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send payment submission notifications', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendPaymentStatusConfirmationToCompanyAdmins(Payment $payment, string $status, ?string $notes = null): void
    {
        $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'user.company']);
        $companyId = $this->resolveCompanyIdFromPayment($payment);

        if (!$companyId) {
            return;
        }

        $companyAdmins = User::where('company_id', $companyId)
            ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
            ->whereNotNull('email')
            ->get();

        foreach ($companyAdmins as $companyAdmin) {
            try {
                Mail::to($companyAdmin->email)->send(
                    new PaymentStatusUpdatedNotification($payment, $status, $notes, $companyAdmin)
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send payment status notification to company admin', [
                    'payment_id' => $payment->id,
                    'company_admin_id' => $companyAdmin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function notifyMasterAdminsOnSubmission(Payment $payment, User $submittedBy): void
    {
        $masterAdmins = User::whereHas('role', fn($q) => $q->where('role_key', 'master_admin'))
            ->whereNotNull('email')
            ->get();

        foreach ($masterAdmins as $admin) {
            try {
                Mail::to($admin->email)->send(
                    new PaymentSubmittedNotification($payment, $submittedBy, $admin, 'master_admin')
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send payment notification to master admin', [
                    'payment_id' => $payment->id,
                    'master_admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function notifyCompanyAdminsOnBookingPayment(Payment $payment, User $submittedBy): void
    {
        $companyId = $payment->bookingRequest?->company_id;

        if (!$companyId) {
            return;
        }

        $companyAdmins = User::where('company_id', $companyId)
            ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
            ->whereNotNull('email')
            ->get();

        foreach ($companyAdmins as $companyAdmin) {
            try {
                Mail::to($companyAdmin->email)->send(
                    new PaymentSubmittedNotification($payment, $submittedBy, $companyAdmin, 'company_admin')
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send booking payment notification to company admin', [
                    'payment_id' => $payment->id,
                    'company_admin_id' => $companyAdmin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function resolveCompanyIdFromPayment(Payment $payment): ?int
    {
        if ($payment->type === 'booking') {
            return $payment->bookingRequest?->company_id ? (int) $payment->bookingRequest->company_id : null;
        }

        if ($payment->type === 'subscription') {
            return $payment->subscription?->company_id ? (int) $payment->subscription->company_id : null;
        }

        return $payment->user?->company_id ? (int) $payment->user->company_id : null;
    }

    private function createPaymentSubmissionInAppNotifications(Payment $payment, User $submittedBy): void
    {
        $payment->loadMissing(['bookingRequest.company', 'subscription.company']);
        $roleKey = optional($submittedBy->role)->role_key;

        if ($roleKey === 'customer' && $payment->type === 'booking') {
            $this->notificationService->notifyMasterAdmins(
                'New Booking Payment Submitted',
                'Customer submitted payment #' . $payment->id . ' for booking #' . ($payment->booking_requests_id ?? 'N/A'),
                'payment_submitted',
                'payment',
                route('payments.show', $payment),
                4,
                ['payment_id' => $payment->id]
            );

            $companyId = $payment->bookingRequest?->company_id;
            if ($companyId) {
                $this->notificationService->notifyCompanyAdmins(
                    (int) $companyId,
                    'Customer Booking Payment Submitted',
                    'A customer submitted booking payment #' . $payment->id . '.',
                    'payment_submitted',
                    'payment',
                    route('payments.show', $payment),
                    3,
                    ['payment_id' => $payment->id]
                );
            }
        }

        if ($roleKey === 'company_admin' && $payment->type === 'subscription') {
            $this->notificationService->notifyMasterAdmins(
                'Subscription Payment Submitted',
                'Company submitted subscription payment #' . $payment->id . '.',
                'payment_submitted',
                'payment',
                route('payments.show', $payment),
                4,
                ['payment_id' => $payment->id]
            );
        }
    }

    private function createPaymentVerificationNotifications(Payment $payment, string $status): void
    {
        $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'user']);

        if ($payment->user_id) {
            $this->notificationService->createForUser(
                (int) $payment->user_id,
                'Payment ' . ucfirst($status),
                'Your payment #' . $payment->id . ' was marked as ' . $status . '.',
                'payment_' . $status,
                'payment',
                route('payments.show', $payment),
                $status === 'completed' ? 3 : 2,
                $this->resolveCompanyIdFromPayment($payment),
                ['payment_id' => $payment->id, 'status' => $status]
            );
        }

        if ($status === 'completed') {
            $companyId = $this->resolveCompanyIdFromPayment($payment);
            if ($companyId) {
                $this->notificationService->notifyCompanyAdmins(
                    $companyId,
                    'Payment Approved',
                    'Payment #' . $payment->id . ' has been approved by master admin.',
                    'payment_completed',
                    'payment',
                    route('payments.show', $payment),
                    3,
                    ['payment_id' => $payment->id]
                );
            }
        }
    }
}
