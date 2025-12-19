-- File SQL Final untuk Setup Admin
-- Password: admin123
-- Jalankan file ini di phpMyAdmin untuk setup user admin

USE sistem_pencatatan_keuangan;

-- Pastikan tabel users ada
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hapus user admin lama jika ada
DELETE FROM users WHERE username = 'admin';

-- Insert user admin baru dengan password yang benar
-- Password: admin123
-- Hash ini di-generate dengan password_hash('admin123', PASSWORD_DEFAULT)
-- Catatan: Hash akan berbeda setiap generate, jadi gunakan setup_admin.php untuk generate hash terbaru
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK', 'admin');

-- Verifikasi
SELECT id, username, role, 
       CASE 
           WHEN password LIKE '$2y$%' THEN 'Password hash format benar' 
           ELSE 'Password perlu diupdate' 
       END as status_password
FROM users 
WHERE username = 'admin';

