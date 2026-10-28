<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   FILTER
========================= */
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$tanggal_mulai = mysqli_real_escape_string($conn, $tanggal_mulai);
$tanggal_selesai = mysqli_real_escape_string($conn, $tanggal_selesai);

/* =========================
   DETEKSI KOLOM PENGELUARAN
========================= */
$columns = [];

$colQuery = mysqli_query($conn, "SHOW COLUMNS FROM pengeluaran");

if (!$colQuery) {
    die("Tabel pengeluaran tidak ditemukan.");
}

while ($col = mysqli_fetch_assoc($colQuery)) {
    $columns[] = $col['Field'];
}

function pickColumn($columns, $candidates)
{
    foreach ($candidates as $c) {
        if (in_array($c, $columns)) {
            return $c;
        }
    }
    return null;
}

$idPengeluaranCol = pickColumn($columns, [
    'id_pengeluaran',
    'id'
]);

$tanggalPengeluaranCol = pickColumn($columns, [
    'tanggal_pengeluaran',
    'tgl_pengeluaran',
    'tanggal',
    'tgl',
    'tanggal_transaksi',
    'tgl_transaksi'
]);

$jumlahPengeluaranCol = pickColumn($columns, [
    'jumlah_pengeluaran',
    'nominal_pengeluaran',
    'total_pengeluaran',
    'biaya_pengeluaran',
    'jumlah',
    'nominal',
    'total',
    'biaya'
]);

$keteranganPengeluaranCol = pickColumn($columns, [
    'keterangan',
    'deskripsi',
    'catatan',
    'detail',
    'nama_pengeluaran',
    'keperluan',
    'uraian'
]);

if (!$tanggalPengeluaranCol || !$jumlahPengeluaranCol || !$keteranganPengeluaranCol) {
    die("
        <div style='font-family:Arial;padding:30px'>
            <h2>Struktur tabel pengeluaran belum sesuai</h2>
            <p>Kolom tersedia:</p>
            <pre>" . implode(", ", $columns) . "</pre>
        </div>
    ");
}

/* =========================
   FILTER QUERY
========================= */
$filterPembayaran = "1=1";
$filterPengeluaran = "1=1";

if ($tanggal_mulai != '' && $tanggal_selesai != '') {

    $filterPembayaran = "
        tanggal_bayar BETWEEN '$tanggal_mulai'
        AND '$tanggal_selesai'
    ";

    $filterPengeluaran = "
        $tanggalPengeluaranCol BETWEEN '$tanggal_mulai'
        AND '$tanggal_selesai'
    ";
}

/* =========================
   LABEL PERIODE
========================= */
if ($tanggal_mulai != '' && $tanggal_selesai != '') {

    $labelPeriode =
        date('d F Y', strtotime($tanggal_mulai))
        . ' - ' .
        date('d F Y', strtotime($tanggal_selesai));
} else {

    $labelPeriode = 'Semua Periode';
}

/* =========================
   TOTAL PEMASUKAN
========================= */
$totalPemasukan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) as total
    FROM pembayaran
    WHERE $filterPembayaran
    AND status_pembayaran = 'Lunas'
"))['total'] ?? 0;

/* =========================
   TOTAL PENGELUARAN
========================= */
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM($jumlahPengeluaranCol) as total
    FROM pengeluaran
    WHERE $filterPengeluaran
"))['total'] ?? 0;

/* =========================
   TOTAL TRANSAKSI
========================= */
$totalTransaksiPembayaran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM pembayaran
    WHERE $filterPembayaran
"))['total'] ?? 0;

$totalTransaksiPengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM pengeluaran
    WHERE $filterPengeluaran
"))['total'] ?? 0;

$totalTransaksi = $totalTransaksiPembayaran + $totalTransaksiPengeluaran;
$saldoBersih = ($totalPemasukan ?? 0) - ($totalPengeluaran ?? 0);

