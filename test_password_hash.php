<?php
/**
 * Script untuk generate dan test password hash admin123
 * Jalankan file ini untuk memastikan hash password benar
 */

// Generate hash untuk password "admin123"
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: admin123\n";
echo "Generated Hash: " . $hash . "\n\n";

// Test hash yang sudah ada
$existing_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "Existing Hash: " . $existing_hash . "\n";
echo "Verify dengan admin123: " . (password_verify($password, $existing_hash) ? "BENAR ✓" : "SALAH ✗") . "\n\n";

// Generate hash baru dengan PASSWORD_BCRYPT (sama dengan $2y$)
$hash_bcrypt = password_hash($password, PASSWORD_BCRYPT);
echo "New BCRYPT Hash: " . $hash_bcrypt . "\n";
echo "Verify hash baru: " . (password_verify($password, $hash_bcrypt) ? "BENAR ✓" : "SALAH ✗") . "\n";

