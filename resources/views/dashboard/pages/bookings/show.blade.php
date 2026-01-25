@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="calendar-check" class="me-2"></i>Booking Details #{{ $booking->id }}
            </h4>
            <p class="text-muted mb-0 mt-1">Complete booking information and management</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-sm">
                <i data-lucide="arrow-left" class="me-1"></i>Back to List
            </a>
            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-primary btn-sm">
                <i data-lucide="edit" class="me-1"></i>Edit Booking
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Main Booking Information --}}
        <div class="col-lg-8">
            {{-- Status Card --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1 fw-semibold">Booking Status</h6>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'in_progress' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    'rejected' => 'danger'
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'confirmed' => 'check-circle',
                                    'in_progress' => 'loader',
                                    'completed' => 'check-check',
                                    'cancelled' => 'x-circle',
                                    'rejected' => 'alert-triangle'
                                ];
                                $status = $booking->status ?? 'pending';
                                $color = $statusColors[$status] ?? 'secondary';
                                $icon = $statusIcons[$status] ?? 'info';
                            @endphp
                            <div class="d-flex align-items-center">
                                <i data-lucide="{{ $icon }}" class="text-{{ $color }} me-2"></i>
                                <span class="badge bg-{{ $color }} fs-14 px-3 py-2">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1 fw-semibold">Booking ID</h6>
                            <p class="mb-0 fw-bold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1 fw-semibold">Created On</h6>
                            <p class="mb-0">{{ $booking->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1 fw-semibold">Last Updated</h6>
                            <p class="mb-0">{{ $booking->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Event Information --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="calendar" class="me-2"></i>Event Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-soft-primary rounded">
                                        <i data-lucide="tag" class="text-primary avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-semibold">Event Type</h6>
                                    <p class="text-muted mb-0">{{ $booking->eventType->event_type ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-soft-success rounded">
                                        <i data-lucide="calendar-days" class="text-success avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-semibold">Event Date</h6>
                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($booking->event_date)->format('l, F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @if($booking->wedding_time)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-soft-info rounded">
                                        <i data-lucide="clock" class="text-info avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-semibold">Event Time</h6>
                                    <p class="text-muted mb-0">{{ $booking->wedding_time }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-soft-warning rounded">
                                        <i data-lucide="map-pin" class="text-warning avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-semibold">Venue Address</h6>
                                    <p class="text-muted mb-0">{{ $booking->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assigned Artist Information --}}
            @if($booking->assignedArtist)
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="music" class="me-2"></i>Assigned Artist
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            @if($booking->assignedArtist->profile_image)
                                <img src="{{ asset('storage/' . $booking->assignedArtist->profile_image) }}"
                                     alt="{{ $booking->assignedArtist->user->name }}"
                                     class="rounded-circle"
                                     style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                                <span class="avatar-title rounded-circle bg-primary fs-3">
                                    {{ substr($booking->assignedArtist->user->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $booking->assignedArtist->user->name }}</h5>
                            <p class="text-muted mb-1">{{ $booking->assignedArtist->specialization ?? 'DJ/Artist' }}</p>
                            @if($booking->assignedArtist->rating)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">⭐</span>
                                <strong>{{ number_format($booking->assignedArtist->rating, 1) }}</strong>
                                <span class="text-muted ms-1">({{ $booking->assignedArtist->total_reviews ?? 0 }} reviews)</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="mail" class="me-1"></i>Email</h6>
                            <p class="mb-0">
                                <a href="mailto:{{ $booking->assignedArtist->user->email }}">{{ $booking->assignedArtist->user->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="phone" class="me-1"></i>Phone</h6>
                            <p class="mb-0">
                                <a href="tel:{{ $booking->assignedArtist->user->phone }}">{{ $booking->assignedArtist->user->phone ?? 'N/A' }}</a>
                            </p>
                        </div>
                        @if($booking->assignedArtist->experience_years)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="award" class="me-1"></i>Experience</h6>
                            <p class="mb-0">{{ $booking->assignedArtist->experience_years }} years</p>
                        </div>
                        @endif
                    </div>
                    @if($booking->company_notes)
                    <div class="mt-3">
                        <h6 class="text-muted mb-2"><i data-lucide="file-text" class="me-1"></i>Company Notes</h6>
                        <div class="alert alert-info mb-0">{{ $booking->company_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @elseif(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
            <div class="card mb-3 border-warning">
                <div class="card-body text-center py-4">
                    <i data-lucide="alert-circle" class="text-warning mb-2" style="width: 48px; height: 48px;"></i>
                    <h5 class="text-warning mb-2">No Artist Assigned</h5>
                    <p class="text-muted mb-3">This booking doesn't have an assigned artist yet.</p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#assignArtistModal">
                        <i data-lucide="user-plus" class="me-1"></i>Assign Artist Now
                    </button>
                </div>
            </div>
            @endif

            {{-- Customer Information --}}
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="user" class="me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="user-circle" class="me-1"></i>Full Name</h6>
                            <p class="mb-0 fw-semibold">{{ $booking->name }} {{ $booking->surname }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="cake" class="me-1"></i>Date of Birth</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($booking->date_of_birth)->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="phone" class="me-1"></i>Phone Number</h6>
                            <p class="mb-0">
                                <a href="tel:{{ $booking->phone }}" class="text-decoration-none">{{ $booking->phone }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="mail" class="me-1"></i>Email Address</h6>
                            <p class="mb-0">
                                <a href="mailto:{{ $booking->email }}" class="text-decoration-none">{{ $booking->email }}</a>
                            </p>
                        </div>
                        @if($booking->user)
                        <div class="col-12">
                            <div class="alert alert-light border d-flex align-items-center">
                                <i data-lucide="info" class="text-info me-2"></i>
                                <span>Registered User: <strong>{{ $booking->user->name }}</strong> ({{ $booking->user->email }})</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Wedding/Partner Information --}}
            @if($booking->partner_name || $booking->wedding_date)
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="heart" class="me-2"></i>Wedding Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($booking->partner_name)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="users" class="me-1"></i>Partner Name</h6>
                            <p class="mb-0 fw-semibold">{{ $booking->partner_name }}</p>
                        </div>
                        @endif
                        @if($booking->wedding_date)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2"><i data-lucide="calendar-heart" class="me-1"></i>Wedding Date</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($booking->wedding_date)->format('F j, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Music & Preferences --}}
            @if($booking->dos || $booking->donts || $booking->playlist_spotify)
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="music" class="me-2"></i>Music Preferences
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->dos)
                    <div class="mb-3">
                        <h6 class="text-success mb-2"><i data-lucide="check-circle" class="me-1"></i>Do's (Preferred)</h6>
                        <div class="alert alert-success mb-0">
                            {{ $booking->dos }}
                        </div>
                    </div>
                    @endif
                    @if($booking->donts)
                    <div class="mb-3">
                        <h6 class="text-danger mb-2"><i data-lucide="x-circle" class="me-1"></i>Don'ts (Avoid)</h6>
                        <div class="alert alert-danger mb-0">
                            {{ $booking->donts }}
                        </div>
                    </div>
                    @endif
                    @if($booking->playlist_spotify)
                    <div>
                        <h6 class="text-muted mb-2"><i data-lucide="music-2" class="me-1"></i>Spotify Playlist</h6>
                        <a href="{{ $booking->playlist_spotify }}" target="_blank" class="btn btn-spotify btn-sm">
                            <i class="bx bxl-spotify"></i> Open Spotify Playlist
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Additional Notes --}}
            @if($booking->additional_notes)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="file-text" class="me-2"></i>Additional Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i data-lucide="info" class="me-2"></i>
                        {{ $booking->additional_notes }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="zap" class="me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
                            @if(!$booking->assignedArtist)
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignArtistModal">
                                    <i data-lucide="user-plus" class="me-1"></i>Assign Artist
                                </button>
                            @else
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reassignArtistModal">
                                    <i data-lucide="user-check" class="me-1"></i>Reassign Artist
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-warning">
                            <i data-lucide="edit" class="me-1"></i>Edit Booking
                        </a>
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i data-lucide="refresh-cw" class="me-1"></i>Update Status
                        </button>
                        <a href="mailto:{{ $booking->email }}" class="btn btn-primary">
                            <i data-lucide="mail" class="me-1"></i>Email Customer
                        </a>
                        <a href="tel:{{ $booking->phone }}" class="btn btn-success">
                            <i data-lucide="phone-call" class="me-1"></i>Call Customer
                        </a>
                        <button class="btn btn-secondary" onclick="window.print()">
                            <i data-lucide="printer" class="me-1"></i>Print Details
                        </button>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="clock" class="me-2"></i>Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="timeline timeline-left">
                        <li class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Booking Created</h6>
                                <p class="text-muted mb-0 small">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </li>
                        @if($booking->updated_at != $booking->created_at)
                        <li class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <p class="text-muted mb-0 small">{{ $booking->updated_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </li>
                        @endif
                        <li class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Event Date</h6>
                                <p class="text-muted mb-0 small">{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="bar-chart-2" class="me-2"></i>Booking Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="card bg-soft-primary border-0">
                                <div class="card-body p-3">
                                    <h3 class="mb-1">{{ \Carbon\Carbon::parse($booking->event_date)->diffInDays(now(), false) > 0 ? \Carbon\Carbon::parse($booking->event_date)->diffInDays(now()) . ' days ago' : \Carbon\Carbon::parse($booking->event_date)->diffInDays(now()) . ' days' }}</h3>
                                    <p class="text-muted mb-0 small">{{ \Carbon\Carbon::parse($booking->event_date)->isPast() ? 'Event Passed' : 'Until Event' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-soft-success border-0">
                                <div class="card-body p-3">
                                    <h3 class="mb-1">{{ $booking->created_at->diffInDays(now()) }}</h3>
                                    <p class="text-muted mb-0 small">Days Booked</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Status Modal --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Booking Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $booking->status ?? 'pending')) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status...</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
    {{-- Assign Artist Modal --}}
    <div class="modal fade" id="assignArtistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bookings.assign-artist', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Artist to Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Artist <span class="text-danger">*</span></label>
                            <select name="artist_id" class="form-select" required>
                                <option value="">Choose an artist...</option>
                                @foreach($artists ?? [] as $artist)
                                    @php
                                        $companyMatch = auth()->user()->role->role_key === 'master_admin' ?
                                            ($booking->company_id == $artist->company_id) : true;
                                    @endphp
                                    @if($companyMatch)
                                    <option value="{{ $artist->id }}">
                                        {{ $artist->user->name }} - {{ $artist->specialization ?? 'DJ' }}
                                        @if(auth()->user()->role->role_key === 'master_admin' && $artist->company)
                                            ({{ $artist->company->name }})
                                        @endif
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            @if(auth()->user()->role->role_key === 'master_admin')
                                <small class="text-muted">Only artists from {{ $booking->company->name ?? 'the booking company' }} are shown</small>
                            @else
                                <small class="text-muted">Select the artist who will perform at this event</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Notes (Optional)</label>
                            <textarea name="company_notes" class="form-control" rows="3" placeholder="Add any special instructions or notes for the artist...">{{ $booking->company_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="check" class="me-1"></i>Assign Artist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reassign Artist Modal --}}
    <div class="modal fade" id="reassignArtistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bookings.assign-artist', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Reassign Artist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i data-lucide="alert-triangle" class="me-2"></i>
                            <strong>Current Artist:</strong> {{ $booking->assignedArtist->user->name ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Artist <span class="text-danger">*</span></label>
                            <select name="artist_id" class="form-select" required>
                                <option value="">Choose a new artist...</option>
                                @foreach($artists ?? [] as $artist)
                                    @php
                                        $companyMatch = auth()->user()->role->role_key === 'master_admin' ?
                                            ($booking->company_id == $artist->company_id) : true;
                                    @endphp
                                    @if($companyMatch)
                                    <option value="{{ $artist->id }}" {{ $booking->assigned_artist_id == $artist->id ? 'selected' : '' }}>
                                        {{ $artist->user->name }} - {{ $artist->specialization ?? 'DJ' }}
                                        @if(auth()->user()->role->role_key === 'master_admin' && $artist->company)
                                            ({{ $artist->company->name }})
                                        @endif
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            @if(auth()->user()->role->role_key === 'master_admin')
                                <small class="text-muted">Only artists from {{ $booking->company->name ?? 'the booking company' }} are shown</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Notes (Optional)</label>
                            <textarea name="company_notes" class="form-control" rows="3" placeholder="Reason for reassignment or special instructions...">{{ $booking->company_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i data-lucide="refresh-cw" class="me-1"></i>Reassign Artist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @push('styles')
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-soft-primary { background-color: rgba(99, 102, 241, 0.1); }
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1); }
        .bg-soft-info { background-color: rgba(59, 130, 246, 0.1); }
        .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
        .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }

        .timeline {
            list-style: none;
            padding-left: 0;
            position: relative;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid #fff;
        }
        .btn-spotify {
            background: #1DB954;
            color: white;
        }
        .btn-spotify:hover {
            background: #1ed760;
            color: white;
        }
    </style>
    @endpush
@endsection
