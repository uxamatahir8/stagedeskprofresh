@props([
    'title' => 'Payment proof',
    'path' => null,
    'status' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-card border border-border bg-surface p-4']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="sd-title-card">{{ $title }}</p>
            <p class="sd-helper mt-1">Uploaded proof is retained for manual verification.</p>
        </div>
        @if ($status)
            <x-tw.status-badge domain="payment" :status="$status" />
        @endif
    </div>

    @if ($path)
        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 rounded-field border border-border px-3 py-2 text-sm font-semibold text-primary hover:bg-primary-soft">
            <i data-lucide="paperclip" class="size-4"></i>
            View uploaded proof
        </a>
    @else
        <p class="sd-text-muted mt-4">No proof uploaded yet.</p>
    @endif
</div>
