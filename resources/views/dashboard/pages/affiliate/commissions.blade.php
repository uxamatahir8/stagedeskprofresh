@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="dollar-sign" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Track your commission earnings</p>
        </div>
    </div>

    <!-- Summary Cards -->
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
                            <p class="text-muted mb-1 fs-sm">Total Earned</p>
                            <h4 class="mb-0">${{ number_format($summary['total_commissions'], 2) }}</h4>
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
                            <h4 class="mb-0">${{ number_format($summary['pending_commissions'], 2) }}</h4>
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
                                <i data-lucide="check-circle" class="text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Paid Out</p>
                            <h4 class="mb-0">${{ number_format($summary['paid_commissions'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Commission History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Referral</th>
                            <th>Type</th>
                            <th>Base Amount</th>
                            <th>Rate</th>
                            <th>Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                <td>{{ $commission->referral_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $commission->type === 'user' ? 'primary' : 'info' }}">
                                        {{ ucfirst($commission->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>${{ number_format($commission->base_amount ?? 0, 2) }}</td>
                                <td>{{ $commission->commission_rate ?? 10 }}%</td>
                                <td><strong>${{ number_format($commission->amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($commission->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-lucide="dollar-sign" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No commissions yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($commissions->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
@endsection
