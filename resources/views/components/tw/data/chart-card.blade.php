@props([
    'title',
    'description' => null,
    'canvasId' => null,
])

<x-tw.card :title="$title" :description="$description" {{ $attributes }}>
    <div class="min-h-72">
        @if ($canvasId)
            <canvas id="{{ $canvasId }}" class="h-72 w-full"></canvas>
        @else
            {{ $slot }}
        @endif
    </div>
</x-tw.card>
