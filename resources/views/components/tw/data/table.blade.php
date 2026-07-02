@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-card border border-border bg-surface']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            @if (!empty($headers))
                <thead class="bg-surface-muted">
                    <tr>
                        @foreach ($headers as $header)
                            <th scope="col" class="px-4 py-3 text-left sd-table-heading">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-border bg-surface">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
