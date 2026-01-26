@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i data-lucide="home" style="width: 14px; height: 14px;"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('payments.index') }}">Payments</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('payments.index') }}" class="btn btn-primary">Payments List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode === 'edit' ? route('payments.update', $payment) : route('payments.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Payment Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-control form-select required">
                            <option value="">Select Type</option>
                            <option value="booking" {{ old('type', $payment->type ?? '') == 'booking' ? 'selected' : '' }}>
                                Booking Payment
                            </option>
                            <option value="subscription" {{ old('type', $payment->type ?? '') == 'subscription' ? 'selected' : '' }}>
                                Subscription Payment
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Booking Request</label>
                        <select name="booking_requests_id" class="form-control form-select">
                            <option value="">Select Booking (Optional)</option>
                            @foreach ($bookingRequests as $booking)
                                <option value="{{ $booking->id }}"
                                    {{ old('booking_requests_id', $payment->booking_requests_id ?? '') == $booking->id ? 'selected' : '' }}>
                                    #{{ $booking->id }} - {{ $booking->name }} {{ $booking->surname }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Subscription</label>
                        <select name="company_subscription_id" class="form-control form-select">
                            <option value="">Select Subscription (Optional)</option>
                            @if(isset($subscriptions))
                                @foreach ($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}"
                                        {{ old('company_subscription_id', $payment->company_subscription_id ?? '') == $subscription->id ? 'selected' : '' }}>
                                        {{ $subscription->plan }} ({{ $subscription->package_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control required" step="0.01" min="0.01"
                            placeholder="Enter amount"
                            value="{{ old('amount', $payment->amount ?? '') }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Currency <span class="text-danger">*</span></label>
                        <select name="currency" class="form-control form-select required">
                            <option value="">Select Currency</option>
                            <option value="USD" {{ old('currency', $payment->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('currency', $payment->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ old('currency', $payment->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP</option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control form-select required">
                            <option value="">Select Method</option>
                            <option value="credit_card" {{ old('payment_method', $payment->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ old('payment_method', $payment->payment_method ?? '') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="bank_transfer" {{ old('payment_method', $payment->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paypal" {{ old('payment_method', $payment->payment_method ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="stripe" {{ old('payment_method', $payment->payment_method ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control"
                            placeholder="Enter transaction ID"
                            value="{{ old('transaction_id', $payment->transaction_id ?? '') }}">
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label class="col-form-label">Attachment (Receipt)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.png">
                        @if($mode === 'edit' && $payment->attachment)
                            <small class="text-muted">Current: <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank">Download</a></small>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-{{ $mode === 'edit' ? 'warning' : 'primary' }}">
                        <i data-lucide="{{ $mode === 'edit' ? 'pencil' : 'plus' }}" style="width: 16px; height: 16px;"></i>
                        {{ $mode === 'edit' ? 'Update' : 'Record' }} Payment
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
