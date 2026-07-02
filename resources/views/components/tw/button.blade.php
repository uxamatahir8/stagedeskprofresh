@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
])

@php
    $variantClasses = [
        'primary' => 'bg-primary text-white hover:bg-primary-hover focus-visible:outline-primary',
        'secondary' => 'bg-accent text-white hover:bg-accent-strong focus-visible:outline-accent',
        'outline' => 'border border-border-strong bg-surface text-text-primary hover:bg-surface-muted',
        'ghost' => 'bg-transparent text-text-secondary hover:bg-surface-muted hover:text-text-primary',
        'danger' => 'bg-danger text-white hover:bg-rose-700 focus-visible:outline-danger',
        'success' => 'bg-success text-white hover:bg-emerald-700 focus-visible:outline-success',
        'warning' => 'bg-warning text-slate-950 hover:bg-amber-400 focus-visible:outline-warning',
        'link' => 'bg-transparent px-0 text-primary hover:text-primary-hover',
    ];
    $sizeClasses = [
        'sm' => 'min-h-8 px-3 py-1.5 text-sm',
        'md' => 'min-h-10 px-4 py-2 text-sm',
        'lg' => 'min-h-11 px-5 py-2.5 text-base',
    ];
    $classes = trim('inline-flex items-center justify-center gap-2 rounded-field font-semibold leading-none transition disabled:pointer-events-none disabled:opacity-55 ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="size-4"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="size-4"></i>
        @endif
        {{ $slot }}
    </button>
@endif
