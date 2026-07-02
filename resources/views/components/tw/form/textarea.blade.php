@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'helper' => null,
    'rows' => 4,
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
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @required($required)
        aria-invalid="{{ $hasError ? 'true' : 'false' }}"
        {{ $attributes->except('id')->merge(['class' => 'sd-form-field' . ($hasError ? ' is-invalid' : '')]) }}
    >{{ old($name, $value) }}</textarea>
    @if ($helper)
        <p class="sd-helper">{{ $helper }}</p>
    @endif
    @error($name)
        <p class="sd-error-text">{{ $message }}</p>
    @enderror
</div>
