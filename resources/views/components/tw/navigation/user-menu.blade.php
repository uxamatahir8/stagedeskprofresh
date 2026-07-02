@php
    $user = Auth::user();
    $roleKey = $user?->role?->role_key ?? '';
    $profileRoute = match ($roleKey) {
        'customer' => Route::has('customer.profile') ? route('customer.profile') : '#',
        'artist' => Route::has('artist.profile') ? route('artist.profile') : '#',
        'affiliate' => Route::has('affiliate.profile') ? route('affiliate.profile') : '#',
        default => Route::has('profile') ? route('profile') : '#',
    };
@endphp

<div class="relative" data-tw-dropdown>
    <button type="button" class="inline-flex items-center gap-3 rounded-field px-2 py-1.5 hover:bg-surface-muted" data-tw-dropdown-toggle aria-haspopup="true" aria-expanded="false">
        @if (!empty($user?->profile?->profile_image))
            <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="{{ $user->name }}" class="size-9 rounded-full object-cover">
        @else
            <span class="inline-flex size-9 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">
                {{ $user?->initials ?? 'U' }}
            </span>
        @endif
        <span class="hidden text-left lg:block">
            <span class="block max-w-36 truncate text-sm font-semibold text-text-primary">{{ $user?->name }}</span>
            <span class="block text-xs text-text-muted">{{ str($roleKey ?: 'user')->replace('_', ' ')->title() }}</span>
        </span>
        <i data-lucide="chevron-down" class="hidden size-4 text-text-muted lg:block"></i>
    </button>

    <div class="hidden absolute right-0 z-40 mt-3 w-64 overflow-hidden rounded-shell border border-border bg-surface shadow-panel" data-tw-dropdown-menu>
        <div class="border-b border-border px-4 py-3">
            <p class="text-sm font-semibold text-text-primary">Signed in as</p>
            <p class="truncate text-sm text-text-secondary">{{ $user?->email }}</p>
        </div>
        <div class="py-2">
            <a href="{{ $profileRoute }}" class="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-text-secondary hover:bg-surface-muted hover:text-text-primary">
                <i data-lucide="user-circle" class="size-4"></i>
                My profile
            </a>
            @if (Route::has('notifications.index'))
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-text-secondary hover:bg-surface-muted hover:text-text-primary">
                    <i data-lucide="bell" class="size-4"></i>
                    Notifications
                </a>
            @endif
            @if (Route::has('support.tickets'))
                <a href="{{ route('support.tickets') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-text-secondary hover:bg-surface-muted hover:text-text-primary">
                    <i data-lucide="headphones" class="size-4"></i>
                    Support center
                </a>
            @endif
        </div>
        <div class="border-t border-border py-2">
            @if (Route::has('logout'))
                <a href="{{ route('logout') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-semibold text-danger hover:bg-danger-soft">
                    <i data-lucide="log-out" class="size-4"></i>
                    Log out
                </a>
            @endif
        </div>
    </div>
</div>
