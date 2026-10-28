<?php
session_start();
require_once '../../config/db.php';

/* =========================
   VALIDASI LOGIN
========================= */
if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   VALIDASI ID
========================= */
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   AMBIL DATA PENGHUNI
========================= */
$q = mysqli_query($conn,"
    SELECT *
    FROM penghuni
    WHERE id_penghuni='$id'
");

if(mysqli_num_rows($q) == 0){

    echo "
    <script>
        alert('Data penghuni tidak ditemukan!');
        window.location='index.php';
    </script>
    ";

    exit();
}

$data = mysqli_fetch_assoc($q);

$id_kamar = $data['id_kamar'];

/* =========================
   HAPUS PENGHUNI
========================= */
$hapus = mysqli_query($conn,"
    DELETE FROM penghuni
    WHERE id_penghuni='$id'
");

/* =========================
   JIKA BERHASIL
========================= */
if($hapus){

    /* =========================
       UPDATE STATUS KAMAR
    ========================= */
    mysqli_query($conn,"
        UPDATE kamar
        SET status_kamar='Kosong'
        WHERE id_kamar='$id_kamar'
    ");

    echo "
    <script>
        alert('Data penghuni berhasil dihapus!');
        window.location='index.php';
    </script>
    ";

}
else{

    echo "
    <script>
        alert('Gagal menghapus data penghuni!');
        window.location='index.php';
    </script>
    ";

}
?>