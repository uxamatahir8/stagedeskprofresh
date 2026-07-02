@props([
    'href' => null,
    'type' => 'button',
    'icon' => 'circle',
    'label' => 'Action',
    'variant' => 'ghost',
])

@php
    $variantClasses = [
        'ghost' => 'border border-transparent text-text-secondary hover:bg-surface-muted hover:text-text-primary',
        'outline' => 'border border-border bg-surface text-text-secondary hover:bg-surface-muted hover:text-text-primary',
        'primary' => 'border border-primary bg-primary text-white hover:bg-primary-hover',
        'danger' => 'border border-danger bg-danger text-white hover:bg-rose-700',
    ];
    $classes = 'inline-flex size-10 items-center justify-center rounded-field transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ' . ($variantClasses[$variant] ?? $variantClasses['ghost']);
@endphp

@if ($href)
    <a href="{{ $href }}" aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
        <i data-lucide="{{ $icon }}" class="size-5"></i>
    </a>
@else
    <button type="{{ $type }}" aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
        <i data-lucide="{{ $icon }}" class="size-5"></i>
    </button>
@endif
