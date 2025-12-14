<div>
    <div class="form-group mb-3">
        <label for="name" class="form-label">Nama Kategori</label>
        {{-- PENTING: .live diperlukan agar updatedFormName di Parent tereksekusi --}}
        <input type="text" id="name" class="form-control @error('form.name') is-invalid @enderror"
            wire:model.live.debounce.500ms="form.name">
        @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" id="slug" class="form-control @error('form.slug') is-invalid @enderror"
            wire:model.blur="form.slug" {{ $isEditMode ? '' : 'readonly' }}>
        @error('form.slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
