@props([
    'domain' => null,
    'status' => null,
    'label' => null,
])

@php
    $raw = strtolower((string) $status);
    $normalized = str_replace(['_', ' '], '-', $raw);
    $domainMap = [
        'booking' => [
            'pending' => 'pending',
            'confirmed' => 'confirmed',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'canceled' => 'cancelled',
            'rejected' => 'rejected',
        ],
        'payment' => [
            'unpaid' => 'unpaid',
            'pending' => 'pending',
            'completed' => 'completed',
            'paid' => 'completed',
            'failed' => 'failed',
            'refunded' => 'refunded',
            'internal-paid' => 'internal-paid',
        ],
        'subscription' => [
            'active' => 'active',
            'expired' => 'unpaid',
            'canceled' => 'canceled',
            'cancelled' => 'canceled',
            'paused' => 'paused',
            'awaiting-payment' => 'awaiting-payment',
        ],
        'sharing' => [
            'pending' => 'pending',
            'accepted' => 'accepted',
            'rejected' => 'rejected',
            'revoked' => 'canceled',
        ],
        'withdrawal' => [
            'pending' => 'pending',
            'approved' => 'approved',
            'rejected' => 'rejected',
            'paid' => 'paid',
        ],
        'review' => [
            'pending' => 'pending',
            'approved' => 'approved',
            'rejected' => 'rejected',
        ],
        'support' => [
            'open' => 'open',
            'in-progress' => 'in-progress',
            'closed' => 'closed',
        ],
        'user' => [
            'active' => 'active',
            'inactive' => 'inactive',
            'banned' => 'banned',
        ],
        'content' => [
            'draft' => 'draft',
            'unapproved' => 'unapproved',
            'published' => 'published',
            'archived' => 'archived',
            'rejected' => 'rejected',
        ],
    ];
    $semantic = $domain && isset($domainMap[$domain][$normalized]) ? $domainMap[$domain][$normalized] : $normalized;
    $text = $label ?: str($raw ?: 'unknown')->replace(['_', '-'], ' ')->title();
@endphp

<span {{ $attributes->merge(['class' => 'sd-status sd-status-' . $semantic]) }}>
    {{ $text }}
</span>
