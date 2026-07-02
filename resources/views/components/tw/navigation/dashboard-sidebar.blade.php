@props([
    'mobile' => false,
])

@php
    $items = config('sidebar', []);
    $currentRoute = Route::currentRouteName();
    $linkBase = 'group flex items-center gap-3 rounded-field px-3 py-2 text-sm font-semibold transition';
    $linkIdle = 'text-slate-300 hover:bg-white/10 hover:text-white';
    $linkActive = 'bg-primary text-white shadow-soft';
@endphp

<aside {{ $attributes->merge(['class' => ($mobile ? 'h-full w-[var(--sd-sidebar-width)]' : 'hidden h-screen w-[var(--sd-sidebar-width)] shrink-0 lg:flex') . ' flex-col bg-sidebar text-white']) }}>
    <div class="flex h-[var(--sd-header-height)] items-center border-b border-white/10 px-5">
        <x-tw.app-logo variant="light" size="lg" :href="Route::has('dashboard') ? route('dashboard') : url('/')" />
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Dashboard navigation">
        <p class="px-3 pb-3 text-xs font-bold uppercase tracking-wide text-slate-500">Workspace</p>
        <ul class="space-y-1">
            @foreach ($items as $item)
                @php
                    $parentRoles = $item['roles'] ?? null;
                    $canSeeParent = is_null($parentRoles) || hasRole(...$parentRoles);
                    $visibleSubmenus = collect($item['submenu'] ?? [])->filter(function ($sub) {
                        return !isset($sub['roles']) || hasRole(...$sub['roles']);
                    });
                    $hasSubmenu = isset($item['submenu']);
                    $isParentActive = false;

                    if ($hasSubmenu) {
                        $isParentActive = $visibleSubmenus->contains(fn ($sub) => ($sub['route'] ?? null) === $currentRoute);
                    } elseif (isset($item['route'])) {
                        $isParentActive = $item['route'] === $currentRoute;
                    }
                @endphp

                @if (!$canSeeParent || ($hasSubmenu && $visibleSubmenus->isEmpty()))
                    @continue
                @endif

                @if ($hasSubmenu)
                    <li>
                        <details class="group" {{ $isParentActive ? 'open' : '' }}>
                            <summary class="{{ $linkBase }} {{ $isParentActive ? $linkActive : $linkIdle }} cursor-pointer list-none">
                                <i data-lucide="{{ $item['icon'] ?? 'circle' }}" class="size-4"></i>
                                <span class="min-w-0 flex-1 truncate">{{ $item['title'] }}</span>
                                <i data-lucide="chevron-down" class="size-4 transition group-open:rotate-180"></i>
                            </summary>
                            <ul class="mt-1 space-y-1 pl-7">
                                @foreach ($visibleSubmenus as $sub)
                                    @php
                                        $isSubActive = ($sub['route'] ?? null) === $currentRoute;
                                    @endphp
                                    <li>
                                        <a href="{{ route($sub['route']) }}" class="{{ $linkBase }} {{ $isSubActive ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                                            <span class="truncate">{{ $sub['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </li>
                @else
                    <li>
                        <a href="{{ route($item['route']) }}" class="{{ $linkBase }} {{ $isParentActive ? $linkActive : $linkIdle }}">
                            <i data-lucide="{{ $item['icon'] ?? 'circle' }}" class="size-4"></i>
                            <span class="truncate">{{ $item['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
