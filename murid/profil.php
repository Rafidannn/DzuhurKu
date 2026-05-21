<?php
$title = 'Profil Saya';
include 'header.php';
include 'sidebar.php';

$id_murid = $_SESSION['id'];

// Handle ganti password
if (isset($_POST['ganti_password'])) {
    $pw_lama = mysqli_real_escape_string($koneksi, $_POST['password_lama']);
    $pw_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);
    $pw_konfirm = $_POST['password_konfirm'];

    $cek = mysqli_query($koneksi, "SELECT id_murid FROM murid WHERE id_murid='$id_murid' AND password='$pw_lama'");
    if (mysqli_num_rows($cek) == 0) {
        $error = "Password lama yang Anda masukkan salah!";
    } elseif ($pw_baru !== $pw_konfirm) {
        $error = "Konfirmasi password baru tidak cocok!";
    } elseif (strlen($pw_baru) < 6) {
        $error = "Password baru minimal 6 karakter!";
    } else {
        mysqli_query($koneksi, "UPDATE murid SET password='$pw_baru' WHERE id_murid='$id_murid'");
        $success = "Password berhasil diperbarui!";
    }
}

// Ambil data profil
$q = mysqli_query($koneksi, "SELECT m.*, k.nama_kelas FROM murid m JOIN kelas k ON m.id_kelas = k.id_kelas WHERE m.id_murid='$id_murid'");
$profil = mysqli_fetch_assoc($q);
$nama_kelas_bersih = preg_replace('/\s*\([^)]*\)\s*/', ' ', $profil['nama_kelas']);

$words    = explode(' ', trim($profil['nama_murid']));
$initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

// Statistik total
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as t FROM presensi WHERE id_murid='$id_murid'");
$total_presensi = (int) mysqli_fetch_assoc($q_total)['t'];
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as h FROM presensi WHERE id_murid='$id_murid' AND status='Hadir'");
$total_hadir = (int) mysqli_fetch_assoc($q_hadir)['h'];
$persen = $total_presensi > 0 ? round(($total_hadir / $total_presensi) * 100) : 0;
?>

<!-- TOPBAR -->
<div class="murid-topbar">
    <div class="murid-topbar-left">
        <div>
            <h1 class="murid-topbar-greeting">Profil Saya</h1>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <span class="murid-role-pill">SISWA</span>
                <span style="font-size:13px;color:#6b7280;">Informasi akun dan keamanan</span>
            </div>
        </div>
    </div>
    <div class="murid-topbar-right">
        <div class="murid-topbar-date">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span class="dynamic-date"></span>
        </div>
        <div class="murid-topbar-avatar"><?= $initials; ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    <!-- Kartu Profil -->
    <div class="panel-card" style="padding:32px;">
        <!-- Avatar Besar -->
        <div style="text-align:center;margin-bottom:24px;">
            <div style="width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#1A6546,#2a9d68);display:inline-flex;align-items:center;justify-content:center;font-size:30px;font-weight:800;color:#fff;margin-bottom:14px;">
                <?= $initials; ?>
            </div>
            <div style="font-size:20px;font-weight:800;color:#111827;"><?= htmlspecialchars($profil['nama_murid']); ?></div>
            <div style="margin-top:6px;"><span class="murid-role-pill">SISWA</span></div>
        </div>

        <!-- Data Diri -->
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="padding:14px 16px;background:#F9FAFB;border-radius:14px;border:1px solid #F3F4F6;">
                <div style="font-size:11px;font-weight:700;color:#9ca3af;letter-spacing:0.5px;margin-bottom:4px;">NISN</div>
                <div style="font-size:15px;font-weight:700;color:#111827;font-family:monospace;"><?= htmlspecialchars($profil['nisn']); ?></div>
            </div>
            <div style="padding:14px 16px;background:#F9FAFB;border-radius:14px;border:1px solid #F3F4F6;">
                <div style="font-size:11px;font-weight:700;color:#9ca3af;letter-spacing:0.5px;margin-bottom:4px;">KELAS</div>
                <div style="font-size:15px;font-weight:700;color:#111827;"><?= htmlspecialchars($nama_kelas_bersih); ?></div>
            </div>
        </div>

        <!-- Statistik Total -->
        <div style="margin-top:24px;display:grid;grid-template-columns:repeat(3,1fr);gap:12px;text-align:center;">
            <div style="padding:14px 10px;background:#D1FAE5;border-radius:14px;">
                <div style="font-size:22px;font-weight:800;color:#059669;"><?= $total_hadir; ?></div>
                <div style="font-size:11px;font-weight:600;color:#065F46;margin-top:2px;">Total Hadir</div>
            </div>
            <div style="padding:14px 10px;background:#FEE2E2;border-radius:14px;">
                <div style="font-size:22px;font-weight:800;color:#DC2626;"><?= $total_presensi - $total_hadir; ?></div>
                <div style="font-size:11px;font-weight:600;color:#7F1D1D;margin-top:2px;">Total Absen</div>
            </div>
            <div style="padding:14px 10px;background:#EDE9FE;border-radius:14px;">
                <div style="font-size:22px;font-weight:800;color:#7C3AED;"><?= $persen; ?>%</div>
                <div style="font-size:11px;font-weight:600;color:#4C1D95;margin-top:2px;">Kehadiran</div>
            </div>
        </div>
    </div>

    <!-- Form Ganti Password -->
    <div class="panel-card" style="padding:32px;">
        <h3 style="font-size:17px;font-weight:800;color:#111827;margin:0 0 6px;">Ganti Password</h3>
        <p style="font-size:13px;color:#6b7280;margin:0 0 24px;">Perbarui kata sandi akses kamu secara berkala demi keamanan.</p>

        <?php if (isset($error)): ?>
        <div style="background:#FEE2E2;border:1px solid #FCA5A5;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:#DC2626;">
            ⚠️ <?= $error; ?>
        </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
        <div style="background:#D1FAE5;border:1px solid #34D399;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:#065F46;">
            ✅ <?= $success; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="profil.php">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:8px;">Password Lama</label>
                <input type="password" name="password_lama" class="modal-input-field" placeholder="Masukkan password saat ini" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:8px;">Password Baru</label>
                <input type="password" name="password_baru" class="modal-input-field" placeholder="Minimal 6 karakter" required>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:8px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_konfirm" class="modal-input-field" placeholder="Ulangi password baru" required>
            </div>
            <button type="submit" name="ganti_password" class="btn-signin" style="width:100%;padding:16px;">
                Perbarui Password
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
