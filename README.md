<<<<<<< HEAD
# pencatatan-keuangan-pribadi
=======
# 💰 Sistem Pencatatan Keuangan Pribadi

Aplikasi web berbasis PHP Native untuk manajemen keuangan pribadi tanpa framework apapun. Dibangun dengan PHP, MySQL, HTML, CSS (pure), dan JavaScript (vanilla).

## 📋 Fitur

✅ **Dashboard Keuangan**
- Ringkasan pemasukan dan pengeluaran
- Saldo akhir
- Filter berdasarkan periode
- Daftar transaksi terakhir

✅ **Manajemen Pemasukan**
- Tambah, Edit, Hapus pemasukan
- Kategori pemasukan
- Pencarian dan filter
- Pagination

✅ **Manajemen Pengeluaran**
- Tambah, Edit, Hapus pengeluaran
- Kategori pengeluaran
- Pencarian dan filter
- Pagination

✅ **Laporan Keuangan**
- Laporan per kategori
- Laporan harian (30 hari terakhir)
- Grafik saldo

✅ **Fitur Tambahan**
- Responsive design
- Validasi form dengan JavaScript
- Konfirmasi sebelum hapus
- Format currency Indonesia (Rupiah)
- Alert notification

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP Native (tanpa framework)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3 (Pure), JavaScript (Vanilla)
- **Server**: Apache (XAMPP/LAMPP)

## 📁 Struktur Folder

```
Pencatatan_uang_pribadi/
├── assets/
│   ├── css/
│   │   └── style.css              # Stylesheet utama
│   └── js/
│       └── script.js              # JavaScript untuk validasi & interaksi
├── config/
│   ├── config.php                 # Konfigurasi aplikasi
│   └── koneksi.php                # Koneksi database
├── includes/
│   ├── header.php                 # Header template
│   └── footer.php                 # Footer template
├── pages/
│   ├── pemasukan.php             # Halaman CRUD pemasukan
│   ├── process_pemasukan.php     # Proses CRUD pemasukan
│   ├── pengeluaran.php           # Halaman CRUD pengeluaran
│   ├── process_pengeluaran.php   # Proses CRUD pengeluaran
│   └── laporan.php               # Halaman laporan keuangan
├── sql/
│   └── database.sql              # Struktur database & data sample
├── index.php                     # Halaman dashboard
└── README.md                     # Dokumentasi
```

## 🚀 Instalasi

### 1. Persiapan Server

Pastikan Anda sudah menginstall:
- XAMPP/LAMPP atau web server dengan PHP dan MySQL
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi

### 2. Setup Database

1. Buat database di phpMyAdmin atau via terminal:
```sql
CREATE DATABASE sistem_pencatatan_keuangan;
```

2. Import file SQL:
   - Buka phpMyAdmin: http://localhost/phpmyadmin
   - Pilih database `sistem_pencatatan_keuangan`
   - Klik tab "Import"
   - Pilih file `sql/database.sql`
   - Klik "Go"

   **atau via terminal MySQL:**
```bash
mysql -u root -p sistem_pencatatan_keuangan < sql/database.sql
```

### 3. Konfigurasi Database

Edit file `config/config.php` dan sesuaikan dengan setting database Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // username MySQL Anda
define('DB_PASS', '');             // password MySQL Anda
define('DB_NAME', 'sistem_pencatatan_keuangan');
```

### 4. Akses Aplikasi

1. Start Apache dan MySQL di XAMPP Control Panel
2. Buka browser dan akses:
```
http://localhost/Pencatatan_uang_pribadi/
```

## 📊 Struktur Database

### Tabel: `kategori_pemasukan`
Menampung kategori untuk pemasukan.

### Tabel: `kategori_pengeluaran`
Menampung kategori untuk pengeluaran.

### Tabel: `pemasukan`
Menyimpan data pemasukan.

### Tabel: `pengeluaran`
Menyimpan data pengeluaran.

**Relasi Database:**
- `pemasukan` ← Foreign Key → `kategori_pemasukan`
- `pengeluaran` ← Foreign Key → `kategori_pengeluaran`

## 📝 Cara Penggunaan

### 1. Dashboard
- Beranda aplikasi menampilkan ringkasan keuangan
- Filter periode untuk melihat data dalam rentang waktu tertentu
- Menampilkan transaksi 10 terakhir

### 2. Manajemen Pemasukan
- Klik menu "Pemasukan" di header
- Klik tombol "+ Tambah Pemasukan"
- Isi form: Kategori, Tanggal, Jumlah, Deskripsi
- Gunakan fitur search dan filter untuk mencari data
- Edit atau Hapus data yang sudah ada

### 3. Manajemen Pengeluaran
- Klik menu "Pengeluaran" di header
- Proses sama seperti pemasukan
- Format dan validasi otomatis

### 4. Laporan
- Lihat laporan per kategori
- Lihat laporan harian
- Filter berdasarkan periode

## 🎨 Styling & Theme

Aplikasi menggunakan:
- **Color Scheme**: Purple gradient untuk header
- **Design**: Modern dengan card-based layout
- **Responsive**: Mobile-friendly menggunakan CSS Grid & Flexbox
- **Icons**: Emoji icons untuk visual clarity

## 🔒 Keamanan

- Prepared Statements untuk mencegah SQL Injection
- Form validation di client & server
- XSS prevention dengan `htmlspecialchars()`
- Sanitized input data

## 📱 Fitur Validasi

- Validasi form dengan JavaScript
- Konfirmasi sebelum hapus data
- Validasi jumlah harus positif
- Validasi tanggal tidak boleh melebihi hari ini
- Format otomatis untuk currency

## 🐛 Troubleshooting

### Error: Koneksi Database Gagal
- Pastikan MySQL service sudah running
- Periksa username dan password di `config/config.php`
- Pastikan database sudah dibuat

### Error: Call to undefined function
- Pastikan extension PHP sudah diaktifkan
- Start ulang Apache setelah perubahan php.ini

### Error: 404 Not Found
- Pastikan mod_rewrite aktif (jika perlu)
- Periksa file .htaccess (jika ada)

## 📞 Kontak & Support

Untuk pertanyaan atau issue, silakan buat issue di repository ini.

## 📄 License

Project ini dibuat untuk keperluan edukasi dan penggunaan pribadi.

---

**Dibuat dengan ❤️ menggunakan PHP Native**

>>>>>>> db22ff2 (first init github)
