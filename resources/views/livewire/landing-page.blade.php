{{-- Ubah div pembungkus: Panggil fungsi dan kirim datanya lewat sini --}}
<div x-data="eventSystem(initialData)">

    <script>
        // 1. Simpan data kategori ke variabel global agar tidak merusak HTML attribute
        window.initialData = @json($categories);

        // 2. Definisi fungsi komponen
        window.eventSystem = function(categories) {
            return {
                search: '',
                selectedCategory: 'Semua',
                // Gunakan data yang dikirim dari parameter
                categories: categories,
                events: @entangle('events'),

                get filteredEvents() {
                    if (!this.events) return [];

                    return this.events.filter(event => {
                        const title = event.title ? event.title.toLowerCase() : '';
                        const search = this.search.toLowerCase();
                        const matchSearch = title.includes(search);
                        const matchCategory = this.selectedCategory === 'Semua' || event.category === this.selectedCategory;
                        return matchSearch && matchCategory;
                    });
                },

                handleBookmarkClick(eventId) {
                    this.$wire.toggleBookmark(eventId);
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(angka);
                }
            }
        }
    </script>

    {{-- HERO SECTION --}}
    <section class="hero-section text-center" id="home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">
                        Temukan Event Seru di <br> <span style="color: #ffeb3b">Sekitarmu!</span>
                    </h1>
                    <p class="lead mb-5 opacity-75">
                        Bergabunglah dengan ribuan orang lainnya dalam seminar, workshop, dan konser terbaik. Platform
                        satu pintu untuk pengalaman tak terlupakan.
                    </p>

                    {{-- Search Box --}}
                    <div class="bg-white p-2 rounded-pill shadow-lg d-flex flex-wrap justify-content-between align-items-center mx-auto"
                        style="max-width: 500px;">
                        <div class="flex-grow-1 border-end">
                            <i class="bi bi-search text-muted me-2"></i>
                            <input type="text" x-model="search" class="border-0 py-2 w-75" style="outline: none;"
                                placeholder="Cari event apa hari ini?">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="section py-5" id="events">
        <div class="container">

            {{-- Filter Categories --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary"><i class="bi bi-stars"></i> Event Pilihan</h3>
                <div class="d-flex gap-2 overflow-auto pb-2">
                    <span class="badge rounded-pill border px-3 py-2 category-pill text-dark"
                        :class="{ 'bg-primary text-white border-primary': selectedCategory === 'Semua', 'bg-white': selectedCategory !== 'Semua' }"
                        @click="selectedCategory = 'Semua'" style="cursor: pointer;">
                        Semua
                    </span>
                    <template x-for="cat in categories" :key="cat.id">
                        <span class="badge rounded-pill border px-3 py-2 category-pill text-dark"
                            :class="{ 'bg-primary text-white border-primary': selectedCategory === cat.name, 'bg-white': selectedCategory !== cat.name }"
                            @click="selectedCategory = cat.name" x-text="cat.name" style="cursor: pointer;">
                        </span>
                    </template>
                </div>
            </div>

            {{-- Event Grid --}}
            <div class="row g-4">
                <template x-for="event in filteredEvents" :key="event.id">
                    <div class="col-md-6 col-lg-4" x-transition:enter="animate__animated animate__fadeInUp">
                        <div class="card event-card shadow-sm h-100 position-relative">

                            {{-- Bookmark Button --}}
                            <button type="button"
                                class="btn btn-icon rounded-circle position-absolute top-0 end-0 m-2 z-2"
                                :class="event.is_bookmarked ? 'btn-danger' : 'btn-light btn-outline-danger'"
                                @click.prevent.stop="handleBookmarkClick(event.id)" title="Tambahkan ke favorit">
                                <i class="bi" :class="event.is_bookmarked ? 'bi-heart-fill' : 'bi-heart'"></i>
                            </button>

                            {{-- Date Badge --}}
                            <div class="date-badge">
                                <div class="day" x-text="event.date_day"></div>
                                <div class="month" x-text="event.date_month"></div>
                            </div>

                            {{-- Image --}}
                            <a :href="'/events/' + event.slug" wire:navigate>
                                <img :src="event.image" class="card-img-top" alt="Event Image"
                                    style="height: 200px; object-fit: cover;">
                            </a>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-light text-primary" x-text="event.category"></span>
                                    <span class="badge bg-success-subtle text-success"
                                        x-show="event.price === 0">GRATIS</span>
                                    <span class="badge bg-primary-subtle text-primary" x-show="event.price > 0"
                                        x-text="formatRupiah(event.price)"></span>
                                </div>

                                <h5 class="card-title fw-bold mb-2">
                                    <a :href="'/events/' + event.slug" wire:navigate
                                        class="text-dark text-decoration-none stretched-link" x-text="event.title"></a>
                                </h5>

                                <div class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    <span x-text="event.location"></span>
                                </div>

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning text-white rounded-circle text-center pt-1 me-2"
                                            style="width:30px; height:30px">
                                            {{-- Penanganan jika organizer kosong/null --}}
                                            <small x-text="event.organizer ? event.organizer.charAt(0) : '?'"></small>
                                        </div>
                                        <small class="text-muted" x-text="event.organizer"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="filteredEvents.length === 0" class="col-12 text-center py-5">
                    <img src="{{ asset('assets/images/samples/error-404.svg') }}" height="150" class="mb-3 opacity-50">
                    <h5 class="text-muted">Tidak ada event yang ditemukan.</h5>
                    <p class="text-muted small">Coba ubah kata kunci pencarian atau filter kategori Anda.</p>
                </div>
            </div>
        </div>
    </section>
</div>
