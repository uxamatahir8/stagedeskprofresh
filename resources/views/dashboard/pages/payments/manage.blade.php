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
            <div>
                @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-info me-2">Payment Methods</a>
                @endif
                <a href="{{ route('payments.index') }}" class="btn btn-primary">Payments List</a>
            </div>
        </div>

        <div class="card-body">
            @php $preselect = $preselect ?? []; @endphp
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
                        @php
                            $roleKey = auth()->user()->role->role_key;
                            $selectedType = old('type', $payment->type ?? ($preselect['type'] ?? ($roleKey === 'company_admin' ? 'subscription' : 'booking')));
                        @endphp
                        <select name="type" class="form-control form-select required">
                            <option value="">Select Type</option>
                            <option value="booking" {{ $selectedType == 'booking' ? 'selected' : '' }} {{ $roleKey === 'company_admin' ? 'disabled' : '' }}>
                                Booking Payment
                            </option>
                            <option value="subscription" {{ $selectedType == 'subscription' ? 'selected' : '' }} {{ $roleKey === 'customer' ? 'disabled' : '' }}>
                                Subscription Payment
                            </option>
                        </select>
                        @if($roleKey === 'customer')
                            <small class="text-muted">Customers can submit booking payments only.</small>
                        @elseif($roleKey === 'company_admin')
                            <small class="text-muted">Company admins can submit subscription payments to master admin.</small>
                        @endif
                    </div>

                    <div class="col-lg-6 mb-3 payment-booking-wrap">
                        <label class="col-form-label">Booking Request</label>
                        <select name="booking_requests_id" class="form-control form-select">
                            <option value="">Select Booking (Optional)</option>
                            @foreach ($bookingRequests as $booking)
                                <option value="{{ $booking->id }}"
                                    {{ old('booking_requests_id', $payment->booking_requests_id ?? '') == $booking->id ? 'selected' : '' }}>
                                    #{{ $booking->tracking_code ?? $booking->id }} - {{ $booking->name }} {{ $booking->surname }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3 payment-subscription-wrap">
                        <label class="col-form-label">Subscription <span class="text-danger">*</span> (for subscription payment)</label>
                        <select name="subscription_id" class="form-control form-select">
                            <option value="">Select Subscription</option>
                            @if(isset($subscriptions))
                                @foreach ($subscriptions as $sub)
                                    <option value="{{ $sub->id }}"
                                        {{ old('subscription_id', $payment->subscription_id ?? ($preselect['subscription_id'] ?? '')) == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->package->name ?? 'Package #' . $sub->package_id }} — {{ $sub->start_date->format('M d, Y') }} to {{ $sub->end_date->format('M d, Y') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control required" step="0.01" min="0.01"
                            placeholder="Enter amount"
                            value="{{ old('amount', $payment->amount ?? ($preselect['amount'] ?? '')) }}">
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
                        <label class="col-form-label">Receive-To Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method_id" class="form-control form-select required">
                            <option value="">Select Method</option>
                            @foreach(($paymentMethods ?? collect()) as $method)
                                <option value="{{ $method->id }}" {{ (string) old('payment_method_id', $payment->payment_method_id ?? '') === (string) $method->id ? 'selected' : '' }}>
                                    {{ $method->display_name }} ({{ ucfirst(str_replace('_', ' ', $method->method_type)) }})
                                </option>
                            @endforeach
                        </select>
                        @if(($paymentMethods ?? collect())->isEmpty())
                            <small class="text-danger">No active receiving payment methods are currently available.</small>
                        @endif
                        <small class="text-muted">
                            @if(auth()->user()->role->role_key === 'customer')
                                Select a company payment method to submit your booking payment manually.
                            @elseif(auth()->user()->role->role_key === 'company_admin')
                                Select a master admin payment method to pay subscriptions manually.
                            @endif
                        </small>
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
    @push('scripts')
        <script>
            (function () {
                var typeSelect = document.querySelector('select[name="type"]');
                var bookingWrap = document.querySelector('.payment-booking-wrap');
                var subscriptionWrap = document.querySelector('.payment-subscription-wrap');
                var bookingSelect = document.querySelector('select[name="booking_requests_id"]');
                var subscriptionSelect = document.querySelector('select[name="subscription_id"]');

                function updateTypeVisibility() {
                    if (!typeSelect) return;
                    var isBooking = typeSelect.value === 'booking';
                    if (bookingWrap) bookingWrap.style.display = isBooking ? '' : 'none';
                    if (subscriptionWrap) subscriptionWrap.style.display = isBooking ? 'none' : '';
                    if (!isBooking && bookingSelect) bookingSelect.value = '';
                    if (isBooking && subscriptionSelect) subscriptionSelect.value = '';
                }

                if (typeSelect) {
                    typeSelect.addEventListener('change', updateTypeVisibility);
                    updateTypeVisibility();
                }
            })();
        </script>
    @endpush
@endsection
