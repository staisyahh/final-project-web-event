@props([
'type' => 'button', // button, submit, link
'href' => '#',
'variant' => 'primary', // primary, secondary, outline-primary, etc
'size' => 'md', // sm, md, lg
'icon' => null,
'class' => '',
'alpineClick' => null, // support click handler alpine
'navigate' => true, // control wire:navigate for links
])

@php
$baseClass = "btn btn-{$variant} btn-{$size} {$class}";

// Jika ada icon, tambahkan class icon-left
if($icon) {
$baseClass .= " icon icon-left";
}
@endphp

@if($type === 'link')
<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}
    @if($navigate) wire:navigate @endif
    @if($alpineClick) @click="{{ $alpineClick }}" @endif>
    @if($icon) <i class="{{ $icon }}"></i> @endif
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClass]) }}
    @if($alpineClick) @click="{{ $alpineClick }}" @endif>
    @if($icon) <i class="{{ $icon }}"></i> @endif
    {{ $slot }}
</button>
@endif
