@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="star" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Customer reviews and ratings</p>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i data-lucide="check-circle" class="me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i data-lucide="alert-circle" class="me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-soft-primary rounded">
                                <i data-lucide="star" class="text-primary avatar-icon"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total Reviews</h6>
                            <h3 class="mb-0">{{ $reviews->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-soft-success rounded">
                                <i data-lucide="thumbs-up" class="text-success avatar-icon"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Average Rating</h6>
                            <h3 class="mb-0">
                                @php
                                    $avgRating = $reviews->avg('rating');
                                @endphp
                                {{ number_format($avgRating, 1) }} <small class="text-warning">★</small>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-soft-warning rounded">
                                <i data-lucide="star-half" class="text-warning avatar-icon"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">5-Star Reviews</h6>
                            <h3 class="mb-0">{{ $reviews->where('rating', 5)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-soft-info rounded">
                                <i data-lucide="message-square" class="text-info avatar-icon"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">With Comments</h6>
                            <h3 class="mb-0">{{ $reviews->whereNotNull('review')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews List --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i data-lucide="list" class="me-2"></i>All Reviews
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" id="filterRating">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @forelse($reviews as $review)
                <div class="review-item border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <img src="{{ $review->user->profile?->profile_image ? asset('storage/' . $review->user->profile->profile_image) : asset('images/users/user-4.jpg') }}"
                                 alt="User" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $review->user->name }}</h6>
                                <small class="text-muted">
                                    <i data-lucide="calendar" class="me-1" style="width: 14px; height: 14px;"></i>
                                    {{ $review->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="rating mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="ti ti-star-filled text-warning"></i>
                                    @else
                                        <i class="ti ti-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="badge bg-primary ms-2">{{ $review->rating }}/5</span>
                            </div>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    @if($review->review)
                        <div class="review-comment bg-light p-3 rounded mb-2">
                            <p class="mb-0">{{ $review->review }}</p>
                        </div>
                    @endif

                    <div class="review-meta d-flex gap-3 flex-wrap">
                        @if($review->booking)
                            <small class="text-muted">
                                <i data-lucide="calendar-check" class="me-1" style="width: 14px; height: 14px;"></i>
                                Booking #{{ $review->booking->id }}
                            </small>
                        @endif
                        @if($review->artist)
                            <small class="text-muted">
                                <i data-lucide="user" class="me-1" style="width: 14px; height: 14px;"></i>
                                Artist: {{ $review->artist->user->name ?? 'N/A' }}
                            </small>
                        @endif
                        @if($review->company)
                            <small class="text-muted">
                                <i data-lucide="building" class="me-1" style="width: 14px; height: 14px;"></i>
                                Company: {{ $review->company->name }}
                            </small>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i data-lucide="inbox" style="width: 64px; height: 64px;" class="text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Reviews Found</h5>
                    <p class="text-muted">There are no reviews to display at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($reviews->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews
                    </div>
                    <div>
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

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
        .review-item:last-child {
            border-bottom: none !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        .rating i {
            font-size: 16px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter by rating
            document.getElementById('filterRating').addEventListener('change', function() {
                const rating = this.value;
                const reviews = document.querySelectorAll('.review-item');

                reviews.forEach(review => {
                    if (rating === '') {
                        review.style.display = 'block';
                    } else {
                        const ratingBadge = review.querySelector('.badge');
                        const reviewRating = ratingBadge ? ratingBadge.textContent.trim().split('/')[0] : '';
                        review.style.display = reviewRating === rating ? 'block' : 'none';
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
