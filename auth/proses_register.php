<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['register'])) {
    // Tangkap data dari form register, cegah SQL Injection
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nisn       = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $id_kelas   = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $password   = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_pw = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // 1. Validasi kecocokan password
    if ($password !== $confirm_pw) {
        echo "<script>
                alert('Konfirmasi password tidak cocok!');
                window.history.back();
              </script>";
        exit();
    }

    // 2. Cek apakah NISN sudah digunakan di database
    $cek_nisn = mysqli_query($koneksi, "SELECT * FROM murid WHERE nisn = '$nisn'");
    if (mysqli_num_rows($cek_nisn) > 0) {
        echo "<script>
                alert('NISN sudah terdaftar! Silakan login atau gunakan NISN lain.');
                window.history.back();
              </script>";
        exit();
    }

    // 3. Simpan data murid baru ke database (menyimpan password dalam teks biasa sesuai dengan skema bawaan database)
    $query_insert = "INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES ('$nisn', '$nama', '$password', '$id_kelas')";
    $exec_insert  = mysqli_query($koneksi, $query_insert);

    if ($exec_insert) {
        echo "<script>
                alert('Registrasi berhasil! Silakan masuk menggunakan NISN dan Password Anda.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registrasi gagal, terjadi kesalahan pada server. Silakan coba lagi.');
                window.history.back();
              </script>";
    }
} else {
    // Jika diakses langsung tanpa submit
    header("Location: register.php");
    exit();
}
?>
