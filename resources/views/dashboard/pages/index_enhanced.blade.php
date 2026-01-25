@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fs-xl fw-bold m-0">
                <i class="ti ti-layout-dashboard me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Welcome back, <strong>{{ Auth::user()->name }}</strong>! Here's what's happening
                @if(isset($filter) && $filter !== 'this_month')
                    <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $filter)) }}</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm" onclick="location.reload()">
                <i class="ti ti-refresh"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                    <i class="ti ti-calendar"></i>
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
                                <i class="ti ti-filter"></i> Apply Filter
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Main Stats Cards --}}
    <div class="row g-3 mb-4">
        @php
            $mainStats = [];

            if(Auth::user()->role->role_key === 'master_admin') {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'ti-calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'from last month'],
                    ['title' => 'Pending Requests', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'ti-clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'awaiting action'],
                    ['title' => 'Revenue', 'value' => '$' . number_format($stats['total_revenue'] ?? 0), 'icon' => 'ti-currency-dollar', 'color' => 'success', 'change' => '+23.1%', 'changeType' => 'up', 'subtitle' => 'total earnings'],
                    ['title' => 'Active Companies', 'value' => $stats['active_companies'] ?? 0, 'icon' => 'ti-building', 'color' => 'info', 'change' => '+5.2%', 'changeType' => 'up', 'subtitle' => 'registered companies'],
                ];
            } elseif(Auth::user()->role->role_key === 'company_admin') {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'ti-calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'this month'],
                    ['title' => 'Pending', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'ti-clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'awaiting response'],
                    ['title' => 'Active Artists', 'value' => $stats['active_artists'] ?? 0, 'icon' => 'ti-microphone-2', 'color' => 'info', 'change' => '+3.5%', 'changeType' => 'up', 'subtitle' => 'available now'],
                    ['title' => 'Completed', 'value' => $stats['completed_bookings'] ?? 0, 'icon' => 'ti-check', 'color' => 'success', 'change' => '+15.7%', 'changeType' => 'up', 'subtitle' => 'successful events'],
                ];
            } elseif(Auth::user()->role->role_key === 'dj') {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'ti-calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'all time'],
                    ['title' => 'Pending Requests', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'ti-clock', 'color' => 'warning', 'change' => '+8.3%', 'changeType' => 'up', 'subtitle' => 'new requests'],
                    ['title' => 'Rating', 'value' => $stats['average_rating'] ?? 0, 'icon' => 'ti-star', 'color' => 'success', 'change' => '+0.3', 'changeType' => 'up', 'subtitle' => 'average rating'],
                    ['title' => 'Services', 'value' => $stats['total_services'] ?? 0, 'icon' => 'ti-list', 'color' => 'info', 'change' => 'Active', 'changeType' => 'neutral', 'subtitle' => 'offered services'],
                ];
            } else {
                $mainStats = [
                    ['title' => 'Total Bookings', 'value' => $stats['total_bookings'] ?? 0, 'icon' => 'ti-calendar-check', 'color' => 'primary', 'change' => '+12.5%', 'changeType' => 'up', 'subtitle' => 'all bookings'],
                    ['title' => 'Pending', 'value' => $stats['pending_bookings'] ?? 0, 'icon' => 'ti-clock', 'color' => 'warning', 'change' => 'New', 'changeType' => 'neutral', 'subtitle' => 'awaiting confirmation'],
                    ['title' => 'Completed', 'value' => $stats['completed_bookings'] ?? 0, 'icon' => 'ti-check', 'color' => 'success', 'change' => '+15.7%', 'changeType' => 'up', 'subtitle' => 'successful events'],
                    ['title' => 'Cancelled', 'value' => $stats['cancelled_bookings'] ?? 0, 'icon' => 'ti-x', 'color' => 'danger', 'change' => '-2.1%', 'changeType' => 'down', 'subtitle' => 'cancelled bookings'],
                ];
            }
        @endphp

        @foreach($mainStats as $index => $stat)
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card border-0 shadow-sm h-100" style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase fw-semibold fs-xs mb-2">{{ $stat['title'] }}</p>
                                <h2 class="mb-0 fw-bold">{{ $stat['value'] }}</h2>
                            </div>
                            <div class="stats-icon bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }}">
                                <i class="{{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">{{ $stat['subtitle'] }}</small>
                            <span class="badge bg-{{ $stat['changeType'] === 'up' ? 'success' : ($stat['changeType'] === 'down' ? 'danger' : 'secondary') }}-subtle text-{{ $stat['changeType'] === 'up' ? 'success' : ($stat['changeType'] === 'down' ? 'danger' : 'secondary') }}">
                                <i class="ti ti-trending-{{ $stat['changeType'] === 'up' ? 'up' : ($stat['changeType'] === 'down' ? 'down' : 'up') }}"></i>
                                {{ $stat['change'] }}
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-{{ $stat['color'] }}" role="progressbar" style="width: {{ rand(60, 95) }}%" aria-valuenow="{{ rand(60, 95) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <i class="ti ti-trending-up fs-4"></i>
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
                                <i class="ti ti-users fs-4"></i>
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
                                <i class="ti ti-credit-card fs-4"></i>
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
                                <i class="ti ti-user-check fs-4"></i>
                            </div>
                            <h2 class="text-white mb-1 fw-bold">{{ $statistics['total_customers'] }}</h2>
                            <small class="text-white-75">Registered users</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Charts & Analytics --}}
    <div class="row g-3 mb-4">
        {{-- Booking Trends Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="ti ti-chart-line me-2 text-primary"></i>Booking Trends
                        </h5>
                        <small class="text-muted">Last 7 days performance</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary-subtle text-primary"><i class="ti ti-calendar"></i> Last 7 Days</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="bookingTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Event Types Distribution --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-chart-donut me-2 text-info"></i>Event Types
                    </h5>
                    <small class="text-muted">Distribution by category</small>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="eventTypeChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Recent Activity --}}
    <div class="row g-3 mb-4">
        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-bolt me-2 text-warning"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('bookings.create') }}" class="btn btn-outline-primary">
                            <i class="ti ti-plus me-2"></i>Create New Booking
                        </a>
                        @if(in_array(Auth::user()->role->role_key, ['master_admin', 'company_admin']))
                            <a href="{{ route('users') }}" class="btn btn-outline-info">
                                <i class="ti ti-user-plus me-2"></i>Add New User
                            </a>
                            <a href="{{ route('artists.index') }}" class="btn btn-outline-success">
                                <i class="ti ti-microphone me-2"></i>Manage Artists
                            </a>
                        @endif
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-warning">
                            <i class="ti ti-bell me-2"></i>View Notifications
                            @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                        <a href="{{ route('settings') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-settings me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="ti ti-activity me-2 text-success"></i>Recent Activity
                        </h5>
                        <small class="text-muted">Latest updates and actions</small>
                    </div>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light">
                        <i class="ti ti-external-link"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @php
                            $activities = [
                                ['icon' => 'ti-calendar-check', 'color' => 'success', 'title' => 'New booking received', 'desc' => 'Wedding event for Sarah & John', 'time' => '2 hours ago'],
                                ['icon' => 'ti-user-plus', 'color' => 'info', 'title' => 'New user registered', 'desc' => 'John Doe joined as customer', 'time' => '5 hours ago'],
                                ['icon' => 'ti-edit', 'color' => 'warning', 'title' => 'Booking updated', 'desc' => 'Corporate event details changed', 'time' => '1 day ago'],
                                ['icon' => 'ti-check', 'color' => 'primary', 'title' => 'Booking completed', 'desc' => 'Birthday party at Grand Hotel', 'time' => '2 days ago'],
                            ];
                        @endphp

                        @foreach($activities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-{{ $activity['color'] }}-subtle text-{{ $activity['color'] }}">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                    <p class="text-muted mb-1">{{ $activity['desc'] }}</p>
                                    <small class="text-muted"><i class="ti ti-clock"></i> {{ $activity['time'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Bookings Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="ti ti-list me-2 text-primary"></i>Recent Booking Requests
                        </h5>
                        <small class="text-muted">Latest booking activity</small>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-eye"></i> View All Bookings
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
                                                <strong class="text-primary">#{{ $booking->id }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0 me-2">
                                                        <span class="avatar-title rounded-circle bg-primary text-white fw-bold fs-xs">
                                                            {{ substr($booking->name, 0, 1) }}{{ substr($booking->surname ?? '', 0, 1) }}
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
                                                    <i class="ti ti-calendar"></i> {{ $booking->eventType->event_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="d-block"><i class="ti ti-mail"></i> {{ Str::limit($booking->email, 25) }}</small>
                                                    <small class="d-block"><i class="ti ti-phone"></i> {{ $booking->phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $booking->status ?? 'pending';
                                                    $statusConfig = [
                                                        'pending' => ['color' => 'warning', 'icon' => 'ti-clock'],
                                                        'confirmed' => ['color' => 'info', 'icon' => 'ti-check'],
                                                        'completed' => ['color' => 'success', 'icon' => 'ti-circle-check'],
                                                        'cancelled' => ['color' => 'danger', 'icon' => 'ti-x'],
                                                        'rejected' => ['color' => 'danger', 'icon' => 'ti-ban']
                                                    ];
                                                    $config = $statusConfig[$status] ?? ['color' => 'secondary', 'icon' => 'ti-help'];
                                                @endphp
                                                <span class="badge bg-{{ $config['color'] }}">
                                                    <i class="{{ $config['icon'] }}"></i> {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="ti ti-clock"></i> {{ $booking->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-light" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-light" title="Edit">
                                                        <i class="ti ti-edit"></i>
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
                            <i class="ti ti-calendar-off text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">No Recent Bookings</h5>
                            <p class="text-muted">There are no booking requests yet.</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Create Your First Booking
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
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Booking Trend Chart
            const ctxTrend = document.getElementById('bookingTrendChart');
            if (ctxTrend) {
                new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($bookingStats['dates'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                        datasets: [{
                            label: 'Bookings',
                            data: {!! json_encode($bookingStats['counts'] ?? [12, 19, 15, 25, 22, 30, 28]) !!},
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#000',
                                bodyColor: '#666',
                                borderColor: '#ddd',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => `Bookings: ${context.parsed.y}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Event Types Doughnut Chart
            const ctxEvent = document.getElementById('eventTypeChart');
            if (ctxEvent) {
                new Chart(ctxEvent, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_keys($eventTypeCounts ?? ['Wedding' => 45, 'Corporate' => 30, 'Birthday' => 25])) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($eventTypeCounts ?? [45, 30, 25])) !!},
                            backgroundColor: [
                                '#667eea',
                                '#f093fb',
                                '#4facfe',
                                '#43e97b',
                                '#f5576c',
                                '#764ba2'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
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
                                    label: (context) => `${context.label}: ${context.parsed}`
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
