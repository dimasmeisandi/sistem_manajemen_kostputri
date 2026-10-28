<?php
// Memulai session untuk mengecek status login
session_start();

// Periksa apakah session admin sudah ada
if (!isset($_SESSION['admin_id'])) {
    // Jika belum login, arahkan ke folder auth/login.php
    header("Location: auth/login.php");
    exit();
} else {
    // Jika sudah login, arahkan ke dashboard.php
    header("Location: dashboard.php");
    exit();
}
?>