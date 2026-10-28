<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

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
                Tambah Pengeluaran
            </h1>

            <p class="text-sm text-slate-500">
                Tambahkan data biaya operasional kos
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

<form action="proses_tambah.php" method="POST">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- =========================
         FORM
    ========================== -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100">

                <h2 class="text-lg font-bold text-slate-800">
                    Form Pengeluaran
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Isi data pengeluaran dengan benar
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
                            value="<?= date('Y-m-d') ?>"
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
                        ></textarea>

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
                Catat semua biaya operasional kos agar laporan keuangan lebih rapi dan mudah dipantau.
            </p>

            <!-- PREVIEW -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-100">

                <p class="text-xs text-slate-400 mb-1">
                    Preview Nominal
                </p>

                <h3 id="previewJumlah" class="font-bold text-rose-600 text-xl">
                    Rp 0
                </h3>

                <p class="text-xs text-slate-400 mt-4 mb-1">
                    Jenis Data
                </p>

                <h3 class="font-bold text-slate-800">
                    Biaya Operasional
                </h3>

            </div>

            <!-- ALERT -->
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-100">

                <div class="flex items-start gap-3">

                    <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>

                    <div>
                        <h3 class="font-semibold text-amber-700 text-sm">
                            Catatan
                        </h3>

                        <p class="text-xs text-amber-600 mt-1 leading-relaxed">
                            Pastikan nominal dan keterangan pengeluaran sudah sesuai sebelum disimpan.
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
                Simpan Pengeluaran

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