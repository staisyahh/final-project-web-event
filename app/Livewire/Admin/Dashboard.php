<?php

namespace App\Livewire\Admin;

use App\Services\Admin\DashboardService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    // Properti untuk menyimpan data statistik
    public float $totalRevenue = 0;
    public int $totalTicketsSold = 0;
    public int $activeEventsCount = 0;
    public int $totalMembers = 0;

    // Properti untuk tabel
    public $upcomingEvents = [];
    public $recentRegistrations = [];

    // Properti untuk grafik
    public array $salesChartData = [];

    /**
     * Mount lifecycle hook.
     * Mengambil data dari service.
     */
    public function mount(DashboardService $dashboardService)
    {
        $data = $dashboardService->getDashboardData();

        $this->totalRevenue = $data['totalRevenue'];
        $this->totalTicketsSold = $data['totalTicketsSold'];
        $this->activeEventsCount = $data['activeEventsCount'];
        $this->totalMembers = $data['totalMembers'];
        $this->upcomingEvents = $data['upcomingEvents'];
        $this->recentRegistrations = $data['recentRegistrations'];
        $this->salesChartData = $data['salesChartData'];

        // Dispatch event ke browser untuk merender chart
        $this->dispatch('salesChartUpdated', data: $this->salesChartData);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.admin.dashboard')
            ->title('Admin Dashboard'); // Set page title
    }
}