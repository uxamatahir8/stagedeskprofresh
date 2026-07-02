@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'helper' => null,
])

@php
    $id = $attributes->get('id') ?: $name;
    $current = old($name, $selected);
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
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @required($required)
        aria-invalid="{{ $hasError ? 'true' : 'false' }}"
        {{ $attributes->except('id')->merge(['class' => 'sd-form-field']) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected((string) $current === (string) $value)>{{ $text }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @if ($helper)
        <p class="sd-helper">{{ $helper }}</p>
    @endif
    @error($name)
        <p class="sd-error-text">{{ $message }}</p>
    @enderror
</div>
