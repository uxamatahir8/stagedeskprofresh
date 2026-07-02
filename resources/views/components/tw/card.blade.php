@props([
    'title' => null,
    'description' => null,
    'icon' => null,
])

<section {{ $attributes->merge(['class' => 'sd-card sd-card-pad']) }}>
    @if ($title || $description || $icon || isset($actions))
        <div class="mb-4 flex items-start justify-between gap-4">
            <div class="min-w-0">
                @if ($title)
                    <div class="flex items-center gap-2">
                        @if ($icon)
                            <span class="inline-flex size-9 items-center justify-center rounded-field bg-primary-soft text-primary">
                                <i data-lucide="{{ $icon }}" class="size-4"></i>
                            </span>
                        @endif
                        <h2 class="sd-title-card">{{ $title }}</h2>
                    </div>
                @endif
                @if ($description)
                    <p class="sd-text-muted mt-1">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</section>
