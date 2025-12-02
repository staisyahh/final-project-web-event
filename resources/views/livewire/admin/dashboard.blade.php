<div>
    <div class="page-heading">
        <h3>Dashboard Statistics</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    {{-- Card 1: Total Pendapatan --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                                        <h6 class="font-extrabold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Tiket Terjual --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldTicket"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Tiket Terjual</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalTicketsSold, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Event Aktif --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldCalendar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Event Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{ $activeEventsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Total Peserta --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldUser"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Peserta</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalMembers }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart Penjualan Tiket --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Penjualan Tiket (30 Hari Terakhir)</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-sales"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                {{-- Card Upcoming Events --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Event Akan Datang</h4>
                    </div>
                    <div class="card-content pb-4">
                        @forelse($upcomingEvents as $event)
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <img src="{{ $event->banner_url ? asset('storage/' . $event->banner_url) : 'https://via.placeholder.com/400x200.png?text=No+Image' }}" alt="{{ $event->title }}">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">{{ Str::limit($event->title, 20) }}</h5>
                                    <h6 class="text-muted mb-0">{{ $event->jadwal->format('d M Y, H:i') }}</h6>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center">Tidak ada event akan datang.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Card Recent Registrations --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Registrasi Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-lg">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Event</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRegistrations as $registration)
                                    <tr>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm">
                                                    <img src="{{ asset('mazer/assets/images/faces/2.jpg') }}" alt="Avatar">
                                                </div>
                                                <p class="font-bold ms-3 mb-0">{{ strtok($registration->user->name, ' ') }}</p>
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <p class="mb-0">{{ Str::limit($registration->event->title, 25) }}</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada registrasi terbaru.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        const initSalesChart = (chartData) => {
            // Pastikan elemen chart ada di DOM
            const chartEl = document.querySelector("#chart-sales");
            if (!chartEl) {
                return;
            }

            // Hancurkan instance chart sebelumnya jika ada
            if (window.salesChart instanceof ApexCharts) {
                window.salesChart.destroy();
            }

            const options = {
                chart: { type: 'area', height: 350, toolbar: { show: false } },
                series: [{ name: 'Tiket Terjual', data: chartData.series }],
                xaxis: { categories: chartData.labels, type: 'category' },
                stroke: { curve: 'smooth' },
                dataLabels: { enabled: false },
                yaxis: { labels: { formatter: (val) => val.toFixed(0) } },
                tooltip: { y: { formatter: (val) => val.toFixed(0) + " tiket" } }
            };

            // Buat dan render chart baru, simpan di object window
            window.salesChart = new ApexCharts(chartEl, options);
            window.salesChart.render();
        };

        // Inisialisasi chart saat halaman pertama kali dimuat (load penuh)
        document.addEventListener('DOMContentLoaded', () => {
            initSalesChart(@json($salesChartData));
        });
        
        // Inisialisasi ulang chart setiap kali navigasi Livewire ke halaman ini
        document.addEventListener('livewire:navigated', () => {
            initSalesChart(@json($salesChartData));
        });

        // Tetap dengarkan event dari komponen jika ada pembaruan data dinamis
        Livewire.on('salesChartUpdated', ({ data }) => {
            initSalesChart(data);
        });

    </script>
    @endpush
</div>
