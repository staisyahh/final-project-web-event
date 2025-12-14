<div>
    
    @push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .date-block {
            min-width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            line-height: 1.2;
        }

        .event-item {
            transition: background-color 0.2s;
            border-radius: 10px;
        }

        .event-item:hover {
            background-color: #f8f9fa;
        }

        .avatar-group .avatar {
            border: 2px solid #fff;
            margin-left: -15px;
            transition: margin 0.2s;
        }

        .avatar-group .avatar:first-child {
            margin-left: 0;
        }

        .avatar-group .avatar:hover {
            margin-left: 0;
            z-index: 10;
        }

    </style>
    @endpush

    <div class="page-heading mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1">Dashboard Overview</h3>
                <p class="text-subtitle text-muted mb-0">Ringkasan performa event dan penjualan tiket Anda.</p>
            </div>
            <div>
                <span class="badge bg-light-primary text-primary px-3 py-2 fs-6">
                    <i class="bi bi-calendar-check me-2"></i> {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="row">
            {{-- Bagian Kiri: Statistik & Chart (Lebih Lebar) --}}
            <div class="col-12 col-xl-9">
                {{-- Row Statistik --}}
                <div class="row">
                    {{-- Card 1: Pendapatan --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card stats-card">
                            <div class="card-body px-4 py-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldWallet"></i>
                                    </div>
                                    <span class="text-success fs-7 fw-bold"><i class="bi bi-arrow-up"></i> Revenue</span>
                                </div>
                                <h6 class="text-muted font-semibold mb-1">Total Pendapatan</h6>
                                <h4 class="font-extrabold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Tiket Terjual --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card stats-card">
                            <div class="card-body px-4 py-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldTicket">
                                        </i>
                                    </div>
                                    <span class="text-muted fs-7">Total Sales</span>
                                </div>
                                <h6 class="text-muted font-semibold mb-1">Tiket Terjual</h6>
                                <h4 class="font-extrabold mb-0">{{ number_format($totalTicketsSold, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Peserta --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card stats-card">
                            <div class="card-body px-4 py-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="stats-icon red">
                                        <i class="iconly-boldUser"></i>
                                    </div>
                                    <span class="text-muted fs-7">Users</span>
                                </div>
                                <h6 class="text-muted font-semibold mb-1">Total Peserta</h6>
                                <h4 class="font-extrabold mb-0">{{ $totalMembers }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Event Aktif --}}
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card stats-card">
                            <div class="card-body px-4 py-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <span class="text-purple fs-7 fw-bold">Aktif</span>
                                </div>
                                <h6 class="text-muted font-semibold mb-1">Event Aktif</h6>
                                <h4 class="font-extrabold mb-0">{{ $activeEventsCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart Section --}}
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 pb-0 pt-4">
                                <h5 class="mb-0">Analitik Penjualan</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        30 Hari Terakhir
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">7 Hari Terakhir</a></li>
                                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-sales"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Registrations Table (New Layout) --}}
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-transparent pt-4">
                                <h5 class="mb-0">Transaksi & Registrasi Terbaru</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-4 py-3">Peserta</th>
                                                <th class="py-3">Event</th>
                                                <th class="py-3">Waktu</th>
                                                <th class="px-4 py-3 text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentRegistrations as $registration)
                                            <tr>
                                                <td class="px-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-3">
                                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($registration->user->name) }}&background=random" alt="Avatar">
                                                        </div>
                                                        <div>
                                                            <h6 class="font-bold mb-0 text-dark">{{ $registration->user->name }}</h6>
                                                            <small class="text-muted">{{ $registration->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold">{{ Str::limit($registration->event->title, 30) }}</span>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $registration->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-4 text-end">
                                                    <span class="badge bg-light-success text-success">Paid</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">Belum ada registrasi baru.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-center pb-4">
                                <a href="#" class="btn btn-sm btn-light-primary">Lihat Semua Transaksi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Sidebar Widgets --}}
            <div class="col-12 col-xl-3">
                {{-- Upcoming Events Widget --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent pt-4 pb-2">
                        <h5 class="mb-0">Event Akan Datang</h5>
                    </div>
                    <div class="card-content px-3 pb-4">
                        @forelse($upcomingEvents as $event)
                        <div class="event-item d-flex align-items-center p-2 mb-2 cursor-pointer" onclick="window.location='{{ route('event.detail', $event->slug) }}'">
                            {{-- Date Block Visual --}}
                            <div class="date-block bg-light-primary text-primary me-3 shadow-sm">
                                <span class="fs-7 text-uppercase">{{ $event->jadwal->format('M') }}</span>
                                <span class="fs-4">{{ $event->jadwal->format('d') }}</span>
                            </div>
                            <div class="event-details flex-grow-1 overflow-hidden">
                                <h6 class="mb-1 text-truncate" title="{{ $event->title }}">{{ $event->title }}</h6>
                                <div class="d-flex align-items-center text-muted fs-7">
                                    <i class="bi bi-clock me-1"></i> {{ $event->jadwal->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <img src="{{ asset('assets/images/samples/error-404.svg') }}" alt="Empty" style="width: 80px; opacity: 0.5;">
                            <p class="text-muted mt-2 small">Tidak ada event terdekat.</p>
                        </div>
                        @endforelse

                        <div class="d-grid gap-2 mt-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">Lihat Kalender</a>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions / Mini Profile --}}
                <div class="card bg-primary text-white text-center p-4 shadow-sm" style="background: linear-gradient(45deg, #435ebe, #25396f);">
                    <div class="card-body p-0">
                        <h5 class="text-white mb-2">Buat Event Baru</h5>
                        <p class="text-white-50 fs-7 mb-4">Siap untuk mempublikasikan event spektakuler berikutnya?</p>
                        <a href="{{ route('admin.events.index') }}">

                            <button class="btn btn-light text-primary fw-bold w-100 shadow-sm">
                                <i class="bi bi-plus-lg me-2"></i> Tambah Event
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        const initSalesChart = (chartData) => {
            const chartEl = document.querySelector("#chart-sales");
            if (!chartEl) return;

            if (window.salesChart instanceof ApexCharts) {
                window.salesChart.destroy();
            }

            const options = {
                chart: {
                    type: 'area', // Ganti ke area agar lebih modern
                    height: 320
                    , fontFamily: 'Nunito, sans-serif'
                    , toolbar: {
                        show: false
                    }
                    , zoom: {
                        enabled: false
                    }
                }
                , series: [{
                    name: 'Tiket Terjual'
                    , data: chartData.series
                }]
                , xaxis: {
                    categories: chartData.labels
                    , axisBorder: {
                        show: false
                    }
                    , axisTicks: {
                        show: false
                    }
                    , labels: {
                        style: {
                            colors: '#9aa0ac'
                        }
                    }
                }
                , yaxis: {
                    labels: {
                        style: {
                            colors: '#9aa0ac'
                        }
                        , formatter: (val) => val.toFixed(0)
                    }
                },
                // Warna gradient modern Mazer
                colors: ['#435ebe']
                , fill: {
                    type: "gradient"
                    , gradient: {
                        shadeIntensity: 1
                        , opacityFrom: 0.6
                        , opacityTo: 0.1, // Fade out di bawah
                        stops: [0, 90, 100]
                    }
                }
                , dataLabels: {
                    enabled: false
                }
                , stroke: {
                    curve: 'smooth'
                    , width: 3
                }
                , grid: {
                    borderColor: '#f1f1f1'
                , }
                , tooltip: {
                    theme: 'light'
                    , y: {
                        formatter: (val) => val.toFixed(0) + " tiket"
                    }
                }
            };

            window.salesChart = new ApexCharts(chartEl, options);
            window.salesChart.render();
        };

        document.addEventListener('DOMContentLoaded', () => {
            initSalesChart(@json($salesChartData));
        });

        document.addEventListener('livewire:navigated', () => {
            initSalesChart(@json($salesChartData));
        });

        Livewire.on('salesChartUpdated', ({
            data
        }) => {
            initSalesChart(data);
        });

    </script>
    @endpush
</div>

