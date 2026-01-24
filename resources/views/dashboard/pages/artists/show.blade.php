@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $artist->stage_name }}
            </h4>
            <p class="text-muted mb-0 mt-1">Artist Profile & Performance</p>
        </div>
        <div class="text-end">
            <a href="{{ route('artists.index') }}" class="btn btn-secondary btn-sm me-2">
                <i data-lucide="arrow-left" class="me-1"></i>Back to List
            </a>
            <a href="{{ route('artists.edit', $artist) }}" class="btn btn-primary btn-sm">
                <i data-lucide="edit" class="me-1"></i>Edit Artist
            </a>
        </div>
    </div>

    {{-- Artist Profile --}}
    <div class="row g-3 mb-4">
        {{-- Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($artist->image)
                            <img src="{{ asset('storage/' . $artist->image) }}" alt="{{ $artist->stage_name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="avatar avatar-xl mb-3 mx-auto">
                                <span class="avatar-title rounded-circle bg-primary fs-1">{{ substr($artist->stage_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $artist->stage_name }}</h5>
                        <p class="text-muted mb-2">{{ $artist->specialization }}</p>
                        @if(isset($stats['avg_rating']) && $stats['avg_rating'] > 0)
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($stats['avg_rating']))
                                        <i data-lucide="star" class="text-warning" style="width: 16px; fill: currentColor;"></i>
                                    @else
                                        <i data-lucide="star" class="text-muted" style="width: 16px;"></i>
                                    @endif
                                @endfor
                                <span class="ms-1 text-muted">({{ number_format($stats['avg_rating'], 1) }})</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="building-2" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $artist->company->name ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="user" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $artist->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="mail" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $artist->user->email ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="award" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $artist->experience_years }} years experience</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="music" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $artist->genres }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i data-lucide="calendar" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">Joined {{ $artist->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>

                    @if($artist->bio)
                        <div class="border-top pt-3 mt-3">
                            <h6 class="mb-2">About</h6>
                            <p class="text-muted mb-0 small">{{ $artist->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                                    <i data-lucide="calendar-check" class="text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['total_bookings'] ?? 0 }}</h3>
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
                                <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                                    <i data-lucide="check-circle" class="text-success"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['completed_bookings'] ?? 0 }}</h3>
                                    <small class="text-muted">Completed</small>
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
                                    <i data-lucide="message-circle" class="text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['reviews_count'] ?? 0 }}</h3>
                                    <small class="text-muted">Reviews</small>
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
                                    <h3 class="mb-0">${{ number_format($stats['total_earnings'] ?? 0, 0) }}</h3>
                                    <small class="text-muted">Earnings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Performance Chart --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3"><i data-lucide="trending-up" class="me-2"></i>Performance Overview</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="mb-0 text-primary">{{ number_format((($stats['completed_bookings'] ?? 0) / ($stats['total_bookings'] ?: 1)) * 100, 1) }}%</h5>
                                        <small class="text-muted">Completion Rate</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="mb-0 text-success">{{ number_format($stats['avg_rating'] ?? 0, 1) }}/5.0</h5>
                                        <small class="text-muted">Average Rating</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h5 class="mb-0 text-info">${{ number_format(($stats['total_earnings'] ?? 0) / ($stats['completed_bookings'] ?: 1), 0) }}</h5>
                                    <small class="text-muted">Avg per Booking</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <ul class="nav nav-pills mb-4" id="artistTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="bookings-tab" data-bs-toggle="pill" data-bs-target="#bookings" type="button" role="tab">
                <i data-lucide="calendar" class="me-1"></i>Recent Bookings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews" type="button" role="tab">
                <i data-lucide="message-circle" class="me-1"></i>Reviews ({{ $stats['reviews_count'] ?? 0 }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity" type="button" role="tab">
                <i data-lucide="activity" class="me-1"></i>Activity
            </button>
        </li>
    </ul>

    <div class="tab-content" id="artistTabsContent">
        {{-- Bookings Tab --}}
        <div class="tab-pane fade show active" id="bookings" role="tabpanel">
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
                                    <th>Event Type</th>
                                    <th>Event Date</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td><strong>#{{ $booking->id }}</strong></td>
                                        <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</td>
                                        <td>{{ $booking->company->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $booking->status === 'confirmed' ? 'success' : 
                                                ($booking->status === 'pending' ? 'warning' : 
                                                ($booking->status === 'completed' ? 'info' : 'danger')) 
                                            }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($booking->total_amount ?? 0, 2) }}</td>
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
            </div>
        </div>

        {{-- Reviews Tab --}}
        <div class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Customer Reviews</h5>
                </div>
                <div class="card-body">
                    @forelse($recentReviews ?? [] as $review)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-sm bg-primary-subtle rounded-circle">
                                    <span class="avatar-title">{{ substr($review->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i data-lucide="star" class="text-warning" style="width: 14px; fill: currentColor;"></i>
                                            @else
                                                <i data-lucide="star" class="text-muted" style="width: 14px;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted mb-0">{{ $review->comment ?? 'No comment provided' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i data-lucide="inbox" class="mb-2"></i>
                            <p class="text-muted mb-0">No reviews yet</p>
                        </div>
                    @endforelse
                </div>
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
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-sm bg-success-subtle rounded-circle">
                                        <i data-lucide="user-check" class="text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Artist Profile Created</h6>
                                    <p class="text-muted mb-1">Artist account was created and activated</p>
                                    <small class="text-muted">{{ $artist->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        @foreach($recentBookings->take(3) ?? [] as $booking)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-sm bg-primary-subtle rounded-circle">
                                            <i data-lucide="calendar-check" class="text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Booking #{{ $booking->id }}</h6>
                                        <p class="text-muted mb-1">{{ $booking->eventType->name ?? 'Event' }} - {{ ucfirst($booking->status) }}</p>
                                        <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
