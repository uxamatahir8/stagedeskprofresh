@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="layout-dashboard" class="me-2" style="width: 20px; height: 20px;"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Welcome back, <strong>{{ Auth::user()->name }}</strong>! Here's what's happening
                @if(isset($filter) && $filter !== 'this_month')
                    <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $filter)) }}</span>
                @endif
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-light btn-sm" onclick="location.reload()">
                <i data-lucide="refresh-cw" style="width: 16px; height: 16px;"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                    <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                    @if(isset($filter))
                        {{ ucwords(str_replace('_', ' ', $filter)) }}
                    @else
                        This Month
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px;">
                    <li><a class="dropdown-item filter-option" href="{{ route('dashboard', ['filter' => 'today']) }}">Today</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('dashboard', ['filter' => 'this_week']) }}">This Week</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('dashboard', ['filter' => 'this_month']) }}">This Month</a></li>
                    <li><a class="dropdown-item filter-option" href="{{ route('dashboard', ['filter' => 'this_year']) }}">This Year</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="px-3 py-2">
                        <form action="{{ route('dashboard') }}" method="GET" id="customDateForm">
                            <input type="hidden" name="filter" value="custom">
                            <label class="form-label small fw-semibold">Custom Range</label>
                            <div class="mb-2">
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ request('start_date') }}" required>
                            </div>
                            <div class="mb-2">
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ request('end_date') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i data-lucide="filter" style="width: 16px; height: 16px;"></i> Apply Filter
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Important Alerts --}}
    @if(!empty($dashboardAlerts ?? []))
        <div class="row g-3 mb-4">
            @foreach($dashboardAlerts as $alert)
                <div class="col-lg-4">
                    <div class="alert alert-{{ $alert['type'] }} border-0 shadow-sm mb-0 d-flex align-items-start">
                        <i data-lucide="{{ $alert['icon'] }}" class="me-2 mt-1" style="width:18px;height:18px;"></i>
                        <div>
                            <h6 class="mb-1">{{ $alert['title'] }}</h6>
                            <p class="mb-0 small">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Main Stats Cards --}}
    <div class="row g-3 mb-4">
        @php
            $mainStats = [];

            if(Auth::user()->role->role_key === 'master_admin') {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'from last month'],
                    ['title' => 'Pending Requests', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'awaiting action'],
                    ['title' => 'Revenue', 'value' => '$' . number_format($stats['total_revenue'] ?? 0), 'icon' => 'dollar-sign', 'color' => 'success', 'change' => '+23.1%', 'changeType' => 'up', 'subtitle' => 'total earnings'],
                    ['title' => 'Active Companies', 'value' => $stats['active_companies'] ?? 0, 'icon' => 'building-2', 'color' => 'info', 'change' => '+5.2%', 'changeType' => 'up', 'subtitle' => 'registered companies'],
                ];
            } elseif(Auth::user()->role->role_key === 'company_admin') {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'this month'],
                    ['title' => 'Pending', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'awaiting response'],
                    ['title' => 'Active Artists', 'value' => $stats['active_artists'] ?? 0, 'icon' => 'mic', 'color' => 'info', 'change' => '+3.5%', 'changeType' => 'up', 'subtitle' => 'available now'],
                    ['title' => 'Completed', 'value' => $stats['completed_bookings'] ?? 0, 'icon' => 'check', 'color' => 'success', 'change' => '+15.7%', 'changeType' => 'up', 'subtitle' => 'successful events'],
                ];
            } elseif(Auth::user()->role->role_key === 'artist') {
                $mainStats = [
                    ['title' => 'Assigned Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'all time'],
                    ['title' => 'Pending', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'awaiting action'],
                    ['title' => 'Rating', 'value' => number_format($stats['average_rating'] ?? 0, 1), 'icon' => 'star', 'color' => 'success', 'change' => '+0.3', 'changeType' => 'up', 'subtitle' => 'average rating'],
                    ['title' => 'Confirmed', 'value' => $stats['confirmed_bookings'] ?? 0, 'icon' => 'check', 'color' => 'info', 'change' => 'Active', 'changeType' => 'neutral', 'subtitle' => 'confirmed events'],
                ];
            } elseif(Auth::user()->role->role_key === 'affiliate') {
                $mainStats = [
                    ['title' => 'Total Referrals', 'value' => $stats['total_referrals'] ?? 0, 'icon' => 'users', 'color' => 'primary', 'change' => '+5%', 'changeType' => 'up', 'subtitle' => 'all time'],
                    ['title' => 'Active Referrals', 'value' => $stats['active_referrals'] ?? 0, 'icon' => 'user-check', 'color' => 'success', 'change' => 'Verified', 'changeType' => 'neutral', 'subtitle' => 'active users'],
                    ['title' => 'Total Commission', 'value' => '$' . number_format($stats['total_commissions'] ?? 0, 2), 'icon' => 'dollar-sign', 'color' => 'info', 'change' => '+12%', 'changeType' => 'up', 'subtitle' => 'total earned'],
                    ['title' => 'Pending Payout', 'value' => '$' . number_format($stats['pending_commissions'] ?? 0, 2), 'icon' => 'wallet', 'color' => 'warning', 'change' => 'Pending', 'changeType' => 'neutral', 'subtitle' => 'awaiting payout'],
                ];
            } else {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'all bookings'],
                    ['title' => 'Pending', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'clock', 'color' => 'warning', 'change' => 'New', 'changeType' => 'neutral', 'subtitle' => 'awaiting confirmation'],
                    ['title' => 'Completed', 'value' => $stats['completed_bookings'] ?? 0, 'icon' => 'check', 'color' => 'success', 'change' => '+15.7%', 'changeType' => 'up', 'subtitle' => 'successful events'],
                    ['title' => 'Cancelled', 'value' => $stats['cancelled_bookings'] ?? 0, 'icon' => 'x', 'color' => 'danger', 'change' => '-2.1%', 'changeType' => 'down', 'subtitle' => 'cancelled bookings'],
                ];
            }
        @endphp

        @foreach($mainStats as $index => $stat)
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase fw-semibold fs-xs mb-2">{{ $stat['title'] }}</p>
                                <h2 class="mb-0 fw-bold">{{ $stat['value'] }}</h2>
                            </div>
                            <div class="stats-icon bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }}">
                                <i data-lucide="{{ $stat['icon'] }}" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">{{ $stat['subtitle'] }}</small>
                            <span class="badge bg-{{ $stat['changeType'] === 'up' ? 'success' : ($stat['changeType'] === 'down' ? 'danger' : 'secondary') }}-subtle text-{{ $stat['changeType'] === 'up' ? 'success' : ($stat['changeType'] === 'down' ? 'danger' : 'secondary') }}">
                                @if($stat['changeType'] === 'up')
                                    <i data-lucide="trending-up" style="width: 14px; height: 14px;"></i>
                                @elseif($stat['changeType'] === 'down')
                                    <i data-lucide="trending-down" style="width: 14px; height: 14px;"></i>
                                @else
                                    <i data-lucide="minus" style="width: 14px; height: 14px;"></i>
                                @endif
                                {{ $stat['change'] }}
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-{{ $stat['color'] }}" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Secondary Stats Row (Role-based) --}}
    @if(isset($statistics) && !empty($statistics))
        <div class="row g-3 mb-4">
            @if(isset($statistics['monthly_revenue']))
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm gradient-card gradient-purple h-100">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-75 text-uppercase fw-semibold fs-xs mb-0">Monthly Revenue</h6>
                                <i data-lucide="trending-up" class="fs-4" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h2 class="text-white mb-1 fw-bold">${{ number_format($statistics['monthly_revenue'], 0) }}</h2>
                            <small class="text-white-75">This month earnings</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['total_artists']))
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm gradient-card gradient-blue h-100">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-75 text-uppercase fw-semibold fs-xs mb-0">Total Artists</h6>
                                <i data-lucide="users" class="fs-4" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h2 class="text-white mb-1 fw-bold">{{ $statistics['total_artists'] }}</h2>
                            <small class="text-white-75">DJs & Performers</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['active_subscriptions']))
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm gradient-card gradient-green h-100">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-75 text-uppercase fw-semibold fs-xs mb-0">Subscriptions</h6>
                                <i data-lucide="credit-card" class="fs-4" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h2 class="text-white mb-1 fw-bold">{{ $statistics['active_subscriptions'] }}</h2>
                            <small class="text-white-75">Active now</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['total_customers']))
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm gradient-card gradient-orange h-100">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-75 text-uppercase fw-semibold fs-xs mb-0">Customers</h6>
                                <i data-lucide="user-check" class="fs-4" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h2 class="text-white mb-1 fw-bold">{{ $statistics['total_customers'] }}</h2>
                            <small class="text-white-75">Registered users</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Infographics + Ops Tables --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="wallet-cards" class="me-2 text-success" style="width:20px;height:20px;"></i>Payment Volume Trend
                    </h5>
                    <small class="text-muted">Last 6 months amount trend</small>
                </div>
                <div class="card-body">
                    <div class="chart-container-line">
                        <canvas id="paymentVolumeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="clipboard-check" class="me-2 text-warning" style="width:20px;height:20px;"></i>Payment Status
                    </h5>
                    <small class="text-muted">Operational payment mix</small>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container-donut">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="table-properties" class="me-2 text-primary" style="width:20px;height:20px;"></i>Event Performance Table
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Event Type</th>
                                    <th class="text-end">Bookings</th>
                                    <th class="text-end">Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $eventRows = collect($eventTypeCounts ?? [])->sortDesc();
                                    $eventTotal = max(1, $eventRows->sum());
                                @endphp
                                @forelse($eventRows as $eventType => $count)
                                    <tr>
                                        <td>{{ $eventType }}</td>
                                        <td class="text-end">{{ $count }}</td>
                                        <td class="text-end">{{ number_format(($count / $eventTotal) * 100, 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No event data available.</td>
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
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="radar" class="me-2 text-info" style="width:20px;height:20px;"></i>Executive Snapshot
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <div class="small text-muted">Bookings</div>
                                <div class="h4 mb-0">{{ $stats['total_bookings'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <div class="small text-muted">Completed</div>
                                <div class="h4 mb-0">{{ $stats['completed_bookings'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <div class="small text-muted">Pending</div>
                                <div class="h4 mb-0">{{ $stats['pending_bookings'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <div class="small text-muted">Notifications</div>
                                <div class="h4 mb-0">{{ $unreadNotifications ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts & Analytics --}}
    <div class="row g-3 mb-4">
        {{-- Booking Trends Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i data-lucide="line-chart" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>Booking Trends
                        </h5>
                        <small class="text-muted">Last 7 days performance</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary-subtle text-primary"><i data-lucide="calendar" style="width: 16px; height: 16px;"></i> Last 7 Days</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container-line">
                        <canvas id="bookingTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Types Distribution --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="pie-chart" class="me-2 text-info" style="width: 20px; height: 20px;"></i>Event Types
                    </h5>
                    <small class="text-muted">Distribution by category</small>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container-donut">
                        <canvas id="eventTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Role-Based Insights --}}
    @if(!empty($insights['kpis'] ?? []))
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 fs-sm text-uppercase">Completion Rate</p>
                        <h3 class="mb-1">{{ number_format($insights['kpis']['completion_rate'] ?? 0, 1) }}%</h3>
                        <small class="text-muted">Closed successfully</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 fs-sm text-uppercase">Cancellation Rate</p>
                        <h3 class="mb-1">{{ number_format($insights['kpis']['cancellation_rate'] ?? 0, 1) }}%</h3>
                        <small class="text-muted">Cancelled bookings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 fs-sm text-uppercase">Upcoming Events</p>
                        <h3 class="mb-1">{{ $insights['kpis']['upcoming_events'] ?? 0 }}</h3>
                        <small class="text-muted">Scheduled ahead</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 fs-sm text-uppercase">
                            {{ Auth::user()->role->role_key === 'master_admin' ? 'Revenue This Month' : 'Overdue Open' }}
                        </p>
                        <h3 class="mb-1">
                            @if(Auth::user()->role->role_key === 'master_admin')
                                ${{ number_format($insights['kpis']['revenue_this_month'] ?? 0, 0) }}
                            @else
                                {{ $insights['kpis']['overdue_open'] ?? 0 }}
                            @endif
                        </h3>
                        <small class="text-muted">
                            {{ Auth::user()->role->role_key === 'master_admin' ? 'Completed payments' : 'Past event date, still open' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i data-lucide="bar-chart-3" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>Status Mix
                        </h5>
                        <small class="text-muted">Bookings by lifecycle status</small>
                    </div>
                    <div class="card-body">
                        <div class="chart-container-line" style="height:280px;min-height:280px;">
                            <canvas id="statusMixChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i data-lucide="activity" class="me-2 text-success" style="width: 20px; height: 20px;"></i>6-Month Trend
                        </h5>
                        <small class="text-muted">Monthly booking volume</small>
                    </div>
                    <div class="card-body">
                        <div class="chart-container-line" style="height:280px;min-height:280px;">
                            <canvas id="monthlyInsightChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Quick Actions & Recent Activity (Master Admin only) --}}
    @if(Auth::user()->role->role_key === 'master_admin')
    <div class="row g-3 mb-4">
        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i data-lucide="zap" class="me-2 text-warning" style="width: 20px; height: 20px;"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('bookings.create') }}" class="btn btn-outline-primary">
                            <i data-lucide="plus" class="me-2" style="width: 16px; height: 16px;"></i>Create New Booking
                        </a>
                        @if(in_array(Auth::user()->role->role_key, ['master_admin', 'company_admin']))
                            <a href="{{ route('users') }}" class="btn btn-outline-info">
                                <i data-lucide="user-plus" class="me-2" style="width: 16px; height: 16px;"></i>Add New User
                            </a>
                            <a href="{{ route('artists.index') }}" class="btn btn-outline-success">
                                <i data-lucide="mic" class="me-2" style="width: 16px; height: 16px;"></i>Manage Artists
                            </a>
                        @endif
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-warning">
                            <i data-lucide="bell" class="me-2" style="width: 16px; height: 16px;"></i>View Notifications
                            @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                        <a href="{{ route('settings') }}" class="btn btn-outline-secondary">
                            <i data-lucide="settings" class="me-2" style="width: 16px; height: 16px;"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i data-lucide="activity" class="me-2 text-success" style="width: 20px; height: 20px;"></i>Recent Activity
                        </h5>
                        <small class="text-muted">Meaningful updates only (latest 10)</small>
                    </div>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light">
                        <i data-lucide="external-link" style="width: 14px; height: 14px;"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @forelse(($recentActivities ?? collect()) as $activity)
                            @php
                                $action = strtolower((string) ($activity->action ?? 'updated'));
                                $activityMap = [
                                    'created' => ['icon' => 'plus-circle', 'color' => 'success', 'title' => 'Record created'],
                                    'updated' => ['icon' => 'edit', 'color' => 'warning', 'title' => 'Record updated'],
                                    'deleted' => ['icon' => 'trash-2', 'color' => 'danger', 'title' => 'Record deleted'],
                                    'login' => ['icon' => 'log-in', 'color' => 'info', 'title' => 'User login'],
                                    'logout' => ['icon' => 'log-out', 'color' => 'secondary', 'title' => 'User logout'],
                                ];
                                $meta = $activityMap[$action] ?? ['icon' => 'activity', 'color' => 'primary', 'title' => ucfirst($action)];
                            @endphp
                            <div class="timeline-item">
                                <div class="timeline-icon bg-{{ $meta['color'] }}-subtle text-{{ $meta['color'] }}">
                                    <i data-lucide="{{ $meta['icon'] }}" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $meta['title'] }}</h6>
                                    <p class="text-muted mb-1">
                                        {{ $activity->description ?: (($activity->user->name ?? 'System') . ' performed an action') }}
                                    </p>
                                    <small class="text-muted">
                                        <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                                        {{ $activity->created_at?->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No recent activity found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Bookings Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i data-lucide="list" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>Recent Booking Requests
                        </h5>
                        <small class="text-muted">Latest booking activity</small>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm">
                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i> View All Bookings
                    </a>
                </div>

                <div class="card-body p-0">
                    @if ($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">#</th>
                                        <th>Customer</th>
                                        <th>Event Type</th>
                                        <th>Event Date</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentBookings as $booking)
                                        <tr>
                                            <td class="px-3">
                                                <strong class="text-primary">#{{ $booking->tracking_code ?? $booking->id }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0 me-2">
                                                        <span class="avatar-title rounded-circle bg-primary text-white fw-bold fs-xs">
                                                            {{ $booking->customer_initials }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $booking->name }} {{ $booking->surname }}</p>
                                                        <small class="text-muted">{{ $booking->user->email ?? $booking->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i data-lucide="calendar" style="width: 16px; height: 16px;"></i> {{ $booking->eventType->event_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="d-block"><i data-lucide="mail" style="width: 16px; height: 16px;"></i> {{ Str::limit($booking->email, 25) }}</small>
                                                    <small class="d-block"><i data-lucide="phone" style="width: 16px; height: 16px;"></i> {{ $booking->phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $booking->status ?? 'pending';
                                                    $statusConfig = [
                                                        'pending' => ['color' => 'warning', 'icon' => 'clock'],
                                                        'confirmed' => ['color' => 'info', 'icon' => 'check'],
                                                        'completed' => ['color' => 'success', 'icon' => 'circle-check'],
                                                        'cancelled' => ['color' => 'danger', 'icon' => 'x'],
                                                        'rejected' => ['color' => 'danger', 'icon' => 'ban']
                                                    ];
                                                    $config = $statusConfig[$status] ?? ['color' => 'secondary', 'icon' => 'help-circle'];
                                                @endphp
                                                <span class="badge bg-{{ $config['color'] }}">
                                                    <i data-lucide="{{ $config['icon'] }}" style="width: 14px; height: 14px;"></i> {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i data-lucide="clock" style="width: 14px; height: 14px;"></i> {{ $booking->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-light" title="View">
                                                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                                    </a>
                                                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-light" title="Edit">
                                                        <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i data-lucide="calendar-off" class="text-muted" style="width: 64px; height: 64px;"></i>
                            <h5 class="mt-3 text-muted">No Recent Bookings</h5>
                            <p class="text-muted">There are no booking requests yet.</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Create Your First Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Styles --}}
    <style>
        /* Card Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card {
            animation: slideUp 0.6s ease-out forwards;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stats-card:hover::before {
            transform: scaleX(1);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15) !important;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* Gradient Cards */
        .gradient-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .gradient-card.gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-card.gradient-blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .gradient-card.gradient-green {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .gradient-card.gradient-orange {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .gradient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2) !important;
        }

        /* Activity Timeline */
        .activity-timeline {
            position: relative;
        }

        .timeline-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            z-index: 1;
        }

        .timeline-content {
            flex: 1;
        }

        /* Table Enhancements */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Button Enhancements */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Progress Bars */
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }

        /* Fixed chart heights prevent Chart.js resize loops */
        .chart-container-line {
            position: relative;
            height: 320px;
            min-height: 320px;
            width: 100%;
        }

        .chart-container-donut {
            position: relative;
            height: 280px;
            min-height: 280px;
            width: 100%;
        }

        .chart-container-line canvas,
        .chart-container-donut canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* Card Headers */
        .card-header {
            padding: 1rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Text Colors */
        .text-white-75 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        @media (max-width: 767.98px) {
            .page-title-head .dropdown,
            .page-title-head .btn {
                width: 100%;
            }

            .page-title-head .dropdown .btn {
                width: 100%;
            }

            .chart-container-line {
                height: 260px;
                min-height: 260px;
            }

            .chart-container-donut {
                height: 240px;
                min-height: 240px;
            }
        }
    </style>

    @php
        $fallbackEventTypes = ['Wedding' => 45, 'Corporate' => 30, 'Birthday' => 25];
        $dashboardChartData = [
            'bookingCounts' => $bookingStats['counts'] ?? [12, 19, 15, 25, 22, 30, 28],
            'bookingDates' => $bookingStats['dates'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'eventTypeLabels' => array_keys($eventTypeCounts ?? $fallbackEventTypes),
            'eventTypeValues' => array_values($eventTypeCounts ?? $fallbackEventTypes),
            'statusLabels' => $insights['status_labels'] ?? [],
            'statusValues' => $insights['status_values'] ?? [],
            'monthlyLabels' => $insights['monthly_labels'] ?? [],
            'monthlyValues' => $insights['monthly_values'] ?? [],
            'paymentLabels' => $paymentInsights['labels'] ?? [],
            'paymentValues' => $paymentInsights['values'] ?? [],
            'paymentStatusLabels' => $paymentInsights['status_labels'] ?? [],
            'paymentStatusValues' => $paymentInsights['status_values'] ?? [],
        ];
    @endphp
    <script id="dashboard-chart-data" type="application/json">@json($dashboardChartData)</script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (function() {
                // Initialize Lucide icons for stats and timeline
                var lib = window.lucide || window.Lucide;
                if (lib && typeof lib.createIcons === 'function') {
                    lib.createIcons();
                    setTimeout(lib.createIcons, 150);
                }
            })();

            var dashboardDataNode = document.getElementById('dashboard-chart-data');
            var dashboardData = {};
            try {
                dashboardData = dashboardDataNode ? JSON.parse(dashboardDataNode.textContent || '{}') : {};
            } catch (e) {
                dashboardData = {};
            }

            // Booking Trend Chart - no animation, fixed Y-axis so it never "grows"
            var bookingCounts = dashboardData.bookingCounts || [12, 19, 15, 25, 22, 30, 28];
            var maxCount = Math.max.apply(null, bookingCounts.length ? bookingCounts : [0]);
            var yMax = Math.max(10, Math.ceil((maxCount + 2) / 5) * 5);

            const ctxTrend = document.getElementById('bookingTrendChart');
            if (ctxTrend) {
                new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: dashboardData.bookingDates || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Bookings',
                            data: bookingCounts,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        animations: {},
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#000',
                                bodyColor: '#666',
                                borderColor: '#ddd',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => 'Bookings: ' + context.parsed.y
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: yMax,
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Event Types Doughnut Chart - no animation
            const ctxEvent = document.getElementById('eventTypeChart');
            if (ctxEvent) {
                new Chart(ctxEvent, {
                    type: 'doughnut',
                    data: {
                        labels: dashboardData.eventTypeLabels || ['Wedding', 'Corporate', 'Birthday'],
                        datasets: [{
                            data: dashboardData.eventTypeValues || [45, 30, 25],
                            backgroundColor: [
                                '#667eea',
                                '#f093fb',
                                '#4facfe',
                                '#43e97b',
                                '#f5576c',
                                '#764ba2'
                            ],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        animations: {},
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#000',
                                bodyColor: '#666',
                                borderColor: '#ddd',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    label: (context) => context.label + ': ' + context.parsed
                                }
                            }
                        }
                    }
                });
            }

            // Role insight charts
            const statusMixCtx = document.getElementById('statusMixChart');
            if (statusMixCtx) {
                new Chart(statusMixCtx, {
                    type: 'bar',
                    data: {
                        labels: dashboardData.statusLabels || [],
                        datasets: [{
                            label: 'Bookings',
                            data: dashboardData.statusValues || [],
                            backgroundColor: ['#f59f00', '#0dcaf0', '#6f42c1', '#198754', '#dc3545', '#6c757d'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const monthlyInsightCtx = document.getElementById('monthlyInsightChart');
            if (monthlyInsightCtx) {
                new Chart(monthlyInsightCtx, {
                    type: 'line',
                    data: {
                        labels: dashboardData.monthlyLabels || [],
                        datasets: [{
                            label: 'Bookings',
                            data: dashboardData.monthlyValues || [],
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32, 201, 151, 0.12)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const paymentVolumeCtx = document.getElementById('paymentVolumeChart');
            if (paymentVolumeCtx) {
                new Chart(paymentVolumeCtx, {
                    type: 'bar',
                    data: {
                        labels: dashboardData.paymentLabels || [],
                        datasets: [{
                            label: 'Amount',
                            data: dashboardData.paymentValues || [],
                            backgroundColor: 'rgba(13, 202, 240, 0.55)',
                            borderColor: '#0dcaf0',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const paymentStatusCtx = document.getElementById('paymentStatusChart');
            if (paymentStatusCtx) {
                new Chart(paymentStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: dashboardData.paymentStatusLabels || [],
                        datasets: [{
                            data: dashboardData.paymentStatusValues || [],
                            backgroundColor: ['#f59f00', '#198754', '#dc3545'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, padding: 12 }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
