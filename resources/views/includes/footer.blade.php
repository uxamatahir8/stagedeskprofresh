<footer class="modern-footer">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="StageDesk Pro" class="modern-footer-logo">
                <p class="modern-footer-text">B2B and B2P booking operations in one place: subscriptions for companies, bookings for customers, and artist collaboration flows.</p>
            </div>
            <div class="col-lg-7">
                <div class="modern-footer-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('home') }}#works">Flow</a>
                    <a href="{{ route('home') }}#features">Features</a>
                    <a href="{{ route('home') }}#pricing">Pricing</a>
                    <a href="{{ route('blogs') }}">Blog</a>
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
        <div class="modern-footer-bottom">
            <span>© {{ date('Y') }} StageDesk Pro</span>
            <span>Built for modern booking operations</span>
        </div>
    </div>
</footer>
