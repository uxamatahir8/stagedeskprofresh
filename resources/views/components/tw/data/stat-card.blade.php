@props([
    'label',
    'value',
    'icon' => 'bar-chart-3',
    'tone' => 'primary',
    'meta' => null,
])

@php
    $tones = [
        'primary' => 'bg-primary-soft text-primary',
        'success' => 'bg-success-soft text-emerald-700',
        'warning' => 'bg-warning-soft text-amber-700',
        'danger' => 'bg-danger-soft text-rose-700',
        'info' => 'bg-info-soft text-sky-700',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'sd-card sd-card-pad']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="sd-meta font-semibold uppercase tracking-wide">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold text-text-primary">{{ $value }}</p>
            @if ($meta)
                <p class="sd-text-muted mt-1">{{ $meta }}</p>
            @endif
        </div>
        <span class="inline-flex size-11 items-center justify-center rounded-card {{ $tones[$tone] ?? $tones['primary'] }}">
            <i data-lucide="{{ $icon }}" class="size-5"></i>
        </span>
    </div>
</div>
