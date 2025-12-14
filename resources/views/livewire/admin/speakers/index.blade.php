<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Speaker</h3>
                    <p class="text-subtitle text-muted">Kelola semua speaker yang ada di sistem.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Speaker</li>
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
                <x-ui.card title="Data Pembicara">
                    <x-slot name="header">
                        <div class="d-flex justify-content-between align-items-center">
                            <x-ui.button type="button" variant="primary" icon="bi bi-plus-circle" wire:click="add">
                                Tambah Pembicara
                            </x-ui.button>
                            <div class="w-50">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="Cari berdasarkan nama atau jabatan...">
                            </div>
                        </div>
                    </x-slot>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Avatar</th>
                                    <th>Nama Pembicara</th>
                                    <th>Jabatan</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($speakers as $speaker)
                                    <tr wire:key="{{ $speaker->id }}">
                                        <td>
                                            <div class="avatar avatar-lg">
                                                <img src="{{ $speaker->avatar_url ?  $speaker->avatar_url : asset('assets/images/faces/1.jpg') }}" alt="{{ $speaker->name }}">
                                            </div>
                                        </td>
                                        <td>{{ $speaker->name }}</td>
                                        <td>{{ $speaker->title }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <x-ui.button type="button" variant="outline-primary" size="sm" wire:click="edit({{ $speaker->id }})">
                                                    Edit
                                                </x-ui.button>
                                                <x-ui.button
                                                    type="button"
                                                    variant="outline-danger"
                                                    size="sm"
                                                    x-data
                                                    @click="$dispatch('swal:confirm', {
                                                        title: 'Hapus Pembicara?',
                                                        text: 'Anda yakin ingin menghapus pembicara ini?',
                                                        icon: 'warning',
                                                        onConfirm: {
                                                            event: 'delete-speaker-confirmed',
                                                            params: { id: {{ $speaker->id }} }
                                                        }
                                                    })"
                                                >
                                                    Hapus
                                                </x-ui.button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <p class="my-3">
                                                @if ($search)
                                                    Tidak ada pembicara yang cocok dengan pencarian "{{ $search }}".
                                                @else
                                                    Data pembicara belum tersedia.
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($speakers && $speakers->hasPages())
                        <div class="mt-4">
                            {{ $speakers->links() }}
                        </div>
                    @endif
                </x-ui.card>
            </div>
        </section>
    </div>

    {{-- Speaker Modal --}}
    <x-ui.modal name="speaker-modal" title="{{ $isEditMode ? 'Edit' : 'Tambah' }} Pembicara">
        <form wire:submit="save">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama Pembicara</label>
                        <input type="text" id="name" class="form-control @error('form.name') is-invalid @enderror" wire:model.blur="form.name">
                        @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="title" class="form-label">Jabatan/Gelar</label>
                        <input type="text" id="title" class="form-control @error('form.title') is-invalid @enderror" wire:model.blur="form.title" placeholder="e.g., CEO, Lead Developer">
                        @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="avatar" class="form-label">Avatar</label>
                        <div
                            wire:ignore
                            x-data
                            x-init="
                                FilePond.create($refs.input, {
                                    credits: false,
                                    storeAsFile: true,
                                    labelIdle: `<i class='bi bi-upload fs-2'></i><br>Pilih file atau seret ke sini`,
                                    server: {
                                        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                            @this.upload('form.avatar', file, load, error, progress)
                                        },
                                        revert: (filename, load) => {
                                            @this.removeUpload('form.avatar', filename, load)
                                        },
                                    },
                                    @if($isEditMode && $form->speaker?->avatar_url)
                                    files: [{
                                        source: '{{ $form->speaker->avatar_url }}',
                                        options: {
                                            type: 'local'
                                        }
                                    }]
                                    @endif
                                });
                            "
                        >
                            <input type="file" x-ref="input" />
                        </div>
                        <small class="text-muted">Max file: 2MB. Tipe: JPG, PNG.</small>
                        @error('form.avatar') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="bio" class="form-label">Bio Singkat</label>
                <textarea id="bio" class="form-control @error('form.bio') is-invalid @enderror" wire:model.blur="form.bio" rows="4"></textarea>
                @error('form.bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </form>

        <x-slot name="footer">
            <x-ui.button type="button" variant="light-secondary" wire:click="closeModal">
                Batal
            </x-ui.button>
            <x-ui.button type="button" variant="primary" class="ms-2" wire:click="save" wire:loading.attr="disabled">
                Simpan
            </x-ui.button>
        </x-slot>
    </x-ui.modal>
</div>

