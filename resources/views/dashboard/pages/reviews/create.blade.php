@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="star" style="width: 20px; height: 20px;" class="me-2"></i>{{ $title }}
            </h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i data-lucide="home" style="width: 14px; height: 14px;"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Reviews</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Booking: #{{ $booking->tracking_code ?? $booking->id }} — {{ $booking->eventType->name ?? 'Event' }}</h5>
        </div>
        <div class="card-body">
            @if($booking->assignedArtist)
                <p class="text-muted mb-3">You are reviewing <strong>{{ $booking->assignedArtist->user->name ?? 'Artist' }}</strong>.</p>
            @endif

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                @if($booking->assigned_artist_id)
                    <input type="hidden" name="artist_id" value="{{ $booking->assigned_artist_id }}">
                @endif
                @if($booking->company_id)
                    <input type="hidden" name="company_id" value="{{ $booking->company_id }}">
                @endif

                <div class="mb-3">
                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2 align-items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }} required>
                                <span class="ms-1">{{ $i }} <i data-lucide="star" style="width: 14px; height: 14px;" class="text-warning"></i></span>
                            </label>
                        @endfor
                    </div>
                    @error('rating')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="review" class="form-label">Your Review (optional)</label>
                    <textarea name="review" id="review" class="form-control" rows="4" maxlength="2000" placeholder="Share your experience...">{{ old('review') }}</textarea>
                    @error('review')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" style="width: 16px; height: 16px;" class="me-1"></i> Submit Review
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
