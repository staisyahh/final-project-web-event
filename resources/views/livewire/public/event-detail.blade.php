<div class="bg-white">
    {{-- Hero Section --}}
    <div class="relative h-[400px] md:h-[500px] w-full overflow-hidden">
        {{-- Background Image & Overlay --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $event->banner_url ?? 'https://via.placeholder.com/1200x600.png?text=Event+Banner' }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-vent-secondary/90 via-vent-secondary/50 to-transparent"></div>

        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12">
            <div class="max-w-7xl mx-auto">
                @if($event->category)
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/20 backdrop-blur text-white text-xs font-bold tracking-wider uppercase mb-4 border border-white/10">
                    {{ $event->category->name }}
                </span>
                @endif
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">{{ $event->title }}</h1>
                <p class="text-slate-200 text-lg font-light flex items-center gap-2">
                    <span class="opacity-75">Diselenggarakan oleh:</span>
                    <span class="font-medium text-white">{{ $event->organizer->name }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Left Column: Details --}}
            <div class="lg:col-span-2 space-y-10">

                {{-- Description --}}
                <section>
                    <h3 class="text-2xl font-bold text-vent-secondary mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-vent-primary rounded-full"></span> Deskripsi
                    </h3>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap">
                        {{ $event->description }}
                    </div>
                </section>

                {{-- Speakers --}}
                @if($event->speakers->isNotEmpty())
                <section>
                    <h3 class="text-2xl font-bold text-vent-secondary mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-vent-primary rounded-full"></span> Pembicara
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($event->speakers as $speaker)
                        <div class="flex items-center p-4 bg-vent-surface rounded-xl border border-blue-100">
                            <img src="{{ $speaker->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($speaker->name).'&background=random' }}" alt="{{ $speaker->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md mr-4" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}&background=random';">
                            <div>
                                <h6 class="font-bold text-vent-secondary text-lg">{{ $speaker->name }}</h6>
                                <p class="text-sm text-vent-primary font-medium">{{ $speaker->title }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Reviews --}}
                @if($event->reviews->isNotEmpty())
                <section>
                    <h3 class="text-2xl font-bold text-vent-secondary mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-vent-primary rounded-full"></span> Ulasan & Rating
                    </h3>

                    {{-- Summary --}}
                    <div class="bg-vent-surface border border-slate-200 rounded-xl p-5 flex items-center justify-center gap-6 mb-8">
                        <div class="text-center">
                            <p class="text-5xl font-bold text-vent-primary">{{ number_format($event->reviews->avg('rating'), 1) }}</p>
                            <div class="flex text-yellow-400 mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $event->reviews->avg('rating') >= $i ? 'bi-star-fill' : 'bi-star' }} text-2xl"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="w-px h-16 bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-vent-secondary">{{ $event->reviews->count() }}</p>
                            <p class="text-slate-500">Total Ulasan</p>
                        </div>
                    </div>


                    {{-- Review List --}}
                    <div class="space-y-6">
                        @foreach($event->reviews as $review)
                        <div class="flex gap-4">
                            <img src="{{ 'https://ui-avatars.com/api/?name='.urlencode($review->user->name).'&background=random' }}" alt="{{ $review->user->name }}" class="w-12 h-12 rounded-full object-cover shrink-0">
                            <div class="w-full">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h6 class="font-bold text-vent-secondary">{{ $review->user->name }}</h6>
                                        <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex text-yellow-400">
                                         @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $review->rating >= $i ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-slate-600 mt-3 text-sm leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="border-slate-100">
                        @endif
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Gallery --}}
                @if($event->galleries->isNotEmpty())
                <section x-data="{ imageModalOpen: false, currentImageIndex: 0, galleries: @js($event->galleries) }">
                    <h3 class="text-2xl font-bold text-vent-secondary mb-6 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-1 h-8 bg-vent-primary rounded-full"></span> Galeri
                        </div>
                        <x-vent.button type="button" variant="outline" size="sm"
                            @click="currentImageIndex = 0; imageModalOpen = true;">
                            Lihat Semua ({{ $event->galleries->count() }})
                        </x-vent.button>
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($event->galleries as $galleryImage)
                        <div @click="currentImageIndex = {{ $loop->index }}; imageModalOpen = true" class="cursor-pointer block overflow-hidden rounded-xl group">
                            <img src="{{ $galleryImage->image_url ?? 'https://via.placeholder.com/400x200.png?text=No+Image' }}" class="w-full h-40 object-cover transform group-hover:scale-110 transition duration-500" alt="Galeri Event" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x200.png?text=No+Image';">
                        </div>
                        @endforeach
                    </div>

                    {{-- Lightbox Modal --}}
                    <div x-show="imageModalOpen" @keydown.escape.window="imageModalOpen = false" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/80 backdrop-blur-sm" style="display: none;">
                        {{-- Close button --}}
                        <button @click="imageModalOpen = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition">
                            <i class="bi bi-x-lg text-3xl"></i>
                        </button>

                        {{-- Previous Button --}}
                        <button x-show="galleries.length > 1" @click="currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : galleries.length - 1" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-3 rounded-full bg-black/50 hover:bg-black/70">
                            <i class="bi bi-chevron-left text-2xl"></i>
                        </button>

                        {{-- Image --}}
                        <div @click.away="imageModalOpen = false" class="p-4">
                            <img :src="galleries[currentImageIndex] ? (galleries[currentImageIndex].image_url ?? 'https://via.placeholder.com/800x600.png?text=No+Image') : ''" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
                            <p x-text="galleries[currentImageIndex] ? galleries[currentImageIndex].caption : ''" class="text-center text-white mt-2"></p>
                        </div>

                        {{-- Next Button --}}
                        <button x-show="galleries.length > 1" @click="currentImageIndex = (currentImageIndex < galleries.length - 1) ? currentImageIndex + 1 : 0" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-3 rounded-full bg-black/50 hover:bg-black/70">
                            <i class="bi bi-chevron-right text-2xl"></i>
                        </button>
                    </div>
                </section>
                @endif
            </div>

            {{-- Right Column: Sticky Ticket Card --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-6 overflow-hidden relative">
                        {{-- Decorative Top Border --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-vent-primary to-vent-info"></div>

                        <h5 class="text-xl font-bold text-vent-secondary mb-6">Detail & Tiket</h5>

                        <ul class="space-y-5 mb-8">
                            <li class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-vent-surface flex items-center justify-center text-vent-primary mr-4 shrink-0">
                                    <i class="bi bi-calendar-check-fill text-lg"></i>
                                </div>
                                <div>
                                    <strong class="block text-vent-secondary text-sm">Waktu Pelaksanaan</strong>
                                    <span class="text-slate-500 text-sm">{{ $event->jadwal->format('l, d F Y') }}</span><br>
                                    <span class="text-slate-500 text-sm">{{ $event->jadwal->format('H:i') }} WIB</span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-vent-surface flex items-center justify-center text-vent-primary mr-4 shrink-0">
                                    <i class="bi bi-geo-alt-fill text-lg"></i>
                                </div>
                                <div>
                                    <strong class="block text-vent-secondary text-sm">Lokasi</strong>
                                    <span class="text-slate-500 text-sm block">{{ $event->location_name ?? 'Online' }}</span>
                                    @if($event->location_address)
                                    <small class="text-slate-400 text-xs block mt-1 leading-snug">{{ $event->location_address }}</small>
                                    @endif
                                </div>
                            </li>
                        </ul>

                        <div class="border-t border-slate-100 my-6 pt-6">
                            <h6 class="font-bold text-vent-secondary mb-4 text-sm uppercase tracking-wide">Pilihan Tiket</h6>
                            <div class="space-y-3">
                                @forelse ($event->tickets as $ticket)
                                    @php($is_available = $ticket->status == 'available' && $ticket->stok > 0)
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-slate-50 border border-slate-100 {{ !$is_available ? 'opacity-50' : '' }}">
                                        <div>
                                            <span class="text-sm font-medium text-slate-600">{{ $ticket->name }}</span>
                                            @if(!$is_available)
                                                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs font-semibold">Habis</span>
                                            @endif
                                        </div>
                                        <span class="text-sm font-bold text-vent-primary">
                                            {{ $ticket->harga > 0 ? 'Rp ' . number_format($ticket->harga, 0, ',', '.') : 'Gratis' }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-sm text-center italic">Tiket untuk event ini belum dibuat.</p>
                                @endforelse
                            </div>
                        </div>

                        @php($anyTicketAvailable = $event->tickets->contains(fn($ticket) => $ticket->status == 'available' && $ticket->stok > 0))

                        @if($event->status == 'published')
                            {{-- Tampilkan tombol hanya untuk guest atau user non-admin --}}
                            @if(!auth()->check() || auth()->user()->role !== 'admin')
                                <button type="button" @click="$dispatch('open-modal', { name: 'register-ticket' })" class="w-full py-3.5 bg-vent-primary text-white font-bold rounded-xl shadow-lg shadow-vent-primary/30 hover:bg-vent-primary-hover hover:shadow-vent-primary/50 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-60 disabled:shadow-none disabled:transform-none disabled:cursor-not-allowed" {{ !$anyTicketAvailable ? 'disabled' : '' }}>
                                    {{ !$anyTicketAvailable ? 'Tiket Habis' : 'Daftar Sekarang' }}
                                </button>
                            @endif
                        @else
                             <button type="button" class="w-full py-3.5 bg-slate-200 text-slate-600 font-bold rounded-xl cursor-not-allowed" disabled>
                                {{ $event->status == 'completed' ? 'Event Telah Berakhir' : 'Pendaftaran Ditutup' }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Component (Asumsikan Anda punya komponen modal blade/alpine) --}}
    {{-- Jika belum ada, gunakan wrapper sederhana ini --}}
    <x-vent.modal name="register-ticket" :title="'Daftar Event: ' . $event->title">
        @livewire('public.register-ticket-form', ['eventId' => $event->id, 'eventSlug' => $event->slug])
    </x-vent.modal>
</div>