/* =========================
   DATA PEMBAYARAN
========================= */
$qPembayaran = mysqli_query($conn, "
    SELECT 
        pembayaran.id_pembayaran as id,
        pembayaran.tanggal_bayar as tanggal,
        pembayaran.jumlah_bayar as nominal,
        pembayaran.status_pembayaran as status,
        penghuni.nama_penghuni,
        kamar.nomor_kamar
    FROM pembayaran
    LEFT JOIN penghuni 
        ON pembayaran.id_penghuni = penghuni.id_penghuni
    LEFT JOIN kamar 
        ON pembayaran.id_kamar = kamar.id_kamar
    WHERE $filterPembayaran
");

/* =========================
   DATA PENGELUARAN
========================= */
$qPengeluaran = mysqli_query($conn, "
    SELECT 
        $idPengeluaranCol as id,
        $tanggalPengeluaranCol as tanggal,
        $jumlahPengeluaranCol as nominal,
        $keteranganPengeluaranCol as keterangan
    FROM pengeluaran
    WHERE $filterPengeluaran
");

/* =========================
   GABUNG TRANSAKSI
========================= */
$transaksi = [];

while ($p = mysqli_fetch_assoc($qPembayaran)) {
    $transaksi[] = [
        'tanggal' => $p['tanggal'],
        'jenis' => 'Pemasukan',
        'keterangan' => 'Pembayaran kos ' . ($p['nama_penghuni'] ?? '-') . ' - Kamar ' . ($p['nomor_kamar'] ?? '-'),
        'nominal' => $p['nominal'],
        'status' => $p['status']
    ];
}

while ($k = mysqli_fetch_assoc($qPengeluaran)) {
    $transaksi[] = [
        'tanggal' => $k['tanggal'],
        'jenis' => 'Pengeluaran',
        'keterangan' => $k['keterangan'],
        'nominal' => $k['nominal'],
        'status' => '-'
    ];
}

/* SORT DESC TANGGAL */
usort($transaksi, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

$activePage = 'laporan';

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
                    Laporan Keuangan
                </h1>

                <p class="text-sm text-slate-500">
                    Ringkasan pemasukan dan pengeluaran kos
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
                    <?= strtoupper(substr($_SESSION['admin_nama'], 0, 2)); ?>
                </div>

            </div>

        </div>

    </div>

    <!-- =========================
     MAIN
========================= -->
    <main class="pt-28 px-8 pb-10">

        <!-- =========================
     STATS
========================= -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

            <!-- PEMASUKAN -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-sm text-slate-400">
                            Total Pemasukan
                        </p>

                        <h2 class="text-2xl font-bold text-emerald-600 mt-2">
                            Rp <?= number_format($totalPemasukan ?? 0, 0, ',', '.') ?>
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-trend-up text-xl"></i>
                    </div>

                </div>

            </div>

            <!-- PENGELUARAN -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-sm text-slate-400">
                            Total Pengeluaran
                        </p>

                        <h2 class="text-2xl font-bold text-rose-600 mt-2">
                            Rp <?= number_format($totalPengeluaran ?? 0, 0, ',', '.') ?>
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-trend-down text-xl"></i>
                    </div>

                </div>

            </div>

            <!-- SALDO -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-sm text-slate-400">
                            Saldo Bersih
                        </p>

                        <h2 class="text-2xl font-bold <?= $saldoBersih >= 0 ? 'text-indigo-600' : 'text-rose-600' ?> mt-2">
                            Rp <?= number_format($saldoBersih ?? 0, 0, ',', '.') ?>
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>

                </div>

            </div>

            <!-- TRANSAKSI -->
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

                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-xl"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- =========================
     FILTER
========================= -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">

            <div class="flex flex-col xl:flex-row justify-between gap-4">

                <form method="GET" class="flex flex-wrap gap-3 items-center">

                    <!-- TANGGAL MULAI -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="<?= $tanggal_mulai ?>"
                            class="px-4 py-3 rounded-2xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            value="<?= $tanggal_selesai ?>"
                            class="px-4 py-3 rounded-2xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- FILTER -->
                    <div class="pt-5">
                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-3 rounded-2xl text-sm font-semibold flex items-center gap-2">

                            <i class="fa-solid fa-filter"></i>
                            Filter

                        </button>
                    </div>

                    <!-- RESET -->
                    <div class="pt-5">
                        <a href="index.php"
                            class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center gap-2">

                            <i class="fa-solid fa-rotate-left"></i>
                            Reset

                        </a>
                    </div>

                </form>

                <!-- CETAK -->
                <div class="flex items-end">

                    <button
                        type="button"
                        onclick="validasiCetak()"
                        class="bg-emerald-600 hover:bg-emerald-700 transition text-white px-5 py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2">

                        <i class="fa-solid fa-print"></i>
                        Cetak Laporan

                    </button>

                </div>

            </div>

        </div>

        <!-- =========================
     TABLE
========================= -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="px-6 py-5 border-b border-slate-100">

                <h2 class="font-bold text-slate-800 text-lg">
                    Riwayat Transaksi
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Periode <?= $labelPeriode ?>
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
                                <i class="fa-solid fa-layer-group mr-2"></i>
                                Jenis
                            </th>

                            <th class="px-6 py-4 text-left">
                                <i class="fa-solid fa-note-sticky mr-2"></i>
                                Keterangan
                            </th>

                            <th class="px-6 py-4 text-left">
                                <i class="fa-solid fa-money-bill-wave mr-2"></i>
                                Nominal
                            </th>

                            <th class="px-6 py-4 text-left">
                                <i class="fa-solid fa-circle-info mr-2"></i>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (count($transaksi) > 0) { ?>

                            <?php foreach ($transaksi as $t) { ?>

                                <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                                    <!-- TANGGAL -->
                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-11 h-11 rounded-2xl <?= $t['jenis'] == 'Pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' ?> flex items-center justify-center">
                                                <i class="fa-solid <?= $t['jenis'] == 'Pemasukan' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' ?>"></i>
                                            </div>

                                            <div>
                                                <h3 class="font-semibold text-slate-800">
                                                    <?= !empty($t['tanggal']) ? date('d F Y', strtotime($t['tanggal'])) : '-' ?>
                                                </h3>

                                                <p class="text-sm text-slate-400">
                                                    Transaksi <?= $t['jenis'] ?>
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                    <!-- JENIS -->
                                    <td class="px-6 py-5">

                                        <?php if ($t['jenis'] == 'Pemasukan') { ?>

                                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold">
                                                <i class="fa-solid fa-arrow-trend-up"></i>
                                                Pemasukan
                                            </span>

                                        <?php } else { ?>

                                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-50 text-rose-600 text-sm font-semibold">
                                                <i class="fa-solid fa-arrow-trend-down"></i>
                                                Pengeluaran
                                            </span>

                                        <?php } ?>

                                    </td>

                                    <!-- KETERANGAN -->
                                    <td class="px-6 py-5">

                                        <h3 class="font-semibold text-slate-800">
                                            <?= htmlspecialchars($t['keterangan']) ?>
                                        </h3>

                                    </td>

                                    <!-- NOMINAL -->
                                    <td class="px-6 py-5">

                                        <span class="font-bold <?= $t['jenis'] == 'Pemasukan' ? 'text-emerald-600' : 'text-rose-600' ?>">
                                            <?= $t['jenis'] == 'Pemasukan' ? '+' : '-' ?>
                                            Rp <?= number_format($t['nominal'] ?? 0, 0, ',', '.') ?>
                                        </span>

                                    </td>

                                    <!-- STATUS -->
                                    <td class="px-6 py-5">

                                        <?php if ($t['jenis'] == 'Pemasukan') { ?>

                                            <?php if ($t['status'] == 'Lunas') { ?>

                                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    Lunas
                                                </span>

                                            <?php } else { ?>

                                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-semibold">
                                                    <i class="fa-solid fa-clock"></i>
                                                    <?= $t['status'] ?>
                                                </span>

                                            <?php } ?>

                                        <?php } else { ?>

                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                                <i class="fa-solid fa-minus"></i>
                                                -
                                            </span>

                                        <?php } ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-folder-open text-2xl"></i>
                                        </div>

                                        <h3 class="font-bold text-slate-700">
                                            Data laporan kosong
                                        </h3>

                                        <p class="text-sm text-slate-400 mt-1">
                                            Tidak ada transaksi pada periode ini.
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
<script>
function validasiCetak(){
    const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]').value;
    const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]').value;

    if(tanggalMulai === '' || tanggalSelesai === ''){
        alert('Tanggal mulai dan tanggal selesai wajib diisi sebelum mencetak laporan.');
        return false;
    }

    if(tanggalSelesai < tanggalMulai){
        alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.');
        return false;
    }

    window.open(
        'cetak.php?tanggal_mulai=' + encodeURIComponent(tanggalMulai) +
        '&tanggal_selesai=' + encodeURIComponent(tanggalSelesai),
        '_blank'
    );
}
</script>


<?php include '../../includes/footer.php'; ?>