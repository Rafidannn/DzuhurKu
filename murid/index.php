<?php
$title = 'Dashboard';
include 'header.php';
include 'sidebar.php';

// =============================================
// DATA UTAMA
// =============================================
$id_murid   = $_SESSION['id'];
$nama_murid = $_SESSION['nama'];
$today      = date('Y-m-d');
$bulan_ini  = date('Y-m');

// Info Murid + Kelas
$q_info = mysqli_query($koneksi, "SELECT m.*, k.nama_kelas FROM murid m JOIN kelas k ON m.id_kelas = k.id_kelas WHERE m.id_murid = '$id_murid'");
$info   = mysqli_fetch_assoc($q_info);
$nama_kelas_bersih = preg_replace('/\s*\([^)]*\)\s*/', ' ', $info['nama_kelas'] ?? '-');

// Inisial Avatar
$words    = explode(' ', trim($nama_murid));
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

// =============================================
// STATISTIK BULAN INI
// =============================================
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE id_murid='$id_murid' AND status='Hadir' AND tanggal LIKE '$bulan_ini%'");
$total_hadir = (int) mysqli_fetch_assoc($q_hadir)['total'];

$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM presensi WHERE id_murid='$id_murid' AND tanggal LIKE '$bulan_ini%'");
$total_hari = (int) mysqli_fetch_assoc($q_total)['total'];

$persen = $total_hari > 0 ? round(($total_hadir / $total_hari) * 100) : 0;

// Status hari ini
$q_today = mysqli_query($koneksi, "SELECT status FROM presensi WHERE id_murid='$id_murid' AND tanggal='$today'");
$status_today = mysqli_num_rows($q_today) > 0 ? mysqli_fetch_assoc($q_today)['status'] : 'Belum';

// =============================================
// STREAK CALCULATION
// =============================================
$q_all = mysqli_query($koneksi, "SELECT tanggal, status FROM presensi WHERE id_murid='$id_murid' ORDER BY tanggal DESC");
$all_rec = [];
while ($r = mysqli_fetch_assoc($q_all)) {
    $all_rec[$r['tanggal']] = $r['status'];
}

// Current streak
$current_streak = 0;
for ($i = 0; $i < 365; $i++) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    if (isset($all_rec[$d]) && $all_rec[$d] === 'Hadir') {
        $current_streak++;
    } elseif (isset($all_rec[$d])) {
        break;
    } elseif ($i > 0) {
        break;
    }
}

// Best streak ever
$best_streak = 0;
$temp_streak = 0;
$sorted_dates = array_keys($all_rec);
sort($sorted_dates);
foreach ($sorted_dates as $d) {
    if ($all_rec[$d] === 'Hadir') {
        $temp_streak++;
        $best_streak = max($best_streak, $temp_streak);
    } else {
        $temp_streak = 0;
    }
}

$streak_target = 30;
$streak_pct    = min(100, round(($current_streak / $streak_target) * 100));

// =============================================
// STREAK GRID: 21 hari terakhir
// =============================================
$streak_days = [];
for ($i = 20; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    $streak_days[] = [
        'date'   => $d,
        'label'  => substr(['Min','Sen','Sel','Rab','Kam','Jum','Sab'][date('w', strtotime($d))], 0, 3),
        'day'    => (int) date('j', strtotime($d)),
        'status' => $all_rec[$d] ?? 'kosong',
        'today'  => ($d === $today),
    ];
}

