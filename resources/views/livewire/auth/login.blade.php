<div class="w-full flex flex-col lg:flex-row h-screen overflow-hidden">

    {{-- BAGIAN KIRI: Form Login --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 lg:p-12 bg-white relative">

        {{-- Logo Mobile/Tablet (Hanya muncul di layar kecil) --}}
        <div class="lg:hidden absolute top-8 left-6">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="h-8">
            </a>
        </div>

        <div class="w-full max-w-md space-y-8 animate__animated animate__fadeIn">

            {{-- Header --}}
            <div class="text-center lg:text-left">
                <div class="hidden lg:flex items-center gap-2 mb-8">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="h-10">
                    <span class="font-bold text-2xl text-vent-primary">VentNice</span>
                </div>
                <h2 class="text-3xl font-bold text-vent-secondary tracking-tight">Selamat Datang Kembali</h2>
                <p class="mt-2 text-slate-500">Masuk untuk mengelola event dan tiket Anda.</p>
            </div>

            {{-- Form --}}
            <form wire:submit="login" class="space-y-6">

                <x-vent.input label="Email Address" type="email" wire:model="email" :error="$errors->first('email')" placeholder="nama@email.com" />

                <div class="space-y-1">
                    <x-vent.input label="Password" type="password" wire:model="password" :error="$errors->first('password')" placeholder="••••••••" />
                    <div class="flex justify-end">
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-vent-primary hover:text-vent-primary-hover hover:underline">
                            Lupa password?
                        </a>
                    </div>
                </div>

                {{-- Remember Me & Submit --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-slate-300 text-vent-primary focus:ring-vent-primary/20 transition cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer select-none">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <x-vent.button type="submit" variant="primary" size="lg" fullWidth>
                    Masuk ke Akun
                </x-vent.button>
            </form>

            {{-- Footer Link --}}
            <div class="text-center mt-6">
                <p class="text-slate-500 text-sm">
                    Belum punya akun?
                    <a wire:navigate href="{{ route('register') }}" class="font-bold text-vent-primary hover:underline transition">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>

        {{-- Copyright Mobile --}}
        <div class="lg:hidden mt-12 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} VentNice Event.
        </div>
    </div>

    {{-- BAGIAN KANAN: Visual Image (Desktop Only) --}}
    <div class="hidden lg:block lg:w-1/2 bg-vent-secondary relative overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-60 mix-blend-overlay" style="background-image: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop');">
        </div>

        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-vent-primary/90 to-vent-secondary/90 mix-blend-multiply"></div>

        {{-- Content Visual --}}
        <div class="absolute inset-0 flex flex-col justify-center px-16 text-white z-10">
            <h2 class="text-4xl font-bold mb-6 leading-tight">Temukan Pengalaman <br>Tak Terlupakan.</h2>
            <p class="text-lg text-blue-100/80 mb-8 max-w-md leading-relaxed">
                Bergabunglah dengan ribuan pengguna lainnya di VentNice. Platform satu pintu untuk manajemen dan pemesanan tiket event termudah.
            </p>

            {{-- Testimonial Card Kecil --}}
            {{-- <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 max-w-md shadow-2xl">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center font-bold text-white">
                        D
                    </div>
                    <div>
                        <h5 class="font-bold text-sm">Dimas Pratama</h5>
                        <div class="flex text-yellow-400 text-xs">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-white/90 italic">"Platform event paling responsif yang pernah saya gunakan. Checkout tiket sangat cepat!"</p>
            </div> --}}
        </div>

        {{-- Pattern Decoration --}}
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-vent-info/30 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 -left-24 w-72 h-72 bg-vent-primary/40 rounded-full blur-3xl"></div>
    </div>
</div>

