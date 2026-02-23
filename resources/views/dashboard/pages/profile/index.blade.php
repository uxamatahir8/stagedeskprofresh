@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">View and manage your profile and security settings</p>
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

    @php
        $roleKey = $user->role->role_key ?? 'user';
    @endphp

    {{-- Role-based summary card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center flex-wrap gap-3">
                @if(!empty($user->profile?->profile_image))
                    <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="Profile" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                @else
                    <span class="avatar-title rounded-circle bg-primary d-inline-flex" style="width: 64px; height: 64px; font-size: 24px; line-height: 64px;">
                        {{ $user->initials }}
                    </span>
                @endif
                <div class="flex-grow-1">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-1">{{ $user->email }}</p>
                    <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $roleKey }}</span>
                </div>
                @if($roleKey === 'company_admin' && $user->company)
                    <div class="text-end">
                        <small class="text-muted d-block">Company</small>
                        <strong>{{ $user->company->name }}</strong>
                    </div>
                @endif
                @if($roleKey === 'master_admin')
                    <div class="text-end">
                        <small class="text-muted d-block">Role</small>
                        <strong>Platform Administrator</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
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
            </ul>

            <div class="tab-content" id="profileTabsContent">
                {{-- Personal Information --}}
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="user-circle" class="me-2"></i>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Profile Photo</label>
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($user->profile?->profile_image))
                                                <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <span class="avatar-title rounded-circle bg-light border d-inline-flex" style="width: 80px; height: 80px; font-size: 28px;">{{ $user->initials }}</span>
                                            @endif
                                            <input type="file" name="profile_image" class="form-control" accept="image/*" style="max-width: 260px;">
                                            <small class="text-muted">JPG, PNG or WebP. Max 2MB</small>
                                        </div>
                                    </div>
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
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->profile?->phone) }}" placeholder="+1 (555) 000-0000">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Zip / Postal Code</label>
                                        <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $user->profile?->zipcode) }}" placeholder="Zip Code">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Address</label>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Street address">{{ old('address', $user->profile?->address) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">About</label>
                                        <textarea name="about" class="form-control" rows="3" placeholder="A short bio or description">{{ old('about', $user->profile?->about) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-light ms-2">
                                    <i data-lucide="x" class="me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Password & Security --}}
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i data-lucide="lock" class="me-2"></i>Change Password</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0">
                                    <i data-lucide="info" class="me-2"></i>
                                    <strong>Password requirements:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Minimum 10 characters</li>
                                        <li>Include uppercase and lowercase letters</li>
                                        <li>Include at least one number</li>
                                        <li>Include at least one special character (@$!%*?&)</li>
                                    </ul>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Enter current password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                                <i data-lucide="eye" id="currentPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPassword" class="form-control" placeholder="Enter new password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                                <i data-lucide="eye" id="newPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" placeholder="Confirm new password" required>
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
                    </form>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0"><i data-lucide="shield-alert" class="me-2"></i>Account Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i data-lucide="mail" class="text-success"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Email</h6>
                                            <p class="text-muted small mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i data-lucide="calendar" class="text-info"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Member since</h6>
                                            <p class="text-muted small mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + 'Icon');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
        }
    </script>
@endsection
