@props([
    'message' => 'You do not have permission to access this screen.',
])

<x-tw.feedback.error-state title="Permission denied" :message="$message" icon="lock">
    @isset($actions)
        <x-slot:actions>{{ $actions }}</x-slot:actions>
    @endisset
</x-tw.feedback.error-state>
