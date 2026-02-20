<?php

namespace App\Http\Controllers;

use App\Mail\PaymentStatusUpdatedNotification;
use App\Mail\PaymentSubmittedNotification;
use App\Models\Payment;
use App\Models\BookingRequest;
use App\Models\CompanySubscription;
use App\Models\PaymentMethod;
use App\Models\ActivityLog;
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

        $paymentsQuery = Payment::with(['user', 'bookingRequest', 'subscription', 'paymentMethod']);

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
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        $bookingRequests = collect();
        $subscriptions = collect();

        if ($roleKey === 'customer') {
            $bookingRequests = BookingRequest::where('user_id', $user->id)
                ->whereDoesntHave('payments', function ($q) {
                    $q->where('status', 'completed');
                })
                ->with('company')
                ->get();
        }

        if ($roleKey === 'company_admin') {
            $subscriptions = CompanySubscription::where('company_id', $user->company_id)->get();
        }

        $paymentMethods = $this->resolveAvailablePaymentMethods($roleKey, $user->company_id, $bookingRequests);

        return view('dashboard.pages.payments.manage', compact('title', 'mode', 'bookingRequests', 'subscriptions', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_requests_id'      => 'nullable|exists:booking_requests,id',
            'subscription_id'          => 'nullable|exists:company_subscriptions,id',
            'amount'                   => 'required|numeric|min:0.01',
            'currency'                 => 'required|string|size:3',
            'payment_method_id'        => 'required|exists:payment_methods,id',
            'transaction_id'           => 'nullable|string|max:255|unique:payments',
            'type'                     => 'required|in:booking,subscription',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $user = Auth::user();
        $roleKey = $user->role->role_key;

        if ($roleKey === 'customer' && $validated['type'] !== 'booking') {
            return back()->withInput()->with('error', 'Customers can submit booking payments only.');
        }

        if ($roleKey === 'company_admin' && $validated['type'] !== 'subscription') {
            return back()->withInput()->with('error', 'Company admins can submit company subscription payments only.');
        }

        if ($validated['type'] === 'booking' && empty($validated['booking_requests_id'])) {
            return back()->withInput()->with('error', 'Please select a booking for booking payment.');
        }

        if ($validated['type'] === 'subscription' && empty($validated['subscription_id'])) {
            return back()->withInput()->with('error', 'Please select a subscription for subscription payment.');
        }

        $method = PaymentMethod::findOrFail($validated['payment_method_id']);
        $this->assertMethodUsable($method, $roleKey, $validated, $user);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['payment_method'] = $method->method_type;
        $validated['submitted_to_scope'] = $method->scope;
        $validated['submitted_to_company_id'] = $method->company_id;

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('payments', 'public');
        }

        $payment = Payment::create($validated);
        ActivityLog::log('created', $payment, 'Manual payment submitted', [
            'payment_id' => $payment->id,
            'payment_method_id' => $payment->payment_method_id,
            'company_id' => $payment->submitted_to_company_id,
            'submitted_to_scope' => $payment->submitted_to_scope,
            'booking_id' => $payment->booking_requests_id,
            'amount' => $payment->amount,
        ]);
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
        $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'paymentMethod']);
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
        $payment->loadMissing('paymentMethod');
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
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        $bookingRequests = collect();
        $subscriptions = collect();

        if ($roleKey === 'customer') {
            $bookingRequests = BookingRequest::where('user_id', $user->id)->with('company')->get();
        }

        if ($roleKey === 'company_admin') {
            $subscriptions = CompanySubscription::where('company_id', $user->company_id)->get();
        }

        $paymentMethods = $this->resolveAvailablePaymentMethods($roleKey, $user->company_id, $bookingRequests);

        return view('dashboard.pages.payments.manage', compact('title', 'mode', 'payment', 'bookingRequests', 'subscriptions', 'paymentMethods'));
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
            'payment_method_id'        => 'required|exists:payment_methods,id',
            'transaction_id'           => 'nullable|string|max:255|unique:payments,transaction_id,' . $payment->id,
            'type'                     => 'required|in:booking,subscription',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        if ($roleKey === 'customer' && $validated['type'] !== 'booking') {
            return back()->withInput()->with('error', 'Customers can submit booking payments only.');
        }

        if ($roleKey === 'company_admin' && $validated['type'] !== 'subscription') {
            return back()->withInput()->with('error', 'Company admins can submit company subscription payments only.');
        }

        if ($validated['type'] === 'booking' && empty($validated['booking_requests_id'])) {
            return back()->withInput()->with('error', 'Please select a booking for booking payment.');
        }

        if ($validated['type'] === 'subscription' && empty($validated['subscription_id'])) {
            return back()->withInput()->with('error', 'Please select a subscription for subscription payment.');
        }

        $method = PaymentMethod::findOrFail($validated['payment_method_id']);
        $this->assertMethodUsable($method, $roleKey, $validated, Auth::user());
        $validated['payment_method'] = $method->method_type;
        $validated['submitted_to_scope'] = $method->scope;
        $validated['submitted_to_company_id'] = $method->company_id;

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
            'status' => 'required|in:completed,failed',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $payment->update([
            'status' => $validated['status'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => $validated['notes'] ?? null,
        ]);
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
            $this->syncSubscriptionLifecycleFromPayment($payment);
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
                $this->notifyCompanyAdminsOnBookingPayment($payment, $submittedBy);
                ActivityLog::log('updated', $payment, 'Payment notification routed to company admins', [
                    'payment_id' => $payment->id,
                    'company_id' => $payment->bookingRequest->company_id,
                    'routing' => 'customer_to_company_admin',
                ]);
                return;
            }

            if ($roleKey === 'company_admin' && $payment->type === 'subscription') {
                $this->notifyMasterAdminsOnSubmission($payment, $submittedBy);
                ActivityLog::log('updated', $payment, 'Payment notification routed to master admins', [
                    'payment_id' => $payment->id,
                    'company_id' => $submittedBy->company_id,
                    'routing' => 'company_admin_to_master_admin',
                ]);
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

    private function resolveAvailablePaymentMethods(string $roleKey, ?int $companyId, $bookingRequests)
    {
        if ($roleKey === 'company_admin') {
            return PaymentMethod::masterOwned()->where('is_active', true)->get();
        }

        if ($roleKey === 'customer') {
            $companyIds = collect($bookingRequests)->pluck('company_id')->filter()->unique()->values();
            return PaymentMethod::query()
                ->where('scope', 'company')
                ->whereIn('company_id', $companyIds)
                ->where('is_active', true)
                ->get();
        }

        if ($roleKey === 'master_admin') {
            return PaymentMethod::masterOwned()->where('is_active', true)->get();
        }

        return collect();
    }

    private function assertMethodUsable(PaymentMethod $method, string $roleKey, array $validated, User $user): void
    {
        if ($roleKey === 'company_admin') {
            if ($method->scope !== 'master' || !$method->is_active) {
                abort(422, 'Selected payment method is not available for this payment.');
            }

            if (!empty($validated['subscription_id'])) {
                $subscription = CompanySubscription::find($validated['subscription_id']);
                if (!$subscription || (int) $subscription->company_id !== (int) $user->company_id) {
                    abort(422, 'Selected subscription does not belong to your company.');
                }
            }
            return;
        }

        if ($roleKey === 'customer') {
            if ($method->scope !== 'company' || !$method->is_active) {
                abort(422, 'Selected payment method is not available for this payment.');
            }

            if (!empty($validated['booking_requests_id'])) {
                $booking = BookingRequest::find($validated['booking_requests_id']);
                if (!$booking || (int) $booking->user_id !== (int) $user->id) {
                    abort(422, 'Selected booking is invalid for your account.');
                }
                if ((int) $booking->company_id !== (int) $method->company_id) {
                    abort(422, 'Selected payment method does not belong to booking company.');
                }
            }
            return;
        }

        if ($roleKey === 'master_admin' && !$method->is_active) {
            abort(422, 'Selected payment method is inactive.');
        }
    }

    private function syncSubscriptionLifecycleFromPayment(Payment $payment): void
    {
        $payment->loadMissing(['subscription.package', 'user']);

        if ($payment->type !== 'subscription' || !$payment->subscription) {
            return;
        }

        $subscription = $payment->subscription;
        $package = $subscription->package;

        if (!$package) {
            throw new \RuntimeException('Cannot verify subscription payment without package details.');
        }

        if ((int) $subscription->company_id !== (int) ($payment->user?->company_id ?? 0)) {
            throw new \RuntimeException('Subscription does not belong to payer company.');
        }

        $expectedAmount = (float) ($package->price ?? 0);
        if ($expectedAmount > 0 && (float) $payment->amount + 0.01 < $expectedAmount) {
            throw new \RuntimeException('Submitted payment amount is lower than subscription package price.');
        }

        $startDate = $subscription->end_date && $subscription->end_date->isFuture()
            ? $subscription->end_date->copy()
            : now();

        $durationType = strtolower((string) ($package->duration_type ?? 'monthly'));
        $endDate = match ($durationType) {
            'weekly' => $startDate->copy()->addWeek(),
            'yearly' => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonth(),
        };

        CompanySubscription::where('company_id', $subscription->company_id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $subscription->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        ActivityLog::log(
            'updated',
            $subscription,
            'Subscription activated from verified payment',
            [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
                'company_id' => $subscription->company_id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ]
        );
    }
}
