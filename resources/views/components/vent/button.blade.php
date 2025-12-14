@props([
'variant' => 'primary', // primary, secondary, danger, outline, ghost
'size' => 'md', // sm, md, lg
'type' => 'submit',
'fullWidth' => false,
'target' => null, // Opsional: target spesifik untuk wire:loading (misal: 'save')
])

@php
// Base Classes
$baseClass = 'inline-flex items-center justify-center font-bold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed';

// Rounded Style (Sesuai identitas VentNice: Pill Shape)
$roundedClass = 'rounded-full';

// Size Classes
$sizes = [
'sm' => 'px-4 py-1.5 text-xs',
'md' => 'px-6 py-2.5 text-sm',
'lg' => 'px-8 py-3.5 text-base shadow-lg hover:shadow-xl hover:-translate-y-0.5',
];

// Variant Colors (Sesuai Palette VentNice)
$variants = [
'primary' => 'bg-vent-primary text-white hover:bg-vent-primary-hover shadow-vent-primary/30 focus:ring-vent-primary',
'secondary' => 'bg-vent-secondary text-white hover:bg-slate-800 focus:ring-vent-secondary',
'danger' => 'bg-vent-danger text-white hover:bg-red-600 shadow-vent-danger/30 focus:ring-vent-danger',
'outline' => 'bg-transparent border-2 border-vent-primary text-vent-primary hover:bg-vent-primary hover:text-white',
'ghost' => 'bg-transparent text-vent-secondary hover:bg-vent-surface',
];

// Merge Classes
$classes = $baseClass . ' ' . $roundedClass . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($fullWidth ? 'w-full' : '');

// Auto-detect wire:target if generic wire:click is present and no target is specified
$attributesTarget = $attributes->wire('click')->value() ?? $attributes->wire('submit')->value();
$finalTarget = $target ?? $attributesTarget;
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} {{-- Matikan tombol saat loading --}} @if($finalTarget) wire:loading.attr="disabled" wire:target="{{ $finalTarget }}" @endif>
    {{-- Content Normal (Disembunyikan saat Loading) --}}
    <span @if($finalTarget) wire:loading.remove wire:target="{{ $finalTarget }}" @endif class="flex items-center gap-2">
        {{ $slot }}
    </span>

    {{-- Loading Indicator (Muncul saat Loading) --}}
    @if($finalTarget)
    <span wire:loading.flex wire:target="{{ $finalTarget }}" class="items-center gap-2">
        <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Loading...</span>
    </span>
    @endif
</button>
