<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$id_admin = $_SESSION['admin_id'];

$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

$password_lama = trim($password_lama);
$password_baru = trim($password_baru);
$konfirmasi_password = trim($konfirmasi_password);

/* =========================
   VALIDASI
========================= */
if($password_lama == '' || $password_baru == '' || $konfirmasi_password == ''){
    header("Location: ganti_password.php?status=empty");
    exit();
}

if(strlen($password_baru) < 6){
    header("Location: ganti_password.php?status=short");
    exit();
}

if($password_baru != $konfirmasi_password){
    header("Location: ganti_password.php?status=notmatch");
    exit();
}

/* =========================
   AMBIL DATA ADMIN
========================= */
$q = mysqli_query($conn, "
    SELECT * FROM admin 
    WHERE id_admin = '$id_admin'
    LIMIT 1
");

if(!$q || mysqli_num_rows($q) == 0){
    header("Location: ganti_password.php?status=failed");
    exit();
}

$admin = mysqli_fetch_assoc($q);

/* =========================
   CEK PASSWORD LAMA
========================= */
$password_db = $admin['password'] ?? '';

$validPassword = false;

/*
    Support 2 kondisi:
    1. Password sudah hash password_hash()
    2. Password lama masih plain text di database
*/
if(password_verify($password_lama, $password_db)){
    $validPassword = true;
}else if($password_lama === $password_db){
    $validPassword = true;
}

if(!$validPassword){
    header("Location: ganti_password.php?status=wrong");
    exit();
}

/* =========================
   UPDATE PASSWORD
========================= */
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

$update = mysqli_query($conn, "
    UPDATE admin 
    SET password = '$password_hash'
    WHERE id_admin = '$id_admin'
");

if($update){
    header("Location: ganti_password.php?status=success");
    exit();
}else{
    header("Location: ganti_password.php?status=failed");
    exit();
}