@extends('auth.layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-2" style="width: 72px; height: 72px;">
            <i data-lucide="key" style="font-size: 2rem; color: var(--auth-accent, #089df1);"></i>
        </div>
        <h5 class="mt-2 mb-1">Reset Password</h5>
        <p class="text-muted small mb-0">Enter your new password below</p>
    </div>

    <form class="validate_form" method="post" autocomplete="off" action="{{ route('user.reset-password') }}">
        @csrf

        {{-- Hidden fields to carry token and email --}}
        <input type="hidden" name="token" value="{{ request('token') }}">
        <input type="hidden" name="email" value="{{ request('email') }}">

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i data-lucide="lock" class="text-muted" style="width:18px;height:18px;"></i></span>
                <input type="password" class="form-control border-start-0 required" id="password" name="password"
                    placeholder="Enter new password" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i data-lucide="lock" class="text-muted" style="width:18px;height:18px;"></i></span>
                <input type="password" class="form-control border-start-0 required match" data-match="password" id="password_confirmation"
                    name="password_confirmation" placeholder="Re-enter new password" required>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold py-2">
                <i data-lucide="check-circle" class="me-1" style="width:18px;height:18px;"></i> Reset Password
            </button>
        </div>
    </form>

    <p class="text-muted text-center mt-4 mb-0">
        Remember Password?
        <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">
            Login Here
        </a>
    </p>
@endsection