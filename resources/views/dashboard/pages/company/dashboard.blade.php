@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="building" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Company Admin - Manage Your Company</p>
        </div>
        <div class="text-end">
            <a href="{{ route('company.settings') }}" class="btn btn-primary btn-sm">
                <i data-lucide="settings"></i> Settings
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
                                <i data-lucide="calendar" class="text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Bookings</p>
                            <h4 class="mb-0">{{ number_format($stats['total_bookings']) }}</h4>
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
                                <i data-lucide="users" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Staff Members</p>
                            <h4 class="mb-0">{{ number_format($stats['total_staff']) }}</h4>
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
                                <i data-lucide="music" class="text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Artists/DJs</p>
                            <h4 class="mb-0">{{ number_format($stats['total_artists']) }}</h4>
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
                                <i data-lucide="dollar-sign" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Revenue</p>
                            <h4 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Bookings -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('company.bookings') }}" class="btn btn-outline-primary">
                            <i data-lucide="calendar"></i> Manage Bookings
                        </a>
                        <a href="{{ route('company.staff') }}" class="btn btn-outline-success">
                            <i data-lucide="users"></i> Manage Staff
                        </a>
                        <a href="{{ route('company.artists') }}" class="btn btn-outline-info">
                            <i data-lucide="music"></i> Manage Artists
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
                    <a href="{{ route('company.bookings') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No bookings yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Info -->
    @if(isset($subscription))
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Subscription Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Plan</p>
                            <h5>{{ $subscription->package->name ?? 'N/A' }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Status</p>
                            <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Expires On</p>
                            <h6>{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</h6>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('company.settings') }}" class="btn btn-primary btn-sm">Manage Subscription</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
