<?php
$title = 'Dashboard Admin';
include 'header.php';
include 'sidebar.php';

// Waktu Hari Ini
$today = date('Y-m-d');

// 1. Query Statistik
// Total Murid
$q_murid = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM murid");
$total_murid = mysqli_fetch_assoc($q_murid)['total'];

// Total Kelas
$q_kelas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas");
$total_kelas = mysqli_fetch_assoc($q_kelas)['total'];

// Total Guru
$q_guru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru");
$total_guru = mysqli_fetch_assoc($q_guru)['total'];

// Statistik Kehadiran Hari Ini
$q_hadir_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE tanggal = '$today' AND status = 'Hadir'");
$hadir_today = mysqli_fetch_assoc($q_hadir_today)['total'];

$q_total_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE tanggal = '$today'");
$total_today = mysqli_fetch_assoc($q_total_today)['total'];

// Hitung persentase kehadiran hari ini
$persen_kehadiran = $total_today > 0 ? round(($hadir_today / $total_today) * 100) : 0;

// 2. Query Riwayat Presensi Terbaru (Grup per tanggal, kelas, guru)
$q_riwayat = mysqli_query($koneksi, "
    SELECT p.tanggal, k.nama_kelas, g.nama_guru, 
           COUNT(CASE WHEN p.status = 'Hadir' THEN 1 END) as hadir,
           COUNT(CASE WHEN p.status = 'Tidak Hadir' THEN 1 END) as tidak_hadir
    FROM presensi p
    JOIN murid m ON p.id_murid = m.id_murid
    JOIN kelas k ON m.id_kelas = k.id_kelas
    JOIN guru g ON p.id_guru = g.id_guru
    GROUP BY p.tanggal, k.id_kelas, k.nama_kelas, p.id_guru, g.nama_guru
    ORDER BY p.tanggal DESC, k.nama_kelas ASC
    LIMIT 5
");

// 3. Query Kelengkapan Presensi Kelas Hari Ini
$q_status_kelas = mysqli_query($koneksi, "
    SELECT k.id_kelas, k.nama_kelas, 
           (SELECT COUNT(*) FROM presensi p 
            JOIN murid m ON p.id_murid = m.id_murid 
            WHERE m.id_kelas = k.id_kelas AND p.tanggal = '$today') as jumlah_absen
    FROM kelas k
    ORDER BY k.nama_kelas ASC
");
?>

<!-- Statistics Row -->
<div class="dashboard-stats-grid">
    <!-- Card 1: Murid -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= number_format($total_murid); ?></span>
            <span class="stat-label">Total Murid</span>
        </div>
    </div>

    <!-- Card 2: Kelas -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= number_format($total_kelas); ?></span>
            <span class="stat-label">Total Kelas</span>
        </div>
    </div>

    <!-- Card 3: Guru -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= number_format($total_guru); ?></span>
            <span class="stat-label">Guru Pembimbing</span>
        </div>
    </div>

    <!-- Card 4: Persentase Kehadiran -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="12" y1="2" x2="12" y2="6"></line>
                <path d="M8 14h8"></path>
                <path d="M12 10v8"></path>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= $persen_kehadiran; ?>%</span>
            <span class="stat-label">Kehadiran Hari Ini</span>
        </div>
    </div>
</div>

<!-- Panels Section -->
<div class="dashboard-panels-grid">
    <!-- Left Panel: Recent Activity -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title">Riwayat Presensi Terbaru</h2>
            <a href="rekap.php" class="forgot-link" style="font-size: 12px;">Lihat Semua</a>
        </div>
        <div class="theme-table-wrapper">
            <table class="theme-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Pembimbing</th>
                        <th>Statistik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($q_riwayat) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($q_riwayat)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                                <td><?= htmlspecialchars($row['nama_guru']); ?></td>
                                <td>
                                    <span class="badge-hadir"><?= $row['hadir']; ?> Hadir</span>
                                    <span class="badge-tidak-hadir"><?= $row['tidak_hadir']; ?> Absen</span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--login-text-muted); padding: 30px;">
                                Belum ada aktivitas presensi salat yang tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Panel: Class Attendance Tracker Today -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title">Kelengkapan Hari Ini</h2>
            <span class="badge-role" style="background-color: var(--login-green-mid); color: #FFFFFF; font-size: 9px; padding: 4px 8px;">
                <?= date('d M'); ?>
            </span>
        </div>
        <div class="class-status-list">
            <?php if (mysqli_num_rows($q_status_kelas) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($q_status_kelas)): ?>
                    <div class="class-status-item">
                        <span class="class-name"><?= htmlspecialchars($row['nama_kelas']); ?></span>
                        <div class="class-details">
                            <?php if ($row['jumlah_absen'] > 0): ?>
                                <span class="badge-sudah">Sudah Absen</span>
                                <span class="student-count-badge"><?= $row['jumlah_absen']; ?> Siswa</span>
                            <?php else: ?>
                                <span class="badge-belum">Belum Absen</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--login-text-muted); padding: 20px;">
                    Data kelas tidak ditemukan.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
