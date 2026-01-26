@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="star" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Your reviews and ratings</p>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="row g-3">
        @forelse($reviews as $review)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <!-- Rating -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-2">⭐</span>
                                <strong class="fs-5">{{ $review->rating }}.0</strong>
                            </div>
                            <span class="badge bg-{{
                                $review->status === 'approved' ? 'success' :
                                ($review->status === 'pending' ? 'warning' : 'danger')
                            }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>

                        <!-- Artist Info -->
                        @if($review->artist)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="avatar-sm me-2">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ substr($review->artist->user->name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $review->artist->user->name ?? 'N/A' }}</h6>
                                <small class="text-muted">Artist</small>
                            </div>
                        </div>
                        @endif

                        <!-- Review Comment -->
                        <p class="text-muted mb-3">{{ $review->comment }}</p>

                        <!-- Booking Info -->
                        @if($review->booking)
                        <div class="border-top pt-3">
                            <small class="text-muted">
                                Booking: <a href="{{ route('customer.bookings.details', $review->booking_id) }}" class="text-primary">#{{ $review->booking_id }}</a>
                            </small>
                            <br>
                            <small class="text-muted">
                                Event: {{ $review->booking->eventType->event_type ?? 'N/A' }}
                            </small>
                            <br>
                            <small class="text-muted">
                                Date: {{ $review->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i data-lucide="star" class="mb-3" style="width: 64px; height: 64px;"></i>
                        <h5>No reviews yet</h5>
                        <p class="text-muted">You haven't submitted any reviews</p>
                        <a href="{{ route('customer.bookings') }}" class="btn btn-primary btn-sm mt-2">
                            View Bookings
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
    @endif
@endsection
