@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="share-2" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Share artists with other companies</p>
        </div>
    </div>

    <!-- Pending Requests -->
    @if($pendingRequests->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning bg-soft border-bottom">
            <h5 class="card-title mb-0 text-warning">
                <i data-lucide="bell"></i> Pending Share Requests ({{ $pendingRequests->count() }})
            </h5>
        </div>
        <div class="card-body">
            @foreach($pendingRequests as $request)
            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                <div class="d-flex align-items-center">
                    <div class="avatar-lg me-3">
                        @if($request->artist->profile_image)
                            <img src="{{ Storage::url($request->artist->profile_image) }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span class="avatar-title rounded-circle bg-primary">
                                {{ substr($request->artist->stage_name ?? 'A', 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $request->artist->stage_name ?? 'N/A' }}</h6>
                        <small class="text-muted">
                            From: {{ $request->ownerCompany->name ?? 'N/A' }}<br>
                            @if($request->notes)
                            Note: {{ $request->notes }}
                            @endif
                        </small>
                    </div>
                </div>
                <div>
                    <form action="{{ route('company.artist-sharing.accept', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Accept</button>
                    </form>
                    <form action="{{ route('company.artist-sharing.reject', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="my-artists-tab" data-bs-toggle="tab" data-bs-target="#my-artists" type="button">
                My Artists
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shared-with-me-tab" data-bs-toggle="tab" data-bs-target="#shared-with-me" type="button">
                Shared With Me
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- My Artists Tab -->
        <div class="tab-pane fade show active" id="my-artists" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($myArtists as $artist)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="avatar-lg me-3">
                                                @if($artist->profile_image)
                                                    <img src="{{ Storage::url($artist->profile_image) }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="avatar-title rounded-circle bg-primary fs-3">
                                                        {{ substr($artist->stage_name ?? 'A', 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $artist->stage_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $artist->user->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>

                                        @if($artist->sharedWith->where('status', 'accepted')->count() > 0)
                                        <div class="mb-3">
                                            <small class="text-muted">Shared with:</small>
                                            @foreach($artist->sharedWith->where('status', 'accepted') as $share)
                                                <span class="badge bg-success badge-sm d-block mb-1">
                                                    {{ $share->sharedWithCompany->name ?? 'N/A' }}
                                                    <form action="{{ route('company.artist-sharing.revoke', $share->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-link text-white p-0" title="Revoke">×</button>
                                                    </form>
                                                </span>
                                            @endforeach
                                        </div>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#shareModal{{ $artist->id }}">
                                            <i data-lucide="share-2"></i> Share Artist
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Share Modal -->
                            <div class="modal fade" id="shareModal{{ $artist->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('company.artist-sharing.share') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="artist_id" value="{{ $artist->id }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Share {{ $artist->stage_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Company <span class="text-danger">*</span></label>
                                                    <select name="company_id" class="form-select" required>
                                                        <option value="">Choose company...</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notes (Optional)</label>
                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Add any notes..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Send Share Request</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center text-muted py-5">
                                    <i data-lucide="users" style="width: 64px; height: 64px;"></i>
                                    <p>No artists to share</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if($myArtists->hasPages())
                <div class="card-footer bg-transparent border-top">
                    {{ $myArtists->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Shared With Me Tab -->
        <div class="tab-pane fade" id="shared-with-me" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($sharedWithMe as $share)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="avatar-lg me-3">
                                                @if($share->artist->profile_image)
                                                    <img src="{{ Storage::url($share->artist->profile_image) }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="avatar-title rounded-circle bg-info fs-3">
                                                        {{ substr($share->artist->stage_name ?? 'A', 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $share->artist->stage_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">From: {{ $share->ownerCompany->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <small class="text-muted">Experience: {{ $share->artist->experience_years ?? 0 }} years</small><br>
                                            <small class="text-muted">Rating: ⭐ {{ number_format($share->artist->rating ?? 0, 1) }}</small>
                                        </div>

                                        @if($share->notes)
                                        <div class="alert alert-info alert-sm">
                                            <small>{{ $share->notes }}</small>
                                        </div>
                                        @endif

                                        <span class="badge bg-success w-100">
                                            <i data-lucide="check"></i> Available for Booking
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center text-muted py-5">
                                    <i data-lucide="inbox" style="width: 64px; height: 64px;"></i>
                                    <p>No artists shared with you</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if($sharedWithMe->hasPages())
                <div class="card-footer bg-transparent border-top">
                    {{ $sharedWithMe->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
