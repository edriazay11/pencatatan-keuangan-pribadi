-- Tabel Users untuk Login & Register
-- Versi Sederhana - Jalankan file ini jika users_and_sharing.sql error

USE sistem_pencatatan_keuangan;

-- Tabel: Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: Shared Access (Berbagi Data Keuangan)
CREATE TABLE IF NOT EXISTS shared_access (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    shared_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shared_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_share (owner_id, shared_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan kolom user_id ke tabel pemasukan
-- Catatan: Hapus bagian "IF NOT EXISTS" jika error, atau jalankan manual di phpMyAdmin
ALTER TABLE pemasukan 
ADD COLUMN user_id INT DEFAULT NULL;

ALTER TABLE pemasukan 
ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Tambahkan kolom user_id ke tabel pengeluaran
ALTER TABLE pengeluaran 
ADD COLUMN user_id INT DEFAULT NULL;

ALTER TABLE pengeluaran 
ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Insert user admin contoh (password: admin123)
-- Password di-hash menggunakan password_hash() PHP
-- Default password untuk testing: admin123
-- Catatan: Gunakan setup_admin.php untuk generate hash yang benar
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK', 'admin')
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK',
    role = 'admin';


