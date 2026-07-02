@props([
    'title' => 'Filters',
])

<section {{ $attributes->merge(['class' => 'rounded-card border border-border bg-surface p-4']) }}>
    <div class="mb-4 flex items-center justify-between gap-3">
        <h2 class="sd-title-card">{{ $title }}</h2>
        @isset($actions)
            <div class="flex gap-2">{{ $actions }}</div>
        @endisset
    </div>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        {{ $slot }}
    </div>
</section>
