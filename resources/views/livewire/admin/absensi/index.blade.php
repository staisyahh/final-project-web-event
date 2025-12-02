<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Absensi / Check-in</h3>
                    <p class="text-subtitle text-muted">Pindai QR Code pada e-tiket peserta untuk melakukan check-in.
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Absensi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-8">
                <x-ui.card>
                    <x-slot name="header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">QR Code Scanner</h5>
                            <div class="w-50">
                                <select class="form-select" wire:model.live="selectedEventId" @if(empty($events))
                                    disabled @endif>
                                    <option value="">-- Pilih Event --</option>
                                    @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </x-slot>

                    {{-- QR Scanner Area --}}
                    {{-- PENTING: wire:ignore wajib ada agar Livewire tidak mereset div ini saat select berubah --}}
                    <div wire:ignore id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results"></div>

                </x-ui.card>
            </div>
            <div class="col-12 col-lg-4">
                <x-ui.card title="Hasil Scan">
                    <div id="feedback-box"
                        class="d-flex align-items-center justify-content-center text-center p-4 rounded"
                        style="height: 200px; transition: background-color 0.3s ease;"
                        x-data="{ status: @entangle('feedbackStatus') }" :class="{
                            'bg-light-secondary': status === 'info',
                            'bg-light-success': status === 'success',
                            'bg-light-warning': status === 'warning',
                            'bg-light-danger': status === 'error',
                        }">
                        <div>
                            <p class="fs-5 mb-0" wire:loading.remove>
                                {{ $feedbackMessage ?: 'Arahkan kamera ke QR Code tiket...' }}
                            </p>
                            <div wire:loading>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memproses...</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        // 1. Variabel Global untuk menyimpan instance scanner
        let html5QrcodeScanner = null;

        // 2. FLAG PENTING: Mencegah fungsi startScanner jalan 2x bersamaan
        let isInitializing = false;

        function onScanSuccess(decodedText, decodedResult) {
            console.log("Scan success:", decodedText);
            Livewire.dispatch('qrCodeScanned', { code: decodedText });
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        async function startScanner() {
            const readerElement = document.getElementById('qr-reader');

            // Cek element dan library
            if (!readerElement) return;
            if (typeof Html5QrcodeScanner === 'undefined') {
                console.error('Html5QrcodeScanner belum dimuat.');
                return;
            }

            // --- PENCEGAHAN DOUBLE RENDER ---

            // Cek 1: Jika sedang proses inisialisasi, STOP. Jangan lanjut.
            if (isInitializing) {
                console.log("Scanner sedang diinisialisasi, membatalkan perintah ganda.");
                return;
            }

            // Set flag menjadi TRUE (sedang sibuk)
            isInitializing = true;

            try {
                // Cek 2: Jika scanner scanner sudah aktif, matikan dulu
                if (html5QrcodeScanner) {
                    try {
                        await html5QrcodeScanner.clear();
                    } catch (e) {
                        console.log("Pembersihan scanner lama:", e);
                    }
                }

                // Cek 3 (NUCLEAR OPTION): Hapus paksa isi elemen HTML sampai bersih
                // Ini menghapus sisa-sisa elemen "hantu" yang menyebabkan double camera
                while (readerElement.firstChild) {
                    readerElement.removeChild(readerElement.firstChild);
                }

                // Beri jeda sedikit (100ms) agar DOM benar-benar bersih sebelum render ulang
                await new Promise(r => setTimeout(r, 100));

                // Mulai Scanner Baru
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    false
                );

                html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            } catch (err) {
                console.error("Error starting scanner:", err);
            } finally {
                // Setelah selesai semua proses, kembalikan status menjadi FALSE (tidak sibuk)
                isInitializing = false;
            }
        }

        // --- Event Listeners ---

        // Gunakan Livewire Navigated (ini yang utama untuk SPA)
        document.addEventListener('livewire:navigated', startScanner);

        // Cleanup saat pindah halaman
        document.addEventListener('livewire:navigating', () => {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(e => {});
                html5QrcodeScanner = null;
            }
        });
    </script>
    @endpush
</div>
