<?php
session_start();
// Cek apakah user sudah login, jika belum lempar ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DzuhurKu</title>
    <!-- Kita bisa pakai style yang sama untuk fondasi font dll -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-container {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            background: var(--card-bg);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-logout { background: #EF4444; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .btn-logout:hover { background: #DC2626; }
    </style>
</head>
<body style="background: var(--bg-color);">

    <div class="dashboard-container">
        <div class="header">
            <div>
                <h1 style="color: var(--text-main);">Selamat Datang, <?php echo $_SESSION['nama']; ?>! 👋</h1>
                <p style="color: var(--text-muted);">Login sebagai: <strong><?php echo strtoupper($_SESSION['role']); ?></strong></p>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Keluar</a>
        </div>
        
        <div style="background: var(--primary-light); padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary);">
            <p style="color: var(--primary-dark);">Ini adalah halaman Dashboard sementara. Nanti di sini kita akan buat fitur "Pilih Kelas" dan form absensi.</p>
        </div>
    </div>

</body>
</html>
