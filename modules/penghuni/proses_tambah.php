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
   VALIDASI METHOD
========================= */
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: tambah.php");
    exit();
}

/* =========================
   AMBIL DATA
========================= */
$nama_penghuni = mysqli_real_escape_string($conn, $_POST['nama_penghuni']);
$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
$no_hp          = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);
$tgl_masuk      = mysqli_real_escape_string($conn, $_POST['tgl_masuk']);
$id_kamar       = mysqli_real_escape_string($conn, $_POST['id_kamar']);

/* =========================
   VALIDASI KAMAR
========================= */
$cekKamar = mysqli_query($conn,"
    SELECT *
    FROM kamar
    WHERE id_kamar='$id_kamar'
");

if(mysqli_num_rows($cekKamar) == 0){

    echo "
    <script>
        alert('Kamar tidak ditemukan!');
        window.location='tambah.php';
    </script>
    ";

    exit();
}

$kamar = mysqli_fetch_assoc($cekKamar);

/* =========================
   CEK STATUS KAMAR
========================= */
if($kamar['status_kamar'] == 'Terisi'){

    echo "
    <script>
        alert('Kamar sudah terisi!');
        window.location='tambah.php';
    </script>
    ";

    exit();
}

/* =========================
   INSERT PENGHUNI
========================= */
$insert = mysqli_query($conn,"
    INSERT INTO penghuni (
        id_kamar,
        nama_penghuni,
        jenis_kelamin,
        no_hp,
        alamat,
        tgl_masuk
    )
    VALUES (
        '$id_kamar',
        '$nama_penghuni',
        '$jenis_kelamin',
        '$no_hp',
        '$alamat',
        '$tgl_masuk'
    )
");

/* =========================
   JIKA INSERT BERHASIL
========================= */
if($insert){

    /* UPDATE STATUS KAMAR */
    mysqli_query($conn,"
        UPDATE kamar
        SET status_kamar='Terisi'
        WHERE id_kamar='$id_kamar'
    ");

    echo "
    <script>
        alert('Data penghuni berhasil ditambahkan!');
        window.location='index.php';
    </script>
    ";

}
else{

    echo "
    <script>
        alert('Gagal menambahkan data penghuni!');
        window.location='tambah.php';
    </script>
    ";

}
?>