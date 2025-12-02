<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Web Event' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-light">

    {{-- Navbar Sederhana --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2" href="/" wire:navigate>
                <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="Logo" height="30">
                <span>Web Event</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#events">Semua Event</a></li>
                    <li class="nav-item"><a class="nav-link" href="#categories">Kategori</a></li>

                    @auth
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary" wire:navigate>Dashboard</a>
                    </li>
                    @else
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-primary px-4" wire:navigate>Masuk / Daftar</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div style="margin-top: 60px;">
        {{ $slot }}
    </div>

    {{-- Footer --}}
    <footer class="bg-white pt-5 pb-3 mt-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-primary mb-3">Web Event</h5>
                    <p class="text-muted">Platform manajemen event terbaik untuk menemukan dan mengatur acara impian
                        Anda.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Cari Event</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2">Teknologi</li>
                        <li class="mb-2">Bisnis</li>
                        <li class="mb-2">Musik</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">Berlangganan</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email Anda">
                        <button class="btn btn-primary" type="button">Kirim</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} Web Event. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>

</html>
