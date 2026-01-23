@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="calendar" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage company bookings</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('company.bookings') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Bookings List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Event Type</th>
                            <th>Event Date</th>
                            <th>Artist</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 fs-sm">{{ $booking->user->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                                    </div>
                                </td>
                                <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</td>
                                <td>
                                    @if($booking->assignedArtist)
                                        <span class="badge bg-info">{{ $booking->assignedArtist->user->name }}</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignArtistModal{{ $booking->id }}">
                                            Assign Artist
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{
                                        $booking->status === 'confirmed' ? 'success' :
                                        ($booking->status === 'pending' ? 'warning' :
                                        ($booking->status === 'completed' ? 'info' : 'danger'))
                                    }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-light">
                                        <i data-lucide="eye"></i> View
                                    </a>
                                </td>
                            </tr>

                            <!-- Assign Artist Modal -->
                            @if(!$booking->assignedArtist)
                            <div class="modal fade" id="assignArtistModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('company.bookings.assign-artist', $booking->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Assign Artist to Booking #{{ $booking->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Artist</label>
                                                    <select name="artist_id" class="form-select" required>
                                                        <option value="">Choose an artist...</option>
                                                        @foreach($availableArtists ?? [] as $artist)
                                                            <option value="{{ $artist->id }}">
                                                                {{ $artist->user->name }} - {{ $artist->availability }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Assign Artist</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i data-lucide="calendar-x" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No bookings found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
@endsection
