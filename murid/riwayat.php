<?php
$title = 'Riwayat Presensi';
include 'header.php';
include 'sidebar.php';

$id_murid = $_SESSION['id'];

// Filter Bulan
$filter_bulan = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : date('Y-m');

// Query Riwayat
$q_riwayat = mysqli_query($koneksi, "
    SELECT p.tanggal, p.status, g.nama_guru
    FROM presensi p
    LEFT JOIN guru g ON p.id_guru = g.id_guru
    WHERE p.id_murid = '$id_murid' AND p.tanggal LIKE '$filter_bulan%'
    ORDER BY p.tanggal DESC
");

// Hitung ringkasan
$total = mysqli_num_rows($q_riwayat);
$hadir = 0;
$rows  = [];
while ($r = mysqli_fetch_assoc($q_riwayat)) {
    if ($r['status'] === 'Hadir') $hadir++;
    $rows[] = $r;
}
$absen   = $total - $hadir;
$persen  = $total > 0 ? round(($hadir / $total) * 100) : 0;
?>

<!-- TOPBAR -->
<div class="murid-topbar">
    <div class="murid-topbar-left">
        <div>
            <h1 class="murid-topbar-greeting">Riwayat Presensi</h1>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <span class="murid-role-pill">SISWA</span>
                <span style="font-size:13px;color:#6b7280;">Rekam jejak kehadiran salat Dzuhur kamu</span>
            </div>
        </div>
    </div>
    <div class="murid-topbar-right">
        <div class="murid-topbar-date">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span class="dynamic-date"></span>
        </div>
        <div class="murid-topbar-avatar"><?= strtoupper(substr($_SESSION['nama'], 0, 1)); ?></div>
    </div>
</div>

<!-- FILTER BULAN -->
<div style="margin-bottom:20px;">
    <form action="riwayat.php" method="GET" style="display:flex;align-items:center;gap:12px;">
        <label style="font-size:13px;font-weight:700;color:#374151;">Tampilkan Bulan:</label>
        <input type="month" name="bulan" value="<?= $filter_bulan; ?>" class="filter-input" style="width:180px;" onchange="this.form.submit()">
    </form>
</div>

<!-- RINGKASAN BULAN -->
<div class="murid-stats-grid" style="margin-bottom:20px;">
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#D1FAE5;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div>
            <div class="murid-stat-number text-green"><?= $hadir; ?></div>
            <div class="murid-stat-label">Total Hadir</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#FEE2E2;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number text-red"><?= $absen; ?></div>
            <div class="murid-stat-label">Total Absen</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#FEF3C7;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number <?= $persen >= 85 ? 'text-green' : ($persen >= 60 ? 'text-yellow' : 'text-red'); ?>"><?= $persen; ?>%</div>
            <div class="murid-stat-label">Kehadiran Bulan Ini</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#EDE9FE;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#7C3AED" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number" style="color:#7C3AED;"><?= $total; ?></div>
            <div class="murid-stat-label">Total Hari Tercatat</div>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT -->
<div class="panel-card" style="padding:24px 28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h3 style="font-size:16px;font-weight:800;color:#111827;margin:0;">
            Detail Riwayat — <?= date('F Y', strtotime($filter_bulan . '-01')); ?>
        </h3>
        <span style="font-size:13px;font-weight:600;color:#6b7280;"><?= $total; ?> data ditemukan</span>
    </div>

    <div class="theme-table-wrapper">
        <table class="theme-table">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center;">No</th>
                    <th>Tanggal</th>
                    <th style="text-align:center;">Status</th>
                    <th>Guru Pencatat</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($rows) > 0):
                $no = 1;
                foreach ($rows as $row):
                    $hari = ['Sunday'=>'Ahad','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
                    $nama_hari = $hari[date('l', strtotime($row['tanggal']))];
                    $tgl_fmt = $nama_hari . ', ' . date('d M Y', strtotime($row['tanggal']));
            ?>
            <tr>
                <td style="text-align:center;color:#9ca3af;font-weight:700;"><?= $no++; ?>.</td>
                <td style="font-weight:600;color:#374151;"><?= $tgl_fmt; ?></td>
                <td style="text-align:center;">
                    <?php if ($row['status'] === 'Hadir'): ?>
                        <span class="status-badge badge-green">✓ Hadir</span>
                    <?php else: ?>
                        <span class="status-badge badge-red">✗ Tidak Hadir</span>
                    <?php endif; ?>
                </td>
                <td style="font-size:13px;color:#6b7280;"><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="4" style="text-align:center;padding:48px;color:#9ca3af;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 12px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    Tidak ada data presensi pada bulan ini.
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
