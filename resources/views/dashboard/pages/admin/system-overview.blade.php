@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="server" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Complete System Overview & Health Metrics</p>
        </div>
        <div class="text-end">
            <button class="btn btn-light btn-sm" onclick="location.reload()">
                <i data-lucide="refresh-cw"></i> Refresh
            </button>
        </div>
    </div>

    <!-- System Stats -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <i data-lucide="database" class="text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Database Size</p>
                            <h4 class="mb-0">{{ $overview['database_size'] ?? 'N/A' }}</h4>
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
                                <i data-lucide="hard-drive" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Disk Usage</p>
                            <h4 class="mb-0">{{ $overview['disk_usage'] ?? 'N/A' }}</h4>
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
                                <i data-lucide="layers" class="text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">Total Records</p>
                            <h4 class="mb-0">{{ number_format($overview['total_records'] ?? 0) }}</h4>
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
                                <i data-lucide="cpu" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-sm">System Status</p>
                            <h4 class="mb-0 text-success">Healthy</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Metrics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">User Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Total Users</td>
                                    <td class="text-end"><strong>{{ number_format($overview['total_users'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Master Admins</td>
                                    <td class="text-end"><strong>{{ number_format($overview['master_admins'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Company Admins</td>
                                    <td class="text-end"><strong>{{ number_format($overview['company_admins'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Customers</td>
                                    <td class="text-end"><strong>{{ number_format($overview['customers'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Artists/DJs</td>
                                    <td class="text-end"><strong>{{ number_format($overview['artists'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Affiliates</td>
                                    <td class="text-end"><strong>{{ number_format($overview['affiliates'] ?? 0) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Business Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Total Companies</td>
                                    <td class="text-end"><strong>{{ number_format($overview['total_companies'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Active Companies</td>
                                    <td class="text-end"><strong>{{ number_format($overview['active_companies'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Total Bookings</td>
                                    <td class="text-end"><strong>{{ number_format($overview['total_bookings'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Completed Bookings</td>
                                    <td class="text-end"><strong>{{ number_format($overview['completed_bookings'] ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Total Revenue</td>
                                    <td class="text-end"><strong>${{ number_format($overview['total_revenue'] ?? 0, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Pending Payments</td>
                                    <td class="text-end"><strong>${{ number_format($overview['pending_payments'] ?? 0, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Overview -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Event Types</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">Total Event Types: <strong>{{ number_format($overview['event_types'] ?? 0) }}</strong></p>
                    <p class="mb-2">Categories: <strong>{{ number_format($overview['categories'] ?? 0) }}</strong></p>
                    <p class="mb-0">Packages: <strong>{{ number_format($overview['packages'] ?? 0) }}</strong></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Content</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">Blog Posts: <strong>{{ number_format($overview['blogs'] ?? 0) }}</strong></p>
                    <p class="mb-2">Testimonials: <strong>{{ number_format($overview['testimonials'] ?? 0) }}</strong></p>
                    <p class="mb-0">Reviews: <strong>{{ number_format($overview['reviews'] ?? 0) }}</strong></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Support</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">Total Tickets: <strong>{{ number_format($overview['support_tickets'] ?? 0) }}</strong></p>
                    <p class="mb-2">Open Tickets: <strong>{{ number_format($overview['open_tickets'] ?? 0) }}</strong></p>
                    <p class="mb-0">Resolved Tickets: <strong>{{ number_format($overview['resolved_tickets'] ?? 0) }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection
