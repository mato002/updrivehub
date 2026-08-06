@props([
    'icon',
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'span' => 1,
])

<div @class(['sm:col-span-2' => $span === 2])>
    <label for="{{ $name }}" class="form-label">
        <i class="fa-solid {{ $icon }} mr-1.5 text-brand-500"></i>{{ $label }}@if($required) *@endif
    </label>
    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">
            <i class="fa-solid {{ $icon }} text-sm"></i>
        </span>
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'form-input form-input-icon']) }}
        />
    </div>
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
