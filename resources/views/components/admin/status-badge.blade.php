@props(['status'])

@php
    $meta = \App\Models\DriverApplication::statuses()[$status] ?? ['label' => ucfirst($status), 'color' => 'slate'];
@endphp

<span {{ $attributes->merge(['class' => 'status-badge status-badge-'.$meta['color']]) }}>
    {{ $meta['label'] }}
</span>
