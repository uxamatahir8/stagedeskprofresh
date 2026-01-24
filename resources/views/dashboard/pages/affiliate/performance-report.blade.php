@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="bar-chart" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Analyze your performance and conversions</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($metrics['total_clicks']) }}</h2>
                    <p class="text-muted mb-0">Total Clicks</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($metrics['conversions']) }}</h2>
                    <p class="text-muted mb-0">Conversions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($metrics['conversion_rate'], 1) }}%</h2>
                    <p class="text-muted mb-0">Conversion Rate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">${{ number_format($metrics['avg_commission'], 2) }}</h2>
                    <p class="text-muted mb-0">Avg Commission</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Monthly Performance</h5>
        </div>
        <div class="card-body">
            <canvas id="performanceChart" height="80"></canvas>
        </div>
    </div>

    <!-- Top Performing Campaigns -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Top Campaigns</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Clicks</th>
                                    <th>Conversions</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCampaigns ?? [] as $campaign)
                                    <tr>
                                        <td>{{ $campaign->name }}</td>
                                        <td>{{ $campaign->clicks }}</td>
                                        <td>{{ $campaign->conversions }}</td>
                                        <td>${{ number_format($campaign->revenue, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No campaigns yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Top Referrals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topReferrals ?? [] as $referral)
                                    <tr>
                                        <td>{{ $referral->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $referral->type === 'user' ? 'primary' : 'info' }} badge-sm">
                                                {{ ucfirst($referral->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success badge-sm">Active</span>
                                        </td>
                                        <td>${{ number_format($referral->commission, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No referrals yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Traffic Sources -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Traffic Sources</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Clicks</th>
                            <th>Conversions</th>
                            <th>Conversion Rate</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trafficSources ?? [] as $source)
                            <tr>
                                <td><strong>{{ $source->name }}</strong></td>
                                <td>{{ number_format($source->clicks) }}</td>
                                <td>{{ number_format($source->conversions) }}</td>
                                <td>{{ number_format($source->conversion_rate, 1) }}%</td>
                                <td>${{ number_format($source->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No traffic data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('performanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($performanceData['months'] ?? []),
                datasets: [{
                    label: 'Commissions ($)',
                    data: @json($performanceData['commissions'] ?? []),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Conversions',
                    data: @json($performanceData['conversions'] ?? []),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    }
</script>
@endpush
