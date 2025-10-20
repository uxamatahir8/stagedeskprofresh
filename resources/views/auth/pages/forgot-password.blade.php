@extends('auth.layouts.auth')


@section('content')
    <form method="post" autocomplete="off" action="{{ route('user.forgot-password') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary fw-semibold py-2">Send Recovery Email</button>
        </div>
    </form>

    <p class="text-muted text-center mt-4 mb-0">
        Remember Password? <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">Login
            Here</a>
    </p>
@endsection
