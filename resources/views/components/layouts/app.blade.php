<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Web Event' }}</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">

    {{-- Vite Assets (SCSS & JS) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    @stack('styles')
</head>

<body x-data="{ isSidebarMinimized: false }">

    <div id="app" :class="isSidebarMinimized ? 'sidebar-minimized' : ''">
        {{-- SIDEBAR WRAPPER --}}
        @include('components.layouts.partials.sidebars.sidebar')

        {{-- MAIN CONTENT --}}
        <div id="main" class="layout-navbar">
            {{-- Header (Burger Button & User Menu) --}}
            @include('components.layouts.partials.header')

            <div id="main-content">
                {{-- Page Heading --}}
                <div class="page-heading">
                    {{-- Breadcrumbs (Optional) --}}
                    @if(isset($breadcrumbs))
                    {{ $breadcrumbs }}
                    @endif
                </div>

                {{-- Content Slot Livewire --}}
                <div class="page-content">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @include('components.layouts.partials.footer')
            </div>
        </div>
    </div>

    {{-- Livewire Scripts --}}
    @livewireScripts
    @stack('scripts')
</body>

</html>
