<?php

namespace App\Services\Admin;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Mengambil semua data yang diperlukan untuk dashboard admin.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        return [
            'totalRevenue' => Registration::where('status', 'confirmed')->sum('total_bayar'),
            'totalTicketsSold' => Registration::where('status', 'confirmed')->sum('jumlah_tiket'),
            'activeEventsCount' => Event::where('status', 'published')->where('jadwal', '>=', now())->count(),
            'totalMembers' => User::where('role', 'peserta')->count(),
            'upcomingEvents' => Event::where('jadwal', '>=', now())->orderBy('jadwal', 'asc')->take(5)->get(),
            'recentRegistrations' => Registration::with(['user', 'event'])->latest()->take(5)->get(),
            'salesChartData' => $this->getSalesChartData(),
        ];
    }

    /**
     * Menyiapkan data untuk grafik penjualan tiket 30 hari terakhir.
     *
     * @return array
     */
    protected function getSalesChartData(): array
    {
        $sales = Registration::where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(jumlah_tiket) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = [];
        // Inisialisasi 30 hari terakhir dengan 0 tiket terjual
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('d M');
            $dates[$date] = 0;
        }

        // Isi data penjualan dari database
        foreach ($sales as $sale) {
            $date = Carbon::parse($sale->date)->format('d M');
            if (isset($dates[$date])) {
                $dates[$date] = $sale->total;
            }
        }

        return [
            'labels' => array_keys($dates),
            'series' => array_values($dates),
        ];
    }
}
