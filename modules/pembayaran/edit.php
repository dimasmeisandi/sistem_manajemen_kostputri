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
   DATA PEMBAYARAN
========================= */
$q = mysqli_query($conn,"
    SELECT 
        pembayaran.*,
        penghuni.nama_penghuni,
        kamar.nomor_kamar,
        kamar.harga_kamar
    FROM pembayaran
    LEFT JOIN penghuni 
        ON pembayaran.id_penghuni = penghuni.id_penghuni
    LEFT JOIN kamar 
        ON pembayaran.id_kamar = kamar.id_kamar
    WHERE pembayaran.id_pembayaran = '$id'
    LIMIT 1
");

if(!$q || mysqli_num_rows($q) == 0){
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($q);

/* =========================
   DATA PENGHUNI
========================= */
$penghuni = mysqli_query($conn,"
    SELECT 
        penghuni.id_penghuni,
        penghuni.nama_penghuni,
        penghuni.id_kamar,
        kamar.nomor_kamar,
        kamar.harga_kamar
    FROM penghuni
    LEFT JOIN kamar 
        ON penghuni.id_kamar = kamar.id_kamar
    ORDER BY penghuni.nama_penghuni ASC
");

$activePage = 'pembayaran';

include '../../includes/header.php';
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="ml-72 min-h-screen bg-slate-50">

<!-- TOPBAR -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Pembayaran
            </h1>

            <p class="text-sm text-slate-500">
                Perbarui data transaksi pembayaran
            </p>
        </div>

        <a href="index.php"
           class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center gap-2">

            <i class="fa-solid fa-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

<!-- MAIN -->
<main class="pt-28 px-8 pb-10">

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id_pembayaran" value="<?= $data['id_pembayaran'] ?>">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- FORM -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100">

                <h2 class="text-lg font-bold text-slate-800">
                    Form Edit Pembayaran
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Ubah data pembayaran penghuni
                </p>

            </div>

            <!-- BODY -->
            <div class="p-6 space-y-6">

                <!-- PENGHUNI -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Pilih Penghuni
                    </label>

                    <select
                        name="id_penghuni"
                        id="id_penghuni"
                        required
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                        <option value="">
                            -- Pilih Penghuni --
                        </option>

                        <?php while($p=mysqli_fetch_assoc($penghuni)) { ?>

                            <option
                                value="<?= $p['id_penghuni'] ?>"
                                data-kamar="<?= $p['id_kamar'] ?>"
                                data-nomor="<?= $p['nomor_kamar'] ?>"
                                data-harga="<?= $p['harga_kamar'] ?>"
                                <?= ($p['id_penghuni'] == $data['id_penghuni']) ? 'selected' : '' ?>
                            >

                                <?= $p['nama_penghuni'] ?>
                                -
                                Kamar <?= $p['nomor_kamar'] ?>

                            </option>

                        <?php } ?>

                    </select>

                    <input type="hidden" name="id_kamar" id="id_kamar" value="<?= $data['id_kamar'] ?>">

                </div>

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- TANGGAL BAYAR -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Bayar
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                type="date"
                                name="tanggal_bayar"
                                required
                                value="<?= $data['tanggal_bayar'] ?>"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                        </div>

                    </div>

                    <!-- BULAN BAYAR -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Bulan Bayar
                        </label>

                        <select
                            name="bulan_bayar"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                            <?php
                            $bulanList = [
                                'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            ];
                            ?>

                            <option value="">-- Pilih Bulan --</option>

                            <?php foreach($bulanList as $bulan){ ?>

                                <option value="<?= $bulan ?>" <?= ($data['bulan_bayar'] == $bulan) ? 'selected' : '' ?>>
                                    <?= $bulan ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- JUMLAH -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Jumlah Bayar
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-money-bill-wave absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                type="number"
                                name="jumlah_bayar"
                                id="jumlah_bayar"
                                required
                                value="<?= $data['jumlah_bayar'] ?>"
                                placeholder="Masukkan jumlah bayar"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                        </div>

                    </div>

                    <!-- STATUS -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Status Pembayaran
                        </label>

                        <select
                            name="status_pembayaran"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                            <option value="Lunas" <?= ($data['status_pembayaran'] == 'Lunas') ? 'selected' : '' ?>>
                                Lunas
                            </option>

                            <option value="Belum Lunas" <?= ($data['status_pembayaran'] == 'Belum Lunas') ? 'selected' : '' ?>>
                                Belum Lunas
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- SIDE INFO -->
    <div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sticky top-28">

            <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <i class="fa-solid fa-wallet text-2xl"></i>
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Informasi Pembayaran
            </h2>

            <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                Ubah data pembayaran sesuai transaksi terbaru.
            </p>

            <!-- INFO KAMAR -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-100">

                <p class="text-xs text-slate-400 mb-1">
                    Kamar Dipilih
                </p>

                <h3 id="previewKamar" class="font-bold text-slate-800">
                    Kamar <?= $data['nomor_kamar'] ?? '-' ?>
                </h3>

                <p class="text-xs text-slate-400 mt-4 mb-1">
                    Harga Kamar
                </p>

                <h3 id="previewHarga" class="font-bold text-indigo-600">
                    Rp <?= number_format($data['harga_kamar'] ?? 0,0,',','.') ?>
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
                            Pastikan perubahan data pembayaran sudah sesuai sebelum disimpan.
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
const selectPenghuni = document.getElementById('id_penghuni');
const idKamar = document.getElementById('id_kamar');
const jumlahBayar = document.getElementById('jumlah_bayar');
const previewKamar = document.getElementById('previewKamar');
const previewHarga = document.getElementById('previewHarga');

function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}

selectPenghuni.addEventListener('change', function(){

    const selected = this.options[this.selectedIndex];

    const kamar = selected.getAttribute('data-kamar') || '';
    const nomor = selected.getAttribute('data-nomor') || '-';
    const harga = selected.getAttribute('data-harga') || 0;

    idKamar.value = kamar;

    if(jumlahBayar.value == '' || jumlahBayar.value == 0){
        jumlahBayar.value = harga;
    }

    previewKamar.innerText = nomor !== '-' ? 'Kamar ' + nomor : '-';
    previewHarga.innerText = 'Rp ' + formatRupiah(harga);

});
</script>

<?php include '../../includes/footer.php'; ?>