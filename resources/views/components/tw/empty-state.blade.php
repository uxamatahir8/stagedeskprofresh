@props([
    'icon' => 'inbox',
    'title' => 'No records found',
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-card border border-dashed border-border bg-surface p-8 text-center']) }}>
    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-primary-soft text-primary">
        <i data-lucide="{{ $icon }}" class="size-6"></i>
    </div>
    <h3 class="sd-title-card mt-4">{{ $title }}</h3>
    @if ($message)
        <p class="sd-text-muted mx-auto mt-2 max-w-md">{{ $message }}</p>
    @endif
    @isset($actions)
        <div class="mt-5 flex justify-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
