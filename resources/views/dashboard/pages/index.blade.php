@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="layout-dashboard" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Welcome back! Here's what's happening today</p>
        </div>

        <div class="text-end">
            <button class="btn btn-light btn-sm me-2" onclick="location.reload()">
                <i data-lucide="refresh-cw"></i> Refresh
            </button>
            <ol class="breadcrumb m-0 py-0 d-inline-flex">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <!-- Enhanced Stats Section with Animation -->
    <div class="row mb-4 g-3">
        @php
            $statsCards = [
                [
                    'title' => 'Total Bookings',
                    'value' => $stats['total_bookings'] ?? 0,
                    'icon' => 'calendar-check',
                    'color' => 'primary',
                    'trend' => '+12%',
                    'trendType' => 'up',
                    'subtitle' => 'All time records'
                ],
                [
                    'title' => 'Total Users',
                    'value' => $stats['total_users'] ?? 0,
                    'icon' => 'users',
                    'color' => 'info',
                    'trend' => '+8%',
                    'trendType' => 'up',
                    'subtitle' => 'Active users'
                ],
                [
                    'title' => 'Companies',
                    'value' => $stats['total_companies'] ?? 0,
                    'icon' => 'building',
                    'color' => 'warning',
                    'trend' => '+5%',
                    'trendType' => 'up',
                    'subtitle' => 'Registered'
                ],
                [
                    'title' => 'Event Types',
                    'value' => $stats['total_event_types'] ?? 0,
                    'icon' => 'sparkles',
                    'color' => 'success',
                    'trend' => 'Stable',
                    'trendType' => 'neutral',
                    'subtitle' => 'Available categories'
                ]
            ];
        @endphp

        @foreach($statsCards as $index => $card)
            <div class="col-md-6 col-xl-3">
                <div class="card stats-card border-0 shadow-sm h-100 animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <p class="text-muted text-uppercase fw-semibold fs-xs mb-1">{{ $card['title'] }}</p>
                                <h2 class="mb-0 fw-bold counter" data-target="{{ $card['value'] }}">0</h2>
                            </div>
                            <div class="stats-icon-lg bg-{{ $card['color'] }}-subtle">
                                <i data-lucide="{{ $card['icon'] }}" class="text-{{ $card['color'] }}"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">{{ $card['subtitle'] }}</small>
                            <span class="badge bg-{{ $card['trendType'] === 'up' ? 'success' : ($card['trendType'] === 'down' ? 'danger' : 'secondary') }}-subtle text-{{ $card['trendType'] === 'up' ? 'success' : ($card['trendType'] === 'down' ? 'danger' : 'secondary') }}">
                                @if($card['trendType'] === 'up')
                                    <i data-lucide="trending-up" style="width: 12px; height: 12px;"></i>
                                @elseif($card['trendType'] === 'down')
                                    <i data-lucide="trending-down" style="width: 12px; height: 12px;"></i>
                                @else
                                    <i data-lucide="minus" style="width: 12px; height: 12px;"></i>
                                @endif
                                {{ $card['trend'] }}
                            </span>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-{{ $card['color'] }}" role="progressbar" style="width: {{ rand(40, 95) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Additional Stats Row for Specific Roles -->
    @if(isset($statistics) && !empty($statistics))
        <div class="row mb-4 g-3">
            @if(isset($statistics['total_revenue']))
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm bg-gradient-primary text-white animate-slide-up">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-50 text-uppercase fw-semibold fs-xs mb-0">Total Revenue</h6>
                                <i data-lucide="dollar-sign" class="text-white-50"></i>
                            </div>
                            <h2 class="text-white mb-0 fw-bold">$<span class="counter" data-target="{{ number_format($statistics['total_revenue'], 0, '', '') }}">0</span></h2>
                            <small class="text-white-75">All time earnings</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['pending_bookings']))
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm bg-gradient-warning text-white animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-50 text-uppercase fw-semibold fs-xs mb-0">Pending Bookings</h6>
                                <i data-lucide="clock" class="text-white-50"></i>
                            </div>
                            <h2 class="text-white mb-0 fw-bold counter" data-target="{{ $statistics['pending_bookings'] }}">0</h2>
                            <small class="text-white-75">Awaiting response</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['active_subscriptions']))
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm bg-gradient-success text-white animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-50 text-uppercase fw-semibold fs-xs mb-0">Active Subscriptions</h6>
                                <i data-lucide="credit-card" class="text-white-50"></i>
                            </div>
                            <h2 class="text-white mb-0 fw-bold counter" data-target="{{ $statistics['active_subscriptions'] }}">0</h2>
                            <small class="text-white-75">Currently active</small>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($statistics['total_artists']))
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm bg-gradient-info text-white animate-slide-up" style="animation-delay: 0.3s;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="text-white-50 text-uppercase fw-semibold fs-xs mb-0">Total Artists</h6>
                                <i data-lucide="music" class="text-white-50"></i>
                            </div>
                            <h2 class="text-white mb-0 fw-bold counter" data-target="{{ $statistics['total_artists'] }}">0</h2>
                            <small class="text-white-75">DJs & Artists</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Charts Section -->
    <div class="row mb-4 g-3">
        <!-- Booking Trends Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom">
                    <div>
                        <h5 class="card-title mb-0"><i data-lucide="trending-up" class="me-2"></i>Booking Trends</h5>
                        <small class="text-muted">Last 7 days performance</small>
                    </div>
                    <span class="badge bg-primary">Last 7 Days</span>
                </div>
                <div class="card-body">
                    <canvas id="bookingTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Event Types Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom">
                    <div>
                        <h5 class="card-title mb-0"><i data-lucide="pie-chart" class="me-2"></i>Event Types</h5>
                        <small class="text-muted">Distribution</small>
                    </div>
                    <span class="badge bg-info">Total</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="eventTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4 g-3">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0"><i data-lucide="zap" class="me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('bookings.create') }}" class="btn btn-outline-primary w-100 btn-lg">
                                <i data-lucide="plus-circle" class="me-2"></i>New Booking
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('users') }}" class="btn btn-outline-info w-100 btn-lg">
                                <i data-lucide="user-plus" class="me-2"></i>Add User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-warning w-100 btn-lg">
                                <i data-lucide="bell" class="me-2"></i>Notifications
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('settings') }}" class="btn btn-outline-success w-100 btn-lg">
                                <i data-lucide="settings" class="me-2"></i>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0"><i data-lucide="list" class="me-2"></i>Recent Booking Requests</h5>
                        <small class="text-muted">Latest booking activity</small>
                    </div>
                    <div class="action-btns">
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm">
                            <i data-lucide="external-link"></i> View All
                        </a>
                    </div>
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
                                        <th>Requested</th>
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
                                                        <span class="avatar-title rounded-circle bg-primary text-white fw-bold">
                                                            {{ substr($booking->name, 0, 1) }}{{ substr($booking->surname, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $booking->name }} {{ $booking->surname }}</p>
                                                        <small class="text-muted">{{ $booking->user->name ?? 'Guest' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                                                    {{ $booking->eventType->event_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-dark fw-semibold">{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="d-block text-muted"><i data-lucide="mail" style="width: 12px; height: 12px;"></i> {{ $booking->email }}</small>
                                                    <small class="d-block text-muted"><i data-lucide="phone" style="width: 12px; height: 12px;"></i> {{ $booking->phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $booking->status ?? 'pending';
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger',
                                                        'rejected' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i data-lucide="clock" style="width: 12px; height: 12px;"></i>
                                                    {{ $booking->created_at->diffForHumans() }}
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
                        <div class="alert alert-info mx-3 my-3 d-flex align-items-center">
                            <i data-lucide="info" class="me-2"></i>
                            <div>
                                <strong>No booking requests yet.</strong>
                                <p class="mb-0">
                                    <a href="{{ route('bookings.create') }}" class="alert-link">Create your first booking</a> to get started!
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Animations */
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

        .animate-slide-up {
            animation: slideUp 0.6s ease-out forwards;
            opacity: 0;
        }

        /* Stats Card Styles */
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stats-card:hover::before {
            transform: scaleX(1);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15) !important;
        }

        .stats-icon-lg {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Counter Animation */
        .counter {
            display: inline-block;
        }

        /* Gradient Backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Table Enhancements */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 102, 204, 0.05);
            transform: scale(1.01);
        }

        /* Quick Actions */
        .btn-outline-primary:hover, .btn-outline-info:hover,
        .btn-outline-warning:hover, .btn-outline-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Initialize Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Counter Animation
            document.querySelectorAll('.counter').forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };

                updateCounter();
            });

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
                            data: {!! json_encode(array_values($eventTypeCounts ?? ['Wedding' => 45, 'Corporate' => 30, 'Birthday' => 25])) !!},
                            backgroundColor: [
                                '#667eea',
                                '#f093fb',
                                '#4facfe',
                                '#43e97b',
                                '#f5576c'
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
                                    label: (context) => `${context.label}: ${context.parsed}%`
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
