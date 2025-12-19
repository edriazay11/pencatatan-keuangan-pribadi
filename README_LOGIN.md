# 🚀 PANDUAN LOGIN FINAL - Langsung Bisa Digunakan

## ⚡ CARA CEPAT - Setup Admin (PILIH SALAH SATU)

### **OPSI 1: Menggunakan Script PHP (PALING MUDAH)** ⭐

1. **Buka browser**, akses:
   ```
   http://localhost/Pencatatan_uang_pribadi/setup_admin.php
   ```

2. Script akan otomatis:
   - Generate hash password yang benar untuk "admin123"
   - Membuat/update user admin di database
   - Verifikasi bahwa password sudah benar

3. **Selesai!** Login sekarang bisa digunakan.

---

### **OPSI 2: Menggunakan SQL di phpMyAdmin**

1. Buka **phpMyAdmin** (http://localhost/phpmyadmin)
2. Pilih database: `sistem_pencatatan_keuangan`
3. Klik tab **SQL**
4. Copy dan paste query berikut:

```sql
USE sistem_pencatatan_keuangan;

-- Hapus user admin lama jika ada
DELETE FROM users WHERE username = 'admin';

-- Insert user admin baru dengan password yang benar
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK', 'admin');
```

5. Klik **Go** atau tekan **F5**
6. **Selesai!**

---

## 🔐 Kredensial Login

Setelah setup selesai, gunakan:

- **URL Login:** `http://localhost/Pencatatan_uang_pribadi/auth/login.php`
- **Username:** `admin`
- **Password:** `admin123`

---

## ✅ Verifikasi Login Berhasil

1. Buka: `http://localhost/Pencatatan_uang_pribadi/auth/login.php`
2. Masukkan:
   - Username: `admin`
   - Password: `admin123`
3. Klik **Login**
4. Seharusnya **redirect ke dashboard** tanpa error

---

## 🔧 Troubleshooting

### Jika masih tidak bisa login:

1. **Pastikan tabel users ada:**
   ```sql
   SHOW TABLES LIKE 'users';
   ```

2. **Cek user admin:**
   ```sql
   SELECT * FROM users WHERE username = 'admin';
   ```

3. **Update password manual:**
   - Gunakan `setup_admin.php` di browser (OPSI 1)
   - Atau jalankan query di OPSI 2

4. **Cek error PHP:**
   - Buka `config/config.php`
   - Pastikan `error_reporting(E_ALL);` dan `ini_set('display_errors', 1);` aktif
   - Lihat error yang muncul di browser

---

## 📝 File yang Dibuat/Diperbaiki

1. ✅ `auth/login.php` - Sudah diperbaiki dengan error handling
2. ✅ `setup_admin.php` - Script untuk setup admin otomatis
3. ✅ `sql/setup_admin_final.sql` - SQL untuk setup admin
4. ✅ `sql/fix_admin_password.sql` - SQL untuk fix password
5. ✅ `sql/users_and_sharing_simple.sql` - Updated dengan hash benar

---

## 🎯 Langkah Selanjutnya

Setelah login berhasil:

1. ✅ Dashboard akan muncul
2. ✅ Bisa tambah pemasukan/pengeluaran
3. ✅ Bisa lihat laporan
4. ✅ Bisa gunakan fitur sharing

**Selamat menggunakan aplikasi!** 🎉

