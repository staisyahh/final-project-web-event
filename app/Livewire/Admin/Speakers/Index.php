<?php

namespace App\Livewire\Admin\Speakers;

use App\Livewire\Forms\Admin\SpeakerForm;
use App\Models\Speaker;
use Livewire\Component;
use Livewire\WithPagination;
use App\Queries\Admin\SpeakerQuery;
use App\Services\Admin\SpeakerService;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'q')]
    public string $search = '';

    public SpeakerForm $form;

    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function add()
    {
        $this->form->resetForm();
        $this->isEditMode = false;
        $this->isModalOpen = true;
        $this->dispatch('open-modal', name: 'speaker-modal');
    }

    public function edit(Speaker $speaker)
    {
        $this->form->setSpeaker($speaker);
        $this->isEditMode = true;
        $this->isModalOpen = true;
        $this->dispatch('open-modal', name: 'speaker-modal');
    }

    public function save(SpeakerService $speakerService)
    {
        $validatedData = $this->form->validate();

        $speakerService->save($validatedData, $this->form->speaker?->id);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Data Pembicara Berhasil Disimpan!',
        ]);

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->form->resetForm();
        $this->dispatch('close-modal');
    }

    #[On('delete-speaker-confirmed')]
    public function delete($params, SpeakerService $speakerService)
    {
        $speakerService->delete($params['id']);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Pembicara Berhasil Dihapus!',
        ]);
    }

    public function render(SpeakerQuery $speakerQuery)
    {
        $speakers = $speakerQuery->getPaginated($this->search);

        return view('livewire.admin.speakers.index', [
            'speakers' => $speakers
        ]);
    }
}
