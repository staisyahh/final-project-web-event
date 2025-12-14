<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Event Favorit</h3>
                    <p class="text-subtitle text-muted">Semua event yang Anda simpan untuk dilihat nanti.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Favorit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <div class="row">
            @forelse ($events as $event)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4" wire:key="event-card-{{ $event->id }}">
                    <div class="card event-card shadow-sm h-100 position-relative">
                        {{-- Tombol Bookmark --}}
                        <button
                            type="button"
                            class="btn btn-icon rounded-circle position-absolute top-0 end-0 m-2 z-2 btn-danger"
                            wire:click="toggleBookmark({{ $event->id }})"
                            title="Hapus dari favorit"
                        >
                            <i class="bi bi-heart-fill"></i>
                        </button>

                        {{-- Badge Tanggal --}}
                        <div class="date-badge">
                            <div class="day">{{ $event->jadwal->format('d') }}</div>
                            <div class="month">{{ strtoupper($event->jadwal->format('M')) }}</div>
                        </div>

                        {{-- Image --}}
                        <a href="{{ route('event.detail', $event->slug) }}">
                            <img src="{{ $event->banner_url ?? 'https://via.placeholder.com/400x200.png?text=No+Image' }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                        </a>

                        <div class="card-body d-flex flex-column">
                            {{-- Kategori --}}
                            @if($event->category)
                                <span class="badge bg-light-primary mb-2" style="align-self: flex-start;">{{ $event->category->name }}</span>
                            @endif

                            {{-- Title --}}
                            <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title stretched-link">{{ Str::limit($event->title, 50) }}</h5>
                            </a>

                            {{-- Details --}}
                            <div class="text-muted small mt-2">
                                <p class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $event->jadwal->format('d M Y, H:i') }} WIB</p>
                                <p class="mb-0"><i class="bi bi-geo-alt me-2"></i>{{ Str::limit($event->location_name ?? 'Online', 30) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light-info text-center">
                        <h4 class="alert-heading">Belum Ada Favorit</h4>
                        <p>Anda belum menyimpan event apapun. Mulai <a href="{{ route('home') }}" wire:navigate>jelajahi event</a> dan tandai yang Anda suka!</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
