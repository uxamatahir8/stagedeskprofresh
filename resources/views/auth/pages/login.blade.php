@extends('auth.layouts.auth')

@section('content')
    <div class="mb-3">
        <h5 class="mb-1">Sign in</h5>
        <p class="text-muted mb-0">Use password login or one-time email code.</p>
    </div>

    <!-- Login Method Tabs -->
    <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ (session('code_sent') || session('active_tab') === 'code-login') ? '' : 'active' }}" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-login"
                type="button" role="tab" aria-controls="password-login" aria-selected="{{ (session('code_sent') || session('active_tab') === 'code-login') ? 'false' : 'true' }}">
                <i data-lucide="lock" class="me-1" style="width:16px;height:16px;"></i> Password Login
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ (session('code_sent') || session('active_tab') === 'code-login') ? 'active' : '' }}" id="code-tab" data-bs-toggle="pill" data-bs-target="#code-login" type="button"
                role="tab" aria-controls="code-login" aria-selected="{{ (session('code_sent') || session('active_tab') === 'code-login') ? 'true' : 'false' }}">
                <i data-lucide="mail" class="me-1" style="width:16px;height:16px;"></i> Email Code Login
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loginTabContent">
        <!-- Password Login -->
        <div class="tab-pane fade {{ (session('code_sent') || session('active_tab') === 'code-login') ? '' : 'show active' }}" id="password-login" role="tabpanel" aria-labelledby="password-tab">
            <form method="post" autocomplete="off" action="{{ route('user_login') }}">
                @csrf
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-lucide="mail" class="text-muted" style="width:18px;height:18px;"></i></span>
                        <input type="email" class="form-control border-start-0" name="email"
                            autocomplete="off" id="userEmail" placeholder="you@example.com"
                            value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-lucide="lock" class="text-muted" style="width:18px;height:18px;"></i></span>
                        <input type="password" name="password" autocomplete="off"
                            class="form-control border-start-0"
                            id="userPassword" placeholder="••••••••" required>
                    </div>
                </div>

                @if(session('verification_sent'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i data-lucide="mail" class="me-2" style="width:18px;height:18px;"></i>
                        <strong>Verification Email Sent!</strong> A new verification link has been sent to your email address. Please check your inbox and spam folder.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('email_not_verified'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i data-lucide="info" class="me-2" style="width:18px;height:18px;"></i>
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
                        <i data-lucide="log-in" class="me-1" style="width:18px;height:18px;"></i> Sign In
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
                            <i data-lucide="mail" class="me-2" style="width:18px;height:18px;"></i>
                            <strong>Email Verification Required!</strong><br>
                            We've sent a verification link to your email. Please verify your email address before using code login.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info" role="alert">
                        <i data-lucide="info" class="me-2" style="width:18px;height:18px;"></i>
                        <strong>Secure Login:</strong> Enter your email to receive a 6-digit code for passwordless login.
                    </div>

                    <div class="mb-3">
                        <label for="codeEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-lucide="mail" class="text-muted" style="width:18px;height:18px;"></i></span>
                            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email"
                                id="codeEmail" placeholder="you@example.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                            <i data-lucide="send" class="me-1" style="width:18px;height:18px;"></i> Send Login Code
                        </button>
                    </div>
                </form>
            @else
                <!-- Verify Code Form -->
                <form method="post" action="{{ route('login-with-code') }}" id="verifyCodeForm">
                    @csrf
                    <div class="alert alert-success" role="alert">
                        <i data-lucide="check-circle" class="me-2" style="width:18px;height:18px;"></i>
                        <strong>Code Sent!</strong> Check your email for the 6-digit login code. It expires in 10 minutes.
                    </div>

                    <input type="hidden" name="email" value="{{ old('email') }}">

                    <div class="mb-3">
                        <label for="loginCode" class="form-label">Enter 6-Digit Code <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i data-lucide="key" class="text-muted" style="width:18px;height:18px;"></i></span>
                            <input type="text" class="form-control form-control-lg text-center border-start-0 @error('code') is-invalid @enderror"
                                name="code" id="loginCode" placeholder="000000" maxlength="6" pattern="[0-9]{6}"
                                autocomplete="off" required style="letter-spacing: 10px; font-size: 24px; font-weight: bold;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted d-block mt-1">Enter the 6-digit code sent to {{ old('email') }}</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success fw-semibold py-2">
                            <i data-lucide="check" class="me-1" style="width:18px;height:18px;"></i> Verify & Login
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i data-lucide="arrow-left" class="me-1" style="width:18px;height:18px;"></i> Request New Code
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <p class="text-muted text-center mt-4 mb-0">
        New here? <a href="{{ route('register') }}" class="text-decoration-underline link-offset-3 fw-semibold">Create an account</a>
    </p>

    @push('scripts')
        <script>
            const shouldForceCodeTab = "{{ (session('code_sent') || session('active_tab') === 'code-login') ? 'true' : 'false' }}" === "true";
            const shouldRestoreSavedTab = "{{ (!session('code_sent') && !session('active_tab')) ? 'true' : 'false' }}" === "true";

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
                if (shouldForceCodeTab) {
                    const codeTab = document.getElementById('code-tab');
                    if (codeTab) {
                        const tab = new bootstrap.Tab(codeTab);
                        tab.show();
                    }
                }

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
                if (shouldRestoreSavedTab) {
                    const savedTab = sessionStorage.getItem('activeLoginTab');
                    if (savedTab === '#code-login') {
                        const codeTab = document.getElementById('code-tab');
                        if (codeTab) {
                            const tab = new bootstrap.Tab(codeTab);
                            tab.show();
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
