<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   CEK METHOD
========================= */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: tambah.php");
    exit();
}

/* =========================
   AMBIL DATA POST
========================= */
$id_penghuni = $_POST['id_penghuni'] ?? '';
$id_kamar = $_POST['id_kamar'] ?? '';
$tanggal_bayar = $_POST['tanggal_bayar'] ?? '';
$bulan_bayar = $_POST['bulan_bayar'] ?? '';
$jumlah_bayar = $_POST['jumlah_bayar'] ?? '';
$status_pembayaran = $_POST['status_pembayaran'] ?? '';

/* =========================
   VALIDASI DATA KOSONG
========================= */
if(
    $id_penghuni == '' ||
    $tanggal_bayar == '' ||
    $bulan_bayar == '' ||
    $jumlah_bayar == '' ||
    $status_pembayaran == ''
){
    echo "
        <script>
            alert('Data pembayaran belum lengkap!');
            window.location='tambah.php';
        </script>
    ";
    exit();
}

/* =========================
   AMANKAN INPUT
========================= */
$id_penghuni = mysqli_real_escape_string($conn, $id_penghuni);
$id_kamar = mysqli_real_escape_string($conn, $id_kamar);
$tanggal_bayar = mysqli_real_escape_string($conn, $tanggal_bayar);
$bulan_bayar = mysqli_real_escape_string($conn, $bulan_bayar);
$jumlah_bayar = mysqli_real_escape_string($conn, $jumlah_bayar);
$status_pembayaran = mysqli_real_escape_string($conn, $status_pembayaran);

/* =========================
   AMBIL DATA PENGHUNI
========================= */
$cekPenghuni = mysqli_query($conn,"
    SELECT 
        penghuni.id_penghuni,
        penghuni.id_kamar
    FROM penghuni
    WHERE penghuni.id_penghuni = '$id_penghuni'
    LIMIT 1
");

if(mysqli_num_rows($cekPenghuni) == 0){
    echo "
        <script>
            alert('Data penghuni tidak ditemukan!');
            window.location='tambah.php';
        </script>
    ";
    exit();
}

$dataPenghuni = mysqli_fetch_assoc($cekPenghuni);

/* =========================
   PASTIKAN ID KAMAR
========================= */
if($id_kamar == ''){
    $id_kamar = $dataPenghuni['id_kamar'];
}

/* =========================
   CEK DUPLIKAT PEMBAYARAN
========================= */
$cekDuplikat = mysqli_query($conn,"
    SELECT id_pembayaran
    FROM pembayaran
    WHERE id_penghuni = '$id_penghuni'
    AND bulan_bayar = '$bulan_bayar'
    LIMIT 1
");

if(mysqli_num_rows($cekDuplikat) > 0){
    echo "
        <script>
            alert('Pembayaran untuk bulan ini sudah pernah dicatat!');
            window.location='tambah.php';
        </script>
    ";
    exit();
}

/* =========================
   INSERT DATA
========================= */
$insert = mysqli_query($conn,"
    INSERT INTO pembayaran (
        id_penghuni,
        id_kamar,
        tanggal_bayar,
        bulan_bayar,
        jumlah_bayar,
        status_pembayaran
    ) VALUES (
        '$id_penghuni',
        '$id_kamar',
        '$tanggal_bayar',
        '$bulan_bayar',
        '$jumlah_bayar',
        '$status_pembayaran'
    )
");

if($insert){

    header("Location: index.php");
    exit();

}else{

    echo "
        <script>
            alert('Gagal menambahkan pembayaran: ".mysqli_error($conn)."');
            window.location='tambah.php';
        </script>
    ";
    exit();

}
?>