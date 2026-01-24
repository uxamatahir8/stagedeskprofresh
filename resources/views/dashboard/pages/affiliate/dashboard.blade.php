@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="users" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Affiliate Portal - Track Your Referrals & Earnings</p>
        </div>
        <div class="text-end">
            <a href="{{ route('affiliate.referral-links') }}" class="btn btn-primary btn-sm">
                <i data-lucide="link"></i> Get Referral Links
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <i data-lucide="users" class="text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Referrals</p>
                            <h4 class="mb-0">{{ number_format($stats['total_referrals']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <i data-lucide="dollar-sign" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Commissions</p>
                            <h4 class="mb-0">${{ number_format($stats['total_commissions'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <i data-lucide="clock" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Pending</p>
                            <h4 class="mb-0">${{ number_format($stats['pending_commissions'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-soft">
                                <i data-lucide="trending-up" class="text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">This Month</p>
                            <h4 class="mb-0">${{ number_format($stats['this_month_commissions'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Referrals & Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Referrals</h5>
                    <a href="{{ route('affiliate.referrals') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUserReferrals ?? [] as $referral)
                                    <tr>
                                        <td>{{ $referral->name }}</td>
                                        <td><span class="badge bg-primary">User</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                @forelse($recentCompanyReferrals ?? [] as $referral)
                                    <tr>
                                        <td>{{ $referral->name }}</td>
                                        <td><span class="badge bg-info">Company</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                @if((count($recentUserReferrals ?? []) + count($recentCompanyReferrals ?? [])) === 0)
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No referrals yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('affiliate.referral-links') }}" class="btn btn-outline-primary">
                            <i data-lucide="link"></i> Get Referral Links
                        </a>
                        <a href="{{ route('affiliate.marketing-materials') }}" class="btn btn-outline-info">
                            <i data-lucide="image"></i> Marketing Materials
                        </a>
                        <a href="{{ route('affiliate.commissions') }}" class="btn btn-outline-success">
                            <i data-lucide="dollar-sign"></i> View Commissions
                        </a>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#payoutModal">
                            <i data-lucide="credit-card"></i> Request Payout
                        </button>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <p class="text-muted mb-2 fs-sm">Your Referral Code:</p>
                        <div class="d-flex align-items-center">
                            <code class="flex-grow-1">{{ Auth::user()->referral_code }}</code>
                            <button class="btn btn-sm btn-light ms-2" onclick="navigator.clipboard.writeText('{{ Auth::user()->referral_code }}')">
                                <i data-lucide="copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Monthly Commission Performance</h5>
                    <a href="{{ route('affiliate.performance') }}" class="btn btn-sm btn-light">Full Report</a>
                </div>
                <div class="card-body">
                    <canvas id="monthlyCommissionsChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Request Modal -->
    <div class="modal fade" id="payoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('affiliate.payout.request') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Request Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Available Balance</label>
                            <h4 class="text-success">${{ number_format($stats['paid_commissions'], 2) }}</h4>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" min="50" step="0.01" required>
                            <small class="text-muted">Minimum payout: $50.00</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                                <option value="crypto">Cryptocurrency</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Details</label>
                            <textarea name="payment_details" class="form-control" rows="3" required placeholder="Enter your payment details (account number, PayPal email, wallet address, etc.)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyCommissionsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyCommissions['months'] ?? []),
                datasets: [{
                    label: 'Commissions ($)',
                    data: @json($monthlyCommissions['commissions'] ?? []),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
@endpush
