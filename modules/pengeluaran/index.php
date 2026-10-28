<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
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
    'tgl'
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
    'detail'
]);

/* =========================
   VALIDASI KOLOM WAJIB
========================= */
$missing = [];

if(!$idCol) $missing[] = 'id_pengeluaran / id';
if(!$tanggalCol) $missing[] = 'tanggal_pengeluaran / tanggal';
if(!$jumlahCol) $missing[] = 'jumlah_pengeluaran / jumlah / nominal';
if(!$keteranganCol) $missing[] = 'keterangan / deskripsi / catatan';

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
   SEARCH
========================= */
$search = $_GET['search'] ?? '';
$searchEsc = mysqli_real_escape_string($conn, $search);

$where = "";

if($search != ''){
    $where = "WHERE 
        $keteranganCol LIKE '%$searchEsc%'
        OR $tanggalCol LIKE '%$searchEsc%'
        OR $jumlahCol LIKE '%$searchEsc%'
    ";
}

/* =========================
   QUERY DATA
========================= */
$q = mysqli_query($conn,"
    SELECT *
    FROM pengeluaran
    $where
    ORDER BY $idCol DESC
");

if(!$q){
    die("Query gagal: " . mysqli_error($conn));
}

/* =========================
   STATISTIK
========================= */
$totalTransaksi = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM pengeluaran
"))['t'];

$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM($jumlahCol) as total 
    FROM pengeluaran
"))['total'];

$bulanIni = date('Y-m');

$pengeluaranBulanIni = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM($jumlahCol) as total
    FROM pengeluaran
    WHERE DATE_FORMAT($tanggalCol, '%Y-%m') = '$bulanIni'
"))['total'];

$transaksiBulanIni = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t
    FROM pengeluaran
    WHERE DATE_FORMAT($tanggalCol, '%Y-%m') = '$bulanIni'
"))['t'];

$activePage = 'pengeluaran';

include '../../includes/header.php';
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="ml-72 min-h-screen bg-slate-50">

<!-- TOPBAR -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Data Pengeluaran
            </h1>

            <p class="text-sm text-slate-500">
                Kelola biaya operasional kos
            </p>
        </div>

        <div class="flex items-center gap-4">

            <div class="text-right">
                <h3 class="font-semibold text-slate-800">
                    <?= $_SESSION['admin_nama'] ?>
                </h3>

                <p class="text-xs text-slate-400">
                    Administrator
                </p>
            </div>

            <div class="w-11 h-11 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold">
                <?= strtoupper(substr($_SESSION['admin_nama'],0,2)); ?>
            </div>

        </div>

    </div>

</div>

<!-- MAIN -->
<main class="pt-28 px-8 pb-10">

<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

    <!-- TOTAL TRANSAKSI -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Transaksi
                </p>

                <h2 class="text-4xl font-bold text-slate-800 mt-2">
                    <?= $totalTransaksi ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-receipt text-xl"></i>
            </div>

        </div>

    </div>

    <!-- TOTAL PENGELUARAN -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Pengeluaran
                </p>

                <h2 class="text-2xl font-bold text-rose-600 mt-2">
                    Rp <?= number_format($totalPengeluaran ?? 0,0,',','.') ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i>
            </div>

        </div>

    </div>

    <!-- BULAN INI -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Pengeluaran Bulan Ini
                </p>

                <h2 class="text-2xl font-bold text-amber-600 mt-2">
                    Rp <?= number_format($pengeluaranBulanIni ?? 0,0,',','.') ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-calendar-days text-xl"></i>
            </div>

        </div>

    </div>

    <!-- TRANSAKSI BULAN INI -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Transaksi Bulan Ini
                </p>

                <h2 class="text-4xl font-bold text-indigo-600 mt-2">
                    <?= $transaksiBulanIni ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>

        </div>

    </div>

</div>

<!-- FILTER -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">

    <div class="flex flex-col md:flex-row gap-4 justify-between">

        <form method="GET" class="relative w-full md:w-[380px]">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

            <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Cari keterangan, tanggal, atau jumlah..."
                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
            >

        </form>

        <a href="tambah.php"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2 transition">

            <i class="fa-solid fa-plus"></i>
            Tambah Pengeluaran

        </a>

    </div>

</div>

<!-- TABLE -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-100">

        <h2 class="font-bold text-slate-800 text-lg">
            Riwayat Pengeluaran
        </h2>

        <p class="text-sm text-slate-400 mt-1">
            Total <?= mysqli_num_rows($q) ?> data pengeluaran
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr class="text-slate-400 text-sm">

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-calendar mr-2"></i>
                        Tanggal
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-note-sticky mr-2"></i>
                        Keterangan
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-money-bill-wave mr-2"></i>
                        Jumlah
                    </th>

                    <th class="px-6 py-4 text-center">
                        <i class="fa-solid fa-gear mr-2"></i>
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php if(mysqli_num_rows($q) > 0){ ?>

                <?php while($r=mysqli_fetch_assoc($q)) { ?>

                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                        <!-- TANGGAL -->
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>

                                <div>
                                    <h3 class="font-semibold text-slate-800">
                                        <?= !empty($r[$tanggalCol]) ? date('d F Y', strtotime($r[$tanggalCol])) : '-' ?>
                                    </h3>

                                    <p class="text-sm text-slate-400">
                                        Biaya Operasional
                                    </p>
                                </div>

                            </div>

                        </td>

                        <!-- KETERANGAN -->
                        <td class="px-6 py-5">

                            <div>
                                <h3 class="font-semibold text-slate-800">
                                    <?= htmlspecialchars($r[$keteranganCol] ?? '-') ?>
                                </h3>

                                <p class="text-sm text-slate-400 mt-1">
                                    Catatan pengeluaran
                                </p>
                            </div>

                        </td>

                        <!-- JUMLAH -->
                        <td class="px-6 py-5">

                            <span class="px-4 py-2 rounded-full bg-rose-50 text-rose-600 text-sm font-bold">
                                Rp <?= number_format($r[$jumlahCol] ?? 0,0,',','.') ?>
                            </span>

                        </td>

                        <!-- AKSI -->
                        <td class="px-6 py-5">

                            <div class="flex items-center justify-center gap-3">

                                <a href="detail.php?id=<?= $r[$idCol] ?>"
                                   class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <a href="edit.php?id=<?= $r[$idCol] ?>"
                                   class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <a href="hapus.php?id=<?= $r[$idCol] ?>"
                                   onclick="return confirm('Hapus data pengeluaran ini?')"
                                   class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">

                                    <i class="fa-solid fa-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">

                        <div class="flex flex-col items-center">

                            <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-folder-open text-2xl"></i>
                            </div>

                            <h3 class="font-bold text-slate-700">
                                Data tidak ditemukan
                            </h3>

                            <p class="text-sm text-slate-400 mt-1">
                                Belum ada data pengeluaran.
                            </p>

                        </div>

                    </td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</main>

</div>

<?php include '../../includes/footer.php'; ?>