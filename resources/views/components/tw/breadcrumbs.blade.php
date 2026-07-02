@props([
    'items' => [],
])

@if (!empty($items))
    <nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-4']) }}>
        <ol class="flex flex-wrap items-center gap-2 text-sm text-text-muted">
            @foreach ($items as $item)
                @php
                    $label = is_array($item) ? ($item['label'] ?? '') : $item;
                    $url = is_array($item) ? ($item['url'] ?? null) : null;
                    $isLast = $loop->last;
                @endphp
                <li class="inline-flex items-center gap-2">
                    @if ($url && !$isLast)
                        <a href="{{ $url }}" class="font-medium text-text-secondary hover:text-primary">{{ $label }}</a>
                    @else
                        <span class="{{ $isLast ? 'font-semibold text-text-primary' : '' }}">{{ $label }}</span>
                    @endif
                    @if (!$isLast)
                        <i data-lucide="chevron-right" class="size-3.5 text-slate-400"></i>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
