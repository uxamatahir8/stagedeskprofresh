@props([
    'id',
    'title' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-xl',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
    ];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" data-tw-modal>
    <div class="absolute inset-0 bg-slate-950/55" data-tw-modal-close></div>
    <div class="relative w-full {{ $sizes[$size] ?? $sizes['md'] }} rounded-shell bg-surface shadow-panel">
        <div class="flex items-center justify-between gap-4 border-b border-border px-5 py-4">
            @if ($title)
                <h2 id="{{ $id }}-title" class="sd-title-card">{{ $title }}</h2>
            @endif
            <button type="button" class="inline-flex size-9 items-center justify-center rounded-field text-text-muted hover:bg-surface-muted hover:text-text-primary" data-tw-modal-close aria-label="Close modal">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>
        <div class="p-5">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="flex justify-end gap-2 border-t border-border px-5 py-4">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
