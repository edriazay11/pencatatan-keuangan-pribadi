<?php
/**
 * Proses Logout
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login
require_once __DIR__ . '/../config/config.php';
header("Location: " . SITE_URL . "/auth/login.php");
exit();


