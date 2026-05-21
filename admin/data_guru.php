<?php
$title = 'Data Guru';
include 'header.php';
include 'sidebar.php';

// =============================================
// HANDLE: Hapus Guru
// =============================================
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    // Cek apakah guru pernah dipakai di presensi
    $cek_presensi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE id_guru = '$id_hapus'");
    $total_presensi = mysqli_fetch_assoc($cek_presensi)['total'];

    if ($total_presensi > 0) {
        echo "<script>alert('Gagal! Guru ini tercatat sebagai guru pencatat pada $total_presensi data presensi. Data guru tidak dapat dihapus agar rekap presensi tetap valid.'); window.location.href='data_guru.php';</script>";
    } else {
        mysqli_query($koneksi, "DELETE FROM guru WHERE id_guru = '$id_hapus'");
        echo "<script>alert('Data guru berhasil dihapus!'); window.location.href='data_guru.php';</script>";
    }
    exit();
}

// =============================================
// HANDLE: Tambah / Edit Guru
// =============================================
if (isset($_POST['simpan'])) {
    $id_guru   = mysqli_real_escape_string($koneksi, $_POST['id_guru']);
    $nama_guru = mysqli_real_escape_string($koneksi, trim($_POST['nama_guru']));

    if (empty($nama_guru)) {
        echo "<script>alert('Nama guru tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    // Cek duplikasi nama guru
    $cek_q = "SELECT id_guru FROM guru WHERE nama_guru = '$nama_guru'";
    if (!empty($id_guru)) $cek_q .= " AND id_guru != '$id_guru'";
    $cek = mysqli_query($koneksi, $cek_q);

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama guru sudah terdaftar!'); window.history.back();</script>";
        exit();
    }

    if (!empty($id_guru)) {
        mysqli_query($koneksi, "UPDATE guru SET nama_guru = '$nama_guru' WHERE id_guru = '$id_guru'");
        $msg = "Data guru berhasil diperbarui!";
    } else {
        mysqli_query($koneksi, "INSERT INTO guru (nama_guru) VALUES ('$nama_guru')");
        $msg = "Guru baru berhasil ditambahkan!";
    }
    echo "<script>alert('$msg'); window.location.href='data_guru.php';</script>";
    exit();
}

// =============================================
// QUERY: Daftar Guru + Total Presensi yang Dicatat
// =============================================
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where_sql = !empty($search) ? "WHERE g.nama_guru LIKE '%$search%'" : "";

