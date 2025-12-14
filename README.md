# VentNice - Sistem Manajemen Event

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-11.x-orange.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Livewire-3.x-f05a28.svg" alt="Livewire Version">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952b3.svg" alt="Bootstrap Version">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38b2ac.svg" alt="Tailwind CSS Version">
</p>

**VentNice** adalah sebuah platform manajemen event (Event Management System) modern berbasis web yang dibangun menggunakan TALL stack. Proyek ini dirancang untuk memberikan pengalaman yang mulus bagi dua jenis pengguna utama: **Peserta** yang mencari dan mendaftar event, dan **Admin** (Penyelenggara) yang mengelola keseluruhan event dari A sampai Z.

---

## 1. Fitur Utama

### Untuk Peserta (Frontend)
- **Jelajah Event:** Landing page interaktif dengan fitur pencarian dan filter berdasarkan kategori.
- **Detail Event Komprehensif:** Halaman detail untuk setiap event yang mencakup deskripsi, jadwal, lokasi, pembicara, galeri foto (dengan *lightbox*), serta ulasan dan rating dari peserta lain.
- **Pendaftaran Pengguna:** Sistem otentikasi lengkap (login, register).
- **Pendaftaran Event:** Proses pendaftaran tiket melalui form modal yang dinamis.
- **Halaman "Tiket Saya":** Dasbor pribadi untuk melihat semua tiket yang telah dipesan.
  - Menampilkan status tiket (Menunggu Pembayaran, Dikonfirmasi, Dibatalkan).
  - Menampilkan E-Ticket dengan QR Code unik.
  - Fitur **Download PDF** untuk E-Ticket.
  - Indikator visual jika event dibatalkan oleh penyelenggara.
- **Sistem Bookmark:** Menyimpan event yang disukai.
- **Sistem Review:** Memberikan rating dan ulasan untuk event yang telah selesai.

### Untuk Admin (Backend)
- **Dasbor Admin:** Halaman utama yang berisi ringkasan dan statistik (konsep).
- **Manajemen Event (CRUD):**
  - Membuat, membaca, mengedit, dan menghapus (soft delete) event.
  - Form multi-langkah yang intuitif untuk membuat dan mengedit event (Info Dasar, Tiket, Pembicara, Galeri).
  - Logika *smart-sync* yang aman untuk update tiket tanpa menghapus data registrasi yang ada.
  - Manajemen galeri dengan FilePond yang mendukung *multiple upload*.
- **Manajemen Master Data:** CRUD untuk Kategori dan Pembicara.
- **Manajemen Registrasi:**
  - Melihat daftar semua pendaftar dengan filter (berdasarkan event, status, tanggal).
  - Fitur **Ekspor ke Excel** yang mengikuti filter yang sedang aktif.
  - Melihat detail setiap registrasi.
  - Mengubah status registrasi (Konfirmasi Pembayaran, Batalkan Pesanan).
- **Notifikasi Email Otomatis:**
  - Sistem pengingat otomatis H-7 dan H-1 sebelum event berlangsung.
  - Menggunakan sistem antrian (Queue) untuk pengiriman email yang andal dan tidak membebani server.

---

## 2. Teknologi & Arsitektur
- **Backend:** Laravel 12
- **Frontend:** Livewire 3 (berperan sebagai *full-stack framework*) & Alpine.js
- **UI Admin:** Template Mazer (berbasis Bootstrap 5)
- **UI Publik:** Desain kustom menggunakan Tailwind CSS
- **Database:** MySQL
- **Asset Bundling:** Vite
- **Fitur Tambahan:**
  - `maatwebsite/excel`: Untuk fungsionalitas ekspor data ke Excel.
  - `barryvdh/laravel-dompdf`: Untuk generasi E-Ticket dalam format PDF.
  - `chillerlan/php-qrcode`: Untuk membuat QR Code pada E-Ticket.

---

## 3. Panduan Instalasi Lokal

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1.  **Clone Repository**
    ```bash
    git clone [URL_REPOSITORY_ANDA]
    cd [NAMA_FOLDER_PROYEK]
    ```

