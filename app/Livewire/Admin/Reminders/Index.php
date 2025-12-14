<?php

namespace App\Livewire\Admin\Reminders;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Index extends Component
{
    public ?Carbon $lastRunTimestamp = null;
    public bool $buttonDisabled = false;
    public bool $isRunning = false;

    /**
     * Mount the component and check the initial status.
     */
    public function mount()
    {
        $this->checkStatus();
    }

    /**
     * Checks the cache to see when the reminder was last run
     * and updates the component state.
     */
    public function checkStatus()
    {
        $this->lastRunTimestamp = Cache::get('reminder_last_run_timestamp');
        // Disable button if it has been run today
        $this->buttonDisabled = $this->lastRunTimestamp && $this->lastRunTimestamp->isToday();
    }

    /**
     * Runs the event reminder command manually.
     */
    public function runReminders()
    {
        // Double-check to prevent running if already run today
        $this->checkStatus();
        if ($this->buttonDisabled) {
            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => 'Proses sudah dijalankan hari ini.',
            ]);
            return;
        }
        
        $this->isRunning = true;

        // Execute the command
        Artisan::call('app:send-event-reminders');

        // Update the status immediately
        $this->checkStatus();
        $this->isRunning = false;

        // Notify the user
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Proses pengiriman reminder telah dimulai di latar belakang!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.reminders.index')
            ->title('Email Reminder');
    }
}