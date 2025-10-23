<!--=====HEADER START=======-->
<header>
    <div class="header-area homepage4 header header-sticky d-none d-lg-block " id="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav id="navbar-example2" class="navbar">
                        <div class="header-elements">
                            <div class="site-logo">
                                <a href="{{ route('home') }}">
                                    <img width="100" src="{{ asset('images/stagedeskpro_logo.png') }}" alt=""></a>
                                <div class="main-menu">
                                    <ul class="nav nav-pills">
                                        <li><a href="#">Home</a>
                                        </li>
                                        <li class="nav-item"><a href="{{ route('home') }}#works"
                                                class="nav-link active"><span>Works</span></a></li>
                                        <li class="nav-item"><a href="{{ route('home') }}#features"
                                                class="nav-link"><span>Features</span></a></li>
                                        <li class="nav-item"><a href="{{ route('home') }}#pricing"
                                                class="nav-link"><span>Pricing</span></a></li>
                                        <li class="nav-item"><a href="{{ route('home') }}#testimonials"
                                                class="nav-link"><span>Testimonials</span></a></li>
                                        <li class="nav-item"><a href="{{ route('home') }}#blog"
                                                class="nav-link"><span>Blog</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="btn-area">
                                @if(!Auth::user())
                                    <a href="{{ route('login') }}" class="header-btn6">Login</a>
                                    <a href="{{ route('register') }}" class="header-btn7">Try Free Now</a>
                                @else
                                    <div class="dropdown">
                                        <a href="#" class="header-btn7 d-flex align-items-center" id="accountDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-user"></i>
                                            <span class="ms-2">My Account</span>
                                            <span class="ms-2 me-1"><i class="fa fa-angle-down"></i></span>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                            <li><a class="dropdown-item" href="">Profile</a></li>
                                            <li><a class="dropdown-item" href="">Settings</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!--=====HEADER END =======-->
