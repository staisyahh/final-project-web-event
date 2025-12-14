<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Email Reminder</h3>
                    <p class="text-subtitle text-muted">Memicu pengiriman email pengingat H-7 dan H-1 secara manual.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Email Reminder</li>
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
                <x-slot:title>Status Pengiriman</x-slot:title>

                <div class="text-center">
                    @if ($lastRunTimestamp)
                        <div class="alert alert-light-info">
                            <h4 class="alert-heading">Proses Terakhir Dijalankan</h4>
                            <p class="fs-5">{{ $lastRunTimestamp->format('d F Y, H:i:s') }}</p>
                            <hr>
                            <p class="mb-0">
                                @if ($buttonDisabled)
                                    Anda sudah menjalankan proses untuk hari ini. Tombol akan aktif kembali besok.
                                @else
                                    Anda dapat menjalankan proses pengiriman lagi.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="alert alert-light-secondary">
                            <h4 class="alert-heading">Status</h4>
                            <p>Proses pengiriman email reminder belum pernah dijalankan melalui panel ini.</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <x-ui.button 
                            type="button" 
                            variant="primary" 
                            size="lg" 
                            wire:click="runReminders"
                            wire:loading.attr="disabled"
                            wire:target="runReminders"
                            :disabled="$buttonDisabled"
                            >
                            <span wire:loading.remove wire:target="runReminders">
                                <i class="bi bi-send-fill me-2"></i>
                                Jalankan Pengiriman Reminder Sekarang
                            </span>
                            <span wire:loading wire:target="runReminders">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Memproses...
                            </span>
                        </x-ui.button>
                    </div>
                    <small class="d-block text-muted mt-3">
                        * Proses ini akan memasukkan email ke dalam antrian. Pastikan queue worker Anda berjalan untuk mengirim email.
                    </small>
                </div>

            </x-ui.card>
        </section>
    </div>
</div>