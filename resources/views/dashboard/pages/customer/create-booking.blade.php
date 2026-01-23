@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="plus-circle" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Create a new booking request</p>
        </div>
        <div class="text-end">
            <a href="{{ route('customer.bookings') }}" class="btn btn-light btn-sm">
                <i data-lucide="arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <!-- Event Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                <select name="event_type_id" class="form-select" required>
                                    <option value="">Select Event Type</option>
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('event_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_type_id')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-select" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('event_date')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Event Time <span class="text-danger">*</span></label>
                                <input type="time" name="event_time" class="form-control" value="{{ old('event_time') }}" required>
                                @error('event_time')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Venue Address <span class="text-danger">*</span></label>
                                <textarea name="venue_address" class="form-control" rows="2" required>{{ old('venue_address') }}</textarea>
                                @error('venue_address')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Number of Guests</label>
                                <input type="number" name="number_of_guests" class="form-control" value="{{ old('number_of_guests') }}" min="1">
                                @error('number_of_guests')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration (hours) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_hours" class="form-control" value="{{ old('duration_hours', 4) }}" min="1" step="0.5" required>
                                @error('duration_hours')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Special Requests</label>
                                <textarea name="special_requests" class="form-control" rows="3" placeholder="Any special requirements or preferences...">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', Auth::user()->name) }}" required>
                                @error('contact_name')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_phone" class="form-control" value="{{ old('contact_phone', Auth::user()->phone) }}" required>
                                @error('contact_phone')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', Auth::user()->email) }}" required>
                                @error('contact_email')
                                    <div class="text-danger fs-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i data-lucide="send"></i> Submit Booking Request
                        </button>
                        <a href="{{ route('customer.bookings') }}" class="btn btn-light btn-lg ms-2">
                            <i data-lucide="x"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
