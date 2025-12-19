<?php
/**
 * Halaman Laporan Keuangan
 */

$page_title = 'Laporan Keuangan';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../auth/check_session.php';
require_once __DIR__ . '/../includes/header.php';

// Ambil parameter filter
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// Total Pemasukan dan Pengeluaran per Kategori
$sql_pemasukan_kategori = "
    SELECT k.nama_kategori, COALESCE(SUM(p.jumlah), 0) as total
    FROM kategori_pemasukan k
    LEFT JOIN pemasukan p ON k.id = p.kategori_id AND p.tanggal BETWEEN ? AND ?
    GROUP BY k.id, k.nama_kategori
    ORDER BY total DESC
";

$sql_pengeluaran_kategori = "
    SELECT k.nama_kategori, COALESCE(SUM(p.jumlah), 0) as total
    FROM kategori_pengeluaran k
    LEFT JOIN pengeluaran p ON k.id = p.kategori_id AND p.tanggal BETWEEN ? AND ?
    GROUP BY k.id, k.nama_kategori
    ORDER BY total DESC
";

// Laporan Harian
$sql_laporan_harian = "
    SELECT 
        tanggal,
        COALESCE(SUM(CASE WHEN tipe = 'Pemasukan' THEN jumlah ELSE 0 END), 0) as pemasukan,
        COALESCE(SUM(CASE WHEN tipe = 'Pengeluaran' THEN jumlah ELSE 0 END), 0) as pengeluaran,
        (COALESCE(SUM(CASE WHEN tipe = 'Pemasukan' THEN jumlah ELSE 0 END), 0) - 
         COALESCE(SUM(CASE WHEN tipe = 'Pengeluaran' THEN jumlah ELSE 0 END), 0)) as saldo
    FROM (
        SELECT tanggal, jumlah, 'Pemasukan' as tipe FROM pemasukan WHERE tanggal BETWEEN ? AND ?
        UNION ALL
        SELECT tanggal, jumlah, 'Pengeluaran' as tipe FROM pengeluaran WHERE tanggal BETWEEN ? AND ?
    ) as transaksi
    GROUP BY tanggal
    ORDER BY tanggal DESC
    LIMIT 30
";

$stmt_pemasukan = $conn->prepare($sql_pemasukan_kategori);
$stmt_pemasukan->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt_pemasukan->execute();
$result_pemasukan_kategori = $stmt_pemasukan->get_result();

$stmt_pengeluaran = $conn->prepare($sql_pengeluaran_kategori);
$stmt_pengeluaran->bind_param("ss", $tanggal_awal, $tanggal_akhir);
$stmt_pengeluaran->execute();
$result_pengeluaran_kategori = $stmt_pengeluaran->get_result();

$stmt_laporan = $conn->prepare($sql_laporan_harian);
$stmt_laporan->bind_param("ssss", $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir);
$stmt_laporan->execute();
$result_laporan = $stmt_laporan->get_result();
?>

<div class="container">
    <h2 style="margin-bottom: 2rem;">📊 Laporan Keuangan</h2>

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

    <!-- Ringkasan -->
    <div class="dashboard-grid" style="margin-bottom: 2rem;">
        <?php
        // Hitung total
        $total_pemasukan_kategori = 0;
        $total_pengeluaran_kategori = 0;
        
        while($row = $result_pemasukan_kategori->fetch_assoc()) {
            $total_pemasukan_kategori += $row['total'];
        }
        
        while($row = $result_pengeluaran_kategori->fetch_assoc()) {
            $total_pengeluaran_kategori += $row['total'];
        }
        
        $saldo = $total_pemasukan_kategori - $total_pengeluaran_kategori;
        ?>
        
        <div class="card pemasukan">
            <div class="card-header">
                <span class="card-title">Total Pemasukan</span>
                <span class="card-icon">💰</span>
            </div>
            <div class="card-body">
                <h2>Rp <?php echo number_format($total_pemasukan_kategori, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="card pengeluaran">
            <div class="card-header">
                <span class="card-title">Total Pengeluaran</span>
                <span class="card-icon">💸</span>
            </div>
            <div class="card-body">
                <h2>Rp <?php echo number_format($total_pengeluaran_kategori, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="card saldo">
            <div class="card-header">
                <span class="card-title">Saldo Akhir</span>
                <span class="card-icon">💵</span>
            </div>
            <div class="card-body">
                <h2 style="color: <?php echo $saldo >= 0 ? '#4CAF50' : '#f44336'; ?>;">
                    Rp <?php echo number_format($saldo, 0, ',', '.'); ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Laporan Per Kategori -->
    <div class="dashboard-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 2rem;">
        <!-- Pemasukan Per Kategori -->
        <div class="table-container">
            <h3 class="table-title">Pemasukan Per Kategori</h3>
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result_pemasukan_kategori->data_seek(0);
                    while($row = $result_pemasukan_kategori->fetch_assoc()): 
                        if ($row['total'] > 0):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                            <td style="color: #4CAF50; font-weight: bold;">
                                Rp <?php echo number_format($row['total'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php 
                        endif;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pengeluaran Per Kategori -->
        <div class="table-container">
            <h3 class="table-title">Pengeluaran Per Kategori</h3>
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result_pengeluaran_kategori->data_seek(0);
                    while($row = $result_pengeluaran_kategori->fetch_assoc()): 
                        if ($row['total'] > 0):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                            <td style="color: #f44336; font-weight: bold;">
                                Rp <?php echo number_format($row['total'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php 
                        endif;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Laporan Harian -->
    <div class="table-container">
        <h3 class="table-title">Laporan Harian (30 Hari Terakhir)</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_laporan->num_rows > 0): ?>
                    <?php while($row = $result_laporan->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td style="color: #4CAF50;">
                                Rp <?php echo number_format($row['pemasukan'], 0, ',', '.'); ?>
                            </td>
                            <td style="color: #f44336;">
                                Rp <?php echo number_format($row['pengeluaran'], 0, ',', '.'); ?>
                            </td>
                            <td style="color: <?php echo $row['saldo'] >= 0 ? '#4CAF50' : '#f44336'; ?>; font-weight: bold;">
                                Rp <?php echo number_format($row['saldo'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <p>Belum ada transaksi dalam periode ini</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt_pemasukan->close();
$stmt_pengeluaran->close();
$stmt_laporan->close();
require_once __DIR__ . '/../includes/footer.php';
$conn->close();
?>

