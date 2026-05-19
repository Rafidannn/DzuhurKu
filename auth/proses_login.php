<?php
session_start();
include '../config/koneksi.php'; // Panggil koneksi database

if (isset($_POST['login'])) {
    // Tangkap input dari form login, cegah SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // 1. Cek apakah yang login adalah Admin (Cek di tabel admin)
    $query_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query_admin) > 0) {
        $data_admin = mysqli_fetch_assoc($query_admin);
        
        // Buat Session Admin
        $_SESSION['login'] = true;
        $_SESSION['role']  = 'admin';
        $_SESSION['nama']  = $data_admin['nama_admin'];
        $_SESSION['id']    = $data_admin['id_admin'];
        
        // Arahkan ke dashboard admin
        header("Location: ../admin/index.php");
        exit();
    }
    
    // 2. Cek apakah yang login adalah Murid (Cek di tabel murid menggunakan NISN)
    $query_murid = mysqli_query($koneksi, "SELECT * FROM murid WHERE nisn='$username' AND password='$password'");
    
    if (mysqli_num_rows($query_murid) > 0) {
        $data_murid = mysqli_fetch_assoc($query_murid);
        
        // Buat Session Murid
        $_SESSION['login'] = true;
        $_SESSION['role']  = 'murid';
        $_SESSION['nama']  = $data_murid['nama_murid'];
        $_SESSION['id']    = $data_murid['id_murid'];
        
        // Sementara arahkan ke halaman dummy (bisa kita ubah nanti)
        // Kita paksa arahkan kembali ke admin/index.php atau dashboard murid
        header("Location: ../admin/index.php");
        exit();
    }
    
    // 3. Jika salah username/password
    echo "<script>
            alert('Username/NISN atau Password salah!');
            window.location.href = 'login.php';
          </script>";
} else {
    // Jika ada yang mencoba buka proses_login.php tanpa menekan tombol Masuk
    header("Location: login.php");
}
?>
