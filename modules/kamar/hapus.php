<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   VALIDASI ID
========================= */
if(!isset($_GET['id']) || empty($_GET['id'])){
    
    echo "
    <script>
        alert('ID kamar tidak ditemukan!');
        window.location='index.php';
    </script>
    ";

    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   CEK DATA KAMAR
========================= */
$cek = mysqli_query($conn,"
    SELECT *
    FROM kamar
    WHERE id_kamar='$id'
");

if(mysqli_num_rows($cek) == 0){

    echo "
    <script>
        alert('Data kamar tidak ditemukan!');
        window.location='index.php';
    </script>
    ";

    exit();
}

/* =========================
   OPTIONAL:
   CEK APAKAH MASIH DIGUNAKAN
========================= */

/*
Jika nanti ada relasi penghuni -> kamar,
gunakan validasi ini.

Contoh:

$cekPenghuni = mysqli_query($conn,"
    SELECT *
    FROM penghuni
    WHERE id_kamar='$id'
");

if(mysqli_num_rows($cekPenghuni') > 0){

    echo "
    <script>
        alert('Kamar masih digunakan penghuni!');
        window.location='index.php';
    </script>
    ";

    exit();
}
*/

/* =========================
   HAPUS DATA
========================= */
$hapus = mysqli_query($conn,"
    DELETE FROM kamar
    WHERE id_kamar='$id'
");

/* =========================
   RESPONSE
========================= */
if($hapus){

    echo "
    <script>
        alert('Data kamar berhasil dihapus!');
        window.location='index.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Gagal menghapus data kamar!');
        window.location='index.php';
    </script>
    ";
}
?>