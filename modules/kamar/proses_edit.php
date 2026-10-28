<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   VALIDASI POST
========================= */
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: index.php");
    exit();
}

/* =========================
   AMBIL DATA
========================= */
$id_kamar     = mysqli_real_escape_string($conn, $_POST['id_kamar']);
$nomor_kamar  = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['nomor_kamar'])));
$harga_kamar  = mysqli_real_escape_string($conn, $_POST['harga_kamar']);
$status_kamar = mysqli_real_escape_string($conn, $_POST['status_kamar']);

/* =========================
   VALIDASI
========================= */
if(
    empty($id_kamar) ||
    empty($nomor_kamar) ||
    empty($harga_kamar) ||
    empty($status_kamar)
){
    echo "
    <script>
        alert('Semua field wajib diisi!');
        window.history.back();
    </script>
    ";
    exit();
}

/* =========================
   CEK DUPLIKAT NOMOR
========================= */
$cek = mysqli_query($conn,"
    SELECT *
    FROM kamar
    WHERE nomor_kamar='$nomor_kamar'
    AND id_kamar != '$id_kamar'
");

if(mysqli_num_rows($cek) > 0){

    echo "
    <script>
        alert('Nomor kamar sudah digunakan!');
        window.history.back();
    </script>
    ";

    exit();
}

/* =========================
   UPDATE DATA
========================= */
$update = mysqli_query($conn,"
    UPDATE kamar
    SET
        nomor_kamar='$nomor_kamar',
        harga_kamar='$harga_kamar',
        status_kamar='$status_kamar'
    WHERE id_kamar='$id_kamar'
");

/* =========================
   RESPONSE
========================= */
if($update){

    echo "
    <script>
        alert('Data kamar berhasil diperbarui!');
        window.location='index.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Gagal memperbarui data kamar!');
        window.history.back();
    </script>
    ";
}
?>