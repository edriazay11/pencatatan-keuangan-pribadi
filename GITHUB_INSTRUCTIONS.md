# Instruksi Upload ke GitHub

Panduan singkat untuk mengupload proyek Sistem Pencatatan Keuangan Pribadi ke GitHub.

## Prasyarat

1. Pastikan Git sudah terinstall di komputer Anda
2. Pastikan Anda sudah memiliki akun GitHub
3. Pastikan Anda sudah membuat repository baru di GitHub (kosong atau dengan README)

## Langkah-langkah

### 1. Inisialisasi Git Repository

Buka terminal/command prompt di folder proyek Anda (`C:\xampp\htdocs\Pencatatan_uang_pribadi`) dan jalankan:

```bash
git init
```

### 2. Buat File .gitignore (Opsional tapi Disarankan)

Buat file `.gitignore` di root folder untuk mengabaikan file yang tidak perlu di-commit:

```
# XAMPP
.htaccess
error_log

# IDE
.vscode/
.idea/
*.sublime-project
*.sublime-workspace

# OS
.DS_Store
Thumbs.db
desktop.ini

# Temporary files
*.tmp
*.log
*.cache
```

### 3. Tambahkan Semua File ke Staging Area

```bash
git add .
```

Atau jika ingin menambahkan file tertentu saja:

```bash
git add index.php
git add pages/
git add auth/
git add config/
# ... dan seterusnya
```

### 4. Commit Perubahan

```bash
git commit -m "Initial commit: Sistem Pencatatan Keuangan Pribadi dengan fitur login, register, dan sharing"
```

Atau dengan pesan yang lebih detail:

```bash
git commit -m "Initial commit

- Fitur login dan register
- Halaman landing page (info.php)
- Fitur berbagi data keuangan (multi-user sharing)
- Session management
- Dashboard keuangan
- Manajemen pemasukan dan pengeluaran
- Laporan keuangan"
```

### 5. Tambahkan Remote Repository

Ganti `YOUR_USERNAME` dan `YOUR_REPOSITORY_NAME` dengan username GitHub dan nama repository Anda:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git
```

Contoh:
```bash
git remote add origin https://github.com/johndoe/pencatatan-keuangan-pribadi.git
```

### 6. Push ke GitHub

```bash
git branch -M main
git push -u origin main
```

Jika menggunakan branch `master`:
```bash
git branch -M master
git push -u origin master
```

### 7. Verifikasi

Buka browser dan kunjungi repository GitHub Anda untuk memastikan semua file sudah ter-upload.

## Catatan Penting

### Jangan Commit File Sensitif

Pastikan file `config/config.php` tidak mengandung informasi sensitif yang seharusnya tidak di-share. Jika perlu, buat file `config/config.example.php` sebagai template.

### Update .gitignore untuk File Konfigurasi

Tambahkan ke `.gitignore` jika tidak ingin commit file konfigurasi:

```
config/config.php
```

### Push Perubahan Selanjutnya

Setelah perubahan pertama, untuk push perubahan selanjutnya:

```bash
git add .
git commit -m "Deskripsi perubahan"
git push
```

## Troubleshooting

### Error: "remote origin already exists"

Jika remote sudah ada, hapus dulu:
```bash
git remote remove origin
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git
```

### Error: "failed to push some refs"

Jika ada konflik dengan remote repository:
```bash
git pull origin main --allow-unrelated-histories
git push -u origin main
```

### Error: Authentication failed

Gunakan Personal Access Token (PAT) sebagai password:
1. Buka GitHub Settings > Developer settings > Personal access tokens
2. Generate new token dengan scope `repo`
3. Gunakan token sebagai password saat push

## Struktur Repository di GitHub

Setelah berhasil di-upload, struktur repository akan seperti ini:

```
Pencatatan_uang_pribadi/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── info.css
│   └── js/
│       └── script.js
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── check_session.php
├── config/
│   ├── config.php
│   └── koneksi.php
├── includes/
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── info.php
│   ├── pemasukan.php
│   ├── pengeluaran.php
│   ├── laporan.php
│   ├── sharing.php
│   ├── process_pemasukan.php
│   └── process_pengeluaran.php
├── sql/
│   ├── database.sql
│   └── users_and_sharing.sql
├── index.php
├── README.md
├── GITHUB_INSTRUCTIONS.md
└── .gitignore
```

## Selesai! 🎉

Proyek Anda sekarang sudah tersedia di GitHub dan bisa diakses oleh siapa saja (jika repository public) atau hanya oleh Anda dan kolaborator (jika private).


