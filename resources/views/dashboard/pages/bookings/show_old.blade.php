@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i data-lucide="home" style="width: 14px; height: 14px;"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('bookings.index') }}">Bookings</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('bookings.index') }}" class="btn btn-primary">Booking Requests</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">ID:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">#{{ $booking->id }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Customer:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->user->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Event Type:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->eventType->event_type ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Event Date:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->event_date }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Name:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Surname:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->surname }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Date of Birth:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->date_of_birth }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Phone:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->phone }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Email:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Address:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->address }}</p>
                        </div>
                    </div>

                    @if($booking->partner_name)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Partner Name:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->partner_name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($booking->wedding_date)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Wedding Date:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->wedding_date }}</p>
                        </div>
                    </div>
                    @endif

                    @if($booking->wedding_time)
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Wedding Time:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->wedding_time }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($booking->dos || $booking->donts || $booking->playlist_spotify || $booking->additional_notes)
            <hr class="my-4">
            <div class="row">
                @if($booking->dos)
                <div class="col-lg-6 mb-3">
                    <h6 class="mb-2">Do's:</h6>
                    <p class="text-muted">{{ $booking->dos }}</p>
                </div>
                @endif

                @if($booking->donts)
                <div class="col-lg-6 mb-3">
                    <h6 class="mb-2">Don'ts:</h6>
                    <p class="text-muted">{{ $booking->donts }}</p>
                </div>
                @endif

                @if($booking->playlist_spotify)
                <div class="col-lg-6 mb-3">
                    <h6 class="mb-2">Spotify Playlist:</h6>
                    <p class="text-muted">{{ $booking->playlist_spotify }}</p>
                </div>
                @endif

                @if($booking->additional_notes)
                <div class="col-lg-6 mb-3">
                    <h6 class="mb-2">Additional Notes:</h6>
                    <p class="text-muted">{{ $booking->additional_notes }}</p>
                </div>
                @endif
            </div>
            @endif

            <hr class="my-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Created:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="mb-1">Updated:</h6>
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted mb-0">{{ $booking->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-warning">
                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i> Edit Booking
                </a>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                    <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
