@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="calendar-check" class="me-2"></i>Booking Details
            </h4>
            <p class="text-muted mb-0 mt-1">Booking #{{ $booking->id }} - {{ $booking->eventType->name ?? 'Event' }}</p>
        </div>
        <div class="text-end">
            <a href="{{ route('artist.bookings') }}" class="btn btn-light btn-sm">
                <i data-lucide="arrow-left"></i> Back to My Bookings
            </a>
        </div>
    </div>

    {{-- DEBUG INFO (Remove after testing) --}}
    <div class="alert alert-info mb-3">
        <strong>Debug Info:</strong><br>
        Booking Status: <strong>{{ $booking->status }}</strong><br>
        Can Accept: <strong>{{ $canAccept ?? 'NOT SET' ? 'YES' : 'NO' }}</strong><br>
        Can Reject: <strong>{{ $canReject ?? 'NOT SET' ? 'YES' : 'NO' }}</strong><br>
        Can Complete: <strong>{{ $canComplete ?? 'NOT SET' ? 'YES' : 'NO' }}</strong><br>
        Is Completed: <strong>{{ $isCompleted ?? 'NOT SET' ? 'YES' : 'NO' }}</strong><br>
        Is Cancelled: <strong>{{ $isCancelled ?? 'NOT SET' ? 'YES' : 'NO' }}</strong>
    </div>

    {{-- Quick Action Buttons (Always Visible for Testing) --}}
    @if($booking->status === 'pending')
    <div class="alert alert-warning">
        <h5>Quick Actions (Always Visible - Status: Pending)</h5>
        <form action="{{ route('artist.bookings.accept', $booking->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Accept this booking?')">
                ✅ Accept Booking
            </button>
        </form>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            ❌ Reject Booking
        </button>
    </div>
    @endif

    @if($booking->status === 'confirmed')
    <div class="alert alert-success">
        <h5>Quick Actions (Always Visible - Status: Confirmed)</h5>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal">
            🚫 Cancel Commitment
        </button>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#completeModal">
            ✅ Mark as Completed
        </button>
    </div>
    @endif

    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Status Banner --}}
            @php
                $statusConfig = [
                    'pending' => ['color' => 'warning', 'icon' => 'clock', 'text' => 'Pending Your Response', 'bg' => 'bg-warning-subtle'],
                    'confirmed' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Confirmed - Ready to Perform', 'bg' => 'bg-success-subtle'],
                    'completed' => ['color' => 'info', 'icon' => 'check-check', 'text' => 'Event Completed', 'bg' => 'bg-info-subtle'],
                    'cancelled' => ['color' => 'danger', 'icon' => 'x-circle', 'text' => 'Booking Cancelled', 'bg' => 'bg-danger-subtle'],
                    'rejected' => ['color' => 'danger', 'icon' => 'x-octagon', 'text' => 'Booking Rejected', 'bg' => 'bg-danger-subtle']
                ];
                $currentStatus = $statusConfig[$booking->status] ?? $statusConfig['pending'];
            @endphp

            <div class="alert {{ $currentStatus['bg'] }} border-{{ $currentStatus['color'] }} d-flex align-items-center" role="alert">
                <i data-lucide="{{ $currentStatus['icon'] }}" class="text-{{ $currentStatus['color'] }} me-3" style="width: 32px; height: 32px;"></i>
                <div class="flex-grow-1">
                    <h5 class="mb-0 text-{{ $currentStatus['color'] }}">{{ $currentStatus['text'] }}</h5>
                    @if($booking->status === 'pending')
                        <small class="text-muted">Please review the booking details and accept or reject this booking request.</small>
                    @elseif($booking->status === 'confirmed')
                        <small class="text-muted">Event Date: {{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y h:i A') }}</small>
                    @endif
                </div>
                <span class="badge bg-{{ $currentStatus['color'] }} fs-6 px-3 py-2">{{ strtoupper($booking->status) }}</span>
            </div>
            {{-- Event Information --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="calendar" class="me-2"></i>Event Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="tag" class="text-primary me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Event Type</p>
                                    <h6 class="mb-0">{{ $booking->eventType->name ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="calendar-days" class="text-success me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Event Date & Time</p>
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->event_date)->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="map-pin" class="text-danger me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Venue Location</p>
                                    <h6 class="mb-0">{{ $booking->address ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="clock" class="text-info me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Duration</p>
                                    <h6 class="mb-0">{{ $booking->duration_hours ?? 'N/A' }} hours</h6>
                                </div>
                            </div>
                        </div>
                        @if($booking->dos || $booking->donts)
                        <div class="col-12">
                            <hr>
                            <h6 class="text-muted mb-3">Music Preferences</h6>
                            @if($booking->dos)
                            <div class="mb-2">
                                <strong class="text-success"><i data-lucide="check" class="me-1" style="width: 16px;"></i>Do's (Play These):</strong>
                                <p class="ms-4 mb-0">{{ $booking->dos }}</p>
                            </div>
                            @endif
                            @if($booking->donts)
                            <div>
                                <strong class="text-danger"><i data-lucide="x" class="me-1" style="width: 16px;"></i>Don'ts (Avoid These):</strong>
                                <p class="ms-4 mb-0">{{ $booking->donts }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($booking->playlist_spotify)
                        <div class="col-12">
                            <div class="alert alert-success mb-0">
                                <i data-lucide="music-2" class="me-2"></i>
                                <strong>Spotify Playlist:</strong>
                                <a href="{{ $booking->playlist_spotify }}" target="_blank" class="ms-2 btn btn-sm btn-success">
                                    <i class="bx bxl-spotify"></i> Open Playlist
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($booking->additional_notes)
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <strong><i data-lucide="info" class="me-2"></i>Additional Notes:</strong>
                                <p class="mb-0 mt-2">{{ $booking->additional_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Name</p>
                            <h6>{{ $booking->user->name ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Email</p>
                            <h6>{{ $booking->user->email ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Phone</p>
                            <h6>{{ $booking->contact_phone ?? $booking->user->phone ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Actions Card (Conditional based on booking status) --}}
            @if($canAccept)
            <div class="card border-0 shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="check-square" class="me-2"></i>Action Required
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">This booking is waiting for your response.</p>

                    <form action="{{ route('artist.bookings.accept', $booking->id) }}" method="POST" class="mb-2" onsubmit="return confirm('Are you sure you want to accept this booking?')">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i data-lucide="check-circle"></i> Accept Booking
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i data-lucide="x-circle"></i> Reject Booking
                    </button>

                    <hr class="my-3">
                    <small class="text-muted">
                        <i data-lucide="info" class="me-1" style="width: 14px;"></i>
                        Once accepted, the customer will be notified.
                    </small>
                </div>
            </div>
            @endif

            @if($canReject && !$canAccept)
            <div class="card border-0 shadow-sm mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i data-lucide="alert-triangle" class="me-2"></i>Emergency Cancel
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">If you can no longer perform at this event:</p>

                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i data-lucide="x-octagon"></i> Cancel Commitment
                    </button>

                    <hr class="my-3">
                    <small class="text-danger">
                        <i data-lucide="alert-circle" class="me-1" style="width: 14px;"></i>
                        This will unassign you and notify the company.
                    </small>
                </div>
            </div>
            @endif

            @if($canComplete)
            <div class="card border-0 shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i data-lucide="check-check" class="me-2"></i>Complete Event
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Mark this booking as completed after the event.</p>

                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i data-lucide="check-circle-2"></i> Mark as Completed
                    </button>
                </div>
            </div>
            @endif

            {{-- Status Display --}}
            @if(!$canAccept && !$canReject && !$canComplete)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <span class="badge bg-{{
                        $booking->status === 'confirmed' ? 'success' :
                        ($booking->status === 'pending' ? 'warning' :
                        ($booking->status === 'completed' ? 'info' : 'danger'))
                    }} fs-5 w-100 py-2">
                        {{ ucfirst($booking->status) }}
                    </span>
                    @if($isCompleted)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i data-lucide="check-circle" class="text-success me-1" style="width: 14px;"></i>
                            Event completed successfully!
                        </small>
                    </div>
                    @endif
                    @if($isCancelled)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i data-lucide="info" class="me-1" style="width: 14px;"></i>
                            This booking has been cancelled.
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('artist.bookings.reject', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i data-lucide="x-circle" class="me-2"></i>
                            {{ $booking->status === 'confirmed' ? 'Cancel Commitment' : 'Reject Booking' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i data-lucide="alert-triangle" class="me-2"></i>
                            <strong>Important:</strong> This booking will be returned to the company for reassignment.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason for {{ $booking->status === 'confirmed' ? 'Cancellation' : 'Rejection' }} <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="4" required minlength="10"
                                      placeholder="Please provide a detailed reason (minimum 10 characters)..."></textarea>
                            <small class="text-muted">The company and customer will see this reason.</small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <strong>What happens next:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Booking status returns to "Pending"</li>
                                <li>You will be unassigned from this booking</li>
                                <li>Company admin will be notified</li>
                                <li>Customer will be informed</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you absolutely sure?')">
                            <i data-lucide="x-octagon" class="me-1"></i>Confirm {{ $booking->status === 'confirmed' ? 'Cancellation' : 'Rejection' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Modal -->
    @if($canComplete)
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('artist.bookings.complete', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i data-lucide="check-check" class="me-2"></i>Mark as Completed
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i data-lucide="check-circle" class="me-2"></i>
                            <strong>Great!</strong> You're about to mark this event as completed.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Completion Notes (Optional)</label>
                            <textarea name="completion_notes" class="form-control" rows="4"
                                      placeholder="Add notes about the event, customer feedback, or special moments..."></textarea>
                            <small class="text-muted">These notes will be visible to the company admin.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">
                            <i data-lucide="check-circle-2" class="me-1"></i>Mark as Completed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @push('styles')
    <style>
        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }
        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }
        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }
        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
    @endpush
@endsection
