@props([
    'href' => null,
    'showText' => false,
    'size' => 'md',
    'variant' => 'default',
])

@php
    $siteName = function_exists('settings_get') ? settings_get('site_name') : config('app.name', 'StageDesk Pro');
    $siteName = $siteName ?: 'StageDesk Pro';
    $target = $href ?: (Route::has('home') ? route('home') : url('/'));
    $sizes = [
        'sm' => 'h-8',
        'md' => 'h-10',
        'lg' => 'h-12',
        'xl' => 'h-16',
    ];
    $logoSize = $sizes[$size] ?? $sizes['md'];
@endphp

<a href="{{ $target }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <img src="{{ asset('images/stagedeskpro_logo.png') }}" alt="{{ $siteName }}" class="{{ $logoSize }} w-auto object-contain">
    @if ($showText)
        <span class="text-base font-bold tracking-normal {{ $variant === 'light' ? 'text-white' : 'text-text-primary' }}">
            {{ $siteName }}
        </span>
    @endif
</a>
