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
                    {{-- Event Date --}}
                    <div class="col-lg-4 mb-2">
                        <label class="col-form-label required">Event Date <span class="text-danger">*</span></label>
                        <input type="text" name="event_date" class="form-control required" data-provider="flatpickr"
                            data-date-format="d M, Y" data-minDate="{{ now()->addDays(1)->format('d M, Y') }}"
                            placeholder="Select event date"
                            value="{{ old('event_date', isset($booking) ? \Carbon\Carbon::parse($booking->event_date)->format('d M, Y') : '') }}">
                    </div>
                </div>

                <div class="row">
                    {{-- USER (Company Admin only) --}}
                    @if (auth()->user()->role->role_key === 'company_admin' || auth()->user()->role->role_key === 'master_admin')
                        <div class="col-lg-6 mb-2">
                            <label class="col-form-label required">Customer <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control form-select required">
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
                        <label class="col-form-label required">Event Type <span class="text-danger">*</span></label>
                        <select name="event_type_id" class="form-control form-select required">
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
                        <label class="col-form-label required">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control required" placeholder="Enter first name"
                            value="{{ old('name', $booking->name ?? '') }}">
                    </div>

                    {{-- SURNAME --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label required">Surname <span class="text-danger">*</span></label>
                        <input type="text" name="surname" class="form-control required" placeholder="Enter surname"
                            value="{{ old('surname', $booking->surname ?? '') }}">
                    </div>

                    {{-- WEDDING FIELDS --}}
                    <div style="display: none; gap: 10px;" id="wedding_fields">
                        <div class="col-lg-4 mb-2">
                            <label class="col-form-label wedding-label">Partner Name</label>
                            <input type="text" name="partner_name" class="form-control wedding-field"
                                placeholder="Enter partner name"
                                value="{{ old('partner_name', $booking->partner_name ?? '') }}">
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label class="col-form-label wedding-label">Wedding Date</label>
                            <input type="text" name="wedding_date" class="form-control wedding-field"
                                data-provider="flatpickr" data-date-format="d M, Y"
                                data-minDate="{{ now()->addDays(1)->format('d M, Y') }}" placeholder="Select wedding date"
                                value="{{ old('wedding_date', $booking->wedding_date ?? '') }}">
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label class="col-form-label wedding-label">Wedding Time</label>
                            <input type="text" name="wedding_time" class="form-control wedding-field"
                                data-provider="timepickr" data-time-basic="true" placeholder="Select wedding time"
                                value="{{ old('wedding_time', $booking->wedding_time ?? '') }}">
                        </div>
                    </div>

                    {{-- DOB --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label required">Date of Birth <span class="text-danger">*</span></label>
                        <input type="text" name="date_of_birth" class="form-control required" data-provider="flatpickr"
                            data-date-format="d M, Y" data-maxDate="{{ now()->subDays(5)->format('d M, Y') }}"
                            placeholder="Select date of birth"
                            value="{{ old('date_of_birth', $booking->date_of_birth ?? '') }}">
                    </div>

                    {{-- PHONE --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label required">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control required phone"
                            placeholder="Enter phone number" value="{{ old('phone', $booking->phone ?? '') }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label required">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control required" placeholder="Enter email"
                            value="{{ old('email', $booking->email ?? '') }}">
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label required">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control required" placeholder="Enter address"
                            value="{{ old('address', $booking->address ?? '') }}">
                    </div>

                    {{-- DOs --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Do's</label>
                        <textarea name="dos" class="form-control" rows="3" placeholder="Enter Do's">{{ old('dos', $booking->dos ?? '') }}</textarea>
                    </div>

                    {{-- DON'Ts --}}
                    <div class="col-lg-6 mb-2">
                        <label class="col-form-label">Don'ts</label>
                        <textarea name="donts" class="form-control" rows="3" placeholder="Enter Don'ts">{{ old('donts', $booking->donts ?? '') }}</textarea>
                    </div>

                    {{-- PLAYLIST --}}
                    <div class="col-lg-12 mb-2">
                        <label class="col-form-label">Spotify Playlist</label>
                        <textarea name="playlist_spotify" class="form-control" rows="2" placeholder="Enter Spotify playlist">{{ old('playlist_spotify', $booking->playlist_spotify ?? '') }}</textarea>
                    </div>

                    {{-- NOTES --}}
                    <div class="col-lg-12 mb-2">
                        <label class="col-form-label">Additional Notes</label>
                        <textarea name="additional_notes" class="form-control" rows="3" placeholder="Enter additional notes">{{ old('additional_notes', $booking->additional_notes ?? '') }}</textarea>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-{{ $mode === 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode === 'edit' ? 'Update' : 'Save' }} Booking
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventTypeSelect = document.querySelector('select[name="event_type_id"]');
            const weddingFields = document.getElementById('wedding_fields');
            const weddingInputs = weddingFields.querySelectorAll('.wedding-field');
            const weddingLabels = weddingFields.querySelectorAll('.wedding-label');

            function toggleWeddingFields() {
                // Get the selected option's text and convert to lowercase
                const selectedText = eventTypeSelect.options[eventTypeSelect.selectedIndex]?.text.toLowerCase() ||
                    '';
                const isWedding = selectedText.includes('wedding');

                if (isWedding) {
                    weddingFields.style.display = 'flex';
                    weddingInputs.forEach(input => input.classList.add('required'));
                    weddingLabels.forEach(label => {
                        if (!label.querySelector('.text-danger')) {
                            label.innerHTML += ' <span class="text-danger">*</span>';
                        }
                    });
                } else {
                    weddingFields.style.display = 'none';
                    weddingInputs.forEach(input => input.classList.remove('required'));
                    weddingLabels.forEach(label => {
                        const span = label.querySelector('.text-danger');
                        if (span) span.remove();
                    });
                }
            }

            // Run on page load
            toggleWeddingFields();

            // Run when user changes event type
            eventTypeSelect.addEventListener('change', toggleWeddingFields);
        });
    </script>

@endsection
