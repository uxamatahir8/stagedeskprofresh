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
            <a href="{{ route('customer.bookings') }}" class="btn btn-light btn-sm">
                <i data-lucide="arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Booking Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Event Type</p>
                            <h6>{{ $booking->eventType->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Company</p>
                            <h6>{{ $booking->company->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Event Date & Time</p>
                            <h6>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y h:i A') }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status</p>
                            <span class="badge bg-{{
                                $booking->status === 'confirmed' ? 'success' :
                                ($booking->status === 'pending' ? 'warning' :
                                ($booking->status === 'completed' ? 'info' : 'danger'))
                            }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="col-12">
                            <p class="text-muted mb-1">Venue Address</p>
                            <h6>{{ $booking->venue_address ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Number of Guests</p>
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

            <!-- Artist Information -->
            @if($booking->assignedArtist)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Assigned Artist</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg me-3">
                            @if($booking->assignedArtist->profile_image)
                                <img src="{{ Storage::url($booking->assignedArtist->profile_image) }}" alt="{{ $booking->assignedArtist->user->name }}" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                                <span class="avatar-title rounded-circle bg-primary fs-3">
                                    {{ substr($booking->assignedArtist->user->name ?? 'A', 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $booking->assignedArtist->user->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-2">{{ $booking->assignedArtist->user->email ?? '' }}</p>
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">⭐</span>
                                <strong>{{ number_format($booking->assignedArtist->rating ?? 0, 1) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Review Section -->
            @if($canReview)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Leave a Review</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.reviews.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="artist_id" value="{{ $booking->assigned_artist_id }}">

                        <div class="mb-3">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                                    <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                        {{ $i }} ⭐
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Review <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="send"></i> Submit Review
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    @forelse($booking->payments as $payment)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Payment #{{ $payment->id }}</span>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Amount</span>
                            <strong>${{ number_format($payment->amount, 2) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No payments recorded</p>
                    @endforelse
                </div>
            </div>

            <!-- Actions -->
            @if($booking->status === 'pending' || $booking->status === 'confirmed')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelBookingModal">
                        <i data-lucide="x-circle"></i> Cancel Booking
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            Are you sure you want to cancel this booking?
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
