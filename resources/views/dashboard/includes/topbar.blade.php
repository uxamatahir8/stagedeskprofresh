<!-- Topbar Start -->
<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="d-flex align-items-center gap-2">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="{{ route('dashboard') }}" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="small logo">
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="small logo">
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-default btn-icon">
                <i class="ti ti-menu-4 fs-22"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ti ti-menu-4 fs-22"></i>
            </button>

        </div> <!-- .d-flex-->

        <div class="d-flex align-items-center gap-2">

            <!-- Notification Dropdown -->
            <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
                        data-bs-offset="0,24" type="button" data-bs-auto-close="outside" aria-haspopup="false"
                        aria-expanded="false">
                        <i data-lucide="bell" class="fs-xxl"></i>
                        @if ($unreadNotificationCount > 0)
                            <span
                                class="badge text-bg-danger badge-circle topbar-badge">{{ $unreadNotificationCount }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                        <div class="px-3 py-2 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-md fw-semibold">Notifications</h6>
                                </div>
                                <div class="col text-end">
                                    <a href="javascript:void(0);" class="badge badge-soft-success badge-label py-1">01
                                        Notifications</a>
                                </div>
                            </div>
                        </div>

                        <div style="max-height: 300px;" data-simplebar>
                            @forelse ($topbarNotifications as $notification)
                                <!-- Notification Item -->
                                <div class="dropdown-item notification-item py-2 text-wrap {{ !$notification->is_read ? 'bg-light' : '' }}" id="notification-{{ $notification->id }}">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="flex-shrink-0 position-relative">
                                            <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                                <i class="ti ti-bell text-primary"></i>
                                            </div>
                                            @if(!$notification->is_read)
                                                <span class="position-absolute rounded-pill bg-danger notification-badge" style="width: 8px; height: 8px; bottom: 0; right: 0;"></span>
                                            @endif
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="fw-medium text-body d-block">{{ $notification->title }}</span>
                                            <span class="text-muted small">{{ Str::limit($notification->message, 40) }}</span><br>
                                            <span class="fs-xs text-muted"><i class="ti ti-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                        </span>
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="flex-shrink-0 text-muted btn btn-link p-0" title="Mark as read">
                                                    <i class="ti ti-check fs-md"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="dropdown-item py-3 text-center text-muted">
                                    <p class="mb-0 small">No notifications</p>
                                </div>
                            @endforelse
                        </div>


                        <!-- All Notifications Link-->
                        <a href="{{ route('notifications.index') }}"
                            class="dropdown-item text-center text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light py-2">
                            View All Notifications
                        </a>

                    </div> <!-- End dropdown-menu -->
                </div> <!-- end dropdown-->
            </div> <!-- end topbar item-->

            <!-- Theme Mode Dropdown -->
            <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i data-lucide="sun" class="fs-xxl"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end thememode-dropdown">

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="sun" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">Light</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="light">
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="moon" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">Dark</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="dark">
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="monitor-cog" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">System</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="system">
                            </label>
                        </li>

                    </ul> <!-- end dropdown-menu-->
                </div> <!-- end dropdown-->
            </div> <!-- end topbar item-->

            <!-- FullScreen -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" type="button" data-toggle="fullscreen">
                    <i data-lucide="maximize" class="fs-xxl fullscreen-off"></i>
                    <i data-lucide="minimize" class="fs-xxl fullscreen-on"></i>
                </button>
            </div>

            <!-- Light/Dark Mode Button -->
            <div class="topbar-item d-none">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                        data-bs-offset="0,19" href="#!" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ Auth::user()->profile?->profile_image == '' ? asset('images/users/user-4.jpg') : asset('storage/' . Auth::user()->profile->profile_image) }}"
                            width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                        <div class="d-lg-flex align-items-center gap-1 d-none">
                            <h5 class="my-0">
                                {{ Auth::user()->name }}
                            </h5>
                            <i class="ti ti-chevron-down align-middle"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back 👋!</h6>
                        </div>

                        <!-- My Profile -->
                        @php
                            $roleKey = Auth::user()->role->role_key;
                            $profileRoute = match($roleKey) {
                                'customer' => route('customer.profile'),
                                'artist', 'dj' => route('artist.profile'),
                                'affiliate' => route('affiliate.profile'),
                                default => route('settings')
                            };
                        @endphp
                        <a href="{{ $profileRoute }}" class="dropdown-item">
                            <i class="ti ti-user-circle me-1 fs-17 align-middle"></i>
                            <span class="align-middle">My Profile</span>
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('notifications.index') }}" class="dropdown-item">
                            <i class="ti ti-bell-ringing me-1 fs-17 align-middle"></i>
                            <span class="align-middle">Notifications</span>
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i class="ti ti-settings-2 me-1 fs-17 align-middle"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>

                        <!-- Support -->
                        <a href="{{ route('support.tickets') }}" class="dropdown-item">
                            <i class="ti ti-headset me-1 fs-17 align-middle"></i>
                            <span class="align-middle">Support Center</span>
                        </a>

                        <!-- Divider -->
                        <div class="dropdown-divider"></div>

                        <!-- Logout -->
                        <a href="{{ route('logout') }}" class="dropdown-item fw-semibold">
                            <i class="ti ti-logout-2 me-1 fs-17 align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
<!-- Topbar End -->
