@props([
    'name',
    'label' => null,
    'required' => false,
    'helper' => null,
    'accept' => null,
    'currentPath' => null,
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
    <div class="rounded-card border border-dashed border-border-strong bg-surface px-4 py-5">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="file"
            @required($required)
            @if ($accept) accept="{{ $accept }}" @endif
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            {{ $attributes->except('id')->merge(['class' => 'block w-full text-sm text-text-secondary file:mr-4 file:rounded-field file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-hover']) }}
        >
        @if ($currentPath)
            <a href="{{ asset('storage/' . $currentPath) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-hover">
                <i data-lucide="paperclip" class="size-4"></i>
                View current file
            </a>
        @endif
    </div>
    @if ($helper)
        <p class="sd-helper">{{ $helper }}</p>
    @endif
    @error($name)
        <p class="sd-error-text">{{ $message }}</p>
    @enderror
</div>
