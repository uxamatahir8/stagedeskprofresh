@extends('auth.layouts.auth')

@section('content')

    <!-- Alert for must change password notification -->
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i data-lucide="info" class="me-2" style="width:18px;height:18px;"></i>
            <strong>Action Required!</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Display -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i data-lucide="alert-circle" class="me-2" style="width:18px;height:18px;"></i>
            <strong>Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-2" style="width: 72px; height: 72px;">
            <i data-lucide="shield-check" style="font-size: 2rem; color: var(--auth-accent, #089df1);"></i>
        </div>
        <h4 class="fw-bold mb-2">Change Your Password</h4>
        <p class="text-muted small mb-0">For security reasons, you must change your temporary password before continuing.</p>
    </div>

    <form method="post" action="{{ route('update-password') }}" class="validate_form" autocomplete="off">
                @csrf

                <div class="mb-4">
                    <label for="current_password" class="form-label">Current (Temporary) Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-lucide="lock" class="text-muted" style="width:18px;height:18px;"></i></span>
                        <input type="password"
                               class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                               name="current_password"
                               id="current_password"
                               placeholder="Enter your temporary password"
                               required
                               autocomplete="off">
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleCurrentPassword">
                            <i data-lucide="eye" style="width:18px;height:18px;"></i>
                        </button>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">Enter the temporary password sent to your email</small>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-lucide="key" class="text-muted" style="width:18px;height:18px;"></i></span>
                        <input type="password"
                               class="form-control border-start-0 @error('new_password') is-invalid @enderror"
                               name="new_password"
                               id="new_password"
                               placeholder="Create a strong password"
                               required
                               autocomplete="off">
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleNewPassword">
                            <i data-lucide="eye" style="width:18px;height:18px;"></i>
                        </button>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-lucide="shield-check" class="text-muted" style="width:18px;height:18px;"></i></span>
                        <input type="password"
                               class="form-control border-start-0"
                               name="new_password_confirmation"
                               id="new_password_confirmation"
                               placeholder="Re-enter your new password"
                               required
                               autocomplete="off">
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleConfirmPassword">
                            <i data-lucide="eye" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="alert alert-light border mb-4">
                    <h6 class="alert-heading mb-2"><i data-lucide="info" class="me-1" style="width:16px;height:16px;"></i> Password Requirements:</h6>
                    <ul class="mb-0 small" id="passwordRequirements">
                        <li id="req-length" class="text-muted">
                            <i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> At least 10 characters
                        </li>
                        <li id="req-uppercase" class="text-muted">
                            <i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One uppercase letter (A-Z)
                        </li>
                        <li id="req-lowercase" class="text-muted">
                            <i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One lowercase letter (a-z)
                        </li>
                        <li id="req-number" class="text-muted">
                            <i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One number (0-9)
                        </li>
                        <li id="req-special" class="text-muted">
                            <i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One special character (@$!%*?&)
                        </li>
                    </ul>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold py-2">
                        <i data-lucide="check-circle" class="me-1" style="width:18px;height:18px;"></i> Change Password & Continue
                    </button>
                </div>
            </form>

    <div class="text-center mt-4">
        <p class="text-muted small mb-0">
            <i data-lucide="shield-check" class="me-1" style="width:16px;height:16px;"></i>
            Your password will be encrypted and stored securely
        </p>
    </div>

    @push('scripts')
    <script>
        // Toggle password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
            const input = document.getElementById('current_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            const input = document.getElementById('new_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const input = document.getElementById('new_password_confirmation');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
        });

        // Real-time password validation
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;

            // Length check
            const lengthReq = document.getElementById('req-length');
            if (password.length >= 10) {
                lengthReq.innerHTML = '<i data-lucide="check-circle" class="text-success" style="width:14px;height:14px;"></i> At least 10 characters';
                lengthReq.classList.remove('text-muted');
                lengthReq.classList.add('text-success');
            } else {
                lengthReq.innerHTML = '<i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> At least 10 characters';
                lengthReq.classList.remove('text-success');
                lengthReq.classList.add('text-muted');
            }

            // Uppercase check
            const uppercaseReq = document.getElementById('req-uppercase');
            if (/[A-Z]/.test(password)) {
                uppercaseReq.innerHTML = '<i data-lucide="check-circle" class="text-success" style="width:14px;height:14px;"></i> One uppercase letter (A-Z)';
                uppercaseReq.classList.remove('text-muted');
                uppercaseReq.classList.add('text-success');
            } else {
                uppercaseReq.innerHTML = '<i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One uppercase letter (A-Z)';
                uppercaseReq.classList.remove('text-success');
                uppercaseReq.classList.add('text-muted');
            }

            // Lowercase check
            const lowercaseReq = document.getElementById('req-lowercase');
            if (/[a-z]/.test(password)) {
                lowercaseReq.innerHTML = '<i data-lucide="check-circle" class="text-success" style="width:14px;height:14px;"></i> One lowercase letter (a-z)';
                lowercaseReq.classList.remove('text-muted');
                lowercaseReq.classList.add('text-success');
            } else {
                lowercaseReq.innerHTML = '<i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One lowercase letter (a-z)';
                lowercaseReq.classList.remove('text-success');
                lowercaseReq.classList.add('text-muted');
            }

            // Number check
            const numberReq = document.getElementById('req-number');
            if (/[0-9]/.test(password)) {
                numberReq.innerHTML = '<i data-lucide="check-circle" class="text-success" style="width:14px;height:14px;"></i> One number (0-9)';
                numberReq.classList.remove('text-muted');
                numberReq.classList.add('text-success');
            } else {
                numberReq.innerHTML = '<i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One number (0-9)';
                numberReq.classList.remove('text-success');
                numberReq.classList.add('text-muted');
            }

            // Special character check
            const specialReq = document.getElementById('req-special');
            if (/[@$!%*?&]/.test(password)) {
                specialReq.innerHTML = '<i data-lucide="check-circle" class="text-success" style="width:14px;height:14px;"></i> One special character (@$!%*?&)';
                specialReq.classList.remove('text-muted');
                specialReq.classList.add('text-success');
            } else {
                specialReq.innerHTML = '<i data-lucide="x-circle" class="text-danger" style="width:14px;height:14px;"></i> One special character (@$!%*?&)';
                specialReq.classList.remove('text-success');
                specialReq.classList.add('text-muted');
            }
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
        });
    </script>
    @endpush
@endsection
