<?php
/**
 * File Konfigurasi Database
 * Sistem Pencatatan Keuangan Pribadi
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Sesuaikan dengan username MySQL Anda
define('DB_PASS', '');             // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'sistem_pencatatan_keuangan');

// Konfigurasi Aplikasi
define('SITE_NAME', 'Sistem Pencatatan Keuangan Pribadi');
define('SITE_URL', 'http://localhost/Pencatatan_uang_pribadi');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (set ke 0 di production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

