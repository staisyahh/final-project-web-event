@props([
'options' => [],
])

<div wire:ignore x-data="{
        model: @entangle($attributes->wire('model')),
        options: @js($options)
    }" x-init="
        $nextTick(() => {
            const selectEl = $($el).find('select');

            selectEl.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '{{ $attributes['placeholder'] ?? 'Pilih salah satu' }}',
                allowClear: true,
                data: options, // Jika menggunakan data dari array
            });

            // Saat nilai select2 berubah, update model Livewire
            selectEl.on('change', function () {
                model = $(this).val();
            });

          // Saat model Livewire berubah, update nilai select2
$watch('model', (value) => {
    // Cek dulu apakah nilainya beda untuk mencegah looping (Infinite Loop)
    // Kita gunakan JSON.stringify untuk membandingkan array (jika multiple select)
    let currentVal = selectEl.val();

    // Konversi ke string untuk perbandingan yang aman (terutama jika array/multiple)
    if (JSON.stringify(currentVal) !== JSON.stringify(value)) {
        selectEl.val(value).trigger('change');
    }
});

            // Set nilai awal dari model
            selectEl.val(model).trigger('change');
        });
    ">
    <select {{ $attributes->whereDoesntStartWith('wire:model') }}>
        {{-- Jika options disediakan melalui slot --}}
        @if(empty($options))
        {{ $slot }}
        @endif
    </select>
</div>
