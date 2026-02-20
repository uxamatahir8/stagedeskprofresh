<!--===== MOBILE HEADER START =======-->
<div class="mobile-header d-block d-lg-none">
    <div class="container">
        <div class="mobile-header-elements">
            <div class="mobile-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="StageDesk Pro" style="width: 110px; height: auto;">
                </a>
            </div>
            <button type="button" class="mobile-nav-icon" aria-label="Toggle mobile navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<aside class="mobile-sidebar d-block d-lg-none" aria-label="Mobile navigation">
    <button type="button" class="menu-close" aria-label="Close mobile navigation">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <nav class="mobile-nav">
        <ul class="mobile-nav-list">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('home') }}#works">Works</a></li>
            <li><a href="{{ route('home') }}#features">Features</a></li>
            <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
            <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
            <li><a href="{{ route('home') }}#blog">Blog</a></li>
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Try Free Now</a></li>
            @else
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            @endguest
        </ul>
    </nav>
</aside>
<!--===== MOBILE HEADER END =======-->