// =============================================
// RIWAYAT 6 TERAKHIR
// =============================================
$q_riwayat = mysqli_query($koneksi, "
    SELECT p.tanggal, p.status, g.nama_guru
    FROM presensi p
    LEFT JOIN guru g ON p.id_guru = g.id_guru
    WHERE p.id_murid = '$id_murid'
    ORDER BY p.tanggal DESC
    LIMIT 6
");

// =============================================
// HADIS HARIAN (rotasi berdasarkan hari)
// =============================================
$hadis_list = [
    ["text" => "Sesungguhnya amalan yang pertama kali dihisab dari seorang hamba pada hari kiamat adalah shalatnya.", "sumber" => "HR. Abu Dawud"],
    ["text" => "Jagalah shalat wajib, terutama shalat Wustha (Ashar). Dan berdirilah karena Allah dengan khusyu'.", "sumber" => "QS. Al-Baqarah: 238"],
    ["text" => "Shalat adalah tiang agama, barangsiapa mendirikannya maka ia telah mendirikan agama.", "sumber" => "HR. Baihaqi"],
    ["text" => "Perbedaan antara seorang mukmin dan orang kafir adalah meninggalkan shalat.", "sumber" => "HR. Muslim"],
    ["text" => "Barangsiapa shalat di awal waktu, maka ia mendapat keridhaan Allah.", "sumber" => "HR. Tirmidzi"],
    ["text" => "Shalatlah sebelum dishalatkan (meninggal), dan segarkanlah hatimu dengan dzikrullah.", "sumber" => "Nasihat Ulama"],
    ["text" => "Dan dirikanlah shalat, tunaikanlah zakat, dan rukuklah beserta orang-orang yang rukuk.", "sumber" => "QS. Al-Baqarah: 43"],
];
$hadis = $hadis_list[date('N') % count($hadis_list)];
?>

<!-- TOP BAR -->
<div class="murid-topbar">
    <div class="murid-topbar-left">
        <button class="murid-mobile-toggle" id="mobileSidebarToggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
        <div>
            <h1 class="murid-topbar-greeting">Assalamu Alaikum, <?= htmlspecialchars($nama_murid); ?>! 👋</h1>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <span class="murid-role-pill">SISWA</span>
                <span style="font-size:13px;color:#6b7280;">Selamat datang kembali</span>
            </div>
        </div>
    </div>
    <div class="murid-topbar-right">
        <div class="murid-topbar-date">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span class="dynamic-date"></span>
        </div>
        <?php if ($current_streak > 0): ?>
        <div class="murid-topbar-streak">
            🔥 Streak <?= $current_streak; ?> hari — jangan putus!
        </div>
        <?php endif; ?>
        <div class="murid-topbar-avatar"><?= $initials; ?></div>
    </div>
</div>

<!-- =============================================
     4 STAT CARDS
     ============================================= -->
<div class="murid-stats-grid">
    <!-- Total Hadir -->
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#D1FAE5;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div>
            <div class="murid-stat-number count-up" data-target="<?= $total_hadir; ?>"><?= $total_hadir; ?></div>
            <div class="murid-stat-label">Total Zuhur</div>
        </div>
    </div>

    <!-- Total Hari Sekolah -->
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#E0F2FE;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number count-up" data-target="<?= $total_hari; ?>"><?= $total_hari; ?></div>
            <div class="murid-stat-label">Hari Sekolah</div>
        </div>
    </div>

    <!-- Persentase -->
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#FEF3C7;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number <?= $persen >= 85 ? 'text-green' : ($persen >= 60 ? 'text-yellow' : 'text-red'); ?>">
                <span class="count-up" data-target="<?= $persen; ?>"><?= $persen; ?></span>%
            </div>
            <div class="murid-stat-label">Tingkat Kehadiran</div>
        </div>
    </div>

    <!-- Status Hari Ini -->
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:<?= $status_today === 'Hadir' ? '#D1FAE5' : ($status_today === 'Tidak Hadir' ? '#FEE2E2' : '#FEF3C7'); ?>">
            <?php if ($status_today === 'Hadir'): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <?php elseif ($status_today === 'Tidak Hadir'): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#DC2626" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#D97706" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <?php endif; ?>
        </div>
        <div>
            <div class="murid-stat-number <?= $status_today === 'Hadir' ? 'text-green' : ($status_today === 'Tidak Hadir' ? 'text-red' : 'text-yellow'); ?>" style="font-size:22px;">
                <?= $status_today; ?>
            </div>
            <div class="murid-stat-label">Status Hari Ini</div>
        </div>
    </div>
</div>

<!-- =============================================
     STREAK CARD
     ============================================= -->
<div class="murid-streak-card">
    <div class="murid-streak-header">
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="streak-fire-big">🔥</div>
            <div>
                <div class="murid-streak-title">Streak Zuhur — <?= $current_streak; ?> Hari Beruntun!</div>
                <div class="murid-streak-subtitle">
                    <?php if ($current_streak >= $streak_target): ?>
                        🎉 Luar biasa! Kamu sudah mencapai target <?= $streak_target; ?> hari!
                    <?php else: ?>
                        Pertahankan streak kamu. Target berikutnya: <?= $streak_target; ?> hari
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="streak-stats-row">
            <div class="streak-stat-item">
                <div class="streak-stat-number"><?= $current_streak; ?></div>
                <div class="streak-stat-label">Streak Saat Ini</div>
            </div>
            <div class="streak-divider"></div>
            <div class="streak-stat-item">
                <div class="streak-stat-number"><?= $best_streak; ?></div>
                <div class="streak-stat-label">Terbaik</div>
            </div>
            <div class="streak-divider"></div>
            <div class="streak-stat-item">
                <div class="streak-stat-number"><?= $streak_target; ?></div>
                <div class="streak-stat-label">Target</div>
            </div>
        </div>
    </div>

    <!-- Streak Grid 21 hari -->
    <div class="streak-grid">
        <?php foreach ($streak_days as $sd): ?>
        <div class="streak-day-col">
            <div class="streak-day-label"><?= $sd['label']; ?></div>
            <div class="streak-day-dot 
                <?= $sd['status'] === 'Hadir' ? 'dot-hadir' : ''; ?>
                <?= $sd['status'] === 'Tidak Hadir' ? 'dot-absen' : ''; ?>
                <?= $sd['today'] ? 'dot-today' : ''; ?>
                <?= $sd['status'] === 'kosong' ? 'dot-empty' : ''; ?>
            ">
                <?php if ($sd['status'] === 'Hadir'): ?>✓
                <?php elseif ($sd['status'] === 'Tidak Hadir'): ?>✗
                <?php else: ?><?= $sd['day']; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Progress Bar -->
    <div style="margin-top:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <span style="font-size:13px;font-weight:700;color:#374151;">Menuju target <?= $streak_target; ?> hari ❤️</span>
            <span style="font-size:13px;font-weight:800;color:#1A6546;"><?= $current_streak; ?> / <?= $streak_target; ?> hari</span>
        </div>
        <div class="streak-progress-bar">
            <div class="streak-progress-fill" style="width: <?= $streak_pct; ?>%;"></div>
        </div>
    </div>
</div>

<!-- =============================================
     BOTTOM ROW: Riwayat + Status Hari Ini
     ============================================= -->
<div class="murid-bottom-grid">

    <!-- Riwayat Terbaru -->
    <div class="panel-card" style="padding: 24px 28px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:16px;font-weight:800;color:#111827;margin:0;">Riwayat Zuhur Terbaru</h3>
            <a href="riwayat.php" style="font-size:13px;font-weight:700;color:#1A6546;text-decoration:none;">Lihat Semua →</a>
        </div>
        <div class="theme-table-wrapper">
            <table class="theme-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th style="text-align:center;">Status</th>
                        <th>Guru Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($q_riwayat) > 0):
                        while ($row = mysqli_fetch_assoc($q_riwayat)):
                            $tgl = date('D, d M Y', strtotime($row['tanggal']));
                            $tgl_indo = str_replace(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], ['Sen','Sel','Rab','Kam','Jum','Sab','Min'], $tgl);
                    ?>
                    <tr>
                        <td style="font-size:13px;"><?= $tgl_indo; ?></td>
                        <td style="text-align:center;">
                            <?php if ($row['status'] === 'Hadir'): ?>
                                <span class="status-badge badge-green">✓ Tercatat</span>
                            <?php else: ?>
                                <span class="status-badge badge-red">✗ Absen</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:13px;color:#6b7280;"><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center;padding:32px;color:#9ca3af;">
                            Belum ada data presensi tercatat.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel Kanan: Status + Hadis -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Status Hari Ini -->
        <div class="panel-card" style="padding:24px 28px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h3 style="font-size:16px;font-weight:800;color:#111827;margin:0;">Status Zuhur Hari Ini</h3>
                <span class="status-badge badge-green"><?= date('d M'); ?></span>
            </div>
            <div style="text-align:center;padding:20px 0;">
                <?php if ($status_today === 'Hadir'): ?>
                    <div style="font-size:56px;margin-bottom:8px;">🎉</div>
                    <div style="font-size:18px;font-weight:800;color:#059669;">Alhamdulillah!</div>
                    <div style="font-size:13px;color:#6b7280;margin-top:4px;">Salat Dzuhur hari ini sudah tercatat</div>
                <?php elseif ($status_today === 'Tidak Hadir'): ?>
                    <div style="font-size:56px;margin-bottom:8px;">😔</div>
                    <div style="font-size:18px;font-weight:800;color:#DC2626;">Tidak Hadir</div>
                    <div style="font-size:13px;color:#6b7280;margin-top:4px;">Semoga besok bisa lebih baik ya!</div>
                <?php else: ?>
                    <div style="font-size:56px;margin-bottom:8px;">⏳</div>
                    <div style="font-size:18px;font-weight:800;color:#D97706;">Belum Tercatat</div>
                    <div style="font-size:13px;color:#6b7280;margin-top:4px;">Presensi hari ini belum diisi oleh guru</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hadis Harian -->
        <div class="murid-hadis-card">
            <div class="hadis-label">✨ Motivasi Hari Ini</div>
            <p class="hadis-text">"<?= $hadis['text']; ?>"</p>
            <div class="hadis-source">— <?= $hadis['sumber']; ?></div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
