@props([
    'name',
    'label',
    'value' => '1',
    'checked' => false,
    'helper' => null,
])

@php
    $id = $attributes->get('id') ?: $name;
    $isChecked = old($name, $checked);
@endphp

<div class="space-y-1">
    <label for="{{ $id }}" class="flex items-start gap-3">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            type="checkbox"
            @checked($isChecked)
            {{ $attributes->except('id')->merge(['class' => 'mt-1 size-4 rounded border-border-strong text-primary focus:ring-accent']) }}
        >
        <span>
            <span class="sd-label">{{ $label }}</span>
            @if ($helper)
                <span class="sd-helper block">{{ $helper }}</span>
            @endif
        </span>
    </label>
    @error($name)
        <p class="sd-error-text">{{ $message }}</p>
    @enderror
</div>
