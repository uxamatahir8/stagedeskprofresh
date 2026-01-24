@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="building-2" class="me-2"></i>{{ $company->name }}
            </h4>
            <p class="text-muted mb-0 mt-1">Company Details & Analytics</p>
        </div>
        <div class="text-end">
            <a href="{{ route('companies') }}" class="btn btn-secondary btn-sm me-2">
                <i data-lucide="arrow-left" class="me-1"></i>Back to List
            </a>
            <a href="{{ route('company.edit', $company->id) }}" class="btn btn-primary btn-sm">
                <i data-lucide="edit" class="me-1"></i>Edit Company
            </a>
        </div>
    </div>

    {{-- Company Overview --}}
    <div class="row g-3 mb-4">
        {{-- Company Info Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="rounded mb-3" style="max-width: 120px;">
                        @else
                            <div class="avatar avatar-xl mb-3">
                                <span class="avatar-title rounded bg-primary fs-1">{{ substr($company->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $company->name }}</h5>
                        <span class="badge bg-{{ $company->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($company->status) }}</span>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="mail" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $company->user->email }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="phone" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $company->phone ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="map-pin" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $company->city }}, {{ $company->state }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i data-lucide="calendar" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">Joined {{ $company->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                                    <i data-lucide="users" class="text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['total_artists'] }}</h3>
                                    <small class="text-muted">Artists</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                                    <i data-lucide="calendar-check" class="text-success"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                                    <small class="text-muted">Bookings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-warning-subtle rounded me-3">
                                    <i data-lucide="star" class="text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</h3>
                                    <small class="text-muted">Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-info-subtle rounded me-3">
                                    <i data-lucide="dollar-sign" class="text-info"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</h3>
                                    <small class="text-muted">Revenue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Subscription Info --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3"><i data-lucide="credit-card" class="me-2"></i>Current Subscription</h6>
                            @if(isset($subscription) && $subscription)
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="mb-0 text-primary">{{ $subscription->package->name ?? 'N/A' }}</h5>
                                            <small class="text-muted">Plan</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center border-start">
                                            <h6 class="mb-0">${{ $subscription->package->price ?? 0 }}</h6>
                                            <small class="text-muted">{{ ucfirst($subscription->package->type ?? 'N/A') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center border-start">
                                            <h6 class="mb-0">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</h6>
                                            <small class="text-muted">Expires</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center border-start">
                                            <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($subscription->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No active subscription</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <ul class="nav nav-pills mb-4" id="companyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="artists-tab" data-bs-toggle="pill" data-bs-target="#artists" type="button" role="tab">
                <i data-lucide="users" class="me-1"></i>Artists ({{ $stats['total_artists'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bookings-tab" data-bs-toggle="pill" data-bs-target="#bookings" type="button" role="tab">
                <i data-lucide="calendar" class="me-1"></i>Bookings ({{ $stats['total_bookings'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                <i data-lucide="activity" class="me-1"></i>Activity
            </button>
        </li>
    </ul>

    <div class="tab-content" id="companyTabsContent">
        {{-- Artists Tab --}}
        <div class="tab-pane fade show active" id="artists" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Company Artists</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Artist</th>
                                    <th>Email</th>
                                    <th>Experience</th>
                                    <th>Hourly Rate</th>
                                    <th>Availability</th>
                                    <th>Bookings</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($artists as $artist)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($artist->profile_image) && $artist->profile_image)
                                                    <img src="{{ asset('storage/' . $artist->profile_image) }}" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-title rounded-circle bg-primary">{{ substr($artist->user->name ?? 'A', 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <span>{{ $artist->user->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $artist->user->email ?? 'N/A' }}</td>
                                        <td>{{ $artist->experience_years ?? '0' }} years</td>
                                        <td>${{ number_format($artist->hourly_rate ?? 0, 2) }}/hr</td>
                                        <td>
                                            <span class="badge bg-{{ $artist->availability === 'available' ? 'success' : ($artist->availability === 'busy' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($artist->availability ?? 'unavailable') }}
                                            </span>
                                        </td>
                                        <td>{{ $artist->bookings_count ?? 0 }}</td>
                                        <td>
                                            <a href="{{ route('artists.show', $artist->id) }}" class="btn btn-sm btn-light">
                                                <i data-lucide="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i data-lucide="inbox" class="mb-2"></i>
                                            <p class="text-muted mb-0">No artists found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bookings Tab --}}
        <div class="tab-pane fade" id="bookings" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer</th>
                                    <th>Event Type</th>
                                    <th>Event Date</th>
                                    <th>Artist</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->name }} {{ $booking->surname }}</td>
                                        <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</td>
                                        <td>{{ $booking->assignedArtist->user->name ?? 'Unassigned' }}</td>
                                        <td>
                                            <span class="badge bg-{{
                                                $booking->status === 'confirmed' ? 'success' :
                                                ($booking->status === 'pending' ? 'warning' :
                                                ($booking->status === 'completed' ? 'info' : 'danger'))
                                            }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-light">
                                                <i data-lucide="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i data-lucide="inbox" class="mb-2"></i>
                                            <p class="text-muted mb-0">No bookings found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($bookings) && $bookings->hasPages())
                    <div class="card-footer bg-white">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Activity Tab --}}
        <div class="tab-pane fade" id="activity" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($activityLogs ?? [] as $log)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-sm bg-primary-subtle rounded-circle">
                                            <i data-lucide="activity" class="text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $log->action }}</h6>
                                        <p class="text-muted mb-1">{{ $log->description }}</p>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i data-lucide="inbox" class="mb-2"></i>
                                <p class="text-muted mb-0">No activity logs found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        lucide.createIcons();
    </script>
    @endpush
@endsection
