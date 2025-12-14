@props(['disabled' => false, 'label' => null, 'error' => null])

<div class="w-full">
    @if($label)
        <label class="block text-sm font-medium text-slate-700 mb-1.5">
            {{ $label }}
        </label>
    @endif

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'py-2 px-3 w-full rounded-xl border-slate-300 shadow-sm focus:border-vent-primary focus:ring focus:ring-vent-primary/20 transition disabled:opacity-50 disabled:bg-slate-50 ' .
        ($error ? 'border-vent-danger focus:border-vent-danger focus:ring-vent-danger/20' : '')
    ]) !!}>

    @if($error)
        <p class="mt-1 text-xs text-vent-danger flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $error }}
        </p>
    @endif
</div>
