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
   AMBIL STRUKTUR KOLOM
========================= */
$columns = [];

$colQuery = mysqli_query($conn, "SHOW COLUMNS FROM pengeluaran");

if(!$colQuery){
    die("Tabel pengeluaran tidak ditemukan.");
}

while($col = mysqli_fetch_assoc($colQuery)){
    $columns[] = $col['Field'];
}

/* =========================
   HELPER
========================= */
function pickColumn($columns, $candidates){
    foreach($candidates as $c){
        if(in_array($c, $columns)){
            return $c;
        }
    }
    return null;
}

/* =========================
   MAPPING KOLOM
========================= */
$idCol = pickColumn($columns, [
    'id_pengeluaran',
    'id'
]);

if(!$idCol){
    die("
        <div style='font-family:Arial;padding:30px'>
            <h2>Struktur tabel pengeluaran belum sesuai</h2>
            <p>Kolom ID tidak ditemukan.</p>
            <p>Kolom tersedia:</p>
            <pre>".implode(", ", $columns)."</pre>
        </div>
    ");
}

/* =========================
   CEK DATA
========================= */
$cek = mysqli_query($conn,"
    SELECT $idCol
    FROM pengeluaran
    WHERE $idCol = '$id'
    LIMIT 1
");

if(!$cek || mysqli_num_rows($cek) == 0){
    echo "
        <script>
            alert('Data pengeluaran tidak ditemukan!');
            window.location='index.php';
        </script>
    ";
    exit();
}

/* =========================
   HAPUS DATA
========================= */
$hapus = mysqli_query($conn,"
    DELETE FROM pengeluaran
    WHERE $idCol = '$id'
");

if($hapus){

    header("Location: index.php");
    exit();

}else{

    echo "
        <script>
            alert('Gagal menghapus pengeluaran: ".mysqli_error($conn)."');
            window.location='index.php';
        </script>
    ";
    exit();

}
?>