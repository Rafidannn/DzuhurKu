<?php
$id_murid    = $_SESSION['id'];
$nama_murid  = $_SESSION['nama'];
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil data kelas murid
$q_info = mysqli_query($koneksi, "SELECT m.*, k.nama_kelas FROM murid m JOIN kelas k ON m.id_kelas = k.id_kelas WHERE m.id_murid = '$id_murid'");
$info   = mysqli_fetch_assoc($q_info);
$nama_kelas = preg_replace('/\s*\([^)]*\)\s*/', ' ', $info['nama_kelas'] ?? '-');

// Hitung streak (beruntun hadir dari hari ini mundur)
$q_streak = mysqli_query($koneksi, "SELECT tanggal, status FROM presensi WHERE id_murid = '$id_murid' ORDER BY tanggal DESC");
$all_records = [];
while ($r = mysqli_fetch_assoc($q_streak)) {
    $all_records[$r['tanggal']] = $r['status'];
}

$streak = 0;
$check  = date('Y-m-d');
for ($i = 0; $i < 365; $i++) {
    $d = date('Y-m-d', strtotime("-$i days"));
    if (isset($all_records[$d]) && $all_records[$d] === 'Hadir') {
        $streak++;
    } elseif (isset($all_records[$d])) {
        break;
    } elseif ($i > 0) {
        break;
    }
}

// Inisial nama untuk avatar
$words    = explode(' ', trim($nama_murid));
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
?>
<aside class="murid-sidebar">
    <!-- Brand -->
    <div class="murid-brand">
        <div class="murid-brand-logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                <path d="M12 6a15.3 15.3 0 0 1 1.5 6 15.3 15.3 0 0 1-1.5 6"></path>
            </svg>
        </div>
        <div>
            <div class="murid-brand-title">Dzuhurku</div>
            <div class="murid-brand-sub">PRAYER COMPANION</div>
        </div>
    </div>

    <!-- Profile Block -->
    <div class="murid-profile-block">
        <div class="murid-avatar"><?= $initials; ?></div>
        <div>
            <div class="murid-profile-name"><?= htmlspecialchars($nama_murid); ?></div>
            <span class="murid-role-badge">SISWA</span>
        </div>
    </div>

    <!-- Streak Block -->
    <div class="murid-streak-block">
        <span class="streak-flame">🔥</span>
        <div>
            <div class="streak-days"><?= $streak; ?> hari</div>
            <div class="streak-label">Streak — Jangan putus!</div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="murid-menu-label">MENU UTAMA</div>
    <nav class="murid-nav">
        <a href="index.php" class="murid-menu-item <?= $current_page == 'index.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>
        <a href="kalender.php" class="murid-menu-item <?= $current_page == 'kalender.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Kalender
        </a>
        <a href="riwayat.php" class="murid-menu-item <?= $current_page == 'riwayat.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            Riwayat
        </a>
        <a href="profil.php" class="murid-menu-item <?= $current_page == 'profil.php' ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Profil
        </a>
    </nav>

    <!-- Logout -->
    <div class="murid-sidebar-footer">
        <a href="../auth/logout.php" class="murid-logout-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Keluar
        </a>
    </div>
</aside>
<main class="murid-main">
    <div class="murid-content-body">
