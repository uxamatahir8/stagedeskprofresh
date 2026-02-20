@extends('auth.layouts.auth')


@section('content')
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-2" style="width: 72px; height: 72px;">
            <i data-lucide="unlock" style="font-size: 2rem; color: var(--auth-accent, #089df1);"></i>
        </div>
        <h5 class="mt-2 mb-1">Forgot Password?</h5>
        <p class="text-muted small mb-0">Enter your email to receive a reset link</p>
    </div>

    <form method="post" autocomplete="off" action="{{ route('user.forgot-password') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i data-lucide="mail" class="text-muted" style="width:18px;height:18px;"></i></span>
                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold py-2">
                <i data-lucide="send" class="me-1" style="width:18px;height:18px;"></i> Send Recovery Email
            </button>
        </div>
    </form>

    <p class="text-muted text-center mt-4 mb-0">
        Remember Password? <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">Login
            Here</a>
    </p>
@endsection
