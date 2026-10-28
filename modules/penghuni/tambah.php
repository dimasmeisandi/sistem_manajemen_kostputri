<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   AMBIL KAMAR KOSONG
========================= */
$kamar = mysqli_query($conn,"
    SELECT *
    FROM kamar
    WHERE status_kamar='Kosong'
    ORDER BY nomor_kamar ASC
");

$activePage = 'penghuni';

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
                Tambah Penghuni
            </h1>

            <p class="text-sm text-slate-500">
                Tambahkan data penghuni kos baru
            </p>

        </div>

        <!-- BUTTON -->
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
    ========================= -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100">

                <h2 class="text-lg font-bold text-slate-800">
                    Form Data Penghuni
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Isi seluruh data penghuni dengan benar
                </p>

            </div>

            <!-- BODY -->
            <div class="p-6 space-y-6">

                <!-- NAMA -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Penghuni
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="text"
                            name="nama_penghuni"
                            required
                            placeholder="Masukkan nama penghuni"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                    </div>

                </div>

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- GENDER -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                            <option value="">
                                -- Pilih Gender --
                            </option>

                            <option value="Laki-laki">
                                Laki-laki
                            </option>

                            <option value="Perempuan">
                                Perempuan
                            </option>

                        </select>

                    </div>

                    <!-- NO HP -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nomor HP
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                type="text"
                                name="no_hp"
                                required
                                placeholder="08xxxxxxxxxx"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                        </div>

                    </div>

                </div>

                <!-- ALAMAT -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        required
                        placeholder="Masukkan alamat lengkap penghuni"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    ></textarea>

                </div>

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- TANGGAL MASUK -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Masuk
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                type="date"
                                name="tgl_masuk"
                                required
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                        </div>

                    </div>

                    <!-- KAMAR -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Pilih Kamar
                        </label>

                        <select
                            name="id_kamar"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                            <option value="">
                                -- Pilih Kamar --
                            </option>

                            <?php while($k=mysqli_fetch_assoc($kamar)) { ?>

                                <option value="<?= $k['id_kamar'] ?>">

                                    <?= $k['nomor_kamar'] ?>
                                    -
                                    Rp <?= number_format($k['harga_kamar'],0,',','.') ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
         SIDE INFO
    ========================= -->
    <div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sticky top-28">

            <!-- ICON -->
            <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">

                <i class="fa-solid fa-users text-2xl"></i>

            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Informasi
            </h2>

            <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                Pastikan kamar yang dipilih masih kosong sebelum menyimpan data penghuni baru.
            </p>

            <!-- ALERT -->
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-100">

                <div class="flex items-start gap-3">

                    <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>

                    <div>

                        <h3 class="font-semibold text-amber-700 text-sm">
                            Perhatian
                        </h3>

                        <p class="text-xs text-amber-600 mt-1 leading-relaxed">
                            Setelah penghuni ditambahkan, status kamar otomatis berubah menjadi <b>Terisi</b>.
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
                Simpan Penghuni

            </button>

        </div>

    </div>

</div>

</form>

</main>

</div>

<?php include '../../includes/footer.php'; ?>