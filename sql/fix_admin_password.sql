-- File untuk memperbaiki password admin
-- Jalankan file ini jika login dengan username "admin" dan password "admin123" gagal
-- ATAU gunakan setup_admin.php di browser untuk generate hash yang benar

USE sistem_pencatatan_keuangan;

-- Hapus user admin lama jika ada (opsional)
-- DELETE FROM users WHERE username = 'admin';

-- Buat/Update user admin dengan password yang benar
-- Password: admin123
-- Hash ini di-generate dengan password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK', 'admin')
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$3p5ZZOS9cJ94koNELzWyd.lsPOg3RF0KuYd1Url5BvSniNy0iH/WK',
    role = 'admin';

-- Verifikasi password sudah benar
SELECT id, username, role, 
       CASE 
           WHEN password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
           THEN 'Password sudah benar' 
           ELSE 'Password perlu diupdate' 
       END as status_password
FROM users 
WHERE username = 'admin';

