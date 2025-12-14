<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'VentNice Event' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons (Opsional, jika masih ingin pakai ikonnya tanpa install full bootstrap) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-vent-surface font-sans text-vent-secondary antialiased selection:bg-vent-primary selection:text-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 group">
                    {{-- Ganti src dengan logo VentNice Anda --}}
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="VentNice" class="h-10 w-auto transition transform group-hover:scale-105">
                    <span class="text-xl font-bold text-vent-primary tracking-tight">VentNice</span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" wire:navigate class="text-sm font-medium text-slate-600 hover:text-vent-primary transition">Beranda</a>
                    <a href="/#events" class="text-sm font-medium text-slate-600 hover:text-vent-primary transition">Jelajah Event</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                    @php
                    match (auth()->user()->role == 'admin') {
                    true => $route = 'admin.dashboard',
                    false => $route = 'member.tiket'
                    }
                    @endphp
                    <a href="{{ route($route) }}" class="px-5 py-2.5 text-sm font-medium text-vent-primary border border-vent-primary rounded-btn hover:bg-vent-primary hover:text-white transition-all shadow-sm hover:shadow-md">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" wire:navigate class="px-6 py-2.5 text-sm font-semibold text-white bg-vent-primary rounded-btn hover:bg-vent-primary-hover transition-all shadow-lg shadow-vent-primary/30 hover:shadow-vent-primary/50">
                        Masuk / Daftar
                    </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button (Hamburger) --}}
                <div class="md:hidden flex items-center">
                    <button type="button" class="text-slate-600 hover:text-vent-primary focus:outline-none">
                        <i class="bi bi-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-24 min-h-screen">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <h5 class="font-bold text-xl text-vent-primary mb-4">VentNice</h5>
                    <p class="text-slate-500 text-sm leading-relaxed">Platform manajemen event modern untuk pengalaman tak terlupakan. Temukan, pesan, dan nikmati.</p>
                </div>
                <div>
                    <h6 class="font-bold text-vent-secondary mb-4">Navigasi</h6>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-vent-primary transition">Beranda</a></li>
                        <li><a href="#" class="hover:text-vent-primary transition">Cari Event</a></li>
                        {{-- <li><a href="#" class="hover:text-vent-primary transition">Tentang Kami</a></li> --}}
                    </ul>
                </div>
                <div>
                    {{-- <h6 class="font-bold text-vent-secondary mb-4">Kategori Populer</h6>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li>Teknologi & Startup</li>
                        <li>Bisnis & Karir</li>
                        <li>Hiburan & Musik</li>
                    </ul> --}}
                </div>
                <div>
                    <h6 class="font-bold text-vent-secondary mb-4">Tetap Terhubung</h6>
                    <div class="flex gap-2">
                        <input type="text" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-vent-primary/50" placeholder="Email Anda">
                        <button class="px-4 py-2 bg-vent-secondary text-white rounded-lg hover:bg-slate-800 transition">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 text-center">
                <p class="text-slate-400 text-sm">&copy; {{ date('Y') }} VentNice Event. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
