<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img style="height:69px;" src="{{ asset('images/stagedeskpro_logo.png') }}"
                    alt="logo"></span>
            <span class="logo-sm"><img style="height:69px;" src="{{ asset('images/stagedeskpro_logo.png') }}"
                    alt="small logo"></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img style="height:69px;" src="{{ asset('images/stagedeskpro_logo.png') }}"
                    alt="dark logo"></span>
            <span class="logo-sm">
                <img style="height:69px;" src="{{ asset('images/stagedeskpro_logo.png') }}" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <i class="ti ti-menu-4 fs-22 align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar>
        <!-- User -->
        <div class="sidenav-user">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="javascript:void(0);" class="link-reset">
                        <img src="{{ asset('images/users/user-3.jpg') }}" alt="user-image"
                            class="rounded-circle mb-2 avatar-md">
                        <span class="sidenav-user-name fw-bold">{{ Auth::user()->name }}</span>
                        <span class="fs-12 fw-semibold" data-lang="user-role">{{ Auth::user()->role->name }}</span>
                    </a>
                </div>
                <div>
                    <a class="dropdown-toggle drop-arrow-none link-reset sidenav-user-set-icon"
                        data-bs-toggle="dropdown" data-bs-offset="0,12" href="#!" aria-haspopup="false"
                        aria-expanded="false">
                        <i class="ti ti-settings fs-24 align-middle ms-1"></i>
                    </a>

                    <div class="dropdown-menu">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        <!-- My Profile -->
                        <a href="profile.html" class="dropdown-item">
                            <i class="ti ti-user-circle me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <!-- Notifications -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-bell-ringing me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Notifications</span>
                        </a>

                        <!-- Settings -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-settings-2 me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>

                        <!-- Support -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-headset me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Support Center</span>
                        </a>

                        <!-- Divider -->
                        <div class="dropdown-divider"></div>
                        <!-- Logout -->
                        <a href="{{ route('logout') }}" class="dropdown-item fw-semibold">
                            <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-title" data-lang="apps-title">Side Bar Menu</li>
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="earth"></i></span>
                    <span class="menu-text" data-lang="dashboard">Dashboard</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('packages') }}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="package-open"></i></span>
                    <span class="menu-text" data-lang="packages">Packages</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('companies') }}" class="side-nav-link">
                    <span class="menu-icon"><i data-lucide="building"></i></span>
                    <span class="menu-text" data-lang="companies">Companies</span>
                </a>
            </li>

            @if(hasRole('master_admin', 'company_admin'))
                <li class="side-nav-item">
                    <a href="{{ route('users') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text" data-lang="users">Users</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->
