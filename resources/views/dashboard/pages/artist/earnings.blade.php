@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="dollar-sign" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Your earnings and withdrawal requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 fs-sm">Available Balance</p>
                    <h4 class="mb-0">€{{ number_format($summary['available_balance'] ?? 0, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 fs-sm">Total Earned</p>
                    <h4 class="mb-0">€{{ number_format($summary['total_earned'] ?? 0, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 fs-sm">Total Withdrawn</p>
                    <h4 class="mb-0">€{{ number_format($summary['total_withdrawn'] ?? 0, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 fs-sm">Pending Withdrawals</p>
                    <h4 class="mb-0">€{{ number_format($summary['pending_withdrawals'] ?? 0, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Withdrawal -->
    @if(($summary['available_balance'] ?? 0) > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Request Withdrawal</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('artist.withdrawal.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Amount (€)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $summary['available_balance'] }}" placeholder="0.00" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Notes (optional)</label>
                    <input type="text" name="artist_notes" class="form-control" placeholder="Optional message">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Withdrawal Requests -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Withdrawal Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Processed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawalRequests ?? [] as $wr)
                            <tr>
                                <td>€{{ number_format($wr->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $wr->status === 'paid' ? 'success' : ($wr->status === 'approved' ? 'info' : ($wr->status === 'rejected' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($wr->status) }}
                                    </span>
                                </td>
                                <td>{{ $wr->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $wr->processed_at ? $wr->processed_at->format('M d, Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No withdrawal requests yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Monthly Earnings</h5>
        </div>
        <div class="card-body">
            <canvas id="earningsChart" height="80"></canvas>
        </div>
    </div>

    <!-- Earnings History -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Earnings History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>Amount</th>
                            <th>Share %</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artistEarnings as $e)
                            <tr>
                                <td>#{{ $e->bookingRequest->tracking_code ?? $e->booking_request_id }}</td>
                                <td>€{{ number_format($e->amount, 2) }}</td>
                                <td>{{ $e->share_percentage }}%</td>
                                <td>
                                    <span class="badge bg-{{ $e->status === 'available' ? 'success' : ($e->status === 'paid_out' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $e->status)) }}
                                    </span>
                                </td>
                                <td>{{ $e->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">No earnings yet. Earnings are added when a booking payment is completed and you have a share % set.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($artistEarnings->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $artistEarnings->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('earningsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyEarnings['months'] ?? []),
                datasets: [{
                    label: 'Earnings (€)',
                    data: @json($monthlyEarnings['earnings'] ?? []),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    }
</script>
@endpush
