<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   AMBIL DATA
========================= */
$nomor = strtoupper(trim($_POST['nomor_kamar']));
$harga = $_POST['harga_kamar'];
$status = $_POST['status_kamar'];

/* =========================
   AUTO FASILITAS
========================= */
$fasilitas = [];

/* A1-A10 */
if(preg_match('/^A([1-9]|10)$/', $nomor)){
    $fasilitas = [
        'AC',
        'Kamar Mandi Dalam',
        'WiFi',
        'Kasur',
        'Lemari'
    ];
}

/* A11-A20 */
elseif(preg_match('/^A(1[1-9]|20)$/', $nomor)){
    $fasilitas = [
        'Kipas',
        'WiFi',
        'Kasur',
        'Lemari'
    ];
}

/* B1-B10 */
elseif(preg_match('/^B([1-9]|10)$/', $nomor)){
    $fasilitas = [
        'AC',
        'Kamar Mandi Bersama',
        'WiFi',
        'Kasur',
        'Lemari'
    ];
}

/* B11-B20 */
elseif(preg_match('/^B(1[1-9]|20)$/', $nomor)){
    $fasilitas = [
        'Kipas',
        'Kamar Mandi Bersama',
        'WiFi',
        'Kasur',
        'Lemari'
    ];
}

/* GABUNG FASILITAS */
$fasilitas_text = implode(', ', $fasilitas);

/* =========================
   INSERT DATABASE
========================= */
mysqli_query($conn,"
    INSERT INTO kamar
    (
        nomor_kamar,
        harga_kamar,
        status_kamar,
        fasilitas
    )
    VALUES
    (
        '$nomor',
        '$harga',
        '$status',
        '$fasilitas_text'
    )
");

/* =========================
   REDIRECT
========================= */
header("Location: index.php");
exit();
?>