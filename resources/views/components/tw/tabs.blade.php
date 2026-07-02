@props([
    'tabs' => [],
    'active' => null,
])

@php
    $activeTab = $active ?: array_key_first($tabs);
@endphp

<div {{ $attributes }}>
    <div class="border-b border-border">
        <nav class="-mb-px flex gap-4 overflow-x-auto" aria-label="Tabs">
            @foreach ($tabs as $key => $label)
                <button type="button" class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-semibold transition {{ $key === $activeTab ? 'border-primary text-primary' : 'border-transparent text-text-muted hover:border-border-strong hover:text-text-primary' }}" data-tw-tab-trigger="{{ $key }}">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
