<div>
    {{-- Step Indicator --}}
    <nav class="nav nav-pills nav-fill mb-4">
        <a class="nav-link {{ $currentStep == 1 ? 'active' : 'disabled' }}" href="#" wire:click.prevent="goToStep(1)">
            1. Info Dasar
        </a>
        <a class="nav-link {{ $currentStep == 2 ? 'active' : 'disabled' }}" href="#" wire:click.prevent="goToStep(2)">
            2. Tiket
        </a>
        <a class="nav-link {{ $currentStep == 3 ? 'active' : 'disabled' }}" href="#" wire:click.prevent="goToStep(3)">
            3. Pembicara
        </a>
        <a class="nav-link {{ $currentStep == 4 ? 'active' : 'disabled' }}" href="#" wire:click.prevent="goToStep(4)">
            4. Galeri
        </a>
    </nav>

    <form wire:submit="save">
        {{-- Step 1: Basic Info --}}
        <div class="{{ $currentStep == 1 ? 'd-block' : 'd-none' }}">
            {{-- Konten Step 1 tidak berubah --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3"><label for="title" class="form-label">Judul Event</label><input type="text" id="title" class="form-control @error('form.title') is-invalid @enderror" wire:model.blur="form.title">@error('form.title') <div class="invalid-feedback">{{ $message
                            }}</div> @enderror</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <div class="input-group">
                            <input type="text" id="slug" class="form-control @error('form.slug') is-invalid @enderror" wire:model.blur="form.slug">
                            @if($eventId)
                            <button class="btn btn-outline-secondary" type="button" wire:click="regenerateSlug" title="Regenerate slug from title">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            @endif
                            @error('form.slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3"><label for="description" class="form-label">Deskripsi</label><textarea id="description" class="form-control @error('form.description') is-invalid @enderror" wire:model.blur="form.description" rows="5"></textarea>@error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3"><label for="jadwal" class="form-label">Jadwal Event</label><input type="datetime-local" id="jadwal" class="form-control @error('form.jadwal') is-invalid @enderror" wire:model.blur="form.jadwal">@error('form.jadwal') <div class="invalid-feedback">{{
                            $message }}</div> @enderror</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3"><label for="banner_image" class="form-label">Gambar
                            Banner</label><input type="file" id="banner_image" class="form-control @error('form.banner_image') is-invalid @enderror" wire:model="form.banner_image">@error('form.banner_image') <div class="invalid-feedback">{{
                            $message }}</div> @enderror @if ($form->banner_image)<img src="{{ $form->banner_image->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-width: 150px;">@endif</div>
                </div>
            </div>
            <div class="form-group mb-3"><label for="location_name" class="form-label">Nama Lokasi</label><input type="text" id="location_name" class="form-control @error('form.location_name') is-invalid @enderror" wire:model.blur="form.location_name">@error('form.location_name') <div class="invalid-feedback">{{
                    $message }}</div> @enderror</div>
            <div class="form-group mb-3"><label for="location_address" class="form-label">Alamat Lokasi</label><textarea id="location_address" class="form-control @error('form.location_address') is-invalid @enderror" wire:model.blur="form.location_address" rows="3"></textarea>@error('form.location_address') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3"><label for="category_id" class="form-label">Kategori</label><select id="category_id" class="form-select @error('form.category_id') is-invalid @enderror" wire:model.blur="form.category_id">
                            <option value="">-- Pilih Kategori --</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach
                        </select>@error('form.category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3"><label for="status" class="form-label">Status</label><select id="status" class="form-select @error('form.status') is-invalid @enderror" wire:model.blur="form.status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>@error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2: Tickets --}}
        <div class="{{ $currentStep == 2 ? 'd-block' : 'd-none' }}">
            {{-- Konten Step 2 tidak berubah --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Manajemen Tiket</h5>
                <x-ui.button type="button" variant="outline-primary" size="sm" wire:click="addTicket"><i class="bi bi-plus-circle me-2"></i>Tambah Tiket Baru</x-ui.button>
            </div>
            <hr>
            @foreach ($form->tickets as $index => $ticket)
            <div class="row align-items-center mb-3" wire:key="ticket-{{ $index }}">
                <div class="col-md-4"><label class="form-label">Nama Tiket</label><input type="text" class="form-control @error('form.tickets.'.$index.'.name') is-invalid @enderror" wire:model="form.tickets.{{ $index }}.name" placeholder="e.g., VIP, Reguler">@error('form.tickets.'.$index.'.name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror</div>
                <div class="col-md-3"><label class="form-label">Harga (Rp)</label><input type="number" class="form-control @error('form.tickets.'.$index.'.harga') is-invalid @enderror" wire:model="form.tickets.{{ $index }}.harga" placeholder="e.g., 50000">@error('form.tickets.'.$index.'.harga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror</div>
                <div class="col-md-3"><label class="form-label">Stok</label><input type="number" class="form-control @error('form.tickets.'.$index.'.stok') is-invalid @enderror" wire:model="form.tickets.{{ $index }}.stok" placeholder="e.g., 100">@error('form.tickets.'.$index.'.stok') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror</div>
                <div class="col-md-2 text-end">@if (count($form->tickets) > 1)<x-ui.button type="button" variant="outline-danger" size="sm" wire:click="removeTicket({{ $index }})" class="mt-3">Hapus
                    </x-ui.button>@endif</div>
            </div>
            @endforeach
            @error('form.tickets') <div class="alert alert-danger mt-2">{{ $message }}</div> @enderror
        </div>

        {{-- Step 3: Speakers --}}
        <div class="{{ $currentStep == 3 ? 'd-block' : 'd-none' }}">
            <h5 class="mb-3">Pilih Pembicara</h5>

            <div class="form-group mb-3">
                <label for="speakers-select2" class="form-label">Pembicara</label>
                <x-ui.select2 id="speakers-select2" wire:model="form.selectedSpeakers" placeholder="Pilih satu atau lebih pembicara" multiple>
                    @foreach ($allSpeakers as $speaker)
                    <option value="{{ $speaker->id }}">{{ $speaker->name }} ({{ $speaker->title }})</option>
                    @endforeach
                </x-ui.select2>
            </div>

            @error('form.selectedSpeakers')
            <div class="text-danger -mt-3 mb-3">{{ $message }}</div>
            @enderror
            <small class="text-muted">Pilih satu atau lebih pembicara untuk event ini.</small>
        </div>

        {{-- Step 4: Gallery --}}
        <div class="{{ $currentStep == 4 ? 'd-block' : 'd-none' }}">

            {{-- Existing Gallery --}}
            @if ($form->existingGallery)
            <div class="mb-4">
                <h6 class="mb-3">Galeri Saat Ini</h6>
                <div class="row g-2">
                    @foreach ($form->existingGallery as $imagePath)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $imagePath) }}" class="img-thumbnail">
                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1" wire:click.prevent="removeExistingImage('{{ $imagePath }}')" title="Hapus gambar ini" style="width: 2rem; height: 2rem; line-height: 1;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <hr>
            @endif

            {{-- New Gallery Upload --}}
            <h6 class="mb-3">Tambah Gambar Baru</h6>
            <p class="text-muted">Unggah satu atau lebih gambar untuk ditambahkan ke galeri.</p>
            <x-filepond-upload wire:model="form.newGallery" multiple />
            @error('form.newGallery.*') <div class="text-danger mt-2">{{ $message }}</div> @enderror
        </div>


        {{-- Navigation Buttons --}}
        <div class="d-flex justify-content-between mt-4">
            <div>
                @if ($currentStep > 1)
                <x-ui.button type="button" variant="secondary" wire:click="previousStep">
                    Sebelumnya
                </x-ui.button>
                @endif
            </div>

            <div>
                <x-ui.button type="button" variant="light-secondary" @click="$dispatch('close-modal')">
                    Batal
                </x-ui.button>

                @if ($currentStep < $totalSteps) <x-ui.button type="button" variant="primary" class="ms-2" wire:click="nextStep">
                    Lanjutkan
                    </x-ui.button>
                    @else
                    <x-ui.button type="submit" variant="primary" class="ms-2" wire:loading.attr="disabled">
                        Simpan Event
                    </x-ui.button>
                    @endif
            </div>
        </div>
    </form>
</div>

