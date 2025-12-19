<?php
/**
 * Halaman Manajemen Pemasukan - CRUD
 */

$page_title = 'Manajemen Pemasukan';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../auth/check_session.php';
require_once __DIR__ . '/../includes/header.php';

// Ambil kategori pemasukan
$sql_kategori = "SELECT * FROM kategori_pemasukan ORDER BY nama_kategori";
$result_kategori = $conn->query($sql_kategori);

// Ambil data pemasukan dengan pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : '';
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

$where_clause = "WHERE 1=1";
$params = [];
$types = "";

if ($search) {
    $where_clause .= " AND (p.deskripsi LIKE ? OR k.nama_kategori LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($kategori_id) {
    $where_clause .= " AND p.kategori_id = ?";
    $params[] = $kategori_id;
    $types .= "i";
}

if ($tanggal_filter) {
    $where_clause .= " AND p.tanggal = ?";
    $params[] = $tanggal_filter;
    $types .= "s";
}

$sql = "SELECT p.*, k.nama_kategori 
        FROM pemasukan p 
        LEFT JOIN kategori_pemasukan k ON p.kategori_id = k.id 
        $where_clause 
        ORDER BY p.tanggal DESC, p.id DESC 
        LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hitung total data untuk pagination
$sql_count = "SELECT COUNT(*) as total FROM pemasukan p $where_clause";
$params_count = array_slice($params, 0, -2);
$types_count = substr($types, 0, -2);
$stmt_count = $conn->prepare($sql_count);
if (!empty($params_count)) {
    $stmt_count->bind_param($types_count, ...$params_count);
}
$stmt_count->execute();
$total_data = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_data / $limit);
?>

<div class="container">
    <h2 style="margin-bottom: 2rem;">💰 Manajemen Pemasukan</h2>

    <!-- Alert Success/Error -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message'], $_SESSION['alert_type']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Form Tambah/Ubah Pemasukan -->
    <div class="form-container" id="formPemasukan" style="display: none;">
        <h3 id="formTitle">Tambah Pemasukan Baru</h3>
        <form id="formPemasukanInput" method="POST" action="process_pemasukan.php">
            <input type="hidden" name="id" id="inputId">
            <input type="hidden" name="action" id="inputAction" value="tambah">
            
            <div class="form-group">
                <label for="kategori_id">Kategori *</label>
                <select name="kategori_id" id="kategori_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php while($kategori = $result_kategori->fetch_assoc()): ?>
                        <option value="<?php echo $kategori['id']; ?>"><?php echo htmlspecialchars($kategori['nama_kategori']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal *</label>
                <input type="date" name="tanggal" id="tanggal" required>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah (Rp) *</label>
                <input type="number" name="jumlah" id="jumlah" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-warning" onclick="cancelForm()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Tombol Tambah -->
    <div style="margin-bottom: 1.5rem;">
        <button class="btn btn-success" onclick="showAddForm()">+ Tambah Pemasukan</button>
    </div>

    <!-- Search dan Filter -->
    <div class="search-filter">
        <form method="GET" style="display: flex; gap: 1rem; width: 100%;">
            <input type="text" name="search" placeholder="Cari deskripsi atau kategori..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 2;">
            <select name="kategori_id" style="flex: 1;">
                <option value="">Semua Kategori</option>
                <?php 
                $result_kategori->data_seek(0);
                while($kategori = $result_kategori->fetch_assoc()): ?>
                    <option value="<?php echo $kategori['id']; ?>" <?php echo $kategori_id == $kategori['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal_filter); ?>" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="pemasukan.php" class="btn btn-warning">Reset</a>
        </form>
    </div>

    <!-- Tabel Pemasukan -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Daftar Pemasukan (<?php echo number_format($total_data); ?> data)</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php 
                    $no = $offset + 1;
                    while($row = $result->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                            <td style="color: #4CAF50; font-weight: bold;">
                                Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                                <a href="process_pemasukan.php?action=hapus&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirmDelete('Apakah Anda yakin ingin menghapus pemasukan ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <p>Belum ada data pemasukan</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&kategori_id=<?php echo $kategori_id; ?>&tanggal=<?php echo $tanggal_filter; ?>" 
                       class="btn btn-info btn-sm">« Prev</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&kategori_id=<?php echo $kategori_id; ?>&tanggal=<?php echo $tanggal_filter; ?>" 
                       class="btn btn-sm <?php echo $i == $page ? 'btn-primary' : 'btn-info'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&kategori_id=<?php echo $kategori_id; ?>&tanggal=<?php echo $tanggal_filter; ?>" 
                       class="btn btn-info btn-sm">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showAddForm() {
    document.getElementById('formPemasukan').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Tambah Pemasukan Baru';
    document.getElementById('inputAction').value = 'tambah';
    document.getElementById('formPemasukanInput').reset();
    document.getElementById('inputId').value = '';
}

function editData(data) {
    document.getElementById('formPemasukan').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Edit Pemasukan';
    document.getElementById('inputAction').value = 'ubah';
    document.getElementById('inputId').value = data.id;
    document.getElementById('kategori_id').value = data.kategori_id;
    document.getElementById('tanggal').value = data.tanggal;
    document.getElementById('jumlah').value = data.jumlah;
    document.getElementById('deskripsi').value = data.deskripsi || '';
}

function cancelForm() {
    document.getElementById('formPemasukan').style.display = 'none';
    document.getElementById('formPemasukanInput').reset();
}

document.getElementById('formPemasukanInput').addEventListener('submit', function(e) {
    if (!validateForm('formPemasukanInput')) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
    }
});
</script>

<?php
$stmt->close();
$stmt_count->close();
require_once __DIR__ . '/../includes/footer.php';
$conn->close();
?>

