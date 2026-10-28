<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: tambah.php");
    exit();
}

/* =========================
   AMBIL KOLOM TABEL
========================= */
$columns = [];
$columnInfo = [];

$colQuery = mysqli_query($conn, "SHOW COLUMNS FROM pengeluaran");

if(!$colQuery){
    die("Tabel pengeluaran tidak ditemukan: " . mysqli_error($conn));
}

while($col = mysqli_fetch_assoc($colQuery)){
    $columns[] = $col['Field'];
    $columnInfo[$col['Field']] = $col;
}

/* =========================
   HELPER
========================= */
function pickColumn($columns, $candidates){
    foreach($candidates as $candidate){
        if(in_array($candidate, $columns)){
            return $candidate;
        }
    }
    return null;
}

/* =========================
   DETEKSI KOLOM
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
    'tgl_transaksi',
    'date'
]);

$jumlahCol = pickColumn($columns, [
    'jumlah_pengeluaran',
    'nominal_pengeluaran',
    'total_pengeluaran',
    'biaya_pengeluaran',
    'jumlah',
    'nominal',
    'total',
    'biaya',
    'harga',
    'amount'
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

$adminCol = pickColumn($columns, [
    'id_admin',
    'admin_id'
]);

$createdCol = pickColumn($columns, [
    'created_at',
    'created',
    'tanggal_input',
    'tgl_input'
]);

$updatedCol = pickColumn($columns, [
    'updated_at',
    'updated'
]);

/* =========================
   VALIDASI KOLOM WAJIB
========================= */
$missing = [];

if(!$tanggalCol){
    $missing[] = 'kolom tanggal';
}

if(!$jumlahCol){
    $missing[] = 'kolom jumlah / nominal';
}

if(!$keteranganCol){
    $missing[] = 'kolom keterangan / deskripsi';
}

if(count($missing) > 0){
    die("
        <div style='font-family:Arial;padding:30px'>
            <h2>Struktur tabel pengeluaran belum cocok</h2>
            <p>Kolom yang belum ditemukan:</p>
            <ul>
                <li>".implode("</li><li>", $missing)."</li>
            </ul>

            <p>Kolom yang tersedia di tabel <b>pengeluaran</b>:</p>
            <pre style='background:#f1f5f9;padding:16px;border-radius:12px'>".implode(", ", $columns)."</pre>

            <p>Silakan kirim teks kolom di atas kalau masih ingin dikunci 100% manual.</p>
        </div>
    ");
}

/* =========================
   AMBIL INPUT FORM
========================= */
$tanggal = $_POST['tanggal_pengeluaran'] 
    ?? $_POST['tanggal'] 
    ?? $_POST['tgl'] 
    ?? '';

$jumlah = $_POST['jumlah'] 
    ?? $_POST['nominal'] 
    ?? $_POST['jumlah_pengeluaran'] 
    ?? '';

$keterangan = $_POST['keterangan'] 
    ?? $_POST['deskripsi'] 
    ?? $_POST['catatan'] 
    ?? '';

/* =========================
   VALIDASI INPUT
========================= */
if($tanggal == '' || $jumlah == '' || $keterangan == ''){
    echo "
        <script>
            alert('Data pengeluaran belum lengkap!');
            window.location='tambah.php';
        </script>
    ";
    exit();
}

$jumlah = str_replace(['.', ',', 'Rp', 'rp', ' '], '', $jumlah);

if(!is_numeric($jumlah) || $jumlah < 0){
    echo "
        <script>
            alert('Jumlah pengeluaran tidak valid!');
            window.location='tambah.php';
        </script>
    ";
    exit();
}

/* =========================
   ESCAPE INPUT
========================= */
$tanggal = mysqli_real_escape_string($conn, $tanggal);
$jumlah = mysqli_real_escape_string($conn, $jumlah);
$keterangan = mysqli_real_escape_string($conn, $keterangan);

/* =========================
   SUSUN INSERT DINAMIS
========================= */
$insertColumns = [];
$insertValues = [];

/* tanggal */
$insertColumns[] = $tanggalCol;
$insertValues[] = "'$tanggal'";

/* keterangan */
$insertColumns[] = $keteranganCol;
$insertValues[] = "'$keterangan'";

/* jumlah */
$insertColumns[] = $jumlahCol;
$insertValues[] = "'$jumlah'";

/* admin jika ada */
if($adminCol){
    $adminId = mysqli_real_escape_string($conn, $_SESSION['admin_id']);
    $insertColumns[] = $adminCol;
    $insertValues[] = "'$adminId'";
}

/* created_at jika ada */
if($createdCol){
    $insertColumns[] = $createdCol;
    $insertValues[] = "NOW()";
}

/* updated_at jika ada */
if($updatedCol){
    $insertColumns[] = $updatedCol;
    $insertValues[] = "NOW()";
}

/* =========================
   INSERT
========================= */
$sql = "
    INSERT INTO pengeluaran (
        ".implode(", ", $insertColumns)."
    ) VALUES (
        ".implode(", ", $insertValues)."
    )
";

$insert = mysqli_query($conn, $sql);

if($insert){
    header("Location: index.php");
    exit();
}else{
    echo "
        <div style='font-family:Arial;padding:30px'>
            <h2>Gagal menambahkan pengeluaran</h2>
            <p><b>Error:</b></p>
            <pre style='background:#fee2e2;padding:16px;border-radius:12px;color:#991b1b'>".mysqli_error($conn)."</pre>

            <p><b>Query:</b></p>
            <pre style='background:#f1f5f9;padding:16px;border-radius:12px'>".$sql."</pre>

            <p><b>Kolom tabel pengeluaran:</b></p>
            <pre style='background:#f1f5f9;padding:16px;border-radius:12px'>".implode(", ", $columns)."</pre>

            <br>
            <a href='tambah.php' style='background:#4f46e5;color:white;padding:12px 18px;border-radius:12px;text-decoration:none'>
                Kembali
            </a>
        </div>
    ";
    exit();
}
?>