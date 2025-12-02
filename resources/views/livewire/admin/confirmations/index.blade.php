<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Konfirmasi Pembayaran</h3>
                    <p class="text-subtitle text-muted">Konfirmasi atau tolak pembayaran yang dikirim oleh peserta.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Konfirmasi Pembayaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <x-ui.card>
                    <x-slot name="header">
                        <h5 class="card-title mb-0">Menunggu Konfirmasi</h5>
                    </x-slot>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Peserta</th>
                                    <th>Event</th>
                                    <th>Total Bayar</th>
                                    <th>Tgl. Unggah</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $registration)
                                    <tr wire:key="{{ $registration->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $registration->user->name }}</td>
                                        <td>{{ Str::limit($registration->event->title, 30) }}</td>
                                        <td>Rp {{ number_format($registration->total_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $registration->updated_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $registration->payment_proof_path) }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-success" wire:click="confirmPayment({{ $registration->id }})" wire:confirm="Anda yakin ingin mengkonfirmasi pembayaran ini? E-tiket akan dibuat.">
                                                    Konfirmasi
                                                </button>
                                                <button class="btn btn-sm btn-danger" wire:click="rejectPayment({{ $registration->id }})" wire:confirm="Anda yakin ingin menolak pembayaran ini?">
                                                    Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada pembayaran yang menunggu konfirmasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $registrations->links() }}
                    </div>

                </x-ui.card>
            </div>
        </div>
    </div>
</div>
