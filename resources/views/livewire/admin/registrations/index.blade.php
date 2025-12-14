<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Registrasi</h3>
                    <p class="text-subtitle text-muted">Kelola semua registrasi/pesanan tiket yang masuk.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Registrasi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <x-ui.card>
                    <x-slot name="header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Filter & Pencarian</h5>
                            <x-ui.button type="button" variant="outline-success" wire:click="exportExcel">
                                <span wire:loading.remove wire:target="exportExcel">
                                    <i class="bi bi-file-earmark-excel-fill me-2"></i>
                                    Export Excel
                                </span>
                                <span wire:loading wire:target="exportExcel">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Exporting...
                                </span>
                            </x-ui.button>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="Cari ID atau Nama...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" wire:model.live="event_id">
                                    <option value="">Semua Event</option>
                                    @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" wire:model.live="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending_payment">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" wire:model.live="start_date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" wire:model.live="end_date">
                            </div>
                        </div>
                    </x-slot>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event</th>
                                    <th>Peserta</th>
                                    <th>Tiket</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tgl. Registrasi</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $reg)
                                <tr wire:key="{{ $reg->id }}">
                                    <td>#{{ $reg->id }}</td>
                                    <td>{{ Str::limit($reg->event->title, 30) }}</td>
                                    <td>{{ $reg->user->name }}</td>
                                    <td>{{ $reg->ticket->name }} ({{ $reg->jumlah_tiket }}x)</td>
                                    <td>Rp {{ number_format($reg->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                        $statusClass = match($reg->status) {
                                        'pending_payment' => 'badge bg-light-warning',
                                        'confirmed' => 'badge bg-light-success',
                                        'cancelled' => 'badge bg-light-danger',
                                        default => 'badge bg-light-secondary',
                                        };
                                        $statusText = match($reg->status) {
                                        'pending_payment' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'cancelled' => 'Cancelled',
                                        default => 'Unknown',
                                        };
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ $reg->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-ui.button type="button" variant="outline-info" size="sm" wire:click="showDetail({{ $reg->id }})">
                                                <i class="bi bi-eye-fill"></i>
                                            </x-ui.button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p class="my-3">Data registrasi tidak ditemukan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($registrations && $registrations->hasPages())
                    <div class="mt-4">
                        {{ $registrations->links() }}
                    </div>
                    @endif
                </x-ui.card>
            </div>
        </section>

        {{-- Registration Detail Modal --}}
        @if($selectedRegistration)
        <x-ui.modal name="registration-detail-modal" title="Detail Registrasi #{{ $selectedRegistration->id }}">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="w-25">ID Registrasi</th>
                            <td>#{{ $selectedRegistration->id }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $selectedRegistration->created_at->format('d M Y, H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                $modalStatusClass = match($selectedRegistration->status) {
                                'pending_payment' => 'badge bg-warning',
                                'confirmed' => 'badge bg-success',
                                'cancelled' => 'badge bg-danger',
                                default => 'badge bg-secondary',
                                };
                                $modalStatusText = match($selectedRegistration->status) {
                                'pending_payment' => 'Pending Payment',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                default => 'Unknown',
                                };
                                @endphp
                                <span class="{{ $modalStatusClass }}">{{ $modalStatusText }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center bg-light">Detail Event</th>
                        </tr>
                        <tr>
                            <th>Nama Event</th>
                            <td>{{ $selectedRegistration->event->title }}</td>
                        </tr>
                        <tr>
                            <th>Jadwal</th>
                            <td>{{ $selectedRegistration->event->jadwal->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center bg-light">Detail Peserta</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $selectedRegistration->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $selectedRegistration->user->email }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center bg-light">Detail Tiket</th>
                        </tr>
                        <tr>
                            <th>Jenis Tiket</th>
                            <td>{{ $selectedRegistration->ticket->name }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ $selectedRegistration->jumlah_tiket }} tiket</td>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <td class="font-bold">Rp {{ number_format($selectedRegistration->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <x-slot name="footer">
                <x-ui.button type="button" variant="light-secondary" wire:click="closeModal">
                    Tutup
                </x-ui.button>
            </x-slot>
        </x-ui.modal>
        @endif
    </div>
</div>

