@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fs-xl fw-bold m-0">
                <i class="ti ti-building me-2"></i>{{ $company->name }}
            </h4>
            <p class="text-muted mb-0 mt-1">Complete company profile and analytics</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('companies') }}" class="btn btn-light btn-sm">
                <i class="ti ti-arrow-left"></i> Back
            </a>
            <a href="{{ route('subscription.create', $company->id) }}" class="btn btn-warning btn-sm">
                <i class="ti ti-credit-card"></i> Manage Subscription
            </a>
            <a href="{{ route('company.edit', $company->id) }}" class="btn btn-primary btn-sm">
                <i class="ti ti-edit"></i> Edit Company
            </a>
        </div>
    </div>

    {{-- Company Overview --}}
    <div class="row g-3 mb-4">
        {{-- Company Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    {{-- Logo --}}
                    <div class="mb-3">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="avatar avatar-xl mx-auto">
                                <span class="avatar-title rounded-circle bg-primary fs-1 shadow-sm">{{ substr($company->name, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Company Name & Status --}}
                    <h5 class="mb-2 fw-bold">{{ $company->name }}</h5>
                    <span class="badge bg-{{ $company->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $company->status === 'active' ? 'success' : 'secondary' }} mb-3">
                        <i class="ti ti-circle-check"></i> {{ ucfirst($company->status) }}
                    </span>

                    {{-- Company Info --}}
                    <div class="border-top pt-3 text-start">
                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-primary-subtle text-primary me-3">
                                <i class="ti ti-mail"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Email Address</small>
                                <p class="mb-0 fw-semibold">{{ $company->email }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-success-subtle text-success me-3">
                                <i class="ti ti-phone"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Phone Number</small>
                                <p class="mb-0 fw-semibold">{{ $company->phone ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-info-subtle text-info me-3">
                                <i class="ti ti-id"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">KVK Number</small>
                                <p class="mb-0 fw-semibold">{{ $company->kvk_number ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-warning-subtle text-warning me-3">
                                <i class="ti ti-map-pin"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Location</small>
                                <p class="mb-0 fw-semibold">{{ $company->city }}, {{ $company->state }}</p>
                                <small class="text-muted">{{ $company->address ?? 'Address not provided' }}</small>
                            </div>
                        </div>

                        @if($company->website)
                            <div class="d-flex align-items-start mb-3">
                                <div class="icon-box bg-purple-subtle text-purple me-3">
                                    <i class="ti ti-world"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">Website</small>
                                    <a href="{{ $company->website }}" target="_blank" class="fw-semibold">{{ $company->website }}</a>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-start">
                            <div class="icon-box bg-secondary-subtle text-secondary me-3">
                                <i class="ti ti-calendar"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Member Since</small>
                                <p class="mb-0 fw-semibold">{{ $company->created_at->format('M d, Y') }}</p>
                                <small class="text-muted">{{ $company->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    @if(isset($company->socialLinks) && $company->socialLinks->count() > 0)
                        <div class="border-top pt-3 mt-3">
                            <h6 class="mb-2 fw-semibold"><i class="ti ti-share me-2"></i>Social Media</h6>
                            <div class="d-flex gap-2 justify-content-center">
                                @foreach($company->socialLinks as $social)
                                    <a href="{{ $social->url }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="ti ti-{{ $social->handle }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats & Subscription --}}
        <div class="col-lg-8">
            {{-- Quick Stats --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary-subtle text-primary me-3">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total_artists'] ?? 0 }}</h3>
                                    <small class="text-muted">Artists</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success me-3">
                                    <i class="ti ti-calendar-check"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total_bookings'] ?? 0 }}</h3>
                                    <small class="text-muted">Bookings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning-subtle text-warning me-3">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</h3>
                                    <small class="text-muted">Avg Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm h-100 stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-info-subtle text-info me-3">
                                    <i class="ti ti-currency-dollar"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</h3>
                                    <small class="text-muted">Revenue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="ti ti-credit-card me-2"></i>Subscription Details</h6>
                    <a href="{{ route('subscription.create', $company) }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-edit"></i> Manage
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($subscription) && $subscription)
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="subscription-badge mb-2">
                                    <i class="ti ti-crown fs-1 text-warning"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-primary">{{ $subscription->package->name ?? 'N/A' }}</h5>
                                <small class="text-muted">Current Plan</small>
                            </div>
                            <div class="col-md-2 text-center border-start">
                                <h4 class="mb-0 fw-bold">${{ $subscription->package->price ?? 0 }}</h4>
                                <small class="text-muted">{{ ucfirst($subscription->package->duration_type ?? 'N/A') }}</small>
                            </div>
                            <div class="col-md-3 text-center border-start">
                                <p class="mb-1 text-muted">Start Date</p>
                                <h6 class="mb-0 fw-semibold">{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : 'N/A' }}</h6>
                            </div>
                            <div class="col-md-2 text-center border-start">
                                <p class="mb-1 text-muted">Expires</p>
                                <h6 class="mb-0 fw-semibold">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</h6>
                                @if($subscription->end_date)
                                    <small class="text-{{ $subscription->end_date->isPast() ? 'danger' : ($subscription->end_date->diffInDays() < 30 ? 'warning' : 'success') }}">
                                        {{ $subscription->end_date->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-2 text-center border-start">
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'expired' ? 'danger' : 'warning') }} px-3 py-2">
                                    <i class="ti ti-circle-check"></i> {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Package Features --}}
                        @if(isset($subscription->package->packageFeatures) && $subscription->package->packageFeatures->count() > 0)
                            <div class="border-top mt-3 pt-3">
                                <h6 class="mb-3 fw-semibold">Package Features</h6>
                                <div class="row g-2">
                                    @foreach($subscription->package->packageFeatures as $feature)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-circle-check text-success me-2"></i>
                                                <span class="text-muted">{{ $feature->feature }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-credit-card-off text-muted" style="font-size: 64px;"></i>
                            <h6 class="mt-3 text-muted">No Active Subscription</h6>
                            <p class="text-muted mb-3">This company doesn't have an active subscription plan.</p>
                            <a href="{{ route('subscription.create', $company) }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add Subscription
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <ul class="nav nav-pills nav-fill bg-light rounded p-1 mb-4 shadow-sm" id="companyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded" id="artists-tab" data-bs-toggle="pill" data-bs-target="#artists" type="button" role="tab">
                <i class="ti ti-users me-2"></i>Artists <span class="badge bg-primary ms-1">{{ $stats['total_artists'] ?? 0 }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded" id="bookings-tab" data-bs-toggle="pill" data-bs-target="#bookings" type="button" role="tab">
                <i class="ti ti-calendar me-2"></i>Bookings <span class="badge bg-success ms-1">{{ $stats['total_bookings'] ?? 0 }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab">
                <i class="ti ti-chart-bar me-2"></i>Analytics
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                <i class="ti ti-activity me-2"></i>Activity
            </button>
        </li>
    </ul>

    <div class="tab-content" id="companyTabsContent">
        {{-- Artists Tab --}}
        <div class="tab-pane fade show active" id="artists" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold"><i class="ti ti-users me-2"></i>Company Artists</h5>
                    <a href="{{ route('artists.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Add Artist
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(isset($artists) && $artists->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">Artist</th>
                                        <th>Stage Name</th>
                                        <th>Contact</th>
                                        <th>Experience</th>
                                        <th>Specialization</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($artists as $artist)
                                        <tr>
                                            <td class="px-3">
                                                <div class="d-flex align-items-center">
                                                    @if($artist->image)
                                                        <img src="{{ asset('storage/' . $artist->image) }}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="avatar avatar-sm me-2">
                                                            <span class="avatar-title rounded-circle bg-primary fw-bold">
                                                                {{ substr($artist->stage_name ?? 'A', 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $artist->user->name ?? 'N/A' }}</p>
                                                        <small class="text-muted">{{ $artist->user->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-primary">{{ $artist->stage_name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="d-block"><i class="ti ti-phone text-muted"></i> {{ $artist->user->phone ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    <i class="ti ti-calendar"></i> {{ $artist->experience_years ?? '0' }} years
                                                </span>
                                            </td>
                                            <td>{{ $artist->specialization ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-star-filled text-warning me-1"></i>
                                                    <strong>{{ number_format($artist->rating ?? 0, 1) }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $artist->availability === 'available' ? 'success' : ($artist->availability === 'busy' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($artist->availability ?? 'unavailable') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('artists.show', $artist) }}" class="btn btn-light" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('artists.edit', $artist) }}" class="btn btn-light" title="Edit">
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
                            <i class="ti ti-user-off text-muted" style="font-size: 64px;"></i>
                            <h5 class="mt-3 text-muted">No Artists Found</h5>
                            <p class="text-muted">This company doesn't have any artists yet.</p>
                            <a href="{{ route('artists.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add First Artist
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bookings Tab --}}
        <div class="tab-pane fade" id="bookings" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold"><i class="ti ti-calendar me-2"></i>Recent Bookings</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>Confirmed</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($bookings) && $bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">#</th>
                                        <th>Customer</th>
                                        <th>Event Type</th>
                                        <th>Event Date</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-3"><strong class="text-primary">#{{ $booking->id }}</strong></td>
                                            <td>
                                                <div>
                                                    <p class="mb-0 fw-semibold">{{ $booking->name }} {{ $booking->surname }}</p>
                                                    <small class="text-muted">{{ $booking->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    {{ $booking->eventType->event_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</strong>
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($booking->event_date)->format('l') }}</small>
                                            </td>
                                            <td>{{ $booking->event_location ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['color' => 'warning', 'icon' => 'ti-clock'],
                                                        'confirmed' => ['color' => 'info', 'icon' => 'ti-check'],
                                                        'completed' => ['color' => 'success', 'icon' => 'ti-circle-check'],
                                                        'cancelled' => ['color' => 'danger', 'icon' => 'ti-x']
                                                    ];
                                                    $status = $booking->status ?? 'pending';
                                                    $config = $statusConfig[$status] ?? ['color' => 'secondary', 'icon' => 'ti-help'];
                                                @endphp
                                                <span class="badge bg-{{ $config['color'] }}">
                                                    <i class="{{ $config['icon'] }}"></i> {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
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
                            <h5 class="mt-3 text-muted">No Bookings Found</h5>
                            <p class="text-muted">This company doesn't have any bookings yet.</p>
                        </div>
                    @endif
                </div>
                @if(isset($bookings) && $bookings->hasPages())
                    <div class="card-footer bg-white">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Analytics Tab --}}
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            <div class="row g-3">
                {{-- Monthly Performance --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-semibold"><i class="ti ti-chart-line me-2"></i>Booking Trends</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="bookingChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Performance Metrics --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-semibold"><i class="ti ti-chart-pie me-2"></i>Performance</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Booking Rate</span>
                                    <span class="fw-bold">78%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 78%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Customer Satisfaction</span>
                                    <span class="fw-bold">92%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: 92%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Artist Availability</span>
                                    <span class="fw-bold">85%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 85%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Response Time</span>
                                    <span class="fw-bold">95%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 95%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Tab --}}
        <div class="tab-pane fade" id="activity" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold"><i class="ti ti-activity me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @php
                            $activities = [
                                ['icon' => 'ti-user-plus', 'color' => 'primary', 'title' => 'New artist added', 'desc' => 'John Smith joined as DJ', 'time' => '2 hours ago'],
                                ['icon' => 'ti-calendar-check', 'color' => 'success', 'title' => 'Booking confirmed', 'desc' => 'Wedding event for Sarah & Tom', 'time' => '5 hours ago'],
                                ['icon' => 'ti-edit', 'color' => 'warning', 'title' => 'Profile updated', 'desc' => 'Company information modified', 'time' => '1 day ago'],
                                ['icon' => 'ti-star', 'color' => 'info', 'title' => 'New review received', 'desc' => '5-star rating from customer', 'time' => '2 days ago'],
                                ['icon' => 'ti-credit-card', 'color' => 'purple', 'title' => 'Subscription renewed', 'desc' => 'Premium plan extended', 'time' => '3 days ago'],
                            ];
                        @endphp

                        @foreach($activities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-{{ $activity['color'] }}-subtle text-{{ $activity['color'] }}">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-semibold">{{ $activity['title'] }}</h6>
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

    {{-- Enhanced Styles --}}
    <style>
        .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
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

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .nav-pills .nav-link {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(102, 126, 234, 0.1);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .subscription-badge {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fff4e0 0%, #ffe7b8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-timeline {
            position: relative;
        }

        .timeline-item {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            bottom: -25px;
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

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.002);
        }

        .bg-purple-subtle {
            background-color: rgba(118, 75, 162, 0.1);
        }

        .text-purple {
            color: #764ba2;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Booking Trend Chart
            const ctx = document.getElementById('bookingChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Bookings',
                            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 40, 42],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
