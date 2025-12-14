<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Queries\Admin\UserQuery;
use App\Services\Admin\UserService;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', keep: true)]
    public string $search = '';

    #[Url(keep: true)]
    public string $role = '';

    public ?User $selectedUser = null;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updating($key)
    {
        if (in_array($key, ['search', 'role'])) {
            $this->resetPage();
        }
    }

    public function viewRegistrationHistory(User $user)
    {
        $this->selectedUser = $user->load(['registrations.event', 'registrations.ticket']);
        $this->dispatch('open-modal', name: 'user-history-modal');
    }

    public function closeModal()
    {
        $this->selectedUser = null;
        $this->dispatch('close-modal');
    }

    #[On('change-user-role')]
    public function changeRole($params, UserService $userService)
    {
        try {
            $userService->changeRole($params['id'], $params['role']);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => 'Peran Pengguna Berhasil Diperbarui!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Gagal Memperbarui Peran!',
                'text' => $e->getMessage(),
            ]);
        }
    }
    
    public function render(UserQuery $userQuery)
    {
        $filters = [
            'search' => $this->search,
            'role' => $this->role,
        ];

        $users = $userQuery->getPaginated($filters);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }
}
