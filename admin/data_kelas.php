<?php
$title = 'Data Kelas';
include 'header.php';
include 'sidebar.php';

// =============================================
// HANDLE: Hapus Kelas
// =============================================
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    // Cek apakah kelas masih punya murid
    $cek_murid = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM murid WHERE id_kelas = '$id_hapus'");
    $total_murid = mysqli_fetch_assoc($cek_murid)['total'];

    if ($total_murid > 0) {
        echo "<script>alert('Gagal! Kelas ini masih memiliki $total_murid murid terdaftar. Pindahkan atau hapus muridnya terlebih dahulu.'); window.location.href='data_kelas.php';</script>";
    } else {
        mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas = '$id_hapus'");
        echo "<script>alert('Kelas berhasil dihapus!'); window.location.href='data_kelas.php';</script>";
    }
    exit();
}

// =============================================
// HANDLE: Tambah / Edit Kelas
// =============================================
if (isset($_POST['simpan'])) {
    $id_kelas  = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $nama_kelas = mysqli_real_escape_string($koneksi, trim($_POST['nama_kelas']));

    if (empty($nama_kelas)) {
        echo "<script>alert('Nama kelas tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    // Cek duplikasi nama kelas (kecuali dirinya sendiri saat edit)
    $cek_q = "SELECT id_kelas FROM kelas WHERE nama_kelas = '$nama_kelas'";
    if (!empty($id_kelas)) $cek_q .= " AND id_kelas != '$id_kelas'";
    $cek = mysqli_query($koneksi, $cek_q);

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama kelas sudah ada!'); window.history.back();</script>";
        exit();
    }

    if (!empty($id_kelas)) {
        mysqli_query($koneksi, "UPDATE kelas SET nama_kelas = '$nama_kelas' WHERE id_kelas = '$id_kelas'");
        $msg = "Kelas berhasil diperbarui!";
    } else {
        mysqli_query($koneksi, "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')");
        $msg = "Kelas baru berhasil ditambahkan!";
    }
    echo "<script>alert('$msg'); window.location.href='data_kelas.php';</script>";
    exit();
}

// =============================================
// QUERY: Daftar Kelas + Jumlah Murid
// =============================================
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where_sql = !empty($search) ? "WHERE k.nama_kelas LIKE '%$search%'" : "";

