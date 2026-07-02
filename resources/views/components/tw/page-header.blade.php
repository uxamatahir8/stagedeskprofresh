@props([
    'title' => null,
    'description' => null,
    'eyebrow' => null,
])

<header {{ $attributes->merge(['class' => 'mb-6 flex flex-col gap-4 border-b border-border pb-5 md:flex-row md:items-end md:justify-between']) }}>
    <div class="min-w-0">
        @if ($eyebrow)
            <p class="sd-meta mb-2 font-semibold uppercase tracking-wide text-primary">{{ $eyebrow }}</p>
        @endif
        @if ($title)
            <h1 class="sd-title-page">{{ $title }}</h1>
        @endif
        @if ($description)
            <p class="sd-text-body mt-2 max-w-3xl">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</header>
