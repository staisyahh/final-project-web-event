<div>
    {{-- Karena kita menggunakan layout 'front', breadcrumb dan struktur halaman utama akan berbeda --}}

    {{-- Hero Section with Banner Image --}}
    <section class="hero-section" style="background-image: url('{{ $event->banner_url ? asset('storage/' . $event->banner_url) : 'https://via.placeholder.com/1200x400.png?text=Event+Banner' }}');">
        <div class="hero-overlay"></div>
        <div class="container text-center text-white position-relative">
            @if($event->category)
                <p class="hero-category">{{ $event->category->name }}</p>
            @endif
            <h1 class="hero-title">{{ $event->title }}</h1>
            <p class="hero-subtitle">Diselenggarakan oleh: {{ $event->organizer->name }}</p>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-5">
        <div class="container">
            <div class="row">
                {{-- Left Column (Main Details) --}}
                <div class="col-lg-8">
                    {{-- Description --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Deskripsi Event</h4>
                            <hr>
                            <p class="card-text" style="white-space: pre-wrap;">{{ $event->description }}</p>
                        </div>
                    </div>

                    {{-- Speakers --}}
                    @if($event->speakers->isNotEmpty())
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Pembicara</h4>
                                <hr>
                                <div class="row">
                                    @foreach ($event->speakers as $speaker)
                                        <div class="col-md-6 d-flex align-items-center mb-3">
                                            <img src="{{ $speaker->avatar_url ?? 'https://via.placeholder.com/80.png?text=' . substr($speaker->name, 0, 1) }}" alt="{{ $speaker->name }}" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $speaker->name }}</h6>
                                                <p class="text-muted mb-0">{{ $speaker->title }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Gallery --}}
                    @if($event->galleries->isNotEmpty())
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Galeri</h4>
                                <hr>
                                <div class="row g-2">
                                    @foreach ($event->galleries as $galleryImage)
                                        <div class="col-6 col-md-4">
                                            <a href="{{ asset('storage/' . $galleryImage->image_url) }}" data-gallery="event-gallery">
                                                <img src="{{ asset('storage/' . $galleryImage->image_url) }}" class="img-fluid rounded" alt="Galeri Event">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column (Sticky Info Card) --}}
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Detail & Tiket</h5>

                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-calendar-check-fill fs-4 me-3 text-primary"></i>
                                            <div>
                                                <strong>Tanggal & Waktu</strong>
                                                <p class="mb-0 text-muted">{{ $event->jadwal->format('l, d F Y, H:i') }} WIB</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-geo-alt-fill fs-4 me-3 text-primary"></i>
                                            <div>
                                                <strong>Lokasi</strong>
                                                <p class="mb-0 text-muted">{{ $event->location_name ?? 'Online' }}</p>
                                                @if($event->location_address)
                                                    <small class="text-muted">{{ $event->location_address }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <hr>
                                <h6 class="mb-3">Beli Tiket</h6>
                                @forelse ($event->tickets as $ticket)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>{{ $ticket->name }}</span>
                                        <span class="fw-bold">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <p class="text-muted text-center">Tiket tidak tersedia saat ini.</p>
                                @endforelse

                                <div class="d-grid mt-4">
                                    <x-ui.button type="button" variant="primary" size="lg"
                                        @click="$dispatch('open-modal', { name: 'register-ticket' })">
                                        Daftar Sekarang
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Some custom styles for the hero section --}}
    @push('styles')
    <style>
        .hero-section {
            position: relative;
            padding: 8rem 0;
            background-size: cover;
            background-position: center;
            color: #fff;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .hero-category {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            background-color: rgba(255,255,255,0.15);
            display: inline-block;
            padding: 0.25rem 1rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 300;
        }
    </style>
    @endpush

    {{-- Register Ticket Modal --}}
    <x-ui.modal name="register-ticket" :title="'Daftar Event: ' . $event->title" size="lg">
        @livewire('public.register-ticket-form', ['eventId' => $event->id, 'eventSlug' => $event->slug])
    </x-ui.modal>
</div>
