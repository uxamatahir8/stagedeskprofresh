@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your profile information</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Personal Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state', $user->state) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Zip Code</label>
                                <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $user->zip_code) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i data-lucide="info"></i> Leave blank if you don't want to change your password
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Preferences</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="emailNotifications">
                                Receive email notifications
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" {{ old('sms_notifications', $user->sms_notifications ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="smsNotifications">
                                Receive SMS notifications
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="marketing_emails" id="marketingEmails" {{ old('marketing_emails', $user->marketing_emails ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="marketingEmails">
                                Receive marketing emails and offers
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i> Save Changes
                        </button>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-light ms-2">
                            <i data-lucide="x"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
