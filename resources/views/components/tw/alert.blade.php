@props([
    'type' => 'info',
    'title' => null,
    'icon' => null,
])

@php
    $types = [
        'success' => ['wrap' => 'border-success/30 bg-success-soft text-emerald-900', 'icon' => 'check-circle'],
        'warning' => ['wrap' => 'border-warning/40 bg-warning-soft text-amber-950', 'icon' => 'alert-triangle'],
        'danger' => ['wrap' => 'border-danger/30 bg-danger-soft text-rose-950', 'icon' => 'x-circle'],
        'info' => ['wrap' => 'border-info/30 bg-info-soft text-sky-950', 'icon' => 'info'],
    ];
    $style = $types[$type] ?? $types['info'];
    $alertIcon = $icon ?: $style['icon'];
@endphp

<div role="alert" {{ $attributes->merge(['class' => 'rounded-card border p-4 ' . $style['wrap']]) }}>
    <div class="flex gap-3">
        <i data-lucide="{{ $alertIcon }}" class="mt-0.5 size-5 shrink-0"></i>
        <div class="min-w-0">
            @if ($title)
                <p class="font-semibold">{{ $title }}</p>
            @endif
            <div class="{{ $title ? 'mt-1' : '' }} text-sm leading-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
