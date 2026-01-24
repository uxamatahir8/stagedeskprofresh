@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $user->name }}
            </h4>
            <p class="text-muted mb-0 mt-1">User Profile & Activity</p>
        </div>
        <div class="text-end">
            <a href="{{ route('users') }}" class="btn btn-secondary btn-sm me-2">
                <i data-lucide="arrow-left" class="me-1"></i>Back to List
            </a>
            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-sm">
                <i data-lucide="edit" class="me-1"></i>Edit User
            </a>
        </div>
    </div>

    {{-- User Profile --}}
    <div class="row g-3 mb-4">
        {{-- Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if(isset($user->profile->profile_image) && $user->profile->profile_image)
                            <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="avatar avatar-xl mb-3 mx-auto">
                                <span class="avatar-title rounded-circle bg-primary fs-1">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-2">{{ $user->role->name ?? 'No Role' }}</p>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($user->status) }}</span>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="mail" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        @if(isset($user->profile->phone))
                            <div class="d-flex align-items-center mb-2">
                                <i data-lucide="phone" class="text-muted me-2" style="width: 16px;"></i>
                                <small class="text-muted">{{ $user->profile->phone }}</small>
                            </div>
                        @endif
                        @if($user->company)
                            <div class="d-flex align-items-center mb-2">
                                <i data-lucide="building-2" class="text-muted me-2" style="width: 16px;"></i>
                                <small class="text-muted">{{ $user->company->name }}</small>
                            </div>
                        @endif
                        @if(isset($user->profile->address))
                            <div class="d-flex align-items-center mb-2">
                                <i data-lucide="map-pin" class="text-muted me-2" style="width: 16px;"></i>
                                <small class="text-muted">{{ $user->profile->address }}</small>
                            </div>
                        @endif
                        <div class="d-flex align-items-center mb-2">
                            <i data-lucide="calendar" class="text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($user->last_login_at)
                            <div class="d-flex align-items-center">
                                <i data-lucide="clock" class="text-muted me-2" style="width: 16px;"></i>
                                <small class="text-muted">Last login {{ $user->last_login_at->diffForHumans() }}</small>
                            </div>
                        @endif
                    </div>

                    @if(isset($user->profile->about) && $user->profile->about)
                        <div class="border-top pt-3 mt-3">
                            <h6 class="mb-2">About</h6>
                            <p class="text-muted mb-0 small">{{ $user->profile->about }}</p>
                        </div>
                    @endif

                    {{-- Email Verification Status --}}
                    <div class="border-top pt-3 mt-3">
                        <h6 class="mb-2">Verification Status</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Email Verified:</span>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i data-lucide="check-circle" style="width: 14px;"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i data-lucide="alert-circle" style="width: 14px;"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats & Info --}}
        <div class="col-lg-8">
            @if($user->role->role_key === 'customer')
                {{-- Customer Stats --}}
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                                        <i data-lucide="calendar" class="text-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $stats['total_bookings'] ?? 0 }}</h3>
                                        <small class="text-muted">Total Bookings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                                        <i data-lucide="check-circle" class="text-success"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $stats['completed_bookings'] ?? 0 }}</h3>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-warning-subtle rounded me-3">
                                        <i data-lucide="clock" class="text-warning"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $stats['pending_bookings'] ?? 0 }}</h3>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-info-subtle rounded me-3">
                                        <i data-lucide="dollar-sign" class="text-info"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">${{ number_format($stats['total_spent'] ?? 0, 0) }}</h3>
                                        <small class="text-muted">Total Spent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($user->role->role_key === 'artist' && $user->artist)
                {{-- Artist Stats --}}
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                                        <i data-lucide="calendar-check" class="text-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $stats['total_bookings'] ?? 0 }}</h3>
                                        <small class="text-muted">Bookings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                                        <i data-lucide="check-circle" class="text-success"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ $stats['completed_bookings'] ?? 0 }}</h3>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-warning-subtle rounded me-3">
                                        <i data-lucide="star" class="text-warning"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</h3>
                                        <small class="text-muted">Rating</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-info-subtle rounded me-3">
                                        <i data-lucide="dollar-sign" class="text-info"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">${{ number_format($stats['total_earnings'] ?? 0, 0) }}</h3>
                                        <small class="text-muted">Earnings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Other Roles Info --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3"><i data-lucide="info" class="me-2"></i>User Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Role</label>
                                <p class="mb-0 fw-bold">{{ $user->role->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted mb-1">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                            </div>
                            @if($user->company)
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Company</label>
                                    <p class="mb-0 fw-bold">{{ $user->company->name }}</p>
                                </div>
                            @endif
                            @if($user->last_login_at)
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted mb-1">Last Login</label>
                                    <p class="mb-0">{{ $user->last_login_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($user->role->role_key === 'customer' || $user->role->role_key === 'artist')
                {{-- Recent Bookings --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="card-title mb-0"><i data-lucide="calendar" class="me-2"></i>Recent Bookings</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Event Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBookings ?? [] as $booking)
                                        <tr>
                                            <td><strong>#{{ $booking->id }}</strong></td>
                                            <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{
                                                    $booking->status === 'confirmed' ? 'success' :
                                                    ($booking->status === 'pending' ? 'warning' :
                                                    ($booking->status === 'completed' ? 'info' : 'danger'))
                                                }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-light">
                                                    <i data-lucide="eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <p class="text-muted mb-0">No bookings found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Activity Tab --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0"><i data-lucide="activity" class="me-2"></i>Recent Activity</h5>
        </div>
        <div class="card-body">
            <div class="timeline">
                <div class="timeline-item mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-sm bg-success-subtle rounded-circle">
                                <i data-lucide="user-check" class="text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Account Created</h6>
                            <p class="text-muted mb-1">User account was created</p>
                            <small class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>

                @if($user->email_verified_at)
                    <div class="timeline-item mb-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-sm bg-info-subtle rounded-circle">
                                    <i data-lucide="mail-check" class="text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Email Verified</h6>
                                <p class="text-muted mb-1">Email address was verified</p>
                                <small class="text-muted">{{ $user->email_verified_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                @endif

                @forelse($recentActivity ?? [] as $activity)
                    <div class="timeline-item mb-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-sm bg-primary-subtle rounded-circle">
                                    <i data-lucide="activity" class="text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $activity->action ?? 'Activity' }}</h6>
                                <p class="text-muted mb-1">{{ $activity->description ?? '' }}</p>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        lucide.createIcons();
    </script>
    @endpush
@endsection
