@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || isset($header))
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">{{ $title }}</h4>
            @if(isset($header))
                {{ $header }}
            @endif
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
