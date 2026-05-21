<?php
$title = 'Kalender Presensi';
include 'header.php';
include 'sidebar.php';

$id_murid = $_SESSION['id'];

// Get selected month and year
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Clamp values
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

// Previous and Next month parameters
$prev_month = $month - 1;
$prev_year  = $year;
if ($prev_month < 1) { $prev_month = 12; $prev_year--; }

$next_month = $month + 1;
$next_year  = $year;
if ($next_month > 12) { $next_month = 1; $next_year++; }

// Month names in Indonesian
$month_names = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Formatting target string for database (e.g., '2026-05')
$month_db_prefix = sprintf('%04d-%02d', $year, $month);

// Fetch attendance for this month
$q_attendance = mysqli_query($koneksi, "
    SELECT p.tanggal, p.status, g.nama_guru 
    FROM presensi p
    LEFT JOIN guru g ON p.id_guru = g.id_guru
    WHERE p.id_murid = '$id_murid' AND p.tanggal LIKE '$month_db_prefix%'
");

$attendance_data = [];
while ($row = mysqli_fetch_assoc($q_attendance)) {
    $attendance_data[$row['tanggal']] = [
        'status' => $row['status'],
        'guru'   => $row['nama_guru']
    ];
}

// Calculate summary stats for the current month view
$stats_hadir = 0;
$stats_absen = 0;
foreach ($attendance_data as $date => $info) {
    if ($info['status'] === 'Hadir') {
        $stats_hadir++;
    } else {
        $stats_absen++;
    }
}
$stats_total = $stats_hadir + $stats_absen;
$stats_pct = $stats_total > 0 ? round(($stats_hadir / $stats_total) * 100) : 0;

// Calendar calculations
$first_day_ts = mktime(0, 0, 0, $month, 1, $year);
$total_days = date('t', $first_day_ts); // Total days in selected month
$first_day_of_week = date('w', $first_day_ts); // 0 (Sunday) to 6 (Saturday)

// Get previous month's total days for padding
$prev_month_ts = mktime(0, 0, 0, $prev_month, 1, $prev_year);
$prev_total_days = date('t', $prev_month_ts);
?>

<!-- TOPBAR -->
<div class="murid-topbar">
    <div class="murid-topbar-left">
        <div>
            <h1 class="murid-topbar-greeting">Kalender Presensi</h1>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <span class="murid-role-pill">SISWA</span>
                <span style="font-size:13px;color:#6b7280;">Pantau kehadiran harian Anda dalam tampilan kalender</span>
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

<!-- MONTHLY SUMMARY STATS -->
<div class="murid-stats-grid" style="margin-bottom:24px;">
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#D1FAE5;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div>
            <div class="murid-stat-number text-green"><?= $stats_hadir; ?></div>
            <div class="murid-stat-label">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#FEE2E2;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number text-red"><?= $stats_absen; ?></div>
            <div class="murid-stat-label">Absen Bulan Ini</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#FEF3C7;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number <?= $stats_pct >= 85 ? 'text-green' : ($stats_pct >= 60 ? 'text-yellow' : 'text-red'); ?>"><?= $stats_pct; ?>%</div>
            <div class="murid-stat-label">Persentase</div>
        </div>
    </div>
    <div class="murid-stat-card">
        <div class="murid-stat-icon" style="background:#E0F2FE;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="murid-stat-number" style="color:#0284c7;"><?= $stats_total; ?></div>
            <div class="murid-stat-label">Total Pencatatan</div>
        </div>
    </div>
</div>

<!-- CALENDAR -->
<div class="calendar-container">
    <div class="calendar-header">
        <div class="calendar-title-wrapper">
            <span style="font-size:24px;">📅</span>
            <div class="calendar-current-month"><?= $month_names[$month] . ' ' . $year; ?></div>
        </div>
        <div class="calendar-nav">
            <a href="?month=<?= $prev_month; ?>&year=<?= $prev_year; ?>" class="calendar-nav-btn" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>
            <a href="?month=<?= date('n'); ?>&year=<?= date('Y'); ?>" class="calendar-nav-btn" style="text-decoration: none; font-size:12px; font-weight:700; width:auto; padding:0 12px;">
                Hari Ini
            </a>
            <a href="?month=<?= $next_month; ?>&year=<?= $next_year; ?>" class="calendar-nav-btn" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- CALENDAR GRID -->
    <div class="calendar-grid">
        <!-- Day Names -->
        <div class="calendar-day-name sunday">Min</div>
        <div class="calendar-day-name">Sen</div>
        <div class="calendar-day-name">Sel</div>
        <div class="calendar-day-name">Rab</div>
        <div class="calendar-day-name">Kam</div>
        <div class="calendar-day-name">Jum</div>
        <div class="calendar-day-name">Sab</div>

        <?php
        // Render padding cells for the previous month
        for ($i = $first_day_of_week - 1; $i >= 0; $i--) {
            $day_num = $prev_total_days - $i;
            echo "<div class='calendar-cell other-month'>";
            echo "<span class='calendar-cell-date'>$day_num</span>";
            echo "</div>";
        }

        // Render current month days
        $today_str = date('Y-m-d');
        for ($day = 1; $day <= $total_days; $day++) {
            $current_date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $has_record = isset($attendance_data[$current_date_str]);
            
            $cell_class = 'calendar-cell';
            if ($current_date_str === $today_str) {
                $cell_class .= ' today';
            }
            
            $status_html = '';
            $info_html = '';
            if ($has_record) {
                $status = $attendance_data[$current_date_str]['status'];
                $guru = $attendance_data[$current_date_str]['guru'];
                
                if ($status === 'Hadir') {
                    $cell_class .= ' hadir';
                    $status_html = '<span class="calendar-cell-status">✓ Hadir</span>';
                } else {
                    $cell_class .= ' absen';
                    $status_html = '<span class="calendar-cell-status">✗ Absen</span>';
                }
                if ($guru) {
                    $info_html = '<span class="calendar-cell-info" title="Dicatat oleh: '.htmlspecialchars($guru).'">✍ ' . htmlspecialchars($guru) . '</span>';
                }
            }

            echo "<div class='$cell_class'>";
            echo "<span class='calendar-cell-date'>$day</span>";
            echo $status_html;
            echo $info_html;
            echo "</div>";
        }

        // Render padding cells for the next month to complete the grid (usually 35 or 42 total slots)
        $total_slots_so_far = $first_day_of_week + $total_days;
        $remaining_slots = (7 - ($total_slots_so_far % 7)) % 7;
        for ($next_day = 1; $next_day <= $remaining_slots; $next_day++) {
            echo "<div class='calendar-cell other-month'>";
            echo "<span class='calendar-cell-date'>$next_day</span>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- LEGEND -->
    <div class="calendar-legend">
        <div class="legend-item">
            <div class="legend-color hadir"></div>
            <span>Salat Dzuhur Hadir / Tercatat</span>
        </div>
        <div class="legend-item">
            <div class="legend-color absen"></div>
            <span>Absen / Tidak Hadir</span>
        </div>
        <div class="legend-item">
            <div class="legend-color kosong"></div>
            <span>Belum Ada Data / Hari Libur</span>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
