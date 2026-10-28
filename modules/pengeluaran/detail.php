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
   QUERY DETAIL
========================= */
$q = mysqli_query($conn,"
    SELECT *
    FROM pengeluaran
    WHERE $idCol = '$id'
    LIMIT 1
");

if(!$q || mysqli_num_rows($q) == 0){
    header("Location: index.php");
    exit();
}

$r = mysqli_fetch_assoc($q);

$activePage = 'pengeluaran';

include '../../includes/header.php';
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="ml-72 min-h-screen bg-slate-50">

<!-- =========================
     TOPBAR
========================= -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Detail Pengeluaran
            </h1>

            <p class="text-sm text-slate-500">
                Informasi lengkap biaya operasional kos
            </p>
        </div>

        <a href="index.php"
           class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center gap-2">

            <i class="fa-solid fa-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

<!-- =========================
     MAIN
========================= -->
<main class="pt-28 px-8 pb-10">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT CONTENT -->
    <div class="lg:col-span-2 space-y-6">

        <!-- SUMMARY CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100 flex justify-between items-start">

                <div class="flex items-center gap-4">

                    <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-transfer text-2xl"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-slate-800">
                            Pengeluaran #<?= $r[$idCol] ?>
                        </h2>

                        <p class="text-sm text-slate-400 mt-1">
                            Data biaya operasional
                        </p>
                    </div>

                </div>

                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-50 text-rose-600 text-sm font-semibold">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                    Pengeluaran
                </span>

            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- TANGGAL -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Tanggal Pengeluaran
                        </p>

                    </div>

                    <h3 class="font-bold text-slate-800">
                        <?= !empty($r[$tanggalCol]) ? date('d F Y', strtotime($r[$tanggalCol])) : '-' ?>
                    </h3>

                </div>

                <!-- JUMLAH -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Jumlah Pengeluaran
                        </p>

                    </div>

                    <h3 class="font-bold text-rose-600 text-xl">
                        Rp <?= number_format($r[$jumlahCol] ?? 0,0,',','.') ?>
                    </h3>

                </div>

                <!-- KETERANGAN -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 md:col-span-2">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-note-sticky"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Keterangan
                        </p>

                    </div>

                    <h3 class="font-semibold text-slate-800 leading-relaxed">
                        <?= htmlspecialchars($r[$keteranganCol] ?? '-') ?>
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="space-y-6">

        <!-- INFO CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mb-5">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Ringkasan Data
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Informasi singkat pengeluaran
            </p>

            <div class="mt-6 space-y-4">

                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-sm text-slate-400">ID Data</span>
                    <span class="font-bold text-slate-800">
                        #<?= $r[$idCol] ?>
                    </span>
                </div>

                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-sm text-slate-400">Nominal</span>
                    <span class="font-bold text-rose-600">
                        Rp <?= number_format($r[$jumlahCol] ?? 0,0,',','.') ?>
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-400">Jenis</span>
                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold">
                        Pengeluaran
                    </span>
                </div>

            </div>

        </div>

        <!-- ACTION CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <h2 class="text-lg font-bold text-slate-800">
                Aksi Pengeluaran
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Kelola data pengeluaran ini
            </p>

            <div class="mt-6 space-y-3">

                <a href="edit.php?id=<?= $r[$idCol] ?>"
                   class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-2xl font-semibold flex items-center justify-center gap-2">

                    <i class="fa-solid fa-pen"></i>
                    Edit Pengeluaran

                </a>

                <a href="hapus.php?id=<?= $r[$idCol] ?>"
                   onclick="return confirm('Hapus data pengeluaran ini?')"
                   class="w-full bg-rose-50 hover:bg-rose-100 transition text-rose-600 py-3 rounded-2xl font-semibold flex items-center justify-center gap-2">

                    <i class="fa-solid fa-trash"></i>
                    Hapus Pengeluaran

                </a>

            </div>

        </div>

    </div>

</div>

</main>

</div>

<?php include '../../includes/footer.php'; ?>