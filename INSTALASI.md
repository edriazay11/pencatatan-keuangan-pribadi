# Panduan Instalasi Sistem Pencatatan Keuangan Pribadi

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi (atau MariaDB 10.2+)
- XAMPP / WAMP / LAMP (atau web server lainnya)
- Web browser modern (Chrome, Firefox, Edge, dll)

## Langkah Instalasi

### 1. Persiapan Database

1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru dengan nama: `sistem_pencatatan_keuangan`
3. Import file SQL:
   - Import `sql/database.sql` terlebih dahulu (untuk tabel dasar)
   - Import `sql/users_and_sharing.sql` atau `sql/users_and_sharing_simple.sql` (untuk tabel users dan sharing)

**Catatan:** Jika `users_and_sharing.sql` error, gunakan `users_and_sharing_simple.sql` sebagai alternatif.

### 2. Konfigurasi Database

Edit file `config/config.php` dan sesuaikan dengan konfigurasi database Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Sesuaikan dengan username MySQL Anda
define('DB_PASS', '');             // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'sistem_pencatatan_keuangan');
```

### 3. Konfigurasi URL

Edit file `config/config.php` dan sesuaikan URL aplikasi:

```php
define('SITE_URL', 'http://localhost/Pencatatan_uang_pribadi');
```

Ganti `Pencatatan_uang_pribadi` dengan nama folder proyek Anda.

### 4. Akses Aplikasi

1. Buka browser
2. Akses: `http://localhost/Pencatatan_uang_pribadi/pages/info.php`
3. Atau langsung ke: `http://localhost/Pencatatan_uang_pribadi/` (akan redirect ke login jika belum login)

### 5. Login Pertama Kali

**User Admin Default:**
- Username: `admin`
- Password: `admin123`

**Catatan:** Setelah login pertama kali, disarankan untuk:
1. Ganti password admin
2. Buat user baru melalui halaman Register
3. Hapus atau ubah password user admin default

## Struktur File

```
Pencatatan_uang_pribadi/
├── assets/              # File CSS dan JavaScript
├── auth/                # File autentikasi (login, register, logout)
├── config/              # File konfigurasi
├── includes/            # File header dan footer
├── pages/               # Halaman aplikasi
├── sql/                 # File SQL untuk database
├── index.php            # Dashboard utama
└── README.md            # Dokumentasi
```

## Fitur Aplikasi

1. **Halaman Informasi (Landing Page)** - `/pages/info.php`
2. **Login & Register** - `/auth/login.php` dan `/auth/register.php`
3. **Dashboard** - `/index.php`
4. **Manajemen Pemasukan** - `/pages/pemasukan.php`
5. **Manajemen Pengeluaran** - `/pages/pengeluaran.php`
6. **Laporan Keuangan** - `/pages/laporan.php`
7. **Berbagi Data Keuangan** - `/pages/sharing.php`

## Troubleshooting

### Error: "Koneksi gagal"
- Pastikan MySQL service berjalan
- Periksa konfigurasi di `config/config.php`
- Pastikan database sudah dibuat

### Error: "Table doesn't exist"
- Pastikan sudah import file SQL
- Periksa nama database di `config/config.php`

### Error: "Access denied"
- Periksa username dan password MySQL
- Pastikan user MySQL memiliki akses ke database

### Halaman kosong atau error
- Aktifkan error reporting di `config/config.php` (sudah aktif secara default)
- Periksa log error PHP
- Pastikan semua file sudah ter-upload dengan lengkap

## Keamanan

1. **Jangan commit file `config/config.php`** ke repository public
2. **Ganti password default** setelah instalasi
3. **Gunakan HTTPS** di production
4. **Backup database** secara berkala
5. **Update PHP** ke versi terbaru

## Support

Jika mengalami masalah, periksa:
1. Log error PHP
2. Log error MySQL
3. Dokumentasi di `README.md`
4. File `GITHUB_INSTRUCTIONS.md` untuk panduan upload ke GitHub


