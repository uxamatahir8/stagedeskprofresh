@props([
    'name',
    'label' => null,
    'required' => false,
    'helper' => null,
])

@php
    $id = $attributes->get('id') ?: $name;
    $hasError = $errors->has($name);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $id }}" class="sd-label">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <div class="relative">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            @required($required)
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            {{ $attributes->except('id')->merge(['class' => 'sd-form-field pr-20' . ($hasError ? ' is-invalid' : '')]) }}
        >
        <button type="button" class="absolute inset-y-1 right-1 inline-flex items-center gap-1 rounded-field px-3 text-xs font-semibold text-text-muted hover:bg-surface-muted hover:text-text-primary" data-tw-password-toggle="{{ $id }}" aria-pressed="false">
            <i data-lucide="eye" class="size-4"></i>
            <span data-password-label>Show</span>
        </button>
    </div>
    @if ($helper)
        <p class="sd-helper">{{ $helper }}</p>
    @endif
    @error($name)
        <p class="sd-error-text">{{ $message }}</p>
    @enderror
</div>
