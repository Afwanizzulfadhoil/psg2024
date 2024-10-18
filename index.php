<?php
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (isset($_SESSION['role'])) {
    // Jika pengguna sudah login, cek role-nya
    if ($_SESSION['role'] == 'admin') {
        // Jika role adalah admin, arahkan ke halaman admin
        header("Location:admin_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] == 'user') {
        // Jika role adalah user, arahkan ke halaman user
        header("Location:user_dashboard.php");
        exit();
    } else {
        // Jika role tidak dikenali, arahkan ke halaman umum atau logout
        header("Location:logout.php"); // atau ke halaman error
        exit();
    }
} else {
    // Jika pengguna belum login, arahkan ke halaman login
    header("Location:login.php");
    exit();
}
