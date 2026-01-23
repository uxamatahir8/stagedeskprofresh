@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="calendar" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">View and manage all your bookings</p>
        </div>
        <div class="text-end">
            <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm">
                <i data-lucide="plus"></i> New Booking
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-lucide="filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Type</th>
                            <th>Company</th>
                            <th>Event Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                <td>{{ $booking->company->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y h:i A') }}</td>
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
                                    <a href="{{ route('customer.bookings.details', $booking->id) }}" class="btn btn-sm btn-light">
                                        <i data-lucide="eye"></i> View
                                    </a>
                                    @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                            Cancel
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Cancel Modal -->
                            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cancel Booking #{{ $booking->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <i data-lucide="alert-triangle"></i>
                                                    Are you sure you want to cancel this booking?
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for cancellation <span class="text-danger">*</span></label>
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-lucide="calendar-x" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No bookings found</p>
                                    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i data-lucide="plus"></i> Create Your First Booking
                                    </a>
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
