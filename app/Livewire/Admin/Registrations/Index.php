<?php

namespace App\Livewire\Admin\Registrations;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registration;
use App\Exports\RegistrationsExport;
use Livewire\Attributes\Url;
use App\Queries\Admin\RegistrationQuery;
use App\Services\Admin\RegistrationService; // Pastikan use ini ada
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    // ... (kode properti lainnya tetap sama: $search, $event_id, dll) ...
    #[Url(as: 'q', keep: true)]
    public string $search = '';

    #[Url(keep: true)]
    public ?int $event_id = null;

    #[Url(keep: true)]
    public string $status = '';

    #[Url(keep: true)]
    public string $start_date = '';

    #[Url(keep: true)]
    public string $end_date = '';

    public ?Registration $selectedRegistration = null;

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'event_id' => $this->event_id,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        return Excel::download(new RegistrationsExport($filters), 'registrations-' . now()->format('Y-m-d') . '.xlsx');
    }

    // ... (method paginationView, updating, showDetail, closeModal tetap sama) ...
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updating($key)
    {
        if (in_array($key, ['search', 'event_id', 'status', 'start_date', 'end_date'])) {
            $this->resetPage();
        }
    }

    public function showDetail(Registration $registration)
    {
        $this->selectedRegistration = $registration->load(['user', 'event', 'ticket']);
        $this->dispatch('open-modal', name: 'registration-detail-modal');
    }

    public function closeModal()
    {
        $this->selectedRegistration = null;
        $this->dispatch('close-modal');
    }

    /**
     * PERBAIKAN: Mengubah signature method agar menerima array $data
     * Karena event dispatch dari frontend mengirim objek dengan id dan status.
     */
    #[On('update-registration-status')]
    public function updateStatus(array $data)
    {
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        // 1. Resolve Service secara manual
        $registrationService = app(RegistrationService::class);

        // 2. Validasi sederhana (Input dari JS bisa saja null)
        if (empty($id) || empty($status)) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Aksi gagal: Data tidak lengkap.',
            ]);
            return;
        }

        try {
            // 3. Panggil method update di service
            $registrationService->updateStatus($id, $status);

            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => 'Status Registrasi Berhasil Diperbarui!',
            ]);

            // Refresh data modal jika sedang terbuka dan ID-nya sama
            if ($this->selectedRegistration && $this->selectedRegistration->id == $id) {
                $this->selectedRegistration = Registration::find($id)->load(['user', 'event', 'ticket']);
            }
        } catch (\Exception $e) {
            // Tangkap error jika ID tidak ketemu atau status invalid
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function render(RegistrationQuery $registrationQuery)
    {
        // ... (kode render tetap sama) ...
        $filters = [
            'search' => $this->search,
            'event_id' => $this->event_id,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        $registrations = $registrationQuery->getPaginated($filters);
        $events = Event::orderBy('title')->get();

        return view('livewire.admin.registrations.index', [
            'registrations' => $registrations,
            'events' => $events,
        ]);
    }
}
