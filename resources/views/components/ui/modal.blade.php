@props([
    'name', // Unique name for the modal
    'title' => '',
    'size' => 'lg', // sm, lg, xl
])

@php
    $modalSize = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => '',
    };
@endphp

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-on:open-modal.window="show = ($event.detail.name === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-cloak
    :class="{ 'show': show }"
    :style="{ 'display': show ? 'block' : 'none' }"
    class="modal fade text-left"
    tabindex="-1" role="dialog"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $modalSize }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close rounded-pill" @click="show = false" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if(isset($footer))
            <div class="modal-footer">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>
