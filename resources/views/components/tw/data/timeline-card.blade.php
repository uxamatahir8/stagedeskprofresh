@props([
    'title' => 'Activity',
])

<x-tw.card :title="$title" {{ $attributes }}>
    <ol class="space-y-4">
        {{ $slot }}
    </ol>
</x-tw.card>
