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
    header("Location: index.php");
    exit();
}

/* =========================
   AMBIL DATA
========================= */
$id_penghuni   = mysqli_real_escape_string($conn, $_POST['id_penghuni']);
$old_kamar     = mysqli_real_escape_string($conn, $_POST['old_kamar']);

$nama_penghuni = mysqli_real_escape_string($conn, $_POST['nama_penghuni']);
$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
$no_hp         = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
$tgl_masuk     = mysqli_real_escape_string($conn, $_POST['tgl_masuk']);
$id_kamar      = mysqli_real_escape_string($conn, $_POST['id_kamar']);

/* =========================
   VALIDASI PENGHUNI
========================= */
$cekPenghuni = mysqli_query($conn,"
    SELECT *
    FROM penghuni
    WHERE id_penghuni='$id_penghuni'
");

if(mysqli_num_rows($cekPenghuni) == 0){

    echo "
    <script>
        alert('Data penghuni tidak ditemukan!');
        window.location='index.php';
    </script>
    ";

    exit();
}

/* =========================
   CEK KAMAR BARU
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
        window.location='index.php';
    </script>
    ";

    exit();
}

$kamar = mysqli_fetch_assoc($cekKamar);

/* =========================
   CEK STATUS KAMAR
========================= */
if($id_kamar != $old_kamar){

    if($kamar['status_kamar'] == 'Terisi'){

        echo "
        <script>
            alert('Kamar sudah terisi!');
            window.history.back();
        </script>
        ";

        exit();
    }

}

/* =========================
   UPDATE PENGHUNI
========================= */
$update = mysqli_query($conn,"
    UPDATE penghuni
    SET
        id_kamar       = '$id_kamar',
        nama_penghuni  = '$nama_penghuni',
        jenis_kelamin  = '$jenis_kelamin',
        no_hp          = '$no_hp',
        alamat         = '$alamat',
        tgl_masuk      = '$tgl_masuk'
    WHERE id_penghuni  = '$id_penghuni'
");

/* =========================
   JIKA BERHASIL
========================= */
if($update){

    /* =========================
       JIKA PINDAH KAMAR
    ========================= */
    if($id_kamar != $old_kamar){

        /* KAMAR LAMA -> KOSONG */
        mysqli_query($conn,"
            UPDATE kamar
            SET status_kamar='Kosong'
            WHERE id_kamar='$old_kamar'
        ");

        /* KAMAR BARU -> TERISI */
        mysqli_query($conn,"
            UPDATE kamar
            SET status_kamar='Terisi'
            WHERE id_kamar='$id_kamar'
        ");

    }

    echo "
    <script>
        alert('Data penghuni berhasil diperbarui!');
        window.location='index.php';
    </script>
    ";

}
else{

    echo "
    <script>
        alert('Gagal memperbarui data penghuni!');
        window.history.back();
    </script>
    ";

}
?>