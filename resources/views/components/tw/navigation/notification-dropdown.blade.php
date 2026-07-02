@props([
    'notifications' => collect(),
    'count' => 0,
])

@php
    $iconMap = [
        'booking' => 'calendar-check',
        'payment' => 'wallet',
        'review' => 'star',
        'security' => 'shield-alert',
        'auth' => 'user-check',
        'email' => 'mail',
        'profile' => 'user-circle',
        'system' => 'cpu',
    ];
@endphp

<div class="relative" data-tw-dropdown>
    <button type="button" class="relative inline-flex size-10 items-center justify-center rounded-field text-text-secondary hover:bg-surface-muted hover:text-text-primary" data-tw-dropdown-toggle aria-haspopup="true" aria-expanded="false" aria-label="Open notifications">
        <i data-lucide="bell" class="size-5"></i>
        @if ($count > 0)
            <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-danger px-1.5 text-xs font-bold text-white topbar-badge">{{ $count }}</span>
        @endif
    </button>

    <div class="hidden absolute right-0 z-40 mt-3 w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-shell border border-border bg-surface shadow-panel" data-tw-dropdown-menu>
        <div class="border-b border-border px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <h2 class="sd-title-card">Notifications</h2>
                <x-tw.status-badge status="info" label="{{ $count }} unread" />
            </div>
            @if ($count > 0 && Route::has('notifications.mark-all-read'))
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm font-semibold text-primary hover:text-primary-hover">Mark all read</button>
                </form>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse ($notifications as $notification)
                @php
                    $icon = $iconMap[$notification->category ?? 'general'] ?? 'bell';
                @endphp
                <div class="border-b border-border px-4 py-3 last:border-b-0 {{ !$notification->is_read ? 'bg-primary-soft/45' : '' }}">
                    <div class="flex gap-3">
                        <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-full bg-surface text-primary ring-1 ring-border">
                            <i data-lucide="{{ $icon }}" class="size-5"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-text-primary">{{ $notification->title }}</p>
                            <p class="mt-1 text-sm leading-5 text-text-secondary">{{ Str::limit($notification->message, 72) }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="sd-meta">{{ $notification->created_at?->diffForHumans() }}</span>
                                <x-tw.status-badge status="{{ $notification->category ?? 'general' }}" label="{{ str($notification->category ?? 'general')->title() }}" />
                            </div>
                        </div>
                        @if (!$notification->is_read && Route::has('notifications.read'))
                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex size-8 items-center justify-center rounded-field text-text-muted hover:bg-surface-muted hover:text-text-primary" aria-label="Mark notification as read">
                                    <i data-lucide="check" class="size-4"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <p class="sd-text-muted">No notifications</p>
                </div>
            @endforelse
        </div>

        @if (Route::has('notifications.index'))
            <a href="{{ route('notifications.index') }}" class="block border-t border-border px-4 py-3 text-center text-sm font-semibold text-primary hover:bg-primary-soft">
                View all notifications
            </a>
        @endif
    </div>
</div>
