@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="file-text" class="me-2"></i>Booking Details
            </h4>
            <p class="text-muted mb-0 mt-1">Booking #{{ $booking->id }}</p>
        </div>
        <div class="text-end">
            <a href="{{ route('artist.bookings') }}" class="btn btn-light btn-sm">
                <i data-lucide="arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Event Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Event Type</p>
                            <h6>{{ $booking->eventType->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Date & Time</p>
                            <h6>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y h:i A') }}</h6>
                        </div>
                        <div class="col-12">
                            <p class="text-muted mb-1">Venue</p>
                            <h6>{{ $booking->venue_address ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Guests</p>
                            <h6>{{ $booking->number_of_guests ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Duration</p>
                            <h6>{{ $booking->duration_hours ?? 0 }} hours</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Amount</p>
                            <h6>${{ number_format($booking->total_amount ?? 0, 2) }}</h6>
                        </div>
                        @if($booking->special_requests)
                        <div class="col-12">
                            <p class="text-muted mb-1">Special Requests</p>
                            <p>{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Name</p>
                            <h6>{{ $booking->user->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Email</p>
                            <h6>{{ $booking->user->email ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Phone</p>
                            <h6>{{ $booking->contact_phone ?? $booking->user->phone ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <span class="badge bg-{{
                        $booking->status === 'confirmed' ? 'success' :
                        ($booking->status === 'pending' ? 'warning' :
                        ($booking->status === 'completed' ? 'info' : 'danger'))
                    }} fs-5 w-100 py-2">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            @if($booking->status === 'pending')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('artist.bookings.accept', $booking->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i data-lucide="check"></i> Accept Booking
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i data-lucide="x"></i> Reject Booking
                    </button>
                </div>
            </div>
            @endif

            @if($canComplete)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Complete Event</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i data-lucide="check-circle"></i> Mark as Completed
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('artist.bookings.reject', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('artist.bookings.complete', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Mark as Completed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Completion Notes (Optional)</label>
                            <textarea name="completion_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Mark as Completed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
