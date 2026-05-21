<?php
$title = 'Input Presensi Salat';
include 'header.php';
include 'sidebar.php';

// Helper function untuk membersihkan nama kelas dari tanda kurung
function cleanClassName($name) {
    return preg_replace('/\s*\([^)]*\)\s*/', ' ', $name);
}

// Ambil parameter filter
$filter_kelas   = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '';
$filter_tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal']) : date('Y-m-d');

// 1. Aksi Simpan Presensi (POST)
if (isset($_POST['simpan'])) {
    $post_kelas   = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $post_tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $id_guru      = mysqli_real_escape_string($koneksi, $_POST['id_guru']);
    $statuses     = isset($_POST['status']) ? $_POST['status'] : [];

    if (!empty($post_kelas) && !empty($post_tanggal) && !empty($id_guru)) {
        // Ambil nama guru untuk notifikasi alert
        $q_guru_info = mysqli_query($koneksi, "SELECT nama_guru FROM guru WHERE id_guru = '$id_guru'");
        $guru_info   = mysqli_fetch_assoc($q_guru_info);
        $nama_guru   = $guru_info ? $guru_info['nama_guru'] : 'Guru';

        $success_count = 0;
        foreach ($statuses as $id_murid => $status) {
            $id_murid = mysqli_real_escape_string($koneksi, $id_murid);
            $status   = mysqli_real_escape_string($koneksi, $status);

            // Cek apakah data presensi murid pada tanggal ini sudah ada
            $cek = mysqli_query($koneksi, "SELECT id_presensi FROM presensi WHERE id_murid = '$id_murid' AND tanggal = '$post_tanggal'");
            
            if (mysqli_num_rows($cek) > 0) {
                // Update
                $q_save = "UPDATE presensi SET status = '$status', id_guru = '$id_guru' WHERE id_murid = '$id_murid' AND tanggal = '$post_tanggal'";
            } else {
                // Insert
                $q_save = "INSERT INTO presensi (tanggal, id_murid, id_guru, status) VALUES ('$post_tanggal', '$id_murid', '$id_guru', '$status')";
            }
            
            if (mysqli_query($koneksi, $q_save)) {
                $success_count++;
            }
        }
        
        echo "<script>
                alert('Berhasil mengirim presensi untuk $success_count siswa! Pencatat: $nama_guru');
                window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal mengirim! Mohon pilih guru pencatat dengan benar.');
              </script>";
    }
}

