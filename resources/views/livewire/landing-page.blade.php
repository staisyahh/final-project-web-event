<div x-data="eventSystem(initialData)" class="pb-20">
    <script>
        window.initialData = @json($categories);
        window.eventSystem = function(categories) {
            return {
                search: ''
                , selectedCategory: 'Semua'
                , categories: categories
                , events: @entangle('events')
                , get filteredEvents() {
                    if (!this.events) return [];
                    return this.events.filter(event => {
                        const title = event.title ? event.title.toLowerCase() : '';
                        const search = this.search.toLowerCase();
                        const matchSearch = title.includes(search);
                        const matchCategory = this.selectedCategory === 'Semua' || event.category === this.selectedCategory;
                        return matchSearch && matchCategory;
                    });
                }
                , handleBookmarkClick(eventId) {
                    this.$wire.toggleBookmark(eventId);
                }
                , formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency'
                        , currency: 'IDR'
                        , minimumFractionDigits: 0
                    }).format(angka);
                }
            }
        }

    </script>

    {{-- HERO SECTION --}}
    <section class="relative py-20 lg:py-32 overflow-hidden" id="home">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 -z-10 bg-vent-gradient"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-vent-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute top-40 -left-20 w-72 h-72 bg-vent-info/10 rounded-full blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-vent-secondary tracking-tight mb-6 leading-tight animate__animated animate__fadeInDown">
                Temukan Event Seru di <br> <span class="text-vent-primary relative">
                    Sekitarmu!
                    <svg class="absolute m-3 w-full h-3 -bottom-1 left-0 text-vent-warning/50 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" /></svg>
                </span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 mb-10 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan orang lainnya. Satu platform untuk seminar, workshop, dan hiburan tak terlupakan.
            </p>

            {{-- Search Box --}}
            <div class="bg-white p-2 rounded-full shadow-xl shadow-vent-primary/10 flex items-center max-w-lg mx-auto border border-slate-100">
                <div class="pl-4 text-slate-400">
                    <i class="bi bi-search text-lg"></i>
                </div>
                <input type="text" x-model="search" class="flex-1 border-none bg-transparent px-4 py-3 text-vent-secondary placeholder-slate-400 focus:ring-0 focus:outline-none w-full" placeholder="Cari event apa hari ini?">
                <button class="bg-vent-secondary text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-slate-800 transition">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" id="events">

        {{-- Filter & Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <h3 class="text-2xl font-bold text-vent-secondary flex items-center gap-2">
                <i class="bi bi-stars text-vent-warning"></i> Event Pilihan
            </h3>

            {{-- Category Pills --}}
            <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto scrollbar-hide">
                <button class="px-5 py-2 rounded-full text-sm font-medium transition whitespace-nowrap border" :class="selectedCategory === 'Semua' ? 'bg-vent-primary border-vent-primary text-white shadow-lg shadow-vent-primary/30' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" @click="selectedCategory = 'Semua'">
                    Semua
                </button>
                <template x-for="cat in categories" :key="cat.id">
                    <button class="px-5 py-2 rounded-full text-sm font-medium transition whitespace-nowrap border" :class="selectedCategory === cat.name ? 'bg-vent-primary border-vent-primary text-white shadow-lg shadow-vent-primary/30' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'" @click="selectedCategory = cat.name" x-text="cat.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- Event Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="event in filteredEvents" :key="event.id">
                <div class="bg-white rounded-card shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden relative group h-full flex flex-col">

                    {{-- Bookmark --}}
                    <button type="button" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full flex items-center justify-center transition bg-white/90 backdrop-blur shadow-sm" :class="event.is_bookmarked ? 'text-vent-danger' : 'text-slate-400 hover:text-vent-danger'" @click.prevent.stop="handleBookmarkClick(event.id)">
                        <i class="bi" :class="event.is_bookmarked ? 'bi-heart-fill' : 'bi-heart'"></i>
                    </button>

                    {{-- Image Wrapper --}}
                    <div class="relative overflow-hidden h-52">
                        <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur rounded-xl px-3 py-1.5 text-center shadow-sm min-w-[60px]">
                            <div class="text-xs font-bold text-vent-danger uppercase tracking-wide" x-text="event.date_month"></div>
                            <div class="text-xl font-bold text-vent-secondary leading-none" x-text="event.date_day"></div>
                        </div>
                        <a :href="'/events/' + event.slug" wire:navigate>
                            <img :src="event.image" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500" alt="Event">
                        </a>
                        {{-- Price Tag --}}
                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold shadow-sm" :class="event.price === 0 ? 'bg-vent-success text-white' : 'bg-white text-vent-primary'" x-text="event.price === 0 ? 'GRATIS' : formatRupiah(event.price)">
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-vent-surface text-vent-primary text-xs font-semibold tracking-wide uppercase" x-text="event.category"></span>
                        </div>

                        <h5 class="text-lg font-bold text-vent-secondary mb-2 line-clamp-2 leading-snug hover:text-vent-primary transition">
                            <a :href="'/events/' + event.slug" wire:navigate class="before:absolute before:inset-0" x-text="event.title"></a>
                        </h5>

                        <div class="flex items-center text-slate-500 text-sm mb-4">
                            <i class="bi bi-geo-alt-fill text-vent-danger mr-2"></i>
                            <span class="truncate" x-text="event.location"></span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-vent-warning text-white flex items-center justify-center text-xs font-bold shadow-sm" x-text="event.organizer ? event.organizer.charAt(0) : '?'"></div>
                                <span class="text-xs font-medium text-slate-600 truncate max-w-[120px]" x-text="event.organizer"></span>
                            </div>
                            <span class="text-vent-primary text-sm font-medium flex items-center group-hover:translate-x-1 transition">
                                Detail <i class="bi bi-arrow-right ml-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Empty State --}}
            <div x-show="filteredEvents.length === 0" class="col-span-full text-center py-16" style="display: none;">
                <div class="bg-slate-50 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-search text-4xl text-slate-300"></i>
                </div>
                <h5 class="text-lg font-semibold text-slate-600">Tidak ada event yang ditemukan.</h5>
                <p class="text-slate-400 text-sm mt-1">Coba kata kunci lain atau ubah filter kategori.</p>
            </div>
        </div>
    </section>
</div>
