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
            @if(hasRole('master_admin'))
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
                <li class="side-nav-item">
                    <a href="{{ route('testimonials') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="star"></i></span>
                        <span class="menu-text" data-lang="companies">Testimonials</span>
                    </a>
                </li>
            @endif

            @if(hasRole('master_admin', 'company_admin'))
                <li class="side-nav-item">
                    <a href="{{ route('users') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text" data-lang="users">Users</span>
                    </a>
                </li>
            @endif

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTickets" aria-expanded="false" aria-controls="sidebarTickets"
                    class="side-nav-link collapsed">
                    <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" data-lucide="life-buoy"
                            class="lucide lucide-life-buoy">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m4.93 4.93 4.24 4.24"></path>
                            <path d="m14.83 9.17 4.24-4.24"></path>
                            <path d="m14.83 14.83 4.24 4.24"></path>
                            <path d="m9.17 14.83-4.24 4.24"></path>
                            <circle cx="12" cy="12" r="4"></circle>
                        </svg></span>
                    <span class="menu-text" data-lang="support"> Blogs CMS</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTickets" style="">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('blog-categories') }}" class="side-nav-link">
                                <span class="menu-text" data-lang="tickets">Blog Categories</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('blogs.list') }}" class="side-nav-link">
                                <span class="menu-text" data-lang="ticket-details">Blogs</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ticket-create.html" class="side-nav-link">
                                <span class="menu-text" data-lang="ticket-create">Blog Comments</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @if(hasRole('master_admin'))
                <li class="side-nav-item">
                    <a href="{{ route('settings') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="cog"></i></span>
                        <span class="menu-text" data-lang="settings">Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->
