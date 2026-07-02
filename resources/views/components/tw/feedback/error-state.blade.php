@props([
    'title' => 'Something went wrong',
    'message' => 'Please try again or contact support if the problem continues.',
    'icon' => 'alert-circle',
])

<div {{ $attributes->merge(['class' => 'rounded-card border border-danger/30 bg-danger-soft p-6 text-center']) }}>
    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-white text-danger">
        <i data-lucide="{{ $icon }}" class="size-6"></i>
    </div>
    <h2 class="sd-title-card mt-4">{{ $title }}</h2>
    <p class="sd-text-muted mx-auto mt-2 max-w-md">{{ $message }}</p>
    @isset($actions)
        <div class="mt-5 flex justify-center gap-2">{{ $actions }}</div>
    @endisset
</div>
