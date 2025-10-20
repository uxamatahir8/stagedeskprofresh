@extends('auth.layouts.auth')

@section('content')
    <form class="validate_form" method="post" autocomplete="off" action="{{ route('user.reset-password') }}">
        @csrf

        {{-- Hidden fields to carry token and email --}}
        <input type="hidden" name="token" value="{{ request('token') }}">
        <input type="hidden" name="email" value="{{ request('email') }}">

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control reqiured" id="password" name="password"
                placeholder="Enter new password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control required match" data-match="password" id="password_confirmation"
                name="password_confirmation" placeholder="Re-enter new password" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold py-2">Reset Password</button>
        </div>
    </form>

    <p class="text-muted text-center mt-4 mb-0">
        Remember Password?
        <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">
            Login Here
        </a>
    </p>
@endsection