<form wire:submit="saveReview">
    <div class="mb-3">
        <label class="form-label">Rating Anda</label>
        <div class="d-flex justify-content-center fs-2" style="cursor: pointer;">
            @for ($i = 1; $i <= 5; $i++)
                <i 
                    class="bi {{ $rating >= $i ? 'bi-star-fill text-warning' : 'bi-star text-warning' }}" 
                    wire:click="$set('rating', {{ $i }})"
                ></i>
            @endfor
        </div>
        @error('rating') <div class="text-danger text-center mt-1 small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="comment" class="form-label">Ulasan Anda (Opsional)</label>
        <textarea wire:model="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" placeholder="Bagaimana pengalaman Anda di event ini?"></textarea>
        @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-grid mt-4">
        <x-ui.button type="submit" variant="primary" size="lg">
            Kirim Ulasan
        </x-ui.button>
    </div>
</form>
