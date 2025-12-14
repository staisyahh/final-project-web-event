<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Speaker;
use App\Models\Category;
use App\Models\EventGallery;
use App\Models\Registration;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Faker\Factory as Faker;

class WebEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai WebEventSeeder...');

        $this->truncateTables();
        $this->seedCoreData();
        $this->seedEventsAndRelations();
        $this->seedUserInteractions();

        $this->command->info('WebEventSeeder selesai dengan sukses.');
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
        User::create(['name' => 'Admin Organizer', 'email' => 'admin@webevent.com', 'password' => Hash::make('password'), 'role' => 'admin', 'phone_number' => '081234567890']);
        User::create(['name' => 'Budi Peserta', 'email' => 'budi@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta', 'phone_number' => '081111111111']);
        User::create(['name' => 'Sita Peserta', 'email' => 'sita@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta', 'phone_number' => '082222222222']);
        User::create(['name' => 'Rudi Peserta', 'email' => 'rudi@mail.com', 'password' => Hash::make('password'), 'role' => 'peserta', 'phone_number' => '083333333333']);
        User::factory(5)->create(['role' => 'peserta']);
        $this->command->info('Users (Admin & Peserta) telah dibuat.');

        // --- CATEGORIES ---
        Category::insert([
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bisnis', 'slug' => 'bisnis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Musik', 'slug' => 'musik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Seni & Budaya', 'slug' => 'seni-budaya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->command->info('Categories telah dibuat.');

        // --- SPEAKERS ---
        Speaker::insert([
            ['name' => 'Dr. Aisyah', 'title' => 'AI Specialist', 'bio' => 'Pakar Kecerdasan Buatan dari Indonesia.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bambang S.', 'title' => 'Full-stack Developer', 'bio' => 'Pengembang berpengalaman dengan fokus pada TALL Stack.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Citra Lestari', 'title' => 'Marketing Guru', 'bio' => 'Pakar strategi pemasaran digital.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dira Ananda', 'title' => 'Penyanyi Jazz', 'bio' => 'Vokalis utama dalam grup musik Melodi Senja.', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->command->info('Speakers telah dibuat.');
    }

    private function seedEventsAndRelations()
    {
        $faker = Faker::create('id_ID');
        $admin = User::where('role', 'admin')->first();
        $categories = Category::all()->keyBy('slug');
        $speakers = Speaker::all()->keyBy('name');

        // --- Event 1: Untuk Tes Notifikasi H-1 ---
        $eventH1 = Event::create([
            'title' => 'Reminder H-1: Tech Conference 2025', 'slug' => 'reminder-h1-tech-conf-2025', 'description' => $faker->realText(400), 'jadwal' => Carbon::tomorrow()->addHours(10), 'location_name' => 'Jakarta Convention Center', 'status' => 'published', 'category_id' => $categories['teknologi']->id, 'organizer_id' => $admin->id
        ]);
        $eventH1->speakers()->attach([$speakers['Dr. Aisyah']->id, $speakers['Bambang S.']->id]);
        $eventH1->tickets()->createMany([
            ['name' => 'Early Bird', 'harga' => 150000, 'stok' => 50],
            ['name' => 'Regular', 'harga' => 250000, 'stok' => 100],
        ]);
        $eventH1->galleries()->createMany([
            ['image_url' => 'placeholders/gallery/1.jpg', 'caption' => 'Suasana tahun lalu'],
            ['image_url' => 'placeholders/gallery/2.jpg', 'caption' => 'Networking session'],
            ['image_url' => 'placeholders/gallery/3.jpg', 'caption' => 'Keynote speaker'],
        ]);

        // --- Event 2: Untuk Tes Notifikasi H-7 ---
        $eventH7 = Event::create([
            'title' => 'Reminder H-7: Marketing Summit', 'slug' => 'reminder-h7-marketing-summit', 'description' => $faker->realText(400), 'jadwal' => now()->addDays(7)->addHours(11), 'location_name' => 'Online via Zoom', 'status' => 'published', 'category_id' => $categories['bisnis']->id, 'organizer_id' => $admin->id
        ]);
        $eventH7->speakers()->attach($speakers['Citra Lestari']->id);
        $eventH7->tickets()->create(['name' => 'Peserta', 'harga' => 50000, 'stok' => 200]);

        // --- Event 3: Event yang Dibatalkan (Soft Deleted) ---
        $eventCancelled = Event::create([
            'title' => 'EVENT DIBATALKAN: Konser Melodi Senja', 'slug' => 'event-dibatalkan-melodi-senja', 'description' => $faker->realText(400), 'jadwal' => now()->addMonth(), 'location_name' => 'Balai Sarbini', 'status' => 'published', 'category_id' => $categories['musik']->id, 'organizer_id' => $admin->id
        ]);
        $eventCancelled->speakers()->attach($speakers['Dira Ananda']->id);
        $eventCancelled->tickets()->create(['name' => 'Festival', 'harga' => 300000, 'stok' => 500]);
        $eventCancelled->delete(); // Soft delete the event

        // --- Event 4: Event Selesai (Completed) ---
        $eventCompleted = Event::create([
            'title' => 'SELESAI: Pameran Seni Rupa', 'slug' => 'selesai-pameran-seni', 'description' => $faker->realText(400), 'jadwal' => now()->subWeeks(2), 'location_name' => 'Galeri Nasional', 'status' => 'completed', 'category_id' => $categories['seni-budaya']->id, 'organizer_id' => $admin->id
        ]);
        $eventCompleted->tickets()->create(['name' => 'Pengunjung', 'harga' => 50000, 'stok' => 1000]);

        // --- Event 5: Event dengan Tiket Habis (Sold Out) ---
        $eventSoldOut = Event::create(['title' => 'Tiket Habis: Workshop Fotografi', 'slug' => 'tiket-habis-workshop-fotografi', 'description' => $faker->realText(400), 'jadwal' => now()->addWeeks(2), 'location_name' => 'Studio KITA', 'status' => 'published', 'category_id' => $categories['seni-budaya']->id, 'organizer_id' => $admin->id]);
        $eventSoldOut->tickets()->create(['name' => 'Peserta', 'harga' => 100000, 'stok' => 0, 'status' => 'sold_out']);

        // --- Event 6: Event Gratis ---
        $eventFree = Event::create(['title' => 'Gratis: Webinar Kesehatan Mental', 'slug' => 'gratis-webinar-kesehatan', 'description' => $faker->realText(400), 'jadwal' => now()->addDays(20), 'location_name' => 'Online via YouTube', 'status' => 'published', 'category_id' => $categories['kesehatan']->id, 'organizer_id' => $admin->id]);
        $eventFree->tickets()->create(['name' => 'Gratis', 'harga' => 0, 'stok' => 1000]);

        $this->command->info('Events dan relasinya (tiket, speaker, galeri) telah dibuat.');
    }

    private function seedUserInteractions()
    {
        $budi = User::where('email', 'budi@mail.com')->first();
        $sita = User::where('email', 'sita@mail.com')->first();
        $rudi = User::where('email', 'rudi@mail.com')->first();

        $eventH1 = Event::where('slug', 'reminder-h1-tech-conf-2025')->first();
        $eventH7 = Event::where('slug', 'reminder-h7-marketing-summit')->first();
        $eventCancelled = Event::withTrashed()->where('slug', 'event-dibatalkan-melodi-senja')->first();
        $eventCompleted = Event::where('slug', 'selesai-pameran-seni')->first();
        $eventFree = Event::where('slug', 'gratis-webinar-kesehatan')->first();

        // --- Skenario Budi ---
        // Tes notifikasi H-1, download tiket
        $regBudi1 = Registration::create(['user_id' => $budi->id, 'event_id' => $eventH1->id, 'ticket_id' => $eventH1->tickets->first()->id, 'jumlah_tiket' => 2, 'total_bayar' => $eventH1->tickets->first()->harga * 2, 'status' => 'confirmed']);
        $regBudi1->eTickets()->createMany([
            ['event_id' => $eventH1->id, 'ticket_code' => 'BUDI-'.Str::upper(Str::random(8))],
            ['event_id' => $eventH1->id, 'ticket_code' => 'BUDI-'.Str::upper(Str::random(8))],
        ]);
        // Tes notifikasi H-7
        $regBudi2 = Registration::create(['user_id' => $budi->id, 'event_id' => $eventH7->id, 'ticket_id' => $eventH7->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $eventH7->tickets->first()->harga, 'status' => 'confirmed']);
        $regBudi2->eTickets()->create(['event_id' => $eventH7->id, 'ticket_code' => 'BUDI-'.Str::upper(Str::random(8))]);
        // Tes tiket untuk event yang dibatalkan
        $regBudi3 = Registration::create(['user_id' => $budi->id, 'event_id' => $eventCancelled->id, 'ticket_id' => $eventCancelled->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $eventCancelled->tickets->first()->harga, 'status' => 'confirmed']);
        $regBudi3->eTickets()->create(['event_id' => $eventCancelled->id, 'ticket_code' => 'BUDI-'.Str::upper(Str::random(8))]);

        // --- Skenario Sita ---
        // Tes tombol "Beri Ulasan"
        $regSita1 = Registration::create(['user_id' => $sita->id, 'event_id' => $eventCompleted->id, 'ticket_id' => $eventCompleted->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $eventCompleted->tickets->first()->harga, 'status' => 'confirmed']);
        $regSita1->eTickets()->create(['event_id' => $eventCompleted->id, 'ticket_code' => 'SITA-'.Str::upper(Str::random(8))]);
        // Tes status "cancelled"
        $regSita2 = Registration::create(['user_id' => $sita->id, 'event_id' => $eventH1->id, 'ticket_id' => $eventH1->tickets->last()->id, 'jumlah_tiket' => 1, 'total_bayar' => $eventH1->tickets->last()->harga, 'status' => 'cancelled']);
        // Tes pending payment
        Registration::create(['user_id' => $sita->id, 'event_id' => $eventH7->id, 'ticket_id' => $eventH7->tickets->first()->id, 'jumlah_tiket' => 3, 'total_bayar' => $eventH7->tickets->first()->harga * 3, 'status' => 'pending_payment']);

        // --- Skenario Rudi ---
        // Tes event gratis
        $regRudi1 = Registration::create(['user_id' => $rudi->id, 'event_id' => $eventFree->id, 'ticket_id' => $eventFree->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => 0, 'status' => 'confirmed']);
        $regRudi1->eTickets()->create(['event_id' => $eventFree->id, 'ticket_code' => 'RUDI-'.Str::upper(Str::random(8))]);
        // Tes sudah memberi ulasan
        $regRudi2 = Registration::create(['user_id' => $rudi->id, 'event_id' => $eventCompleted->id, 'ticket_id' => $eventCompleted->tickets->first()->id, 'jumlah_tiket' => 1, 'total_bayar' => $eventCompleted->tickets->first()->harga, 'status' => 'confirmed']);
        $regRudi2->eTickets()->create(['event_id' => $eventCompleted->id, 'ticket_code' => 'RUDI-'.Str::upper(Str::random(8))]);
        $eventCompleted->reviews()->create(['user_id' => $rudi->id, 'rating' => 5, 'comment' => 'Pameran yang menginspirasi!']);

        // --- Interaksi Tambahan ---
        $budi->bookmarkedEvents()->attach($eventH7->id);
        $sita->bookmarkedEvents()->attach($eventFree->id);

        $this->command->info('Interaksi pengguna (registrasi, review, bookmark) telah dibuat.');
    }
}