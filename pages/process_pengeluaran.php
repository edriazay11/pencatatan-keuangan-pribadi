<?php
/**
 * Proses CRUD Pengeluaran
 */

session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../auth/check_session.php';

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
$user_id = getUserId();

if ($action == 'tambah') {
    $kategori_id = $_POST['kategori_id'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];
    
    // Cek apakah kolom user_id ada, jika ada gunakan, jika tidak skip
    $sql = "INSERT INTO pengeluaran (kategori_id, tanggal, jumlah, deskripsi" . 
           (columnExists($conn, 'pengeluaran', 'user_id') ? ", user_id" : "") . 
           ") VALUES (?, ?, ?, ?" . (columnExists($conn, 'pengeluaran', 'user_id') ? ", ?" : "") . ")";
    $stmt = $conn->prepare($sql);
    if (columnExists($conn, 'pengeluaran', 'user_id')) {
        $stmt->bind_param("isdsi", $kategori_id, $tanggal, $jumlah, $deskripsi, $user_id);
    } else {
        $stmt->bind_param("isds", $kategori_id, $tanggal, $jumlah, $deskripsi);
    }
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pengeluaran berhasil ditambahkan!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['alert_type'] = "danger";
    }
    $stmt->close();
    
    header("Location: pengeluaran.php");
    exit();
}

if ($action == 'ubah') {
    $id = $_POST['id'];
    $kategori_id = $_POST['kategori_id'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];
    
    // Verifikasi ownership jika kolom user_id ada
    if (columnExists($conn, 'pengeluaran', 'user_id')) {
        $sql_check = "SELECT id FROM pengeluaran WHERE id = ? AND user_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows == 0) {
            $_SESSION['message'] = "Anda tidak memiliki izin untuk mengubah data ini!";
            $_SESSION['alert_type'] = "danger";
            $stmt_check->close();
            header("Location: pengeluaran.php");
            exit();
        }
        $stmt_check->close();
    }
    
    $sql = "UPDATE pengeluaran SET kategori_id=?, tanggal=?, jumlah=?, deskripsi=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdsi", $kategori_id, $tanggal, $jumlah, $deskripsi, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pengeluaran berhasil diupdate!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['alert_type'] = "danger";
    }
    $stmt->close();
    
    header("Location: pengeluaran.php");
    exit();
}

if ($action == 'hapus') {
    $id = $_GET['id'];
    
    // Verifikasi ownership jika kolom user_id ada
    if (columnExists($conn, 'pengeluaran', 'user_id')) {
        $sql_check = "SELECT id FROM pengeluaran WHERE id = ? AND user_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows == 0) {
            $_SESSION['message'] = "Anda tidak memiliki izin untuk menghapus data ini!";
            $_SESSION['alert_type'] = "danger";
            $stmt_check->close();
            header("Location: pengeluaran.php");
            exit();
        }
        $stmt_check->close();
    }
    
    $sql = "DELETE FROM pengeluaran WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pengeluaran berhasil dihapus!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['alert_type'] = "danger";
    }
    $stmt->close();
    
    header("Location: pengeluaran.php");
    exit();
}

// Helper function untuk cek apakah kolom ada
function columnExists($conn, $table, $column) {
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $result && $result->num_rows > 0;
}

$conn->close();
?>

