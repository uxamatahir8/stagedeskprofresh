@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">Payment Details</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('payments.index') }}">Payments</a>
                </li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment #{{ $payment->id }}</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Payment Type</label>
                            <p class="mb-0">
                                <span class="badge badge-{{ $payment->type == 'booking' ? 'primary' : 'info' }}">
                                    {{ ucfirst($payment->type) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Status</label>
                            <p class="mb-0">
                                @php
                                    $statusClass = match($payment->status) {
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Amount</label>
                            <p class="mb-0 fw-bold fs-5">
                                {{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Payment Method</label>
                            <p class="mb-0">
                                <i class="ti ti-credit-card"></i>
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Transaction ID</label>
                            <p class="mb-0">
                                <code>{{ $payment->transaction_id ?? 'N/A' }}</code>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Payment Date</label>
                            <p class="mb-0">
                                {{ $payment->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    @if($payment->bookingRequest)
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="text-muted mb-1">Related Booking</label>
                                <p class="mb-0">
                                    <a href="{{ route('bookings.show', $payment->bookingRequest->id) }}" class="text-primary">
                                        Booking #{{ $payment->bookingRequest->id }} - {{ $payment->bookingRequest->name }}
                                        {{ $payment->bookingRequest->surname }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($payment->attachment)
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="text-muted mb-1">Receipt/Attachment</label>
                                <p class="mb-0">
                                    <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="ti ti-download"></i> Download Receipt
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Actions</h4>
                </div>

                <div class="card-body">
                    @if($payment->status === 'pending')
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning w-100 mb-2">
                            <i class="ti ti-pencil"></i> Edit Payment
                        </a>

                        @if(Auth::user()->hasRole('master_admin'))
                            <form action="{{ route('payments.verify', $payment) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="ti ti-check"></i> Verify Payment
                                </button>
                            </form>

                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?')">
                                    <i class="ti ti-trash"></i> Reject Payment
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-info-circle"></i>
                            <strong>{{ ucfirst($payment->status) }} Payment</strong>
                            <p class="mb-0 mt-2">This payment cannot be edited as it has already been {{ $payment->status }}.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Information</h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted mb-1">Payment ID</label>
                        <p class="mb-0">
                            <code>#{{ $payment->id }}</code>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Created</label>
                        <p class="mb-0">
                            {{ $payment->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted mb-1">Last Updated</label>
                        <p class="mb-0">
                            {{ $payment->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Back</h4>
                </div>

                <div class="card-body">
                    <a href="{{ route('payments.index') }}" class="btn btn-primary w-100">
                        <i class="ti ti-arrow-left"></i> Back to Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
