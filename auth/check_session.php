<?php
/**
 * File untuk pengecekan session login
 * Include file ini di halaman yang memerlukan login
 */

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login
    require_once __DIR__ . '/../config/config.php';
    header("Location: " . SITE_URL . "/auth/login.php");
    exit();
}

// Fungsi helper untuk mendapatkan user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Fungsi helper untuk mendapatkan username
function getUsername() {
    return $_SESSION['username'] ?? null;
}

// Fungsi helper untuk mendapatkan role
function getUserRole() {
    return $_SESSION['role'] ?? 'user';
}

// Fungsi helper untuk cek apakah user adalah owner atau memiliki akses shared
function hasAccessToData($conn, $data_user_id) {
    $current_user_id = getUserId();
    
    // Jika user adalah owner
    if ($current_user_id == $data_user_id) {
        return true;
    }
    
    // Cek apakah user memiliki akses shared
    $sql = "SELECT id FROM shared_access WHERE owner_id = ? AND shared_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $data_user_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $has_access = $result->num_rows > 0;
    $stmt->close();
    
    return $has_access;
}