$query_guru = mysqli_query($koneksi, "
    SELECT g.id_guru, g.nama_guru, COUNT(DISTINCT p.id_presensi) as total_presensi
    FROM guru g
    LEFT JOIN presensi p ON g.id_guru = p.id_guru
    $where_sql
    GROUP BY g.id_guru
    ORDER BY g.nama_guru ASC
");

$total_guru = mysqli_num_rows($query_guru);
?>

<div class="panel-back-header">
    <div>
        <h1 class="topbar-title">Manajemen Data Guru</h1>
        <p class="topbar-subtitle">Kelola daftar guru pencatat presensi salat yang terdaftar di sistem.</p>
    </div>
    <div>
        <button onclick="openModal()" class="btn-signin" style="padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Guru Baru
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="filter-card" style="margin-bottom: 24px;">
    <form action="data_guru.php" method="GET" class="filter-grid" style="grid-template-columns: 1fr auto; align-items: end;">
        <div>
            <label class="filter-label">Cari Nama Guru</label>
            <input type="text" name="search" class="filter-input" placeholder="Ketik nama guru..." value="<?= htmlspecialchars($search); ?>">
        </div>
        <div>
            <button type="submit" class="btn-signin" style="padding: 12px 20px;">Cari</button>
        </div>
    </form>
</div>

<!-- Tabel Guru -->
<div class="panel-card" style="padding: 24px 30px;">
    <div class="theme-table-wrapper">
        <table class="theme-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Guru</th>
                    <th style="text-align: center; width: 200px;">Total Sesi Presensi Dicatat</th>
                    <th style="text-align: right; width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_guru > 0):
                    $no = 1;
                    // Reset result pointer
                    mysqli_data_seek($query_guru, 0);
                    while ($row = mysqli_fetch_assoc($query_guru)):
                ?>
                <tr>
                    <td style="text-align: center; color: var(--login-text-muted); font-weight: 700;"><?= $no++; ?>.</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #1A6546, #2a9d68); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="color: white; font-weight: 800; font-size: 16px;">
                                    <?= strtoupper(substr($row['nama_guru'], 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--login-text-dark); font-size: 14px;">
                                    <?= htmlspecialchars($row['nama_guru']); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--login-text-muted); margin-top: 2px;">
                                    Guru Pencatat Presensi
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($row['total_presensi'] > 0): ?>
                            <span class="status-badge badge-green"><?= $row['total_presensi']; ?> sesi</span>
                        <?php else: ?>
                            <span style="font-size: 13px; color: var(--login-text-muted);">Belum pernah mencatat</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <button type="button"
                            class="btn-bulk-action"
                            style="padding: 6px 12px; font-size: 12px; border-color: transparent;"
                            onclick="editModal('<?= $row['id_guru']; ?>', '<?= htmlspecialchars($row['nama_guru'], ENT_QUOTES); ?>')">
                            Edit
                        </button>
                        <?php if ($row['total_presensi'] == 0): ?>
                        <a href="data_guru.php?hapus=<?= $row['id_guru']; ?>"
                            onclick="return confirm('Yakin ingin menghapus guru ini dari sistem?');"
                            class="btn-bulk-action"
                            style="padding: 6px 12px; font-size: 12px; color: #EF4444; border-color: transparent;">
                            Hapus
                        </a>
                        <?php else: ?>
                        <span class="btn-bulk-action"
                            style="padding: 6px 12px; font-size: 12px; color: #9ca3af; border-color: transparent; cursor: not-allowed;"
                            title="Tidak dapat dihapus karena guru ini sudah memiliki riwayat presensi">
                            Hapus
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 48px; color: var(--login-text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 12px auto;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        Tidak ada data guru yang ditemukan.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 16px; font-size: 13px; color: var(--login-text-muted); font-weight: 600;">
        Total: <?= $total_guru; ?> guru terdaftar di sistem
    </div>
</div>

<!-- =============================================
     MODAL: Tambah / Edit Guru
     ============================================= -->
<div class="presence-modal-overlay" id="guruModal">
    <div class="presence-modal">
        <h3 class="modal-title-confirm" id="modalTitle">Tambah Guru Baru</h3>

        <!-- Ikon dekorasi -->
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 18px; background: linear-gradient(135deg, #D1FAE5, #A7F3D0); display: inline-flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#1A6546" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
        </div>

        <form action="data_guru.php" method="POST">
            <input type="hidden" name="id_guru" id="form_id_guru" value="">

            <div>
                <label class="modal-label-text">Nama Lengkap Guru</label>
                <input
                    type="text"
                    name="nama_guru"
                    id="form_nama_guru"
                    class="modal-input-field"
                    placeholder="Contoh: Budi Santoso, S.Pd."
                    required>
                <p style="font-size: 11px; color: var(--login-text-muted); margin-top: -12px; margin-bottom: 20px;">
                    *Sertakan gelar jika ada agar data lebih lengkap.
                </p>
            </div>

            <button type="submit" name="simpan" class="btn-modal-submit-green">Simpan Data Guru</button>
            <a href="javascript:void(0)" class="btn-modal-close-link" onclick="closeModal()">Batal</a>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('guruModal');
const modalTitle = document.getElementById('modalTitle');
const fId   = document.getElementById('form_id_guru');
const fNama = document.getElementById('form_nama_guru');

function openModal() {
    modalTitle.textContent = "Tambah Guru Baru";
    fId.value   = '';
    fNama.value = '';
    modal.classList.add('show');
    fNama.focus();
}

function editModal(id, nama) {
    modalTitle.textContent = "Edit Data Guru";
    fId.value   = id;
    fNama.value = nama;
    modal.classList.add('show');
    fNama.focus();
}

function closeModal() {
    modal.classList.remove('show');
}

// Tutup modal jika klik di luar area modal
modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
});
</script>

<?php include 'footer.php'; ?>
