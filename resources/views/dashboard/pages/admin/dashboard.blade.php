@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="shield" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Master Admin - System Overview</p>
        </div>
        <div class="text-end">
            <button class="btn btn-light btn-sm me-2" onclick="location.reload()">
                <i data-lucide="refresh-cw"></i> Refresh
            </button>
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
                            <p class="text-muted mb-1 fs-sm">Total Users</p>
                            <h4 class="mb-0">{{ number_format($stats['total_users']) }}</h4>
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
                                <i data-lucide="building" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Companies</p>
                            <h4 class="mb-0">{{ number_format($stats['total_companies']) }}</h4>
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
                                <i data-lucide="calendar" class="text-info"></i>
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

    <!-- System Health & Top Performers -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">System Health</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Database Size</span>
                            <strong>{{ $systemHealth['database_size'] ?? 'N/A' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Records</span>
                            <strong>{{ number_format($systemHealth['total_records'] ?? 0) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Disk Usage</span>
                            <strong>{{ $systemHealth['disk_usage'] ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Top Companies</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th class="text-end">Bookings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCompanies ?? [] as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td class="text-end">{{ $company->bookings_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                    <a href="{{ route('admin.activity-logs') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities ?? [] as $activity)
                                    <tr>
                                        <td>{{ $activity->user->name ?? 'System' }}</td>
                                        <td><span class="badge bg-info">{{ $activity->action }}</span></td>
                                        <td>{{ $activity->description }}</td>
                                        <td><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No activities yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
