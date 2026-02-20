<header class="modern-header d-none d-lg-block">
    <div class="container">
        <nav class="modern-nav">
            <a href="{{ route('home') }}" class="modern-brand">
                <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="StageDesk Pro">
            </a>

            <ul class="modern-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('home') }}#works">Flow</a></li>
                <li><a href="{{ route('home') }}#features">Features</a></li>
                <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                <li><a href="{{ route('home') }}#blog">Blog</a></li>
            </ul>

            <div class="modern-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm fw-semibold">Start Free</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm fw-semibold">Dashboard</a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
                @endguest
            </div>
        </nav>
    </div>
</header>
