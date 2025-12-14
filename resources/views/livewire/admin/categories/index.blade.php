<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Kategori</h3>
                    <p class="text-subtitle text-muted">Kelola semua kategori event.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Kategori</h5>
                        <x-ui.button type="button" variant="primary" icon="bi bi-plus-circle" wire:click="create">
                            Tambah Kategori
                        </x-ui.button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th class="text-center">Jumlah Event</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                <tr wire:key="category-{{ $category->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td class="text-center">{{ $category->events_count }}</td>
                                    <td class="text-center">
                                        <x-ui.button type="button" variant="outline-primary" size="sm"
                                            wire:click="edit({{ $category->id }})">
                                            Edit
                                        </x-ui.button>
                                        <x-ui.button type="button" variant="outline-danger" size="sm"
                                            wire:click="confirmDelete({{ $category->id }})">
                                            Delete
                                        </x-ui.button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data kategori ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- MODAL FORM --}}
    <x-ui.modal name="modal-category" :title="$isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru'">
        <form wire:submit.prevent="save">
            {{-- Include form input di sini --}}
            @include('livewire.admin.categories.category-form')

            <div class="modal-footer p-0 mt-3">
                <x-ui.button type="button" variant="light-secondary" x-on:click="$dispatch('close-modal')">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    Simpan
                </x-ui.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- MODAL DELETE --}}
    <x-ui.modal name="modal-delete" title="Konfirmasi Hapus">
        @if($deletingCategory)
        <p>Anda yakin ingin menghapus kategori <strong>"{{ $deletingCategory->name }}"</strong>?</p>
        @endif

        <x-slot name="footer">
            <x-ui.button type="button" variant="light-secondary" x-on:click="$dispatch('close-modal')">
                Batal
            </x-ui.button>
            <x-ui.button type="button" variant="danger" wire:click="delete">
                Ya, Hapus
            </x-ui.button>
        </x-slot>
    </x-ui.modal>
</div>
