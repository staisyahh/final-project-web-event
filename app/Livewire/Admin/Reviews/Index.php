<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Queries\Admin\ReviewQuery;
use App\Services\Admin\ReviewService;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', keep: true)]
    public string $search = '';

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updating($key)
    {
        if ($key === 'search') {
            $this->resetPage();
        }
    }

    #[On('delete-review')]
    public function delete($reviewId, ReviewService $reviewService)
    {
        try {
            $reviewService->delete($reviewId);
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => 'Review Berhasil Dihapus!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Gagal Menghapus Review!',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function render(ReviewQuery $reviewQuery)
    {
        $filters = ['search' => $this->search];
        $reviews = $reviewQuery->getPaginated($filters);

        return view('livewire.admin.reviews.index', [
            'reviews' => $reviews,
        ]);
    }
}
