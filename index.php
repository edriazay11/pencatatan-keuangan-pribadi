<?php
/**
 * Halaman Dashboard - Sistem Pencatatan Keuangan Pribadi
 */

$page_title = 'Dashboard';

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/auth/check_session.php';
require_once __DIR__ . '/includes/header.php';

// Query untuk mendapatkan summary
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// Total Pemasukan
$sql_pemasukan = "SELECT COALESCE(SUM(jumlah), 0) as total FROM pemasukan WHERE tanggal BETWEEN ? AND ?";
$stmt_pemasukan = $conn->prepare($sql_pemasukan);
$stmt_pemasukan->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt_pemasukan->execute();
$result_pemasukan = $stmt_pemasukan->get_result();
$total_pemasukan = $result_pemasukan->fetch_assoc()['total'];

// Total Pengeluaran
$sql_pengeluaran = "SELECT COALESCE(SUM(jumlah), 0) as total FROM pengeluaran WHERE tanggal BETWEEN ? AND ?";
$stmt_pengeluaran = $conn->prepare($sql_pengeluaran);
$stmt_pengeluaran->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt_pengeluaran->execute();
$result_pengeluaran = $stmt_pengeluaran->get_result();
$total_pengeluaran = $result_pengeluaran->fetch_assoc()['total'];

// Saldo Akhir
$saldo_akhir = $total_pemasukan - $total_pengeluaran;

// Query transaksi terakhir
$sql_transaksi = "
    (SELECT 'Pemasukan' as tipe, tanggal, jumlah, deskripsi, DATE_FORMAT(created_at, '%d/%m/%Y') as created 
     FROM pemasukan)
    UNION ALL
    (SELECT 'Pengeluaran' as tipe, tanggal, jumlah, deskripsi, DATE_FORMAT(created_at, '%d/%m/%Y') as created 
     FROM pengeluaran)
    ORDER BY tanggal DESC, created DESC 
    LIMIT 10
";
$result_transaksi = $conn->query($sql_transaksi);

$stmt_pemasukan->close();
$stmt_pengeluaran->close();
?>

<!-- Dashboard Cards -->
<div class="container">
    <h2 style="margin-bottom: 2rem;">📊 Dashboard Keuangan</h2>
    
    <!-- Filter Periode -->
    <div class="card" style="margin-bottom: 2rem;">
        <form method="GET" action="" style="display: flex; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="dashboard-grid">
        <div class="card pemasukan">
            <div class="card-header">
                <span class="card-title">Total Pemasukan</span>
                <span class="card-icon">💰</span>
            </div>
            <div class="card-body">
                <h2>Rp <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="card pengeluaran">
            <div class="card-header">
                <span class="card-title">Total Pengeluaran</span>
                <span class="card-icon">💸</span>
            </div>
            <div class="card-body">
                <h2>Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="card saldo">
            <div class="card-header">
                <span class="card-title">Saldo Akhir</span>
                <span class="card-icon">💵</span>
            </div>
            <div class="card-body">
                <h2 style="color: <?php echo $saldo_akhir >= 0 ? '#4CAF50' : '#f44336'; ?>;">
                    Rp <?php echo number_format($saldo_akhir, 0, ',', '.'); ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">📋 Transaksi Terakhir</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_transaksi->num_rows > 0): ?>
                    <?php while($row = $result_transaksi->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span class="badge <?php echo $row['tipe'] == 'Pemasukan' ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $row['tipe']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                            <td><?php echo $row['created']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
$conn->close();
?>

