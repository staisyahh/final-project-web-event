<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Event</h3>
                    <p class="text-subtitle text-muted">Kelola semua event yang ada di sistem.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Events</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <section class="section">
            <x-ui.card>
                <x-slot:title>Daftar Event</x-slot:title>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <x-ui.button
                            type="button"
                            variant="primary"
                            icon="bi bi-plus-circle"
                            wire:click="prepareCreate"
                        >
                            Tambah Event
                        </x-ui.button>
                    </div>
                </x-slot>

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari judul event..." wire:model.live.debounce.300ms="search">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="filterStatus">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                            <option value="trashed">Trashed</option>
                        </select>
                    </div>
                </div>

                <hr>

                {{-- Card-based Event List --}}
                <div class="row mt-4">
                    @forelse ($events as $event)
                        <div class="col-12 col-md-6 col-lg-4 mb-4" wire:key="event-card-{{ $event->id }}">
                            <div class="card h-100">
                                {{-- Card Image (clickable) --}}
                                <a href="{{ route('event.detail', $event->slug) }}">
                                    <img src="{{ $event->banner_url ?? 'https://via.placeholder.com/400x200.png?text=No+Image' }}" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($event->title) }}&background=random';">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    {{-- Status and Category (clickable) --}}
                                    <div class="mb-2">
                                        @php
                                            $statusInfo = match($event->trashed() ? 'trashed' : $event->status) {
                                                'published' => ['color' => 'success', 'text' => 'Published'],
                                                'draft' => ['color' => 'secondary', 'text' => 'Draft'],
                                                'archived' => ['color' => 'warning', 'text' => 'Archived'],
                                                'completed' => ['color' => 'info', 'text' => 'Completed'],
                                                'trashed' => ['color' => 'danger', 'text' => 'Trashed'],
                                                default => ['color' => 'light', 'text' => $event->status],
                                            };
                                        @endphp
                                        <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none">
                                            <span class="badge bg-light-{{ $statusInfo['color'] }}">{{ $statusInfo['text'] }}</span>
                                        </a>
                                        @if($event->category)
                                            <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none">
                                                <span class="badge bg-light-primary">{{ $event->category->name }}</span>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Title (clickable) --}}
                                    <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none text-dark">
                                        <h5 class="card-title">{{ Str::limit($event->title, 50) }}</h5>
                                    </a>

                                    {{-- Details (clickable) --}}
                                    <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none text-dark">
                                        <div class="text-muted small mt-2">
                                            <p class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $event->jadwal->format('d M Y, H:i') }} WIB</p>
                                            <p class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ Str::limit($event->location_name ?? 'Online', 30) }}</p>
                                        </div>
                                    </a>

                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-3">
                                        <div class="d-flex gap-3">
                                            <div class="fw-bold small" title="Jumlah Pendaftar">
                                                <i class="bi bi-people-fill me-1"></i>
                                                {{ $event->registrations_count }}
                                            </div>
                                            <div class="fw-bold small {{ $event->tickets_stock > 0 ? 'text-success' : 'text-danger' }}" title="Sisa Stok Tiket">
                                                <i class="bi bi-ticket-perforated-fill me-1"></i>
                                                {{ $event->tickets_stock ?? 0 }}
                                            </div>
                                        </div>

                                        {{-- Actions dropdown (NOT clickable to detail page) --}}
                                        <div class="dropstart">
                                            <button class="btn btn-sm btn-outline-secondary py-0 px-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" @click.stop="">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if ($event->trashed())
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="confirmRestore({{ $event->id }})"><i class="bi bi-arrow-counterclockwise me-2"></i>Restore</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="confirmForceDelete({{ $event->id }})"><i class="bi bi-trash-fill me-2"></i>Hapus Permanen</a></li>
                                                @else
                                                    <li><a class="dropdown-item" href="#" wire:click.prevent="prepareEdit({{ $event->id }})"><i class="bi bi-pencil-square me-2"></i>Edit</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="confirmDelete({{ $event->id }})"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light-info text-center">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Tidak ada data event ditemukan. Coba ubah filter atau buat event baru.
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </x-ui.card>
        </section>
    </div>

    {{-- Confirmation Modal --}}
    <x-ui.modal name="confirmation-modal" title="{{ $modalTitle }}">
        <p>{{ $modalMessage }}</p>

        <x-slot name="footer">
            <x-ui.button type="button" variant="light-secondary" @click="show = false">
                Batal
            </x-ui.button>
            <x-ui.button type="button" variant="danger" wire:click="handleModalAction" wire:loading.attr="disabled">
                Ya, Lanjutkan
            </x-ui.button>
        </x-slot>
    </x-ui.modal>

    {{-- Create/Edit Event Modal --}}
    <x-ui.modal name="event-form-modal" :title="$formModalTitle" size="xl">
        <livewire:admin.events.form :eventId="$editingEventId" wire:key="event-form-{{ $editingEventId }}" />
    </x-ui.modal>
</div>
