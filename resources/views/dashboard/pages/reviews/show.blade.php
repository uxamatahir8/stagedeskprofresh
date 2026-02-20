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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3">
                        <span class="avatar-title rounded-circle bg-primary">{{ $review->user->initials ?? 'U' }}</span>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $review->user->name ?? 'Unknown' }}</h6>
                        <small class="text-muted">{{ $review->created_at->format('M d, Y H:i') }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="{{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="width: 18px; height: 18px;"></i>
                    @endfor
                    <span class="badge bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }} ms-2">{{ ucfirst($review->status) }}</span>
                </div>
            </div>

            @if($review->review)
                <div class="bg-light p-3 rounded mb-3">
                    <p class="mb-0">{{ $review->review }}</p>
                </div>
            @endif

            <div class="d-flex gap-3 flex-wrap small text-muted mb-3">
                @if($review->booking)
                    <span><i data-lucide="calendar-check" style="width: 14px; height: 14px;"></i> Booking #{{ $review->booking_id }}</span>
                @endif
                @if($review->artist)
                    <span><i data-lucide="user" style="width: 14px; height: 14px;"></i> Artist: {{ $review->artist->user->name ?? 'N/A' }}</span>
                @endif
                @if($review->company)
                    <span><i data-lucide="building" style="width: 14px; height: 14px;"></i> {{ $review->company->name }}</span>
                @endif
            </div>

            @if(in_array(Auth::user()->role->role_key ?? '', ['master_admin', 'company_admin']))
                <hr>
                <form action="{{ route('reviews.update-status', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>
                <form action="{{ route('reviews.update-status', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </form>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm">
                <i data-lucide="arrow-left" style="width: 14px; height: 14px;" class="me-1"></i> Back to Reviews
            </a>
        </div>
    </div>
@endsection
