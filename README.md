# Sistem Perpustakaan Digital Kampus

Aplikasi manajemen perpustakaan berbasis web menggunakan Laravel 12 dengan arsitektur MVC, REST API internal, dan Blade templating.

---

## Prasyarat

- PHP >= 8.2
- Composer
- MySQL / MariaDB

---

## Panduan Instalasi & Konfigurasi

### 1. Clone Repository & Install Dependensi
```bash
git clone <repository-url>
cd app-perpustakaan
composer install
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` atau buat `.env` baru:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan variabel-variabel berikut diatur di dalam file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
DB_PASSWORD=

APP_FAKER_LOCALE=id_ID

# Base URL instance kedua php artisan serve khusus panggilan API internal
# (Http::get() dari DashboardController dan LoanController@report ke /api/...)
INTERNAL_API_URL=http://127.0.0.1:8011
```

### 3. Migrasi Database & Seeding
Jalankan migrasi dan isi database dengan data dummy awal (users, categories, books, members, loans):
```bash
php artisan migrate:fresh --seed
```

Data pengguna awal dari seeder:
- **Admin**: `admin@pens.ac.id` | Password: `password` (Nama: Bambang Sutrisno)
- **Petugas 1**: `petugas1@pens.ac.id` | Password: `password` (Nama: Siti Rahmawati)
- **Petugas 2**: `petugas2@pens.ac.id` | Password: `password` (Nama: Ahmad Fauzi)

---

## Menjalankan Aplikasi (Dual Server)

Karena `php artisan serve` (PHP built-in server) bersifat *single-threaded* dan menangani satu request dalam satu waktu, pemanggilan API internal dari Controller (`DashboardController` dan `LoanController@report`) ke endpoint `/api/...` memerlukan **dua server berjalan bersamaan di port berbeda** untuk mencegah terjadinya *deadlock*:

### Terminal 1 - Server Utama (Web & Browser)
```bash
php artisan serve --port=8000
```
Akses aplikasi melalui browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Terminal 2 - Server API Internal
```bash
php artisan serve --port=8011
```
Server ini khusus melayani request internal dari aplikasi sendiri melalui Laravel HTTP Client (`Http::get()`).

---

## Fitur Utama

1. **Autentikasi & Otorisasi**: Role Admin & Petugas dengan middleware proteksi.
2. **Dashboard**: Ringkasan statistik (total buku, anggota, peminjaman aktif) dikonsumsi dari `GET /api/stats`.
3. **CRUD Buku, Kategori & Anggota**: Dilengkapi form request validation dan pencarian.
4. **Peminjaman & Pengembalian**: Mendukung multiple buku dalam satu transaksi dan pelacakan status.
5. **Laporan Peminjaman**: Ditampilkan via Blade dari konsumsi `GET /api/loans`.
6. **REST API**: 11 endpoint publik dengan format API Resource (`/api/books`, `/api/members`, `/api/loans`, `/api/stats`).
7. **Kustom Pagination**: Tampilan teks/link rapi tanpa ketergantungan library Tailwind.
