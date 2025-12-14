<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tiket Saya</h3>
                    <p class="text-subtitle text-muted">Semua tiket event yang pernah Anda daftarkan.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" wire:navigate>Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tiket Saya</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <div class="row">
            @forelse ($registrations as $registration)
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-content position-relative">
                        @if ($registration->event->trashed())
                            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center rounded-top" style="background-color: rgba(0,0,0,0.6); z-index: 2;">
                                <h4 class="text-white fw-bold">EVENT DIBATALKAN</h4>
                            </div>
                        @endif
                        <img src="{{ $registration->event->banner_url ?? 'https://via.placeholder.com/400x200.png?text=Event+Banner' }}"
                            class="card-img-top img-fluid" alt="event-banner"
                            style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($registration->event->title) }}&background=random';">
                        <div class="card-body">
                            <span
                                class="badge {{
                                    [
                                        'pending_payment' => 'bg-light-warning',
                                        'confirmed' => 'bg-light-success',
                                        'cancelled' => 'bg-light-danger'
                                    ][$registration->status] ?? 'bg-light-secondary'
                                }} text-dark-emphasis">
                                {{ str_replace('_', ' ', Str::title($registration->status)) }}
                            </span>

                            <h5 class="card-title mt-3 mb-2">{{ $registration->event->title }}</h5>
                            <p class="card-text text-muted small">
                                <i class="bi bi-calendar-check me-2"></i>{{ $registration->event->jadwal->format('d M Y, H:i') }} WIB<br>
                                <i class="bi bi-geo-alt me-2"></i>{{ $registration->event->location_name }}
                            </p>
                        </div>
                    </div>

                    {{-- Tampilkan E-Tiket atau Tombol Aksi berdasarkan Status --}}
                    @if ($registration->status == 'confirmed')
                        @if ($registration->eTickets->isNotEmpty())
                            {{-- TIKET SUKSES: Tampilkan daftar tiket untuk di-download --}}
                            <div class="card-body py-0">
                                <p class="mb-2 text-muted small">E-Ticket Anda:</p>
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach ($registration->eTickets as $eTicket)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span style="font-family: monospace; font-size: 0.9rem;">{{ $eTicket->ticket_code }}</span>
                                        <x-ui.button type="link" :href="route('member.ticket.download', $eTicket)" :navigate="false" variant="outline-primary" size="sm" icon="bi bi-download">
                                            Unduh
                                        </x-ui.button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Opsi Review Jika Event Selesai --}}
                        @if($registration->event->status == 'completed')
                            <div class="card-footer border-top text-center bg-light">
                                @if ($registration->event->userReview)
                                    <div class="text-muted small">
                                        <span>Rating Anda:</span>
                                        <span class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $registration->event->userReview->rating >= $i ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </span>
                                    </div>
                                @else
                                    <x-ui.button type="button" variant="outline-primary" size="sm"
                                        wire:click="selectEventForReview({{ $registration->event->id }})">
                                        Beri Ulasan
                                    </x-ui.button>
                                @endif
                            </div>
                        @endif

                    @elseif ($registration->status == 'pending_payment')
                        {{-- MENUNGGU PEMBAYARAN --}}
                        <div class="card-footer border-top text-center">
                            @if ($registration->payment_proof_path)
                                <span class="text-muted">Menunggu Konfirmasi Admin</span>
                            @else
                                <x-ui.button type="button" variant="primary" size="sm"
                                    wire:click="selectRegistrationForUpload({{ $registration->id }})">
                                    Unggah Bukti Bayar
                                </x-ui.button>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-light-info">
                    <h4 class="alert-heading">Belum Ada Tiket</h4>
                    <p>Anda belum pernah mendaftar event apapun. Ayo <a href="{{ route('home') }}" wire:navigate>cari
                            event</a> seru sekarang!</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Upload Payment Proof Modal --}}
        <x-ui.modal name="upload-proof-modal" title="Unggah Bukti Pembayaran">
            <form wire:submit="savePaymentProof" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="mb-3">
                    <label for="paymentProofFile" class="form-label">Pilih Bukti Pembayaran (Gambar)</label>
                    <input type="file" wire:model="paymentProofFile" id="paymentProofFile" class="form-control @error('paymentProofFile') is-invalid @enderror">
                    @error('paymentProofFile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Progress Bar --}}
                <div x-show="uploading" class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                </div>

                <div class="alert alert-info small" role="alert">
                    Pastikan bukti pembayaran jelas dan berisi informasi transaksi (bank, tanggal, jumlah). Ukuran
                    maksimal
                    2MB.
                </div>

                <div class="d-grid mt-4">
                    <x-ui.button type="submit" variant="primary" size="lg" x-bind:disabled="uploading || {{ json_encode(!$paymentProofFile) }}">
                        <span wire:loading.remove wire:target="savePaymentProof,paymentProofFile">Unggah Bukti</span>
                        <span wire:loading wire:target="savePaymentProof,paymentProofFile">Mengunggah...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.modal>

        {{-- Submit Review Modal --}}
        <x-ui.modal name="review-modal" title="Beri Ulasan">
            @if($selectedEventForReviewId)
            @livewire('member.submit-review-form', ['eventId' => $selectedEventForReviewId],
            key('review-form-'.$selectedEventForReviewId))
            @endif
        </x-ui.modal>
    </div>

    @push('scripts')
    <script>
        // No longer needed, QR is not displayed directly on this page anymore.
        // The script can be removed to keep things clean.
    </script>
    @endpush

