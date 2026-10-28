<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   CEK ID
========================= */
if(!isset($_GET['id']) || $_GET['id'] == ''){
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   CEK DATA PEMBAYARAN
========================= */
$cek = mysqli_query($conn,"
    SELECT id_pembayaran
    FROM pembayaran
    WHERE id_pembayaran = '$id'
    LIMIT 1
");

if(!$cek || mysqli_num_rows($cek) == 0){
    echo "
        <script>
            alert('Data pembayaran tidak ditemukan!');
            window.location='index.php';
        </script>
    ";
    exit();
}

/* =========================
   HAPUS DATA
========================= */
$hapus = mysqli_query($conn,"
    DELETE FROM pembayaran
    WHERE id_pembayaran = '$id'
");

if($hapus){

    header("Location: index.php");
    exit();

}else{

    echo "
        <script>
            alert('Gagal menghapus pembayaran: ".mysqli_error($conn)."');
            window.location='index.php';
        </script>
    ";
    exit();

}
?>