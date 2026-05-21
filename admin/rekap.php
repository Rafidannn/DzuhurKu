<?php
$title = 'Rekapitulasi Laporan';
include 'header.php';
include 'sidebar.php';

// Helper clean class name
function cleanClassName($name) {
    return preg_replace('/\s*\([^)]*\)\s*/', ' ', $name);
}

// Tangkap Filter
$filter_tgl_awal = isset($_GET['tgl_awal']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_awal']) : date('Y-m-01'); // Awal bulan
$filter_tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_akhir']) : date('Y-m-t'); // Akhir bulan
$filter_kelas = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '';

// Query untuk dropdown kelas
$query_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

// Susun Query Rekap
$where_clause = "";
if (!empty($filter_kelas)) {
    $where_clause = "WHERE m.id_kelas = '$filter_kelas'";
}

$query_rekap = "
    SELECT 
        m.id_murid, 
        m.nisn, 
        m.nama_murid, 
        k.nama_kelas,
        SUM(CASE WHEN p.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir,
        SUM(CASE WHEN p.status = 'Tidak Hadir' THEN 1 ELSE 0 END) as total_absen,
        COUNT(p.id_presensi) as total_hari
    FROM murid m
    JOIN kelas k ON m.id_kelas = k.id_kelas
    LEFT JOIN presensi p ON m.id_murid = p.id_murid 
        AND p.tanggal >= '$filter_tgl_awal' AND p.tanggal <= '$filter_tgl_akhir'
    $where_clause
    GROUP BY m.id_murid
    ORDER BY k.nama_kelas ASC, m.nama_murid ASC
";
$result_rekap = mysqli_query($koneksi, $query_rekap);
?>

<div class="panel-back-header hidden-print">
    <div>
        <h1 class="topbar-title">Rekapitulasi Presensi</h1>
        <p class="topbar-subtitle">Laporan kedisiplinan salat murid berdasarkan rentang waktu.</p>
    </div>
    <div>
        <button onclick="window.print()" class="btn-signin" style="padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Laporan
        </button>
    </div>
</div>

<div class="filter-card hidden-print" style="margin-bottom: 24px;">
    <form action="rekap.php" method="GET" class="filter-grid" style="grid-template-columns: auto auto auto 1fr; align-items: end;">
        <div>
            <label class="filter-label">Tanggal Awal</label>
            <input type="date" name="tgl_awal" class="filter-input" value="<?= $filter_tgl_awal; ?>" required>
        </div>
        <div>
            <label class="filter-label">Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" class="filter-input" value="<?= $filter_tgl_akhir; ?>" required>
        </div>
        <div>
            <label class="filter-label">Filter Kelas</label>
            <select name="kelas" class="filter-input" style="min-width: 200px;" onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>
                <?php while ($kls = mysqli_fetch_assoc($query_kelas)): ?>
                    <option value="<?= $kls['id_kelas']; ?>" <?= ($filter_kelas == $kls['id_kelas']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars(cleanClassName($kls['nama_kelas'])); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div style="text-align: right;">
            <button type="submit" class="btn-signin" style="padding: 12px 20px;">Terapkan Filter</button>
        </div>
    </form>
</div>

<!-- Dokumen Print Header (Hanya tampil saat di-print) -->
<div class="print-header" style="display: none;">
    <h2 style="margin: 0 0 8px 0; color: #000; font-size: 24px;">Laporan Presensi Salat Dzuhur</h2>
    <p style="margin: 0; color: #333; font-size: 14px;">Periode: <strong><?= date('d M Y', strtotime($filter_tgl_awal)); ?></strong> s.d <strong><?= date('d M Y', strtotime($filter_tgl_akhir)); ?></strong></p>
    <?php if(!empty($filter_kelas)): 
        $nm_kls = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id_kelas = '$filter_kelas'"))['nama_kelas'];
    ?>
        <p style="margin: 4px 0 0 0; color: #333; font-size: 14px;">Kelas: <strong><?= cleanClassName($nm_kls); ?></strong></p>
    <?php else: ?>
        <p style="margin: 4px 0 0 0; color: #333; font-size: 14px;">Kelas: <strong>Semua Kelas</strong></p>
    <?php endif; ?>
    <hr style="margin: 16px 0; border: none; border-bottom: 2px solid #000;">
</div>

<div class="panel-card" style="padding: 24px 30px;">
    <div class="theme-table-wrapper">
        <table class="theme-table print-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="text-align: center;">Hadir</th>
                    <th style="text-align: center;">Tidak Hadir</th>
                    <th style="text-align: center;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($result_rekap) > 0):
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result_rekap)):
                        $total = $row['total_hari'];
                        $hadir = $row['total_hadir'];
                        $persentase = ($total > 0) ? round(($hadir / $total) * 100) : 0;
                        
                        // Menentukan warna badge/pill persentase
                        $badge_class = 'badge-red';
                        if ($persentase >= 85) {
                            $badge_class = 'badge-green';
                        } elseif ($persentase >= 60) {
                            $badge_class = 'badge-yellow';
                        }
                ?>
                <tr>
                    <td style="text-align: center; color: var(--login-text-muted); font-weight: 700;"><?= $no++; ?>.</td>
                    <td><?= htmlspecialchars($row['nisn']); ?></td>
                    <td style="font-weight: 700; color: var(--login-text-dark);"><?= htmlspecialchars($row['nama_murid']); ?></td>
                    <td><?= htmlspecialchars(cleanClassName($row['nama_kelas'])); ?></td>
                    <td style="text-align: center; font-weight: 700; color: var(--login-green-dark);"><?= $row['total_hadir']; ?></td>
                    <td style="text-align: center; font-weight: 700; color: #EF4444;"><?= $row['total_absen']; ?></td>
                    <td style="text-align: center;">
                        <?php if($total > 0): ?>
                            <span class="status-badge <?= $badge_class; ?>"><?= $persentase; ?>%</span>
                        <?php else: ?>
                            <span style="color: #9ca3af; font-size: 13px;">Belum ada data</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--login-text-muted);">
                        Tidak ada data rekapitulasi pada periode / filter yang dipilih.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Area Tanda Tangan untuk Cetak -->
<div class="print-footer" style="display: none; margin-top: 40px;">
    <div style="float: right; text-align: center; width: 250px;">
        <p style="margin: 0 0 60px 0; font-size: 14px;">Jakarta, <?= date('d F Y'); ?><br>Mengetahui,</p>
        <p style="margin: 0; font-weight: bold; font-size: 14px;"><u>( ........................................... )</u><br>Kepala Sekolah / Wali Kelas</p>
    </div>
    <div style="clear: both;"></div>
</div>

<?php include 'footer.php'; ?>
