@extends('auth.layouts.auth')

@section('content')

    <!-- Login Method Tabs -->
    <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ (session('code_sent') || session('active_tab') === 'code-login') ? '' : 'active' }}" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-login"
                type="button" role="tab" aria-controls="password-login" aria-selected="{{ (session('code_sent') || session('active_tab') === 'code-login') ? 'false' : 'true' }}">
                <i class="bx bx-lock-alt me-1"></i> Password Login
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ (session('code_sent') || session('active_tab') === 'code-login') ? 'active' : '' }}" id="code-tab" data-bs-toggle="pill" data-bs-target="#code-login" type="button"
                role="tab" aria-controls="code-login" aria-selected="{{ (session('code_sent') || session('active_tab') === 'code-login') ? 'true' : 'false' }}">
                <i class="bx bx-envelope me-1"></i> Email Code Login
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loginTabContent">
        <!-- Password Login -->
        <div class="tab-pane fade {{ (session('code_sent') || session('active_tab') === 'code-login') ? '' : 'show active' }}" id="password-login" role="tabpanel" aria-labelledby="password-tab">
            <form method="post" class="validate_form" autocomplete="off" action="{{ route('user_login') }}">
                @csrf
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            autocomplete="off" id="userEmail" placeholder="you@example.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" autocomplete="off"
                            class="form-control @error('password') is-invalid @enderror"
                            id="userPassword" placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(session('verification_sent'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bx bx-envelope me-2"></i>
                        <strong>Verification Email Sent!</strong> A new verification link has been sent to your email address. Please check your inbox and spam folder.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('email_not_verified'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Email Not Verified!</strong> Please check your inbox for the verification link.
                        <form method="post" action="{{ route('resend-verification') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="email" value="{{ old('email') }}">
                            <button type="submit" class="btn btn-sm btn-link p-0 align-baseline">Resend verification email</button>
                        </form>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input form-check-input-light fs-14" type="checkbox" checked id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Keep me signed in</label>
                    </div>
                    <a href="{{ route('forgot-password') }}"
                        class="text-decoration-underline link-offset-3 text-muted">Forgot Password?</a>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                        <i class="bx bx-log-in me-1"></i> Sign In
                    </button>
                </div>
            </form>
        </div>

        <!-- Code Login -->
        <div class="tab-pane fade {{ (session('code_sent') || session('active_tab') === 'code-login') ? 'show active' : '' }}" id="code-login" role="tabpanel" aria-labelledby="code-tab">
            @if(!session('code_sent'))
                <!-- Request Code Form -->
                <form method="post" action="{{ route('send-login-code') }}" id="requestCodeForm">
                    @csrf

                    @if(session('verification_sent'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bx bx-envelope me-2"></i>
                            <strong>Email Verification Required!</strong><br>
                            We've sent a verification link to your email. Please verify your email address before using code login.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Secure Login:</strong> Enter your email to receive a 6-digit code for passwordless login.
                    </div>

                    <div class="mb-3">
                        <label for="codeEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                id="codeEmail" placeholder="you@example.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                            <i class="bx bx-send me-1"></i> Send Login Code
                        </button>
                    </div>
                </form>
            @else
                <!-- Verify Code Form -->
                <form method="post" action="{{ route('login-with-code') }}" id="verifyCodeForm">
                    @csrf
                    <div class="alert alert-success" role="alert">
                        <i class="bx bx-check-circle me-2"></i>
                        <strong>Code Sent!</strong> Check your email for the 6-digit login code. It expires in 10 minutes.
                    </div>

                    <input type="hidden" name="email" value="{{ old('email') }}">

                    <div class="mb-3">
                        <label for="loginCode" class="form-label">Enter 6-Digit Code <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg text-center @error('code') is-invalid @enderror"
                                name="code" id="loginCode" placeholder="000000" maxlength="6" pattern="[0-9]{6}"
                                autocomplete="off" required style="letter-spacing: 10px; font-size: 24px; font-weight: bold;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Enter the 6-digit code sent to {{ old('email') }}</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success fw-semibold py-2">
                            <i class="bx bx-check me-1"></i> Verify & Login
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Request New Code
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <p class="text-muted text-center mt-4 mb-0">
        New here? <a href="{{ route('register') }}"
            class="text-decoration-underline link-offset-3 fw-semibold">Create an account</a>
    </p>

    @push('scripts')
        <script>
            // Auto-focus code input and format
            document.addEventListener('DOMContentLoaded', function() {
                const codeInput = document.getElementById('loginCode');
                if (codeInput) {
                    codeInput.focus();

                    // Only allow numbers
                    codeInput.addEventListener('input', function(e) {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }

                // Activate code tab if code was sent or active_tab is set
                @if(session('code_sent') || session('active_tab') === 'code-login')
                    const codeTab = document.getElementById('code-tab');
                    if (codeTab) {
                        const tab = new bootstrap.Tab(codeTab);
                        tab.show();
                    }
                @endif

                // Store active tab in session on tab change
                const tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
                tabButtons.forEach(button => {
                    button.addEventListener('shown.bs.tab', function(e) {
                        const targetId = e.target.getAttribute('data-bs-target');
                        // Store in sessionStorage for client-side persistence
                        sessionStorage.setItem('activeLoginTab', targetId);
                    });
                });

                // Restore tab from sessionStorage on page load (if no server-side session)
                @if(!session('code_sent') && !session('active_tab'))
                    const savedTab = sessionStorage.getItem('activeLoginTab');
                    if (savedTab === '#code-login') {
                        const codeTab = document.getElementById('code-tab');
                        if (codeTab) {
                            const tab = new bootstrap.Tab(codeTab);
                            tab.show();
                        }
                    }
                @endif
            });
        </script>
    @endpush
@endsection
