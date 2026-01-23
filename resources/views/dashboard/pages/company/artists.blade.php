@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="music" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your company artists and DJs</p>
        </div>
    </div>

    <!-- Artists Grid -->
    <div class="row g-3">
        @forelse($artists as $artist)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar-lg me-3">
                                @if($artist->profile_image)
                                    <img src="{{ Storage::url($artist->profile_image) }}" alt="{{ $artist->user->name }}" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <span class="avatar-title rounded-circle bg-primary fs-3">
                                        {{ substr($artist->user->name ?? 'A', 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $artist->user->name ?? 'N/A' }}</h5>
                                <p class="text-muted mb-2 fs-sm">{{ $artist->user->email ?? 'N/A' }}</p>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-warning me-1">⭐</span>
                                    <strong>{{ number_format($artist->rating ?? 0, 1) }}</strong>
                                    <span class="text-muted ms-1">({{ $artist->reviews_count ?? 0 }} reviews)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1 fs-sm">{{ Str::limit($artist->bio, 100) }}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fs-sm">Experience:</span>
                            <strong>{{ $artist->experience_years ?? 0 }} years</strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fs-sm">Hourly Rate:</span>
                            <strong>${{ number_format($artist->hourly_rate ?? 0, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fs-sm">Availability:</span>
                            <span class="badge bg-{{ $artist->availability === 'available' ? 'success' : ($artist->availability === 'busy' ? 'warning' : 'danger') }}">
                                {{ ucfirst($artist->availability ?? 'unavailable') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i data-lucide="music" class="mb-3" style="width: 64px; height: 64px;"></i>
                        <h5>No artists found</h5>
                        <p class="text-muted">You haven't added any artists yet</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($artists->hasPages())
    <div class="mt-4">
        {{ $artists->links() }}
    </div>
    @endif
@endsection
