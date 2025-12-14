<form wire:submit="register" class="space-y-5">

    {{-- Input Select Tiket --}}
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Jenis Tiket</label>
        <select wire:model.live="selectedTicketId" class="w-full rounded-xl border-slate-300 focus:border-vent-primary focus:ring focus:ring-vent-primary/20 py-2 px-3 shadow-sm transition">
            <option value="">-- Pilih Tiket --</option>
            @foreach($tickets as $ticket)
            <option value="{{ $ticket->id }}" @disabled($ticket->stok == 0)>
                {{ $ticket->name }} — Rp {{ number_format($ticket->harga, 0, ',', '.') }}
            </option>
            @endforeach
        </select>
        @error('selectedTicketId') <span class="text-xs text-vent-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Input Quantity --}}
    @if($selectedTicketId)
    <x-vent.input type="number" label="Jumlah Tiket" wire:model.live="quantity" min="1" :error="$errors->first('quantity')" />
    @endif

    {{-- Total Price Card --}}
    <div class="bg-vent-surface rounded-xl p-4 flex justify-between items-center border border-blue-100">
        <h5 class="text-slate-600 font-medium text-sm">Total:</h5>
        <h4 class="text-2xl font-bold text-vent-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
    </div>

    @if ($errorMessage)
    <div class="p-3 bg-red-50 text-vent-danger text-sm rounded-lg border border-red-100">
        {{ $errorMessage }}
    </div>
    @endif

    {{-- Action Button --}}
    <div class="pt-2">
        <x-vent.button type="submit" variant="primary" size="lg" fullWidth :disabled="!$selectedTicketId || $quantity <= 0">
            Daftar & Lanjutkan Pembayaran
        </x-vent.button>
    </div>
</form>
