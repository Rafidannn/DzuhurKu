<?php
$title = 'Data Murid';
include 'header.php';
include 'sidebar.php';

// Pagination settings
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter and Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_kelas = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '';

$where_clauses = [];
if (!empty($search)) {
    $where_clauses[] = "(m.nama_murid LIKE '%$search%' OR m.nisn LIKE '%$search%')";
}
if (!empty($filter_kelas)) {
    $where_clauses[] = "m.id_kelas = '$filter_kelas'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : "";

// Handle Delete
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM murid WHERE id_murid = '$id_hapus'");
    echo "<script>alert('Data murid berhasil dihapus!'); window.location.href='data_murid.php';</script>";
    exit();
}

// Handle Add/Edit
if (isset($_POST['simpan'])) {
    $id_murid = mysqli_real_escape_string($koneksi, $_POST['id_murid']);
    $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $id_kelas = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Check if NISN exists (excluding current ID if editing)
    $cek_query = "SELECT id_murid FROM murid WHERE nisn = '$nisn'";
    if (!empty($id_murid)) {
        $cek_query .= " AND id_murid != '$id_murid'";
    }
    $cek = mysqli_query($koneksi, $cek_query);
    
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NISN sudah terdaftar pada murid lain!'); window.history.back();</script>";
        exit();
    }
    
    if (!empty($id_murid)) {
        // Update
        $q_update = "UPDATE murid SET nisn = '$nisn', nama_murid = '$nama', id_kelas = '$id_kelas'";
        if (!empty($password)) {
            $q_update .= ", password = '$password'";
        }
        $q_update .= " WHERE id_murid = '$id_murid'";
        mysqli_query($koneksi, $q_update);
        $msg = "Data berhasil diubah!";
    } else {
        // Insert
        if(empty($password)) $password = '123456';
        mysqli_query($koneksi, "INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES ('$nisn', '$nama', '$password', '$id_kelas')");
        $msg = "Murid baru berhasil ditambahkan!";
    }
    echo "<script>alert('$msg'); window.location.href='data_murid.php';</script>";
    exit();
}

// Total rows
$q_total = mysqli_query($koneksi, "SELECT COUNT(id_murid) as total FROM murid m $where_sql");
$total_data = mysqli_fetch_assoc($q_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query Data
$query_murid = mysqli_query($koneksi, "
    SELECT m.*, k.nama_kelas 
    FROM murid m 
    JOIN kelas k ON m.id_kelas = k.id_kelas 
    $where_sql 
    ORDER BY k.nama_kelas ASC, m.nama_murid ASC 
    LIMIT $limit OFFSET $offset
");

// Classes for Dropdown
$query_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$kelas_list = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $kelas_list[] = $row;
}
?>

<div class="panel-back-header">
    <div>
        <h1 class="topbar-title">Manajemen Data Murid</h1>
        <p class="topbar-subtitle">Kelola informasi siswa, kelas, dan kredensial akses mereka.</p>
    </div>
    <div>
        <button onclick="openModal()" class="btn-signin" style="padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Murid Baru
        </button>
    </div>
</div>

<div class="filter-card" style="margin-bottom: 24px;">
    <form action="data_murid.php" method="GET" class="filter-grid" style="grid-template-columns: 1fr auto auto; align-items: end;">
        <div>
            <label class="filter-label">Cari Nama / NISN</label>
            <input type="text" name="search" class="filter-input" placeholder="Ketik kata kunci..." value="<?= htmlspecialchars($search); ?>">
        </div>
        <div>
            <label class="filter-label">Filter Kelas</label>
            <select name="kelas" class="filter-input" style="min-width: 200px;">
                <option value="">-- Semua Kelas --</option>
                <?php foreach($kelas_list as $kls): ?>
                    <option value="<?= $kls['id_kelas']; ?>" <?= ($filter_kelas == $kls['id_kelas']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars(preg_replace('/\s*\([^)]*\)\s*/', ' ', $kls['nama_kelas'])); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="text-align: right;">
            <button type="submit" class="btn-signin" style="padding: 12px 20px;">Cari Data</button>
        </div>
    </form>
</div>

<div class="panel-card" style="padding: 24px 30px;">
    <div class="theme-table-wrapper">
        <table class="theme-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th style="text-align: right; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query_murid) > 0): 
                    $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($query_murid)):
                ?>
                <tr>
                    <td style="text-align: center; color: var(--login-text-muted); font-weight: 700;"><?= $no++; ?>.</td>
                    <td style="font-family: monospace; font-size: 14px;"><?= htmlspecialchars($row['nisn']); ?></td>
                    <td style="font-weight: 700; color: var(--login-text-dark);"><?= htmlspecialchars($row['nama_murid']); ?></td>
                    <td><span class="status-badge badge-green"><?= htmlspecialchars(preg_replace('/\s*\([^)]*\)\s*/', ' ', $row['nama_kelas'])); ?></span></td>
                    <td style="text-align: right;">
                        <button type="button" class="btn-bulk-action" style="padding: 6px 10px; font-size: 12px; border-color: transparent;" onclick="editModal('<?= $row['id_murid']; ?>', '<?= htmlspecialchars($row['nisn'], ENT_QUOTES); ?>', '<?= htmlspecialchars($row['nama_murid'], ENT_QUOTES); ?>', '<?= $row['id_kelas']; ?>')">
                            Edit
                        </button>
                        <a href="data_murid.php?hapus=<?= $row['id_murid']; ?>" onclick="return confirm('Yakin ingin menghapus murid ini? Data presensi terkait mungkin akan ikut terpengaruh!');" class="btn-bulk-action" style="padding: 6px 10px; font-size: 12px; color: #EF4444; border-color: transparent;">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--login-text-muted);">Tidak ada data murid yang ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; font-weight: 700; color: var(--login-text-muted);">
            Menampilkan <?= mysqli_num_rows($query_murid); ?> dari <?= $total_data; ?> data
        </span>
        <div style="display: flex; gap: 8px;">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page-1; ?>&search=<?= urlencode($search); ?>&kelas=<?= $filter_kelas; ?>" class="btn-bulk-action">Sebelumnya</a>
            <?php endif; ?>
            
            <?php if($page < $total_pages): ?>
                <a href="?page=<?= $page+1; ?>&search=<?= urlencode($search); ?>&kelas=<?= $filter_kelas; ?>" class="btn-bulk-action">Selanjutnya</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Form Murid -->
