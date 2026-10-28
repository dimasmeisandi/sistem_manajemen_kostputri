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

$tanggalCol = pickColumn($columns, [
    'tanggal_pengeluaran',
    'tgl_pengeluaran',
    'tanggal',
    'tgl',
    'tanggal_transaksi',
    'tgl_transaksi'
]);

$jumlahCol = pickColumn($columns, [
    'jumlah_pengeluaran',
    'nominal_pengeluaran',
    'total_pengeluaran',
    'biaya_pengeluaran',
    'jumlah',
    'nominal',
    'total',
    'biaya'
]);

$keteranganCol = pickColumn($columns, [
    'keterangan',
    'deskripsi',
    'catatan',
    'detail',
    'nama_pengeluaran',
    'keperluan',
    'uraian'
]);

$updatedCol = pickColumn($columns, [
    'updated_at',
    'updated'
]);

/* =========================
   VALIDASI KOLOM
========================= */
$missing = [];

if(!$idCol) $missing[] = 'id_pengeluaran / id';
if(!$tanggalCol) $missing[] = 'tanggal';
if(!$jumlahCol) $missing[] = 'jumlah / nominal';
if(!$keteranganCol) $missing[] = 'keterangan / deskripsi';

if(count($missing) > 0){
    die("
        <div style='font-family:Arial;padding:30px'>
            <h2>Struktur tabel pengeluaran belum sesuai</h2>
            <p>Kolom yang belum ditemukan:</p>
            <ul>
                <li>".implode("</li><li>", $missing)."</li>
            </ul>
            <p>Kolom tersedia:</p>
            <pre>".implode(", ", $columns)."</pre>
        </div>
    ");
}

/* =========================
   AMBIL DATA POST
========================= */
$id_pengeluaran = $_POST['id_pengeluaran'] ?? '';
$tanggal = $_POST['tanggal_pengeluaran'] ?? $_POST['tanggal'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';

/* =========================
   VALIDASI INPUT
========================= */
if(
    $id_pengeluaran == '' ||
    $tanggal == '' ||
    $keterangan == '' ||
    $jumlah == ''
){
    echo "
        <script>
            alert('Data pengeluaran belum lengkap!');
            window.location='index.php';
        </script>
    ";
    exit();
}

$jumlah = str_replace(['.', ',', 'Rp', 'rp', ' '], '', $jumlah);

if(!is_numeric($jumlah) || $jumlah < 0){
    echo "
        <script>
            alert('Jumlah pengeluaran tidak valid!');
            window.location='edit.php?id=$id_pengeluaran';
        </script>
    ";
    exit();
}

/* =========================
   AMANKAN INPUT
========================= */
$id_pengeluaran = mysqli_real_escape_string($conn, $id_pengeluaran);
$tanggal = mysqli_real_escape_string($conn, $tanggal);
$keterangan = mysqli_real_escape_string($conn, $keterangan);
$jumlah = mysqli_real_escape_string($conn, $jumlah);

/* =========================
   CEK DATA
========================= */
$cek = mysqli_query($conn,"
    SELECT $idCol
    FROM pengeluaran
    WHERE $idCol = '$id_pengeluaran'
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
   UPDATE DATA
========================= */
$setUpdated = "";

if($updatedCol){
    $setUpdated = ", $updatedCol = NOW()";
}

$update = mysqli_query($conn,"
    UPDATE pengeluaran SET
        $tanggalCol = '$tanggal',
        $keteranganCol = '$keterangan',
        $jumlahCol = '$jumlah'
        $setUpdated
    WHERE $idCol = '$id_pengeluaran'
");

if($update){

    header("Location: detail.php?id=$id_pengeluaran");
    exit();

}else{

    echo "
        <script>
            alert('Gagal memperbarui pengeluaran: ".mysqli_error($conn)."');
            window.location='edit.php?id=$id_pengeluaran';
        </script>
    ";
    exit();

}
?>