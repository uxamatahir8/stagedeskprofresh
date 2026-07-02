@props([
    'align' => 'right',
    'label' => 'Open menu',
    'icon' => 'more-horizontal',
])

@php
    $alignment = $align === 'left' ? 'left-0' : 'right-0';
@endphp

<div class="relative inline-flex" data-tw-dropdown>
    <button type="button" class="inline-flex items-center gap-2 rounded-field border border-border bg-surface px-3 py-2 text-sm font-semibold text-text-secondary hover:bg-surface-muted hover:text-text-primary" data-tw-dropdown-toggle aria-haspopup="true" aria-expanded="false">
        <i data-lucide="{{ $icon }}" class="size-4"></i>
        {{ $label }}
    </button>
    <div class="hidden absolute {{ $alignment }} z-40 mt-11 min-w-52 overflow-hidden rounded-shell border border-border bg-surface py-2 shadow-panel" data-tw-dropdown-menu>
        {{ $slot }}
    </div>
</div>
