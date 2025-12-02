<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Speaker;
use App\Models\Category;
use App\Models\Registration;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class WebEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai WebEventSeeder...');

        // 1. Truncate all related tables for a clean slate
        $this->truncateTables();

        // 2. Seed core data
        $this->seedCoreData();

        // 3. Seed events and their direct relations
        $this->seedEvents();

        // 4. Seed user interactions (registrations, reviews, bookmarks)
        $this->seedUserInteractions();

        $this->command->info('WebEventSeeder selesai.');
    }

    private function truncateTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('bookmarks')->truncate();
        DB::table('reviews')->truncate();
        DB::table('event_galleries')->truncate();
        DB::table('e_tickets')->truncate();
        DB::table('registrations')->truncate();
        DB::table('tickets')->truncate();
        DB::table('event_speakers')->truncate();
        DB::table('events')->truncate();
        DB::table('speakers')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
        $this->command->warn('Semua tabel terkait event telah dibersihkan.');
    }

    private function seedCoreData()
    {
        // --- USERS ---
        // Admin
        User::create(['name' => 'Admin Organizer', 'email' => 'admin@webevent.com', 'password' => Hash::make('password'), 'role' => 'admin']);
        // Specific Participants for testing
        User::create(['name' => 'Budi Peserta', 'email' => 'budi@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta']);
        User::create(['name' => 'Sita Peserta', 'email' => 'sita@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta']);
        User::create(['name' => 'Rudi Peserta', 'email' => 'rudi@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta']);
        // Random Participants
        User::factory(5)->create(['role' => 'peserta']);
        $this->command->info('Users (Admin & Peserta) telah dibuat.');

        // --- CATEGORIES ---
        Category::insert([
            ['name' => 'Teknologi', 'slug' => 'teknologi'],
            ['name' => 'Bisnis', 'slug' => 'bisnis'],
            ['name' => 'Musik', 'slug' => 'musik'],
            ['name' => 'Seni & Budaya', 'slug' => 'seni-budaya'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan'],
        ]);
        $this->command->info('Categories telah dibuat.');

        // --- SPEAKERS ---
        Speaker::insert([
            ['name' => 'Dr. Aisyah', 'title' => 'AI Specialist', 'bio' => 'Pakar Kecerdasan Buatan dari Indonesia.'],
            ['name' => 'Bambang S.', 'title' => 'Full-stack Developer', 'bio' => 'Pengembang berpengalaman dengan fokus pada TALL Stack.'],
            ['name' => 'Citra Lestari', 'title' => 'Marketing Guru', 'bio' => 'Pakar strategi pemasaran digital.'],
            ['name' => 'Dira Ananda', 'title' => 'Penyanyi Jazz', 'bio' => 'Vokalis utama dalam grup musik Melodi Senja.'],
        ]);
        $this->command->info('Speakers telah dibuat.');
    }

    private function seedEvents()
    {
        $admin = User::where('role', 'admin')->first();
        $catTeknologi = Category::where('slug', 'teknologi')->first();
        $catBisnis = Category::where('slug', 'bisnis')->first();
        $catMusik = Category::where('slug', 'musik')->first();
        $catSeni = Category::where('slug', 'seni-budaya')->first();

        $speakerAisyah = Speaker::where('name', 'Dr. Aisyah')->first();
        $speakerBambang = Speaker::where('name', 'Bambang S.')->first();
        $speakerCitra = Speaker::where('name', 'Citra Lestari')->first();
        $speakerDira = Speaker::where('name', 'Dira Ananda')->first();

        // --- Event 1: Konferensi Teknologi (Published, Future, Paid) ---
        $event1 = Event::create([
            'title' => 'Konferensi Teknologi Masa Depan', 'slug' => 'konferensi-teknologi-2026', 'description' => 'Konferensi tahunan membahas tren AI, Web3, dan komputasi kuantum.', 'jadwal' => now()->addMonths(3), 'location_name' => 'Jakarta Convention Center', 'status' => 'published', 'category_id' => $catTeknologi->id, 'organizer_id' => $admin->id
        ]);
        $event1->speakers()->attach([$speakerAisyah->id, $speakerBambang->id]);
        $event1->tickets()->createMany([
            ['name' => 'Regular', 'harga' => 250000, 'stok' => 100],
            ['name' => 'VIP', 'harga' => 500000, 'stok' => 50],
        ]);

        // --- Event 2: Workshop UI/UX (Published, Future, Limited Stock) ---
        $event2 = Event::create([
            'title' => 'Workshop UI/UX Design', 'slug' => 'workshop-uiux-2025', 'description' => 'Workshop intensif 3 hari untuk desainer produk.', 'jadwal' => now()->addWeeks(3), 'location_name' => 'Co-working Space Jakarta', 'status' => 'published', 'category_id' => $catTeknologi->id, 'organizer_id' => $admin->id
        ]);
        $event2->tickets()->create(['name' => 'Peserta', 'harga' => 750000, 'stok' => 15]);

        // --- Event 3: Konser Amal (Completed, Past) ---
        $event3 = Event::create([
            'title' => 'Konser Amal Musik', 'slug' => 'konser-amal-musik-2024', 'description' => 'Malam penggalangan dana diiringi musisi ternama.', 'jadwal' => now()->subMonth(), 'location_name' => 'Balai Sarbini', 'status' => 'completed', 'category_id' => $catMusik->id, 'organizer_id' => $admin->id
        ]);
        $event3->speakers()->attach($speakerDira->id);
        $event3->tickets()->create(['name' => 'Donasi', 'harga' => 200000, 'stok' => 500]);


        // --- Event 4: Webinar Gratis (Published, Future) ---
        $event4 = Event::create([
            'title' => 'Webinar Gratis: AI untuk Bisnis', 'slug' => 'webinar-ai-bisnis-2025', 'description' => 'Pelajari bagaimana AI dapat membantu bisnis Anda.', 'jadwal' => now()->addDays(10), 'location_name' => 'Online via Zoom', 'status' => 'published', 'category_id' => $catBisnis->id, 'organizer_id' => $admin->id
        ]);
        $event4->speakers()->attach($speakerAisyah->id);
        $event4->tickets()->create(['name' => 'Gratis', 'harga' => 0, 'stok' => 1000]);

        // --- Event 5: Pameran Seni (Completed, Past) ---
        $event5 = Event::create([
            'title' => 'Pameran Seni Kontemporer', 'slug' => 'pameran-seni-kontemporer-2024', 'description' => 'Menampilkan karya-karya seniman muda Indonesia.', 'jadwal' => now()->subMonths(2), 'location_name' => 'Galeri Nasional', 'status' => 'completed', 'category_id' => $catSeni->id, 'organizer_id' => $admin->id
        ]);
        $event5->tickets()->create(['name' => 'Pengunjung', 'harga' => 0, 'stok' => 2000]);

        // --- Event 6: Festival Kuliner (Archived) ---
        Event::create(['title' => 'Festival Kuliner Nusantara (Diarsipkan)', 'slug' => 'festival-kuliner-2025', 'description' => 'Event ini diarsipkan dan tidak akan tampil.', 'jadwal' => now()->addMonths(6), 'location_name' => 'Lapangan Banteng', 'status' => 'archived', 'category_id' => $catSeni->id, 'organizer_id' => $admin->id]);

        // --- Event 7: Tiket Habis (Sold Out) ---
        $event7 = Event::create(['title' => 'Event Tiket Habis', 'slug' => 'event-tiket-habis-2025', 'description' => 'Event untuk mengetes tiket sold out.', 'jadwal' => now()->addWeeks(2), 'location_name' => 'Online', 'status' => 'published', 'category_id' => $catBisnis->id, 'organizer_id' => $admin->id]);
        $event7->tickets()->create(['name' => 'Regular', 'harga' => 100000, 'stok' => 0, 'status' => 'sold_out']);
        
        $this->command->info('Events dan relasi tiket/speaker telah dibuat.');
    }

    private function seedUserInteractions()
    {
        $budi = User::where('email', 'budi@mail.com')->first();
        $sita = User::where('email', 'sita@mail.com')->first();
        $rudi = User::where('email', 'rudi@mail.com')->first();
        
        $event1 = Event::where('slug', 'konferensi-teknologi-2026')->first();
        $event2 = Event::where('slug', 'workshop-uiux-2025')->first();
        $event3 = Event::where('slug', 'konser-amal-musik-2024')->first();
        $event4 = Event::where('slug', 'webinar-ai-bisnis-2025')->first();
        $event5 = Event::where('slug', 'pameran-seni-kontemporer-2024')->first();
        
        // --- Skenario Pengguna A (Budi) ---
        // 1. Pending, sudah unggah bukti -> Untuk di-approve admin
        $regBudi1 = Registration::create(['user_id' => $budi->id, 'event_id' => $event1->id, 'ticket_id' => $event1->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $event1->tickets->first()->harga, 'status' => 'pending_payment', 'payment_proof_path' => 'placeholders/proof.jpg']);
        // 2. Pending, belum unggah bukti -> Untuk tes tombol unggah
        $regBudi2 = Registration::create(['user_id' => $budi->id, 'event_id' => $event2->id, 'ticket_id' => $event2->tickets->first()->id, 'jumlah_tiket' => 2, 'total_bayar' => $event2->tickets->first()->harga * 2, 'status' => 'pending_payment']);
        // 3. Confirmed, sudah check-in, sudah review
        $regBudi3 = Registration::create(['user_id' => $budi->id, 'event_id' => $event3->id, 'ticket_id' => $event3->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $event3->tickets->first()->harga, 'status' => 'confirmed']);
        $regBudi3->eTickets()->create(['event_id' => $event3->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'checked_in']);
        $event3->reviews()->create(['user_id' => $budi->id, 'rating' => 5, 'comment' => 'Konser yang luar biasa! Sound systemnya mantap.']);

        // --- Skenario Pengguna B (Sita) ---
        // 1. Confirmed, belum check-in -> Untuk tes QR Code
        $regSita1 = Registration::create(['user_id' => $sita->id, 'event_id' => $event1->id, 'ticket_id' => $event1->tickets->last()->id, 'jumlah_tiket' => 1, 'total_bayar' => $event1->tickets->last()->harga, 'status' => 'confirmed']);
        $regSita1->eTickets()->create(['event_id' => $event1->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'active']);
        // 2. Completed, belum review -> Untuk tes tombol "Beri Ulasan"
        $regSita2 = Registration::create(['user_id' => $sita->id, 'event_id' => $event3->id, 'ticket_id' => $event3->tickets->first()->id, 'jumlah_tiket' => 2, 'total_bayar' => $event3->tickets->first()->harga * 2, 'status' => 'confirmed']);
        $regSita2->eTickets()->createMany([
            ['event_id' => $event3->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'active'],
            ['event_id' => $event3->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'active'],
        ]);
        // 3. Pendaftaran dibatalkan
        Registration::create(['user_id' => $sita->id, 'event_id' => $event2->id, 'ticket_id' => $event2->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $event2->tickets->first()->harga, 'status' => 'cancelled']);
        
        // --- Skenario Pengguna C (Rudi) ---
        // 1. Tiket gratis, langsung confirmed
        $regRudi1 = Registration::create(['user_id' => $rudi->id, 'event_id' => $event4->id, 'ticket_id' => $event4->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => 0, 'status' => 'confirmed']);
        $regRudi1->eTickets()->create(['event_id' => $event4->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'active']);
        // 2. Completed, belum review
        $regRudi2 = Registration::create(['user_id' => $rudi->id, 'event_id' => $event5->id, 'ticket_id' => $event5->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => 0, 'status' => 'confirmed']);
        $regRudi2->eTickets()->create(['event_id' => $event5->id, 'ticket_code' => Str::upper(Str::random(10)), 'status' => 'active']);

        // --- Interaksi Tambahan ---
        // Bookmark
        $sita->bookmarkedEvents()->attach($event2->id);
        $rudi->bookmarkedEvents()->attach($event1->id);

        $this->command->info('Interaksi pengguna (registrasi, review, dll) telah dibuat.');
    }
}