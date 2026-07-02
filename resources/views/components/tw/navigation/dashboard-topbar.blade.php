@props([
    'notifications' => collect(),
    'notificationCount' => 0,
])

<header {{ $attributes->merge(['class' => 'sticky top-0 z-30 flex h-[var(--sd-header-height)] items-center border-b border-border bg-surface/95 px-4 backdrop-blur lg:px-6']) }}>
    <div class="flex w-full items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button type="button" class="inline-flex size-10 items-center justify-center rounded-field text-text-secondary hover:bg-surface-muted hover:text-text-primary lg:hidden" data-tw-drawer-open aria-label="Open navigation">
                <i data-lucide="menu" class="size-5"></i>
            </button>
            <div class="hidden lg:block">
                <p class="text-sm font-semibold text-text-primary">StageDesk Pro</p>
                <p class="sd-meta">Operations workspace</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-tw.navigation.notification-dropdown :notifications="$notifications" :count="$notificationCount" />
            <x-tw.navigation.user-menu />
        </div>
    </div>
</header>
