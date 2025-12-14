@props([
'name', // ID unik untuk modal (wajib)
'title' => null, // Judul modal
'maxWidth' => 'md' // sm, md, lg, xl, 2xl, 4xl
])

@php
$maxWidths = [
'sm' => 'sm:max-w-sm',
'md' => 'sm:max-w-md',
'lg' => 'sm:max-w-lg',
'xl' => 'sm:max-w-xl',
'2xl' => 'sm:max-w-2xl',
'4xl' => 'sm:max-w-4xl',
];
@endphp

<div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-{{ $name }}" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">

        {{-- BACKDROP (Dark Overlay) --}}
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="show = false" aria-hidden="true"></div>

        {{-- MODAL PANEL --}}
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full {{ $maxWidths[$maxWidth] }}">
            {{-- Header --}}
            <div class="bg-white px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                @if($title)
                <h3 class="text-lg font-bold text-vent-secondary" id="modal-title-{{ $name }}">
                    {{ $title }}
                </h3>
                @endif

                {{-- Close Button --}}
                <button @click="show = false" class="text-slate-400 hover:text-vent-danger hover:bg-red-50 rounded-full p-1 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{ $slot }}
            </div>

            {{-- Footer (Optional) --}}
            @if(isset($footer))
            <div class="bg-vent-surface/50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-slate-100">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>
