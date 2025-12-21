@extends('auth.layouts.auth')

@section('content')

    <form method="post" class="validate_form" autocomplete="off" action="{{ route('user_login') }}">
        @csrf
        <div class="mb-3">
            <label for="userEmail" class="form-label">Email address <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="email" class="form-control" name="email" autocomplete="off" id="userEmail"
                    placeholder="you@example.com" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="password" name="password" autocomplete="off" class="form-control" id="userPassword"
                    placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input form-check-input-light fs-14" type="checkbox" checked id="rememberMe">
                <label class="form-check-label" for="rememberMe">Keep me signed in</label>
            </div>
            <a href="{{ route('forgot-password') }}" class="text-decoration-underline link-offset-3 text-muted">Forgot
                Password?</a>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold py-2">Sign In</button>
        </div>
    </form>

    <p class="text-muted text-center mt-4 mb-0">
        New here? <a href="{{ route('register') }}" class="text-decoration-underline link-offset-3 fw-semibold">Create an
            account</a>
    </p>
@endsection
