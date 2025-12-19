<?php
/**
 * Halaman Berbagi Data Keuangan (Multi-User Sharing)
 */

$page_title = 'Berbagi Data Keuangan';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../auth/check_session.php';
require_once __DIR__ . '/../includes/header.php';

$current_user_id = getUserId();
$message = '';
$alert_type = '';

// Proses tambah sharing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $shared_user_id = intval($_POST['shared_user_id'] ?? 0);
    
    if ($shared_user_id > 0 && $shared_user_id != $current_user_id) {
        // Cek apakah sudah ada
        $sql_check = "SELECT id FROM shared_access WHERE owner_id = ? AND shared_user_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $current_user_id, $shared_user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows == 0) {
            // Insert sharing
            $sql = "INSERT INTO shared_access (owner_id, shared_user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $current_user_id, $shared_user_id);
            
            if ($stmt->execute()) {
                $message = 'Akses berhasil diberikan!';
                $alert_type = 'success';
            } else {
                $message = 'Error: ' . $stmt->error;
                $alert_type = 'danger';
            }
            $stmt->close();
        } else {
            $message = 'Akses sudah diberikan sebelumnya!';
            $alert_type = 'warning';
        }
        $stmt_check->close();
    } else {
        $message = 'Pilih user yang valid!';
        $alert_type = 'danger';
    }
}

// Proses hapus sharing
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $share_id = intval($_GET['id']);
    
    // Verifikasi bahwa ini adalah sharing milik user saat ini
    $sql_check = "SELECT id FROM shared_access WHERE id = ? AND owner_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $share_id, $current_user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        $sql = "DELETE FROM shared_access WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $share_id);
        
        if ($stmt->execute()) {
            $message = 'Akses berhasil dihapus!';
            $alert_type = 'success';
        } else {
            $message = 'Error: ' . $stmt->error;
            $alert_type = 'danger';
        }
        $stmt->close();
    } else {
        $message = 'Akses tidak ditemukan atau tidak memiliki izin!';
        $alert_type = 'danger';
    }
    $stmt_check->close();
}

// Ambil daftar user yang sudah diberi akses
$sql_shared = "SELECT sa.id, sa.shared_user_id, u.username, sa.created_at 
               FROM shared_access sa 
               JOIN users u ON sa.shared_user_id = u.id 
               WHERE sa.owner_id = ? 
               ORDER BY sa.created_at DESC";
$stmt_shared = $conn->prepare($sql_shared);
$stmt_shared->bind_param("i", $current_user_id);
$stmt_shared->execute();
$result_shared = $stmt_shared->get_result();

// Ambil daftar semua user (kecuali user saat ini) untuk dropdown
$sql_users = "SELECT id, username FROM users WHERE id != ? ORDER BY username";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("i", $current_user_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();

// Ambil daftar user yang sudah diberi akses (untuk filter dropdown)
$shared_user_ids = array();
while ($row = $result_shared->fetch_assoc()) {
    $shared_user_ids[] = $row['shared_user_id'];
}
$result_shared->data_seek(0); // Reset pointer
?>

<div class="container">
    <h2 style="margin-bottom: 2rem;">👥 Berbagi Data Keuangan</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $alert_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="card" style="margin-bottom: 2rem; background: #e3f2fd;">
        <h3 style="margin-bottom: 1rem; color: #1976d2;">ℹ️ Informasi</h3>
        <p style="color: #555;">
            Di halaman ini, Anda dapat memberikan akses kepada pengguna lain untuk <strong>melihat</strong> 
            data keuangan Anda. Pengguna yang diberi akses hanya dapat melihat data, tidak dapat mengedit atau menghapus.
        </p>
    </div>

    <!-- Form Tambah Sharing -->
    <div class="form-container" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">➕ Berikan Akses ke Pengguna Lain</h3>
        <form method="POST" action="">
            <input type="hidden" name="action" value="tambah">
            
            <div class="form-group">
                <label for="shared_user_id">Pilih Pengguna</label>
                <select name="shared_user_id" id="shared_user_id" required>
                    <option value="">-- Pilih Pengguna --</option>
                    <?php while($user = $result_users->fetch_assoc()): ?>
                        <?php if (!in_array($user['id'], $shared_user_ids)): ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </option>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </select>
                <?php if (count($shared_user_ids) >= $result_users->num_rows): ?>
                    <p style="color: #666; font-size: 0.9rem; margin-top: 0.5rem;">
                        Semua pengguna sudah diberi akses.
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Berikan Akses</button>
            </div>
        </form>
    </div>

    <!-- Daftar User yang Sudah Diberi Akses -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">📋 Daftar Pengguna yang Memiliki Akses</h3>
        </div>

        <?php if ($result_shared->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Tanggal Diberikan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = $result_shared->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?action=hapus&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus akses untuk <?php echo htmlspecialchars($row['username']); ?>?')">
                                    Hapus Akses
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <p>Belum ada pengguna yang diberi akses</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt_shared->close();
$stmt_users->close();
require_once __DIR__ . '/../includes/footer.php';
$conn->close();
?>


