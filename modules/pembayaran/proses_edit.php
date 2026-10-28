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
    header("Location: index.php");
    exit();
}

/* =========================
   AMBIL DATA POST
========================= */
$id_pembayaran = $_POST['id_pembayaran'] ?? '';
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
    $id_pembayaran == '' ||
    $id_penghuni == '' ||
    $tanggal_bayar == '' ||
    $bulan_bayar == '' ||
    $jumlah_bayar == '' ||
    $status_pembayaran == ''
){
    echo "
        <script>
            alert('Data pembayaran belum lengkap!');
            window.location='index.php';
        </script>
    ";
    exit();
}

/* =========================
   AMANKAN INPUT
========================= */
$id_pembayaran = mysqli_real_escape_string($conn, $id_pembayaran);
$id_penghuni = mysqli_real_escape_string($conn, $id_penghuni);
$id_kamar = mysqli_real_escape_string($conn, $id_kamar);
$tanggal_bayar = mysqli_real_escape_string($conn, $tanggal_bayar);
$bulan_bayar = mysqli_real_escape_string($conn, $bulan_bayar);
$jumlah_bayar = mysqli_real_escape_string($conn, $jumlah_bayar);
$status_pembayaran = mysqli_real_escape_string($conn, $status_pembayaran);

/* =========================
   CEK DATA PEMBAYARAN
========================= */
$cekPembayaran = mysqli_query($conn,"
    SELECT id_pembayaran
    FROM pembayaran
    WHERE id_pembayaran = '$id_pembayaran'
    LIMIT 1
");

if(mysqli_num_rows($cekPembayaran) == 0){
    echo "
        <script>
            alert('Data pembayaran tidak ditemukan!');
            window.location='index.php';
        </script>
    ";
    exit();
}

/* =========================
   CEK DATA PENGHUNI
========================= */
$cekPenghuni = mysqli_query($conn,"
    SELECT 
        id_penghuni,
        id_kamar
    FROM penghuni
    WHERE id_penghuni = '$id_penghuni'
    LIMIT 1
");

if(mysqli_num_rows($cekPenghuni) == 0){
    echo "
        <script>
            alert('Data penghuni tidak ditemukan!');
            window.location='edit.php?id=$id_pembayaran';
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
   CEK DUPLIKAT BULAN
   selain data yang sedang diedit
========================= */
$cekDuplikat = mysqli_query($conn,"
    SELECT id_pembayaran
    FROM pembayaran
    WHERE id_penghuni = '$id_penghuni'
    AND bulan_bayar = '$bulan_bayar'
    AND id_pembayaran != '$id_pembayaran'
    LIMIT 1
");

if(mysqli_num_rows($cekDuplikat) > 0){
    echo "
        <script>
            alert('Pembayaran untuk penghuni dan bulan ini sudah ada!');
            window.location='edit.php?id=$id_pembayaran';
        </script>
    ";
    exit();
}

/* =========================
   UPDATE DATA
========================= */
$update = mysqli_query($conn,"
    UPDATE pembayaran SET
        id_penghuni = '$id_penghuni',
        id_kamar = '$id_kamar',
        tanggal_bayar = '$tanggal_bayar',
        bulan_bayar = '$bulan_bayar',
        jumlah_bayar = '$jumlah_bayar',
        status_pembayaran = '$status_pembayaran'
    WHERE id_pembayaran = '$id_pembayaran'
");

if($update){

    header("Location: detail.php?id=$id_pembayaran");
    exit();

}else{

    echo "
        <script>
            alert('Gagal memperbarui pembayaran: ".mysqli_error($conn)."');
            window.location='edit.php?id=$id_pembayaran';
        </script>
    ";
    exit();

}
?>