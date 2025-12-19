<?php
/**
 * Script Setup Admin - Jalankan sekali untuk setup user admin
 * Akses: http://localhost/Pencatatan_uang_pribadi/setup_admin.php
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/koneksi.php';

// Generate hash untuk password "admin123"
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Setup Admin</title>";
echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;}";
echo "h2{color:#667eea;} .success{color:green;} .error{color:red;} code{background:#f5f5f5;padding:2px 6px;border-radius:3px;}</style></head><body>";

echo "<h2>🔧 Setup Admin User</h2>";
echo "<p>Password: <strong>admin123</strong></p>";
echo "<p>Generated Hash: <code>" . htmlspecialchars($hash) . "</code></p>";

// Setup user admin
$username = 'admin';
$role = 'admin';

// Cek apakah tabel users ada
$check_table = $conn->query("SHOW TABLES LIKE 'users'");
if ($check_table->num_rows == 0) {
    echo "<p style='color:red'>Error: Tabel 'users' tidak ditemukan. Silakan import file SQL terlebih dahulu.</p>";
    exit;
}

// Cek apakah user admin sudah ada
$sql_check = "SELECT id, username, password FROM users WHERE username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $username);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $existing_user = $result_check->fetch_assoc();
    echo "<p>User admin sudah ada (ID: " . $existing_user['id'] . ")</p>";
    
    // Update password dengan hash yang benar
    $sql_update = "UPDATE users SET password = ?, role = ? WHERE username = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sss", $hash, $role, $username);
    
    if ($stmt_update->execute()) {
        echo "<p class='success'>✓ Password admin berhasil diupdate!</p>";
        
        // Verifikasi password baru
        $sql_verify = "SELECT password FROM users WHERE username = ?";
        $stmt_verify = $conn->prepare($sql_verify);
        $stmt_verify->bind_param("s", $username);
        $stmt_verify->execute();
        $result_verify = $stmt_verify->get_result();
        $user_verify = $result_verify->fetch_assoc();
        
        if (password_verify($password, $user_verify['password'])) {
            echo "<p class='success'><strong>✓ Verifikasi password berhasil! Login sekarang bisa digunakan.</strong></p>";
            echo "<p class='success'>Silakan <a href='" . SITE_URL . "/auth/login.php'>klik di sini untuk login</a></p>";
        } else {
            echo "<p class='error'>✗ Verifikasi password gagal! Silakan refresh halaman ini.</p>";
        }
        $stmt_verify->close();
    } else {
        echo "<p class='error'>Error update: " . htmlspecialchars($stmt_update->error) . "</p>";
    }
    $stmt_update->close();
} else {
    // Insert user admin baru
    $sql_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $username, $hash, $role);
    
    if ($stmt_insert->execute()) {
        echo "<p class='success'><strong>✓ User admin berhasil dibuat!</strong></p>";
        echo "<p>Username: <strong>admin</strong></p>";
        echo "<p>Password: <strong>admin123</strong></p>";
        echo "<p class='success'>Silakan <a href='" . SITE_URL . "/auth/login.php'>klik di sini untuk login</a></p>";
    } else {
        echo "<p class='error'>Error insert: " . htmlspecialchars($stmt_insert->error) . "</p>";
    }
    $stmt_insert->close();
}

$stmt_check->close();

echo "<hr>";
echo "<h3>📋 Informasi Login:</h3>";
echo "<ul>";
echo "<li><strong>URL Login:</strong> <a href='" . SITE_URL . "/auth/login.php' target='_blank'>" . SITE_URL . "/auth/login.php</a></li>";
echo "<li><strong>Username:</strong> <code>admin</code></li>";
echo "<li><strong>Password:</strong> <code>admin123</code></li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>Setelah login berhasil, hapus file ini untuk keamanan.</small></p>";

$conn->close();
echo "</body></html>";
?>

