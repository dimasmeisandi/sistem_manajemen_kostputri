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
   QUERY DATA
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

$data = mysqli_fetch_assoc($q);

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
                Edit Pengeluaran
            </h1>

            <p class="text-sm text-slate-500">
                Perbarui data biaya operasional kos
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

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id_pengeluaran" value="<?= $data[$idCol] ?>">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- =========================
         FORM
    ========================== -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100">

                <h2 class="text-lg font-bold text-slate-800">
                    Form Edit Pengeluaran
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Ubah data pengeluaran dengan benar
                </p>

            </div>

            <!-- BODY -->
            <div class="p-6 space-y-6">

                <!-- TANGGAL -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal Pengeluaran
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="date"
                            name="tanggal_pengeluaran"
                            required
                            value="<?= htmlspecialchars($data[$tanggalCol] ?? '') ?>"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                        >

                    </div>

                </div>

                <!-- JUMLAH -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jumlah Pengeluaran
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-money-bill-wave absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="number"
                            name="jumlah"
                            id="jumlah"
                            required
                            min="0"
                            value="<?= htmlspecialchars($data[$jumlahCol] ?? 0) ?>"
                            placeholder="Contoh: 250000"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                        >

                    </div>

                    <p class="text-xs text-slate-400 mt-2">
                        Masukkan nominal tanpa titik atau koma.
                    </p>

                </div>

                <!-- KETERANGAN -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Keterangan
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-note-sticky absolute left-4 top-4 text-slate-400"></i>

                        <textarea
                            name="keterangan"
                            required
                            rows="5"
                            placeholder="Contoh: Bayar listrik, air, perbaikan kamar, kebersihan..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm resize-none"
                        ><?= htmlspecialchars($data[$keteranganCol] ?? '') ?></textarea>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
         SIDE INFO
    ========================== -->
    <div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sticky top-28">

            <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mb-5">
                <i class="fa-solid fa-money-bill-transfer text-2xl"></i>
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Informasi Pengeluaran
            </h2>

            <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                Perbarui data pengeluaran agar laporan keuangan tetap rapi dan akurat.
            </p>

            <!-- PREVIEW -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-100">

                <p class="text-xs text-slate-400 mb-1">
                    Preview Nominal
                </p>

                <h3 id="previewJumlah" class="font-bold text-rose-600 text-xl">
                    Rp <?= number_format($data[$jumlahCol] ?? 0,0,',','.') ?>
                </h3>

                <p class="text-xs text-slate-400 mt-4 mb-1">
                    ID Pengeluaran
                </p>

                <h3 class="font-bold text-slate-800">
                    #<?= $data[$idCol] ?>
                </h3>

            </div>

            <!-- ALERT -->
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-100">

                <div class="flex items-start gap-3">

                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1"></i>

                    <div>
                        <h3 class="font-semibold text-amber-700 text-sm">
                            Perhatian
                        </h3>

                        <p class="text-xs text-amber-600 mt-1 leading-relaxed">
                            Pastikan perubahan nominal dan keterangan sudah sesuai sebelum disimpan.
                        </p>
                    </div>

                </div>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-2xl font-semibold flex items-center justify-center gap-2"
            >

                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan

            </button>

        </div>

    </div>

</div>

</form>

</main>

</div>

<script>
const jumlahInput = document.getElementById('jumlah');
const previewJumlah = document.getElementById('previewJumlah');

function formatRupiah(angka){
    angka = angka || 0;
    return new Intl.NumberFormat('id-ID').format(angka);
}

jumlahInput.addEventListener('input', function(){
    previewJumlah.innerText = 'Rp ' + formatRupiah(this.value);
});
</script>

<?php include '../../includes/footer.php'; ?>