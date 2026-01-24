@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your profile information and security settings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i data-lucide="check-circle" class="me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i data-lucide="alert-circle" class="me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Profile Navigation Tabs -->
            <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal" type="button" role="tab">
                        <i data-lucide="user" class="me-1"></i>Personal Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                        <i data-lucide="shield" class="me-1"></i>Password & Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="pill" data-bs-target="#preferences" type="button" role="tab">
                        <i data-lucide="settings" class="me-1"></i>Preferences
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="user-circle" class="me-2"></i>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Address</label>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Street address">{{ old('address', $user->address) }}</textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">City</label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="City">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">State / Province</label>
                                        <input type="text" name="state" class="form-control" value="{{ old('state', $user->state) }}" placeholder="State">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Zip / Postal Code</label>
                                        <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $user->zip_code) }}" placeholder="Zip Code">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-light ms-2">
                                    <i data-lucide="x" class="me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password & Security Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="lock" class="me-2"></i>Change Password</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0">
                                    <i data-lucide="info" class="me-2"></i>
                                    <strong>Password Requirements:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Minimum 8 characters</li>
                                        <li>Include uppercase and lowercase letters</li>
                                        <li>Include at least one number</li>
                                        <li>Include at least one special character</li>
                                    </ul>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Enter current password">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                                <i data-lucide="eye" id="currentPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPassword" class="form-control" placeholder="Enter new password">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                                <i data-lucide="eye" id="newPasswordIcon"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2" id="passwordStrength"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" placeholder="Confirm new password">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                                <i data-lucide="eye" id="confirmPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="shield-check" class="me-1"></i>Update Password
                                </button>
                                <button type="reset" class="btn btn-light ms-2">
                                    <i data-lucide="rotate-ccw" class="me-1"></i>Reset
                                </button>
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="shield-alert" class="me-2"></i>Security Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                    <i data-lucide="check-circle" class="text-success"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Email Verified</h6>
                                                <p class="text-muted small mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                    <i data-lucide="calendar" class="text-info"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Account Created</h6>
                                                <p class="text-muted small mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-pane fade" id="preferences" role="tabpanel">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="bell" class="me-2"></i>Notification Preferences</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emailNotifications">
                                            <strong>Email Notifications</strong>
                                            <p class="text-muted small mb-0">Receive notifications about bookings and updates via email</p>
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" {{ old('sms_notifications', $user->sms_notifications ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="smsNotifications">
                                            <strong>SMS Notifications</strong>
                                            <p class="text-muted small mb-0">Get important updates via text message</p>
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="marketing_emails" id="marketingEmails" {{ old('marketing_emails', $user->marketing_emails ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="marketingEmails">
                                            <strong>Marketing Communications</strong>
                                            <p class="text-muted small mb-0">Receive promotional emails and special offers</p>
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="booking_reminders" id="bookingReminders" {{ old('booking_reminders', $user->booking_reminders ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bookingReminders">
                                            <strong>Booking Reminders</strong>
                                            <p class="text-muted small mb-0">Get reminders about upcoming bookings</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="me-1"></i>Save Preferences
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + 'Icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // Password strength indicator
        document.getElementById('newPassword')?.addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthDiv = document.getElementById('passwordStrength');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            const strengthText = ['Weak', 'Fair', 'Good', 'Strong'];
            const strengthColors = ['danger', 'warning', 'info', 'success'];

            if (password.length > 0) {
                strengthDiv.innerHTML = `<span class="badge bg-${strengthColors[strength-1]}">${strengthText[strength-1]}</span>`;
            } else {
                strengthDiv.innerHTML = '';
            }
        });
    </script>
@endsection