$query_kelas = mysqli_query($koneksi, "
    SELECT k.id_kelas, k.nama_kelas, COUNT(m.id_murid) as total_murid
    FROM kelas k
    LEFT JOIN murid m ON k.id_kelas = m.id_kelas
    $where_sql
    GROUP BY k.id_kelas
    ORDER BY k.nama_kelas ASC
");
?>

<div class="panel-back-header">
    <div>
        <h1 class="topbar-title">Manajemen Data Kelas</h1>
        <p class="topbar-subtitle">Kelola daftar kelas dan pantau jumlah murid di setiap kelas.</p>
    </div>
    <div>
        <button onclick="openModal()" class="btn-signin" style="padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Kelas Baru
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="filter-card" style="margin-bottom: 24px;">
    <form action="data_kelas.php" method="GET" class="filter-grid" style="grid-template-columns: 1fr auto; align-items: end;">
        <div>
            <label class="filter-label">Cari Nama Kelas</label>
            <input type="text" name="search" class="filter-input" placeholder="Ketik nama kelas..." value="<?= htmlspecialchars($search); ?>">
        </div>
        <div>
            <button type="submit" class="btn-signin" style="padding: 12px 20px;">Cari</button>
        </div>
    </form>
</div>

<!-- Tabel Kelas -->
<div class="panel-card" style="padding: 24px 30px;">
    <div class="theme-table-wrapper">
        <table class="theme-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Kelas</th>
                    <th style="text-align: center; width: 160px;">Jumlah Murid</th>
                    <th style="text-align: right; width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query_kelas) > 0): 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query_kelas)): 
                        $nama_bersih = preg_replace('/\s*\([^)]*\)\s*/', ' ', $row['nama_kelas']);
                ?>
                <tr>
                    <td style="text-align: center; color: var(--login-text-muted); font-weight: 700;"><?= $no++; ?>.</td>
                    <td style="font-weight: 700; color: var(--login-text-dark);">
                        <?= htmlspecialchars($row['nama_kelas']); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($row['total_murid'] > 0): ?>
                            <span class="status-badge badge-green"><?= $row['total_murid']; ?> Murid</span>
                        <?php else: ?>
                            <span class="status-badge badge-red">Belum ada murid</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" 
                            class="btn-bulk-action" 
                            style="padding: 6px 12px; font-size: 12px; border-color: transparent;"
                            onclick="editModal('<?= $row['id_kelas']; ?>', '<?= htmlspecialchars($row['nama_kelas'], ENT_QUOTES); ?>')">
                            Edit
                        </button>
                        <?php if ($row['total_murid'] == 0): ?>
                        <a href="data_kelas.php?hapus=<?= $row['id_kelas']; ?>" 
                            onclick="return confirm('Yakin ingin menghapus kelas \'<?= htmlspecialchars($nama_bersih, ENT_QUOTES); ?>\'? Tindakan ini tidak dapat dibatalkan!');"
                            class="btn-bulk-action" 
                            style="padding: 6px 12px; font-size: 12px; color: #EF4444; border-color: transparent;">
                            Hapus
                        </a>
                        <?php else: ?>
                        <span class="btn-bulk-action" 
                            style="padding: 6px 12px; font-size: 12px; color: #9ca3af; border-color: transparent; cursor: not-allowed;"
                            title="Tidak dapat dihapus karena masih ada murid terdaftar">
                            Hapus
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 48px; color: var(--login-text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 12px auto;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path></svg>
                        Tidak ada data kelas yang ditemukan.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 16px; font-size: 13px; color: var(--login-text-muted); font-weight: 600;">
        Total: <?= mysqli_num_rows($query_kelas); ?> kelas terdaftar
    </div>
</div>

<!-- =============================================
     MODAL: Tambah / Edit Kelas
     ============================================= -->
<div class="presence-modal-overlay" id="kelasModal">
    <div class="presence-modal">
        <h3 class="modal-title-confirm" id="modalTitle">Tambah Kelas Baru</h3>
        <form action="data_kelas.php" method="POST">
            <input type="hidden" name="id_kelas" id="form_id_kelas" value="">
            
            <div>
                <label class="modal-label-text">Nama Kelas</label>
                <input 
                    type="text" 
                    name="nama_kelas" 
                    id="form_nama_kelas" 
                    class="modal-input-field" 
                    placeholder="Contoh: X (sepuluh) Kuliner 3"
                    required>
                <p style="font-size: 11px; color: var(--login-text-muted); margin-top: -12px; margin-bottom: 20px;">
                    *Gunakan format lengkap sesuai aturan sekolah.
                </p>
            </div>

            <button type="submit" name="simpan" class="btn-modal-submit-green">Simpan Kelas</button>
            <a href="javascript:void(0)" class="btn-modal-close-link" onclick="closeModal()">Batal</a>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('kelasModal');
const modalTitle = document.getElementById('modalTitle');
const fId = document.getElementById('form_id_kelas');
const fNama = document.getElementById('form_nama_kelas');

function openModal() {
    modalTitle.textContent = "Tambah Kelas Baru";
    fId.value = '';
    fNama.value = '';
    modal.classList.add('show');
    fNama.focus();
}

function editModal(id, nama) {
    modalTitle.textContent = "Edit Nama Kelas";
    fId.value = id;
    fNama.value = nama;
    modal.classList.add('show');
    fNama.focus();
}

function closeModal() {
    modal.classList.remove('show');
}

modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
});
</script>

<?php include 'footer.php'; ?>
