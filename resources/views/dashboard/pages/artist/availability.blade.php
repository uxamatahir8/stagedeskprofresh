@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="calendar" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your availability calendar</p>
        </div>
        <div class="text-end">
            <form action="{{ route('artist.availability.update') }}" method="POST" class="d-inline">
                @csrf
                <select name="availability" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="available" {{ ($artist->availability ?? '') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="busy" {{ ($artist->availability ?? '') === 'busy' ? 'selected' : '' }}>Busy</option>
                    <option value="unavailable" {{ ($artist->availability ?? '') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Current Status -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-1">Current Status</h5>
                    <p class="text-muted mb-0">Your availability status is visible to companies</p>
                </div>
                <span class="badge bg-{{ ($artist->availability ?? '') === 'available' ? 'success' : (($artist->availability ?? '') === 'busy' ? 'warning' : 'danger') }} fs-5">
                    {{ ucfirst($artist->availability ?? 'unavailable') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings Calendar -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Upcoming Bookings</h5>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Event</th>
                                <th>Customer</th>
                                <th>Duration</th>
                                <th>Location</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->event_date)->format('h:i A') }}</small>
                                    </td>
                                    <td>{{ $booking->eventType->event_type ?? 'N/A' }}</td>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->duration_hours ?? 0 }} hours</td>
                                    <td><small>{{ Str::limit($booking->venue_address ?? 'N/A', 30) }}</small></td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i data-lucide="calendar-x" class="mb-3" style="width: 64px; height: 64px;"></i>
                    <h5>No upcoming bookings</h5>
                    <p class="text-muted">Your calendar is clear</p>
                </div>
            @endif
        </div>
    </div>
@endsection
