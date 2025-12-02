VentNice adalah platform web untuk pengelolaan, pencarian, dan pemesanan event. Aplikasi ini dirancang untuk memudahkan pengguna menemukan event, melakukan pendaftaran, mendapatkan e-ticket, hingga melihat riwayat event yang pernah mereka ikuti. Admin dapat mengelola event, peserta, galeri, hingga melakukan ekspor data peserta dengan mudah.
Fitur Utama
1. Sistem Pendaftaran Event & Pengiriman E-Tiket

Pengguna dapat mendaftar event secara online.

Setelah berhasil mendaftar, sistem otomatis mengirim E-Tiket

2. Halaman “Tiket Saya” (Riwayat Pendaftaran)

Peserta dapat melihat daftar event yang pernah diikuti.

Menampilkan detail event, status kehadiran, dan e-ticket.

3. Filter Event (Kategori, Tanggal, Lokasi, Harga)

Memudahkan pengguna menelusuri event sesuai kebutuhan.

4. Pencarian Event

Search bar untuk mencari event berdasarkan judul, penyelenggara, lokasi, dan kategori.

5. Ekspor Daftar Peserta (Excel/CSV)

Admin dapat mengunduh daftar peserta dalam format Excel (.xlsx) atau CSV.

6. Galeri Foto Post-Event

Menampilkan dokumentasi foto setelah event selesai berlangsung.

7. Tampilan Kalender Event

Kalender interaktif yang menunjukkan tanggal dan jadwal event.

Mendukung highlight event mendatang.

8. Sistem Ulasan & Rating Event

Peserta yang telah hadir dapat memberi ulasan dan rating terhadap event.

9. Fitur “Simpan Event” (Bookmark)

Pengguna dapat menyimpan event favorit untuk dilihat nanti.

10. Notifikasi Reminder H-1 Event (Email)

Sistem mengirim email pengingat 1 hari sebelum event dimulai.

Teknologi yang Digunakan

Laravel 12 (Framework utama)
Livewire 3 (Komponen interaktif tanpa JavaScript manual)
MySQL (Database)
Bootsrap template mazer (UI styling)
Alpine.js (Interaksi ringan di frontend)
Laravel Excel / Maatwebsite Excel (Ekspor Excel/CSV)
Blade Template Engine

1. Clone Repository
git clone https://github.com/username/ventnice.git
cd ventnice

2. Install Dependency Composer
composer install

4. Install Dependency Frontend
npm install

6. Generate App key
php artisan key:generate

8. Konfigurasi Database
DB_DATABASE=ventnice
DB_USERNAME=root
DB_PASSWORD=

9. Migrasi Database
php artisan migrate

10. php artisan db:seed

11. Build Asset Frontend
Npm run dev

Cara Menjalankan Project

1. jalankan server laravel
php artisan serve
