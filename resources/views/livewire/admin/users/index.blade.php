<div>
    {{-- Page Heading --}}
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Pengguna</h3>
                    <p class="text-subtitle text-muted">Kelola semua pengguna yang terdaftar di sistem.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
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
                            <div class="col-md-8">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="Cari berdasarkan Nama atau Email...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" wire:model.live="role">
                                    <option value="">Semua Peran</option>
                                    <option value="admin">Admin</option>
                                    <option value="peserta">Peserta</option>
                                </select>
                            </div>
                        </div>
                    </x-slot>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Tgl. Bergabung</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr wire:key="{{ $user->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    {{-- Placeholder for avatar, assuming no avatar URL in users table --}}
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="Avatar">
                                                </div>
                                                <p class="font-bold ms-3 mb-0">{{ $user->name }}</p>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $roleClass = $user->role === 'admin' ? 'badge bg-light-primary' : 'badge bg-light-secondary';
                                            @endphp
                                            <span class="{{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <x-ui.button type="button" variant="outline-info" size="sm" wire:click="viewRegistrationHistory({{ $user->id }})" title="Lihat Riwayat">
                                                    <i class="bi bi-receipt"></i>
                                                </x-ui.button>

                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Peran" @if($user->id === 1) disabled @endif>
                                                        <i class="bi bi-person-fill-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($user->role === 'peserta')
                                                            <li><a class="dropdown-item" href="#" x-data @click.prevent="$dispatch('swal:confirm', {
                                                                title: 'Jadikan Admin?',
                                                                text: 'Anda yakin ingin menjadikan {{ addslashes($user->name) }} sebagai Admin?',
                                                                icon: 'warning',
                                                                onConfirm: { event: 'change-user-role', params: { id: {{ $user->id }}, role: 'admin' } }
                                                            })">Jadikan Admin</a></li>
                                                        @elseif($user->role === 'admin')
                                                             <li><a class="dropdown-item" href="#" x-data @click.prevent="$dispatch('swal:confirm', {
                                                                title: 'Jadikan Peserta?',
                                                                text: 'Anda yakin ingin menjadikan {{ addslashes($user->name) }} sebagai Peserta?',
                                                                icon: 'warning',
                                                                onConfirm: { event: 'change-user-role', params: { id: {{ $user->id }}, role: 'peserta' } }
                                                            })">Jadikan Peserta</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="my-3">Data pengguna tidak ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users && $users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </x-ui.card>
            </div>
        </section>

        {{-- User History Modal --}}
        @if($selectedUser)
        <x-ui.modal name="user-history-modal" title="Riwayat Registrasi: {{ $selectedUser->name }}" size="xl">
            @if($selectedUser->registrations->isEmpty())
                <p class="text-center my-4">Pengguna ini belum pernah melakukan registrasi.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Reg.</th>
                                <th>Event</th>
                                <th>Tiket</th>
                                <th>Tgl. Pesan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedUser->registrations as $reg)
                                <tr>
                                    <td>#{{ $reg->id }}</td>
                                    <td>{{ $reg->event->title }}</td>
                                    <td>{{ $reg->ticket->name }} ({{ $reg->jumlah_tiket }}x)</td>
                                    <td>{{ $reg->created_at->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($reg->status) {
                                                'pending_payment' => 'badge bg-light-warning',
                                                'confirmed' => 'badge bg-light-success',
                                                'cancelled' => 'badge bg-light-danger',
                                                default => 'badge bg-light-secondary',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $reg->status)) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <x-slot name="footer">
                <x-ui.button type="button" variant="light-secondary" wire:click="closeModal">
                    Tutup
                </x-ui.button>
            </x-slot>
        </x-ui.modal>
        @endif
    </div>
</div>