<div class="presence-modal-overlay" id="muridModal">
    <div class="presence-modal">
        <h3 class="modal-title-confirm" id="modalTitle">Tambah Murid Baru</h3>
        <form action="data_murid.php" method="POST">
            <input type="hidden" name="id_murid" id="form_id_murid" value="">
            
            <div>
                <label class="modal-label-text">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" id="form_nisn" class="modal-input-field" required>
            </div>
            
            <div>
                <label class="modal-label-text">Nama Lengkap</label>
                <input type="text" name="nama" id="form_nama" class="modal-input-field" required>
            </div>
            
            <div>
                <label class="modal-label-text">Pilih Kelas</label>
                <select name="id_kelas" id="form_id_kelas" class="modal-input-field" required>
                    <option value="" disabled selected hidden>-- Pilih Kelas --</option>
                    <?php foreach($kelas_list as $kls): ?>
                        <option value="<?= $kls['id_kelas']; ?>"><?= htmlspecialchars(preg_replace('/\s*\([^)]*\)\s*/', ' ', $kls['nama_kelas'])); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="modal-label-text">Password Akses</label>
                <input type="text" name="password" id="form_password" class="modal-input-field" placeholder="Kosongkan jika tidak ingin diubah">
                <p style="font-size: 11px; color: var(--login-text-muted); margin-top: -12px; margin-bottom: 20px;">*Default password untuk pendaftar baru adalah 123456</p>
            </div>

            <div>
                <button type="submit" name="simpan" class="btn-modal-submit-green">Simpan Data</button>
                <a href="javascript:void(0)" class="btn-modal-close-link" onclick="closeModal()">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('muridModal');
const modalTitle = document.getElementById('modalTitle');

// Fields
const fId = document.getElementById('form_id_murid');
const fNisn = document.getElementById('form_nisn');
const fNama = document.getElementById('form_nama');
const fKelas = document.getElementById('form_id_kelas');

function openModal() {
    modalTitle.textContent = "Tambah Murid Baru";
    fId.value = '';
    fNisn.value = '';
    fNama.value = '';
    fKelas.value = '';
    
    modal.classList.add('show');
    fNisn.focus();
}

function editModal(id, nisn, nama, kelas) {
    modalTitle.textContent = "Edit Data Murid";
    fId.value = id;
    fNisn.value = nisn;
    fNama.value = nama;
    fKelas.value = kelas;
    
    modal.classList.add('show');
    fNisn.focus();
}

function closeModal() {
    modal.classList.remove('show');
}

modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
});
</script>

<?php include 'footer.php'; ?>
