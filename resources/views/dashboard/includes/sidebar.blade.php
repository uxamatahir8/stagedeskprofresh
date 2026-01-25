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
        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-title" data-lang="apps-title">Side Bar Menu</li>

            @foreach (config('sidebar') as $item)
                @php
                    // Parent role check
                    $parentRoles = $item['roles'] ?? null;
                    $canSeeParent = is_null($parentRoles) || hasRole(...$parentRoles);

                    // Filter visible submenus
                    $visibleSubmenus = collect($item['submenu'] ?? [])->filter(function ($sub) {
                        return !isset($sub['roles']) || hasRole(...$sub['roles']);
                    });
                @endphp

                {{-- Skip parent if role restricted --}}
                @if (!$canSeeParent)
                    @continue
                @endif

                {{-- Skip submenu parent if no visible children --}}
                @if (isset($item['submenu']) && $visibleSubmenus->isEmpty())
                    @continue
                @endif

                {{-- MENU WITH SUBMENU --}}
                @if (isset($item['submenu']))
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#menu{{ Str::slug($item['title']) }}"
                            class="side-nav-link collapsed">
                            <span class="menu-icon">
                                <i data-lucide="{{ $item['icon'] ?? 'circle' }}"></i>
                            </span>
                            <span class="menu-text">{{ $item['title'] }}</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <div class="collapse" id="menu{{ Str::slug($item['title']) }}">
                            <ul class="sub-menu">
                                @foreach ($visibleSubmenus as $sub)
                                    <li class="side-nav-item">
                                        <a href="{{ route($sub['route']) }}" class="side-nav-link">
                                            <span class="menu-text">{{ $sub['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>

                    {{-- SINGLE MENU --}}
                @else
                    <li class="side-nav-item">
                        <a href="{{ route($item['route']) }}" class="side-nav-link">
                            <span class="menu-icon">
                                <i data-lucide="{{ $item['icon'] ?? 'circle' }}"></i>
                            </span>
                            <span class="menu-text">{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>

    </div>
</div>
<!-- Sidenav Menu End -->
