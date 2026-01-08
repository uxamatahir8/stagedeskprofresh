@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('bookings.index') }}" class="btn btn-primary">Booking Requests</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode === 'edit' ? route('bookings.update', $booking) : route('bookings.store') }}"
                method="POST">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">

                    {{-- USER (Company Admin only) --}}
                    @if (auth()->user()->role->role_key === 'company_admin' || auth()->user()->role->role_key === 'master_admin')
                        <div class="col-lg-6 mb-2">
                            <label class="col-form-label">Customer</label>
                            <select name="user_id" class="form-control form-select required" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('user_id', $booking->user_id ?? '') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- EVENT TYPE --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Event Type</label>
                        <select name="event_type_id" class="form-control form-select required" required>
                            <option value="">Select Event Type</option>
                            @foreach ($event_types as $event_type)
                                <option value="{{ $event_type->id }}"
                                    {{ old('event_type_id', $booking->event_type_id ?? '') == $event_type->id ? 'selected' : '' }}>
                                    {{ $event_type->event_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NAME --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Name</label>
                        <input type="text" name="name" class="form-control required"
                            value="{{ old('name', $booking->name ?? '') }}" required>
                    </div>

                    {{-- SURNAME --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Surname</label>
                        <input type="text" name="surname" class="form-control required"
                            value="{{ old('surname', $booking->surname ?? '') }}" required>
                    </div>

                    {{-- DOB --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Date of Birth</label>
                        <input type="text" name="date_of_birth" class="form-control required" data-provider="flatpickr"
                                data-date-format="d M, Y"
                                data-maxDate="{{ now()->subDays(5)->format('d M, Y') }}"
                            value="{{ old('date_of_birth', $booking->date_of_birth ?? '') }}" required>
                    </div>

                    {{-- PHONE --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Phone</label>
                        <input type="text" name="phone" class="form-control required phone"
                            value="{{ old('phone', $booking->phone ?? '') }}" required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Email</label>
                        <input type="email" name="email" class="form-control required"
                            value="{{ old('email', $booking->email ?? '') }}" required>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-lg-12 mb-2">
                        <label class="col-form-label">Address</label>
                        <input type="text" name="address" class="form-control required"
                            value="{{ old('address', $booking->address ?? '') }}" required>
                    </div>

                    {{-- Event Date --}}
                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">Event Date</label>
                        <input type="text" name="event_date" class="form-control required"
                            value="{{ old('event_date', isset($booking) ? \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d\TH:i') : '') }}"
                            required>
                    </div>

                    {{-- START TIME --}}
                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">Start Time</label>
                        <input type="text" name="start_time" class="form-control required"
                            value="{{ old('start_time', isset($booking) ? \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i') : '') }}"
                            required>
                    </div>

                    {{-- END TIME --}}
                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">End Time</label>
                        <input type="text" name="end_time" class="form-control required"
                            value="{{ old('end_time', isset($booking) ? \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i') : '') }}"
                            required>
                    </div>

                    {{-- DOs --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Do's</label>
                        <textarea name="dos" class="form-control" rows="2">{{ old('dos', $booking->dos ?? '') }}</textarea>
                    </div>

                    {{-- DON'Ts --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Don'ts</label>
                        <textarea name="donts" class="form-control" rows="2">{{ old('donts', $booking->donts ?? '') }}</textarea>
                    </div>

                    {{-- PLAYLIST --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Spotify Playlist</label>
                        <input type="text" name="playlist_spotify" class="form-control"
                            value="{{ old('playlist_spotify', $booking->playlist_spotify ?? '') }}">
                    </div>

                    {{-- PARTNER NAME --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Partner Name</label>
                        <input type="text" name="partner_name" class="form-control"
                            value="{{ old('partner_name', $booking->partner_name ?? '') }}">
                    </div>

                    {{-- WEDDING DETAILS --}}
                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">Wedding Date</label>
                        <input type="text" name="wedding_date" class="form-control"
                            value="{{ old('wedding_date', $booking->wedding_date ?? '') }}">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">Wedding Time</label>
                        <input type="text" name="wedding_time" class="form-control"
                            value="{{ old('wedding_time', $booking->wedding_time ?? '') }}">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label">Wedding Location</label>
                        <input type="text" name="wedding_location" class="form-control"
                            value="{{ old('wedding_location', $booking->wedding_location ?? '') }}">
                    </div>

                    {{-- NOTES --}}
                    <div class="col-lg-12 mb-2">
                        <label class="col-form-label">Additional Notes</label>
                        <textarea name="additional_notes" class="form-control" rows="2">{{ old('additional_notes', $booking->additional_notes ?? '') }}</textarea>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-{{ $mode === 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode === 'edit' ? 'Update' : 'Save' }} Booking
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection
