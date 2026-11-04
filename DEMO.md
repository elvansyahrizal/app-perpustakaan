# Skenario Demo Aplikasi Perpustakaan Digital Kampus

Dokumen ini memuat panduan langkah demi langkah demonstrasi aplikasi untuk persiapan Ujian Akhir Semester (UAS).

---

## Persiapan Sebelum Demo

1. Pastikan database telah dimigrasi dan diisi data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
2. Jalankan dua instance server di dua terminal berbeda:
   - **Terminal 1**: `php artisan serve --port=8000` (Server Utama)
   - **Terminal 2**: `php artisan serve --port=8011` (Server API Internal)
3. Buka browser pada URL: `http://127.0.0.1:8000`

---

## Urutan Skenario Demo

### 1. Login ke Sistem
- Buka `http://127.0.0.1:8000/login`.
- Masukkan kredensial akun Admin:
  - **Email**: `admin@pens.ac.id`
  - **Password**: `password`
- Klik tombol **Login**.
- **Hasil yang Diharapkan**: Berhasil login dan langsung diarahkan ke halaman **Dashboard** (`/`) dengan notifikasi selamat datang.

### 2. Tampilkan Dashboard
- Periksa 3 kartu statistik di Dashboard:
  - **Total Buku**: 20
  - **Total Anggota**: 15
  - **Peminjaman Aktif**: Menampilkan jumlah peminjaman berstatus `dipinjam`.
- Jelaskan bahwa data statistik ini dikonsumsi secara dinamis dari API internal `GET /api/stats` via Laravel HTTP Client (`Http::get()`).

### 3. Tambah Kategori Buku (Khusus Admin)
- Klik menu **Kategori** di navbar (`/categories`).
- Klik tombol **Tambah Kategori** (`/categories/create`).
- Isi form:
  - **Nama Kategori**: `Psikologi`
  - **Deskripsi**: `Buku seputar kesehatan mental dan perilaku manusia.`
- Klik **Simpan**.
- **Hasil yang Diharapkan**: Kategori baru berhasil disimpan dan muncul di tabel daftar kategori dengan flash message sukses.

### 4. Tambah Buku Baru
- Klik menu **Buku** di navbar (`/books`).
- Klik tombol **Tambah Buku** (`/books/create`).
- Isi form:
  - **Judul**: `Atomic Habits`
  - **Penulis**: `James Clear`
  - **Penerbit**: `Gramedia Pustaka Utama`
  - **Tahun Terbit**: `2018`
  - **ISBN**: `978-6020633176`
  - **Stok**: `7`
  - **Kategori**: Pilih `Psikologi`
- Klik **Simpan**.
- **Hasil yang Diharapkan**: Buku berhasil ditambahkan dan tampil di daftar buku. Tunjukkan juga bahwa navigasi pagination teks (`« Sebelumnya`, `1`, `2`, `Berikutnya »`) di bawah tabel bekerja rapi.

### 5. Tambah Anggota Baru
- Klik menu **Anggota** di navbar (`/members`).
- Klik tombol **Tambah Anggota** (`/members/create`).
- Isi form:
  - **Nama**: `Rian Pratama`
  - **NIM**: `3123500099`
  - **Email**: `rian.pratama@pens.ac.id`
  - **Nomor Telepon**: `081234567890`
  - **Alamat**: `Jl. Sukolilo No. 12, Surabaya`
  - **Status**: `Aktif`
- Klik **Simpan**.
- **Hasil yang Diharapkan**: Anggota baru tercatat di database dan tabel anggota.

### 6. Buat Transaksi Peminjaman
- Klik menu **Peminjaman** di navbar (`/loans`).
- Klik tombol **Tambah Peminjaman** (`/loans/create`).
- Isi formulir peminjaman:
  - **Anggota**: Pilih `Rian Pratama`
  - **Buku**: Pilih buku `Atomic Habits` (dan buku lainnya jika diinginkan)
  - **Tanggal Pinjam**: Tanggal hari ini
  - **Tanggal Kembali**: 7 hari ke depan
- Klik **Simpan**.
- **Hasil yang Diharapkan**: Transaksi peminjaman tersimpan dengan status `dipinjam`, dan petugas pencatat otomatis terisi nama akun yang sedang login.

### 7. Lihat Daftar Peminjaman Aktif
- Masuk ke menu **Peminjaman** (`/loans`).
- Tunjukkan transaksi yang baru saja dibuat berstatus `dipinjam` (badge kuning).

### 8. Proses Pengembalian Buku
- Pada baris transaksi peminjaman milik `Rian Pratama`, klik tombol **Kembalikan** (atau buka detail lalu kembalikan).
- **Hasil yang Diharapkan**: Status peminjaman berubah menjadi `dikembalikan` (badge hijau) dan tanggal dikembalikan otomatis tercatat hari ini.

### 9. Tampilkan Laporan Peminjaman
- Klik menu **Laporan** di navbar (`/loans/report`).
- Jelaskan bahwa data tabel laporan ini dikonsumsi dari endpoint API `GET /api/loans` menggunakan format array JSON hasil transformasi API Resource (`LoanResource`).
- Tunjukkan bahwa navigasi pagination laporan berfungsi dengan baik.

### 10. Demo REST API di Postman
- Buka aplikasi **Postman**.
- Uji beberapa endpoint utama (base URL `http://127.0.0.1:8000/api` atau `http://127.0.0.1:8011/api`):
  1. `GET /api/stats` -> Mengembalikan JSON ringkasan angka statistik.
  2. `GET /api/books?judul=Atomic` -> Menampilkan hasil pencarian buku terfilter.
  3. `GET /api/loans` -> Menampilkan daftar transaksi dengan relasi nested `member`, `petugas`, dan `books`.
  4. `POST /api/books` dengan body JSON -> Mengembalikan status `201 Created`.

