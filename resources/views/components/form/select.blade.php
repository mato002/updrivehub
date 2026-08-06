@props([
    'icon',
    'label',
    'name',
    'required' => false,
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
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'form-input form-input-icon']) }}
        >
            {{ $slot }}
        </select>
    </div>
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