2.  **Instalasi Dependencies**
    Pastikan Anda memiliki Composer dan Node.js/NPM terinstal.
    ```bash
    composer install
    npm install
    ```

3.  **Setup Lingkungan (.env)**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Setelah itu, buka file `.env` dan konfigurasikan koneksi database Anda (variabel `DB_*`).

4.  **Konfigurasi Database & Seeding**
    Jalankan migrasi untuk membuat semua tabel dan langsung isi dengan data sampel (termasuk user admin, peserta, dan berbagai skenario event).
    ```bash
    php artisan migrate --seed
    ```
    *Catatan: Seeder akan membersihkan data lama dan mengisi dengan data baru yang siap untuk demo.*

5.  **Storage Link**
    Perintah ini penting untuk membuat gambar yang diunggah dapat diakses secara publik.
    ```bash
    php artisan storage:link
    ```

6.  **Jalankan Aplikasi**
    - Buka terminal pertama untuk menjalankan server development Vite.
      ```bash
      npm run dev
      ```
    - Buka terminal kedua untuk menjalankan server Laravel.
      ```bash
      php artisan serve
      ```

7.  **Jalankan Queue Worker (Penting untuk Email)**
    Untuk memproses antrian pengiriman email, buka terminal ketiga.
    ```bash
    php artisan queue:work
    ```

Aplikasi Anda sekarang berjalan di `http://127.0.0.1:8000`.

---

## 4. Panduan Demo & Skenario Penggunaan

Gunakan data dari seeder untuk mencoba berbagai fitur.

**Akun Demo:**
- **Admin:** `admin@webevent.com` / `password`
- **Peserta:** `budi@mail.com` / `password` (dan `sita@mail.com`, `rudi@mail.com`)

### Skenario 1: Alur Peserta
1.  Buka browser dan login sebagai `budi@mail.com`.
2.  Anda akan diarahkan ke halaman "Tiket Saya".
3.  Perhatikan tiket untuk **"EVENT DIBATALKAN"**. Akan ada overlay gelap yang menandakan event tersebut batal.
4.  Coba fitur **Download PDF** pada tiket **"Reminder H-1: Tech Conference 2025"**. Sebuah file PDF berisi E-Ticket dan QR Code akan terunduh.
5.  Klik menu "Jelajah Event" untuk kembali ke landing page.
6.  Gunakan fitur pencarian atau filter kategori untuk menemukan event.
7.  Klik pada salah satu event untuk masuk ke halaman detail. Perhatikan semua informasi yang tampil: deskripsi, pembicara, ulasan, dan galeri.
8.  Klik salah satu gambar di galeri untuk mengetes fitur **lightbox** dan navigasinya.

### Skenario 2: Alur Admin
1.  Login sebagai `admin@webevent.com`.
2.  Masuk ke menu **Manajemen Event**. Di sini Anda bisa membuat event baru, mengedit, atau menghapus (soft delete) event yang ada.
3.  Masuk ke menu **Manajemen Registrasi**.
4.  Gunakan berbagai filter yang tersedia (misalnya, filter berdasarkan event atau status "Confirmed").
5.  Setelah memfilter, klik tombol **"Export Excel"**. File Excel yang terunduh hanya akan berisi data yang sesuai dengan filter Anda.
6.  Pada salah satu registrasi yang berstatus "Pending", klik tombol centang hijau untuk **mengkonfirmasi pembayaran**.

### Skenario 3: Menguji Notifikasi Email Otomatis
1.  Pastikan seeder sudah dijalankan. Seeder telah membuat event yang akan berlangsung besok (H-1) dan 7 hari lagi (H-7).
2.  Jalankan command scheduler secara manual di terminal:
    ```bash
    php artisan app:send-event-reminders
    ```
3.  Periksa terminal tempat `php artisan queue:work` berjalan. Anda akan melihat `SendEventReminderEmailJob` diproses.
4.  Periksa penangkap email Anda (misal: Mailtrap) untuk melihat email pengingat yang dikirim ke `budi@mail.com`.
