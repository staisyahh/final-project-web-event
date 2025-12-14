<div class="w-full flex flex-col lg:flex-row h-screen overflow-hidden">

    {{-- BAGIAN KIRI: Form Register --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 lg:p-12 bg-white relative overflow-y-auto">

        {{-- Logo Mobile --}}
        <div class="lg:hidden absolute top-8 left-6">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="h-8">
            </a>
        </div>

        <div class="w-full max-w-md space-y-8 py-8 animate__animated animate__fadeIn">

            {{-- Header --}}
            <div class="text-center lg:text-left">
                <div class="hidden lg:flex items-center gap-2 mb-8">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="h-10">
                    <span class="font-bold text-2xl text-vent-primary">VentNice</span>
                </div>
                <h2 class="text-3xl font-bold text-vent-secondary tracking-tight">Buat Akun Baru</h2>
                <p class="mt-2 text-slate-500">Mulai perjalanan event seru Anda sekarang.</p>
            </div>

            {{-- Form --}}
            <form wire:submit="register" class="space-y-5">

                {{-- Nama Lengkap --}}
                <x-vent.input label="Nama Lengkap" type="text" wire:model="name" :error="$errors->first('name')" placeholder="John Doe" />

                {{-- Email --}}
                <x-vent.input label="Email Address" type="email" wire:model="email" :error="$errors->first('email')" placeholder="nama@email.com" />

                {{-- Password Group --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-vent.input label="Password" type="password" wire:model="password" :error="$errors->first('password')" placeholder="Min. 8 karakter" />

                    <x-vent.input label="Konfirmasi Password" type="password" wire:model="passwordConfirmation" :error="$errors->first('passwordConfirmation')" placeholder="Ulangi password" />
                </div>

                {{-- Terms Checkbox (Optional tapi bagus untuk UX) --}}
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" required class="h-4 w-4 rounded border-slate-300 text-vent-primary focus:ring-vent-primary/20 cursor-pointer">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="terms" class="text-slate-600">
                            Saya menyetujui <a href="#" class="text-vent-primary hover:underline">Syarat & Ketentuan</a> VentNice.
                        </label>
                    </div>
                </div>

                <x-vent.button type="submit" variant="primary" size="lg" fullWidth>
                    Daftar Sekarang
                </x-vent.button>
            </form>

            {{-- Footer Link --}}
            <div class="text-center mt-6">
                <p class="text-slate-500 text-sm">
                    Sudah punya akun?
                    <a wire:navigate href="{{ route('login') }}" class="font-bold text-vent-primary hover:underline transition">
                        Masuk disini
                    </a>
                </p>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: Visual Image (Desktop Only) --}}
    <div class="hidden lg:block lg:w-1/2 bg-vent-secondary relative overflow-hidden">
        {{-- Background Image (Beda gambar dengan login biar fresh) --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-60 mix-blend-overlay" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50935339?q=80&w=2070&auto=format&fit=crop');">
        </div>

        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-bl from-vent-primary/90 to-vent-secondary/90 mix-blend-multiply"></div>

        {{-- Content Visual --}}
        <div class="absolute inset-0 flex flex-col justify-center px-16 text-white z-10">
            <h2 class="text-4xl font-bold mb-6 leading-tight">Jadilah Bagian dari <br>Komunitas Kreatif.</h2>
            <p class="text-lg text-blue-100/80 mb-8 max-w-md leading-relaxed">
                Akses ke ribuan event workshop, seminar teknologi, dan festival musik hanya dalam satu genggaman.
            </p>

            {{-- Stats Card --}}
            <div class="flex gap-4">
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 text-center min-w-[100px]">
                    <h4 class="text-2xl font-bold">10k+</h4>
                    <span class="text-xs text-blue-100 uppercase tracking-wider">Events</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 text-center min-w-[100px]">
                    <h4 class="text-2xl font-bold">50k+</h4>
                    <span class="text-xs text-blue-100 uppercase tracking-wider">Users</span>
                </div>
            </div>
        </div>

        {{-- Pattern Decoration --}}
        <div class="absolute bottom-10 right-10 w-64 h-64 bg-vent-warning/20 rounded-full blur-3xl"></div>
    </div>
</div>

