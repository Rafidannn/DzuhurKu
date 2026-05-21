<?php
session_start();
// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    // Jika role bukan admin (misal murid), lempar ke portal murid
    header("Location: ../murid/index.php");
    exit();
}
include '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard'; ?> - Dzuhurku Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Google Fonts Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
