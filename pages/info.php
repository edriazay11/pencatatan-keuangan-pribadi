<?php
/**
 * Halaman Informasi (Landing Page)
 * Halaman ini berdiri sendiri sebelum login
 */

$page_title = 'Informasi Aplikasi';
require_once __DIR__ . '/../config/config.php';

// Start session untuk cek apakah sudah login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/info.css">
</head>
<body>
    <div class="landing-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">💰 Sistem Pencatatan Keuangan Pribadi</h1>
                <p class="hero-subtitle">Kelola keuangan Anda dengan mudah dan terorganisir</p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content-section">
            <div class="container">
                <!-- Deskripsi -->
                <div class="info-card">
                    <h2>📝 Deskripsi Aplikasi</h2>
                    <p>
                        Sistem Pencatatan Keuangan Pribadi adalah aplikasi web yang dirancang untuk membantu Anda 
                        mengelola dan melacak keuangan pribadi dengan lebih baik. Aplikasi ini memungkinkan Anda 
                        untuk mencatat pemasukan dan pengeluaran secara terstruktur, membuat laporan keuangan, 
                        dan menganalisis pola pengeluaran Anda.
                    </p>
                </div>

                <!-- Tujuan -->
                <div class="info-card">
                    <h2>🎯 Tujuan Aplikasi</h2>
                    <ul class="feature-list">
                        <li>Membantu pengguna mengelola keuangan pribadi dengan lebih terorganisir</li>
                        <li>Menyediakan catatan lengkap pemasukan dan pengeluaran</li>
                        <li>Memberikan laporan keuangan yang mudah dipahami</li>
                        <li>Membantu pengguna membuat keputusan keuangan yang lebih baik</li>
                        <li>Menyediakan fitur berbagi data keuangan dengan pengguna lain</li>
                    </ul>
                </div>

                <!-- Fitur -->
                <div class="info-card">
                    <h2>✨ Fitur Aplikasi</h2>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">📊</div>
                            <h3>Dashboard Keuangan</h3>
                            <p>Lihat ringkasan keuangan Anda dalam satu tampilan yang mudah dipahami</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">💰</div>
                            <h3>Manajemen Pemasukan</h3>
                            <p>Catat dan kelola semua pemasukan Anda dengan kategori yang terorganisir</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">💸</div>
                            <h3>Manajemen Pengeluaran</h3>
                            <p>Lacak semua pengeluaran Anda dengan detail kategori dan deskripsi</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📈</div>
                            <h3>Laporan Keuangan</h3>
                            <p>Buat laporan keuangan berdasarkan periode tertentu untuk analisis</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🔐</div>
                            <h3>Sistem Login & Register</h3>
                            <p>Keamanan data dengan sistem autentikasi pengguna</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">👥</div>
                            <h3>Berbagi Data Keuangan</h3>
                            <p>Berikan akses kepada pengguna lain untuk melihat data keuangan Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="cta-section">
                    <h2>Mulai Kelola Keuangan Anda Sekarang!</h2>
                    <p>Daftar sekarang dan nikmati kemudahan mengelola keuangan pribadi Anda</p>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn btn-primary">Login</a>
                        <a href="<?php echo SITE_URL; ?>/auth/register.php" class="btn btn-secondary">Register</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="landing-footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Dibuat dengan ❤️</p>
        </footer>
    </div>
</body>
</html>


