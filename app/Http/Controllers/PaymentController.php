<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\BookingRequest;
use App\Models\CompanySubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
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
            'company_subscription_id'  => 'nullable|exists:company_subscriptions,id',
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

        Payment::create($validated);

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
            // Company admin can only view payments from their company users
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only view payments from your company');
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
            // Company admin can only edit payments from their company users
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only edit payments from your company');
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
            // Company admin can only update payments from their company users
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only update payments from your company');
            }
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.index')->with('error', 'Cannot update processed payments.');
        }

        $validated = $request->validate([
            'booking_requests_id'      => 'nullable|exists:booking_requests,id',
            'company_subscription_id'  => 'nullable|exists:company_subscriptions,id',
            'amount'                   => 'required|numeric|min:0.01',
            'currency'                 => 'required|string|size:3',
            'payment_method'           => 'required|in:credit_card,debit_card,bank_transfer,paypal,stripe',
            'transaction_id'           => 'nullable|string|max:255|unique:payments,transaction_id,' . $payment->id,
            'type'                     => 'required|in:booking,subscription',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $roleKey = Auth::user()->role->role_key;

        // Check authorization based on role
        if ($roleKey === 'customer' && $payment->user_id !== Auth::user()->id) {
            return abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin') {
            // Company admin can only delete payments from their company users
            $companyId = Auth::user()->company_id;
            if ($payment->user && $payment->user->company_id !== $companyId) {
                return abort(403, 'Unauthorized - You can only delete payments from your company');
            }
        }

        if ($roleKey !== 'master_admin' && $payment->user_id !== Auth::user()->id
            $validated['attachment'] = $request->file('attachment')->store('payments', 'public');
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->user_id !== Auth::user()->id && Auth::user()->role->role_key !== 'master_admin') {
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

        return redirect()->route('payments.index')->with('success', 'Payment status updated to ' . $validated['status']);
    }
}
