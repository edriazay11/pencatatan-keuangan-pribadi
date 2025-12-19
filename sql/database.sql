-- Database: sistem_pencatatan_keuangan
-- Deskripsi: Database untuk sistem pencatatan keuangan pribadi

-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS sistem_pencatatan_keuangan 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE sistem_pencatatan_keuangan;

-- Tabel: Kategori Pemasukan
CREATE TABLE IF NOT EXISTS kategori_pemasukan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: Kategori Pengeluaran
CREATE TABLE IF NOT EXISTS kategori_pengeluaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: Pemasukan
CREATE TABLE IF NOT EXISTS pemasukan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_pemasukan(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel: Pengeluaran
CREATE TABLE IF NOT EXISTS pengeluaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_pengeluaran(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data kategori pemasukan contoh
INSERT INTO kategori_pemasukan (nama_kategori, deskripsi) VALUES
('Gaji', 'Pendapatan rutin dari pekerjaan'),
('Bonus', 'Tambahan penghasilan'),
('Hadiah', 'Penghasilan dari hadiah'),
('Investasi', 'Return dari investasi'),
('Lainnya', 'Pemasukan lainnya');

-- Insert data kategori pengeluaran contoh
INSERT INTO kategori_pengeluaran (nama_kategori, deskripsi) VALUES
('Makanan', 'Pengeluaran untuk makanan dan minuman'),
('Transportasi', 'Biaya transportasi'),
('Hiburan', 'Pengeluaran untuk hiburan'),
('Tagihan', 'Tagihan rutin'),
('Belanja', 'Pengeluaran belanja'),
('Kesehatan', 'Pengeluaran kesehatan'),
('Pendidikan', 'Pengeluaran pendidikan'),
('Lainnya', 'Pengeluaran lainnya');

-- Insert data pemasukan contoh
INSERT INTO pemasukan (kategori_id, tanggal, jumlah, deskripsi) VALUES
(1, '2024-01-01', 5000000.00, 'Gaji bulanan'),
(1, '2024-02-01', 5000000.00, 'Gaji bulanan'),
(2, '2024-02-15', 500000.00, 'Bonus pencapaian');

-- Insert data pengeluaran contoh
INSERT INTO pengeluaran (kategori_id, tanggal, jumlah, deskripsi) VALUES
(1, '2024-01-05', 50000.00, 'Makan siang'),
(1, '2024-01-10', 30000.00, 'Sarapan'),
(2, '2024-01-15', 100000.00, 'Bensin motor'),
(4, '2024-01-20', 500000.00, 'Tagihan listrik');

