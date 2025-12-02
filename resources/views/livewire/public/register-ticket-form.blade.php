<form wire:submit="register">
    <div class="mb-3">
        <label for="selectedTicket" class="form-label">Pilih Jenis Tiket</label>
        <select wire:model.live="selectedTicketId" id="selectedTicket" class="form-select @error('selectedTicketId') is-invalid @enderror">
            <option value="">-- Pilih Tiket --</option>
            @forelse($tickets as $ticket)
                <option value="{{ $ticket->id }}" @if($ticket->stok == 0) disabled @endif>
                    {{ $ticket->name }} (Rp {{ number_format($ticket->harga, 0, ',', '.') }})
                    @if($ticket->stok > 0)
                        - Sisa: {{ $ticket->stok }}
                    @else
                        - HABIS
                    @endif
                </option>
            @empty
                <option value="" disabled>Tidak ada tiket tersedia</option>
            @endforelse
        </select>
        @error('selectedTicketId') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @if($selectedTicketId)
    <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah Tiket</label>
        <input type="number" wire:model.live="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" max="{{ $tickets->where('id', $selectedTicketId)->first()->stok ?? 1 }}">
        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Total Pembayaran:</h5>
        <h4 class="mb-0 text-primary fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
    </div>

    @if ($errorMessage)
        <div class="alert alert-danger">{{ $errorMessage }}</div>
    @endif

    <div class="d-grid gap-2 mt-4">
        <x-ui.button type="submit" variant="primary" size="lg" :disabled="!$selectedTicketId || $quantity <= 0">
            Daftar & Lanjutkan Pembayaran
        </x-ui.button>
    </div>
</form>
