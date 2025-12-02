<?php

namespace App\Livewire\Admin\Absensi;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Queries\Admin\AbsensiQuery;
use App\Services\Admin\AbsensiService;

class Index extends Component
{
    public $events = [];
    public ?int $selectedEventId = null;

    public string $feedbackMessage = '';
    public string $feedbackStatus = 'info'; // info, success, warning, error

    public function mount(AbsensiQuery $absensiQuery)
    {
        $this->events = $absensiQuery->getAvailableEvents();
        
        // Auto-select the first event if available
        if ($this->events->isNotEmpty()) {
            $this->selectedEventId = $this->events->first()->id;
        }
    }

    #[On('qrCodeScanned')]
    public function processQrCode(string $code, AbsensiService $absensiService)
    {
        if (!$this->selectedEventId) {
            $this->updateFeedback('error', 'Silakan pilih event terlebih dahulu.');
            return;
        }

        $result = $absensiService->processCheckIn($code, $this->selectedEventId);

        $this->updateFeedback($result['status'], $result['message']);
    }

    public function updateFeedback(string $status, string $message)
    {
        $this->feedbackStatus = $status;
        $this->feedbackMessage = $message;

        // Dispatch browser event to handle UI feedback if needed
        $this->dispatch('checkin-processed', status: $status);
    }
    
    public function render()
    {
        return view('livewire.admin.absensi.index');
    }
}
