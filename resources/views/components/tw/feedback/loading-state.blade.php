@props([
    'message' => 'Loading',
])

<div {{ $attributes->merge(['class' => 'rounded-card border border-border bg-surface p-6']) }}>
    <div class="animate-pulse space-y-4">
        <div class="h-4 w-32 rounded bg-surface-muted"></div>
        <div class="h-3 w-full rounded bg-surface-muted"></div>
        <div class="h-3 w-2/3 rounded bg-surface-muted"></div>
    </div>
    <p class="sd-meta mt-4">{{ $message }}</p>
</div>
