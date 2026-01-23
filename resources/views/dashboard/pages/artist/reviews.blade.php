@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="star" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Customer reviews and ratings</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($stats['average_rating'], 1) }}</h2>
                    <div class="text-warning mb-2">⭐⭐⭐⭐⭐</div>
                    <p class="text-muted mb-0">Average Rating</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($stats['total_reviews']) }}</h2>
                    <p class="text-muted mb-0">Total Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ number_format($stats['pending_reviews']) }}</h2>
                    <p class="text-muted mb-0">Pending Approval</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Grid -->
    <div class="row g-3">
        @forelse($reviews as $review)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-2">⭐</span>
                                <strong class="fs-5">{{ $review->rating }}.0</strong>
                            </div>
                            <span class="badge bg-{{ $review->status === 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="avatar-sm me-2">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ substr($review->user->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>

                        <p class="text-muted mb-3">{{ $review->comment }}</p>

                        @if($review->booking)
                        <div class="border-top pt-3">
                            <small class="text-muted">
                                Booking: <a href="{{ route('artist.bookings.details', $review->booking_id) }}">#{{ $review->booking_id }}</a>
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
                        <p class="text-muted">You'll see customer reviews here</p>
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
