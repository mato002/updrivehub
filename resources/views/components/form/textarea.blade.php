@props([
    'icon',
    'label',
    'name',
    'required' => false,
    'rows' => 3,
])

<div class="sm:col-span-2">
    <label for="{{ $name }}" class="form-label">
        <i class="fa-solid {{ $icon }} mr-1.5 text-brand-500"></i>{{ $label }}@if($required) *@endif
    </label>
    <div class="relative">
        <span class="pointer-events-none absolute top-3 left-0 flex w-10 items-start justify-center text-slate-400">
            <i class="fa-solid {{ $icon }} text-sm"></i>
        </span>
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'form-input form-input-icon form-textarea-icon']) }}
        >{{ $slot }}</textarea>
    </div>
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