// Load data untuk Tampilan
if (empty($filter_kelas)) {
    // === LANGKAH 1: PILIH KELAS ===
    $query_kelas = mysqli_query($koneksi, "
        SELECT k.id_kelas, k.nama_kelas, 
               (SELECT COUNT(*) FROM presensi p 
                JOIN murid m ON p.id_murid = m.id_murid 
                WHERE m.id_kelas = k.id_kelas AND p.tanggal = '$filter_tanggal') as jumlah_absen
        FROM kelas k
        ORDER BY k.nama_kelas ASC
    ");
    
    // Kelompokkan kelas berdasarkan tingkat
    $grades = [
        'Kelas X' => [],
        'Kelas XI' => [],
        'Kelas XII' => [],
        'Lainnya' => []
    ];
    
    while ($row = mysqli_fetch_assoc($query_kelas)) {
        $nama_kelas = $row['nama_kelas'];
        if (preg_match('/^X\b|^X\s*\(/i', $nama_kelas)) {
            $grades['Kelas X'][] = $row;
        } elseif (preg_match('/^XI\b|^XI\s*\(/i', $nama_kelas)) {
            $grades['Kelas XI'][] = $row;
        } elseif (preg_match('/^XII\b|^XII\s*\(/i', $nama_kelas)) {
            $grades['Kelas XII'][] = $row;
        } else {
            $grades['Lainnya'][] = $row;
        }
    }
} else {
    // === LANGKAH 2: INPUT PRESENSI SISWA ===
    // Ambil nama kelas aktif
    $q_kelas_active = mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id_kelas = '$filter_kelas'");
    $class_data = mysqli_fetch_assoc($q_kelas_active);
    $active_class_name = $class_data ? cleanClassName($class_data['nama_kelas']) : 'Tidak Diketahui';

    // Ambil siswa dalam kelas terpilih beserta presensi jika sudah diisi
    $siswa_list = [];
    $q_siswa = mysqli_query($koneksi, "
        SELECT m.id_murid, m.nisn, m.nama_murid, p.status 
        FROM murid m 
        LEFT JOIN presensi p ON m.id_murid = p.id_murid AND p.tanggal = '$filter_tanggal'
        WHERE m.id_kelas = '$filter_kelas' 
        ORDER BY m.nama_murid ASC
    ");
    while ($row = mysqli_fetch_assoc($q_siswa)) {
        $siswa_list[] = $row;
    }
}
?>

<?php if (empty($filter_kelas)): ?>
    <!-- ================= LANGKAH 1: PILIH KELAS ================= -->
    <div class="filter-card" style="margin-bottom: 32px;">
        <div class="filter-grid" style="grid-template-columns: 1fr auto; align-items: center;">
            <div>
                <h2 class="panel-title" style="margin-bottom: 4px;">Pilih Kelas Presensi</h2>
                <p class="form-subtitle" style="margin-bottom: 0;">Pilih salah satu kelas di bawah untuk melakukan input kehadiran.</p>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <label for="tanggal" class="filter-label" style="margin-bottom: 0;">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($filter_tanggal); ?>" class="filter-input" style="width: auto; padding: 10px 14px;" onchange="window.location.href='presensi.php?tanggal=' + this.value">
                </div>
            </div>
        </div>
    </div>

    <!-- Grid List Kelas Kelompokan -->
    <?php foreach ($grades as $grade_title => $kelas_items): ?>
        <?php if (count($kelas_items) > 0): ?>
            <div class="class-grid-section">
                <h3 class="class-grade-title"><?= $grade_title; ?></h3>
                <div class="class-grid">
                    <?php foreach ($kelas_items as $kelas): 
                        $is_completed = ($kelas['jumlah_absen'] > 0);
                        $btn_class = $is_completed ? 'class-select-btn class-completed' : 'class-select-btn';
                        $btn_href = $is_completed ? 'javascript:void(0)' : 'presensi.php?kelas=' . $kelas['id_kelas'] . '&tanggal=' . $filter_tanggal;
                    ?>
                        <a href="<?= $btn_href; ?>" class="<?= $btn_class; ?>">
                            <?= htmlspecialchars(cleanClassName($kelas['nama_kelas'])); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

<?php else: ?>
    <!-- ================= LANGKAH 2: INPUT PRESENSI SISWA ================= -->
    <!-- Header panel kembali dan nama kelas -->
    <div class="panel-back-header">
        <a href="presensi.php?tanggal=<?= $filter_tanggal; ?>" class="btn-back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <div>
            <h1 class="topbar-title">Dzuhur - <?= htmlspecialchars($active_class_name); ?></h1>
            <p class="topbar-subtitle">Tanggal: <span style="font-weight: 700; color: var(--login-green-mid);"><?= date('d F Y', strtotime($filter_tanggal)); ?></span></p>
        </div>
    </div>

    <!-- Card Absensi Siswa -->
    <div class="panel-card" style="padding: 24px 30px;">
        <!-- Tombol Aksi Massal (Bulk Actions) -->
        <div class="bulk-actions-row">
            <button type="button" class="btn-bulk-action" id="btnHadirSemua">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#166534" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Hadir Semua
            </button>
            <button type="button" class="btn-bulk-action" id="btnAbsenSemua">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Tidak Hadir Semua
            </button>
        </div>

        <form action="presensi.php" method="POST" id="presenceForm">
            <!-- Hidden Fields untuk filter -->
            <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($filter_kelas); ?>">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($filter_tanggal); ?>">

            <div class="theme-table-wrapper" style="margin-bottom: 24px; max-height: 480px; overflow-y: auto;">
                <table class="theme-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">No</th>
                            <th>Nama Siswa</th>
                            <th style="text-align: right; width: 220px; padding-right: 24px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($siswa_list) > 0): ?>
                            <?php $no = 1; foreach ($siswa_list as $siswa): 
                                // Jika data presensi belum diisi sebelumnya, default ke 'Hadir'
                                $status = is_null($siswa['status']) ? 'Hadir' : $siswa['status'];
                            ?>
                                <tr>
                                    <td style="text-align: center; font-weight: 700; color: var(--login-text-muted);"><?= $no++; ?>.</td>
                                    <td style="font-weight: 700;"><?= htmlspecialchars($siswa['nama_murid']); ?></td>
                                    <td style="text-align: right; padding-right: 16px;">
                                        <!-- Custom Segmented Control -->
                                        <div class="presence-btn-group">
                                            <!-- Pilihan Hadir -->
                                            <label class="presence-btn-option opt-hadir">
                                                <input type="radio" class="radio-hadir" name="status[<?= $siswa['id_murid']; ?>]" value="Hadir" <?= ($status == 'Hadir') ? 'checked' : ''; ?>>
                                                <span class="presence-btn-label">Hadir</span>
                                            </label>
                                            <!-- Pilihan Tidak -->
                                            <label class="presence-btn-option opt-tidak">
                                                <input type="radio" class="radio-tidak" name="status[<?= $siswa['id_murid']; ?>]" value="Tidak Hadir" <?= ($status == 'Tidak Hadir') ? 'checked' : ''; ?>>
                                                <span class="presence-btn-label">Tidak</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 40px; color: var(--login-text-muted);">
                                    Tidak ada data siswa terdaftar di kelas ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($siswa_list) > 0): ?>
                <div>
                    <button type="button" class="btn-signin" style="padding: 18px;" onclick="openModal()">Kirim Presensi</button>
                </div>
            <?php endif; ?>

            <!-- ================= LANGKAH 3: MODAL KONFIRMASI ================= -->
            <div class="presence-modal-overlay" id="confirmModal">
                <div class="presence-modal">
                    <h3 class="modal-title-confirm">Konfirmasi Presensi</h3>
                    
                    <div style="margin-bottom: 24px;">
                        <label class="modal-label-text" for="id_guru">Nama Anda (Guru Pencatat)</label>
                        <select id="id_guru" name="id_guru" class="modal-input-field" required>
                            <option value="" disabled selected hidden>-- Pilih Guru Pencatat --</option>
                            <?php
                            $query_guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama_guru ASC");
                            while ($guru = mysqli_fetch_assoc($query_guru)):
                            ?>
                                <option value="<?= $guru['id_guru']; ?>"><?= htmlspecialchars($guru['nama_guru']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <button type="submit" name="simpan" class="btn-modal-submit-green">Kirim Presensi</button>
                        <a href="javascript:void(0)" class="btn-modal-close-link" onclick="closeModal()">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script fungsionalitas lokal presensi -->
    <script>
        // Modal toggling
        const confirmModal = document.getElementById('confirmModal');
        const teacherInput = document.getElementById('id_guru');

        function openModal() {
            confirmModal.classList.add('show');
            teacherInput.focus();
        }

        function closeModal() {
            confirmModal.classList.remove('show');
        }

        // Bulk Actions
        document.getElementById('btnHadirSemua').addEventListener('click', function() {
            document.querySelectorAll('.radio-hadir').forEach(function(radio) {
                radio.checked = true;
            });
        });

        document.getElementById('btnAbsenSemua').addEventListener('click', function() {
            document.querySelectorAll('.radio-tidak').forEach(function(radio) {
                radio.checked = true;
            });
        });

        // Close modal when click overlay outside card
        confirmModal.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                closeModal();
            }
        });
    </script>
<?php endif; ?>

<?php
include 'footer.php';
?>
