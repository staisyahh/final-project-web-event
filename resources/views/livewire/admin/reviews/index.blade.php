<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Review</h3>
                    <p class="text-subtitle text-muted">Kelola semua review dari pengguna untuk setiap event.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Review</li>
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
                <x-ui.card>
                    <x-slot name="header">
                        <h5 class="card-title">Filter & Pencarian</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="Cari berdasarkan Event, Pengguna, atau Komentar...">
                            </div>
                        </div>
                    </x-slot>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Pengguna</th>
                                    <th>Rating</th>
                                    <th>Komentar</th>
                                    <th>Tgl. Dibuat</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                    <tr wire:key="{{ $review->id }}">
                                        <td>
                                            <p class="font-bold mb-0">{{ $review->event->title ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'N A') }}&background=random" alt="Avatar">
                                                </div>
                                                <p class="font-bold ms-3 mb-0">{{ $review->user->name ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-warning">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @endfor
                                                @for ($i = 5 - $review->rating; $i > 0; $i--)
                                                    <i class="bi bi-star text-warning"></i>
                                                @endfor
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($review->comment, 50) }}</td>
                                        <td>{{ $review->created_at->format('d M Y') }}</td>
                                        <td>
                                            <x-ui.button type="button" variant="outline-danger" size="sm" x-data @click.prevent="$dispatch('swal:confirm', {
                                                title: 'Hapus Review?',
                                                text: 'Anda yakin ingin menghapus review ini secara permanen?',
                                                icon: 'warning',
                                                onConfirm: { event: 'delete-review', params: {{ $review->id }} }
                                            })">
                                                <i class="bi bi-trash-fill"></i>
                                            </x-ui.button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <p class="my-3">Data review tidak ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reviews && $reviews->hasPages())
                        <div class="mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </x-ui.card>
            </div>
        </section>
    </div>
</div>
