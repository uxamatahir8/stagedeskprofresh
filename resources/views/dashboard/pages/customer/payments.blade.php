@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="credit-card" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">View your payment history</p>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <i data-lucide="dollar-sign" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Paid</p>
                            <h4 class="mb-0">${{ number_format($summary['total_paid'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <i data-lucide="clock" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Pending</p>
                            <h4 class="mb-0">${{ number_format($summary['pending_amount'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-soft">
                                <i data-lucide="file-text" class="text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Payments</p>
                            <h4 class="mb-0">{{ $payments->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Payment History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td>
                                    <a href="{{ route('customer.bookings.details', $payment->booking_id) }}" class="text-primary">
                                        #{{ $payment->booking_id }}
                                    </a>
                                </td>
                                <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
                                <td>
                                    <span class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method ?? 'N/A') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{
                                        $payment->status === 'completed' ? 'success' :
                                        ($payment->status === 'pending' ? 'warning' :
                                        ($payment->status === 'failed' ? 'danger' : 'secondary'))
                                    }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    @if($payment->receipt_url)
                                        <a href="{{ $payment->receipt_url }}" target="_blank" class="btn btn-sm btn-light">
                                            <i data-lucide="download"></i> Receipt
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-lucide="credit-card" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No payments found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
@endsection
