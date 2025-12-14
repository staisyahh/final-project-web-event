<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Tabel Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Tabel Speakers
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable(); // Gelar/Jabatan
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('jadwal'); // Timestamp untuk waktu acara
            $table->string('location_name')->nullable();
            $table->text('location_address')->nullable();
            $table->string('banner_url')->nullable(); // [REVISI] Tambahan
            $table->enum('status', ['draft', 'published', 'completed', 'archived'])->default('draft');

            // Foreign Keys
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes(); // Best practice: Jangan hapus permanen event yang ada transaksinya
        });

        // 5. Tabel Event Speakers (Pivot)
        Schema::create('event_speakers', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('speaker_id')->constrained('speakers')->cascadeOnDelete();
            $table->primary(['event_id', 'speaker_id']); // Composite Key
        });

        // 6. Tabel Tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name'); // Contoh: VIP, Reguler
            $table->decimal('harga', 10, 2)->default(0);
            $table->integer('stok');
            $table->enum('status', ['available', 'sold_out'])->default('available');
            $table->timestamps();
        });

        // 7. Tabel Registrations (Orders)
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            $table->integer('jumlah_tiket')->default(1);
            $table->decimal('total_bayar', 12, 2);
            $table->enum('status', ['pending_payment', 'confirmed', 'cancelled'])->default('pending_payment');

            $table->timestamps();
        });

        // 8. Tabel E-Tickets (Detail Tiket Individual & Absensi)
        Schema::create('e_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            $table->string('ticket_code', 100)->unique(); // QR Code String
            $table->enum('status', ['active', 'checked_in'])->default('active');
            $table->string('file_path')->nullable(); // Path PDF (Opsional)

            $table->timestamp('created_at')->useCurrent();
        });

        // 9. Tabel Event Galleries
        Schema::create('event_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 10. Tabel Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Mencegah user mereview event yang sama berkali-kali
            $table->unique(['user_id', 'event_id']);
        });

        // 11. Tabel Bookmarks
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'event_id']); // Toggle behavior
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle Foreign Key constraints
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('event_galleries');
        Schema::dropIfExists('e_tickets');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('event_speakers');
        Schema::dropIfExists('events');
        Schema::dropIfExists('speakers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};
