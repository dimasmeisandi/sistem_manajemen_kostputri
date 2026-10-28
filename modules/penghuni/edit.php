<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   VALIDASI ID
========================= */
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

/* =========================
   DATA PENGHUNI
========================= */
$q = mysqli_query($conn,"
    SELECT *
    FROM penghuni
    WHERE id_penghuni='$id'
");

if(mysqli_num_rows($q) == 0){
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($q);

/* =========================
   AMBIL KAMAR
========================= */
$kamar = mysqli_query($conn,"
    SELECT *
    FROM kamar
    WHERE status_kamar='Kosong'
    OR id_kamar='".$data['id_kamar']."'
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
                Edit Penghuni
            </h1>

            <p class="text-sm text-slate-500">
                Ubah data penghuni kos
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

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id_penghuni" value="<?= $data['id_penghuni'] ?>">
<input type="hidden" name="old_kamar" value="<?= $data['id_kamar'] ?>">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- =========================
         FORM
    ========================= -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100">

                <h2 class="text-lg font-bold text-slate-800">
                    Form Edit Penghuni
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Perbarui data penghuni kos
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
                            value="<?= $data['nama_penghuni'] ?>"
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

                            <option value="Laki-laki"
                                <?= $data['jenis_kelamin']=='Laki-laki' ? 'selected' : '' ?>>
                                Laki-laki
                            </option>

                            <option value="Perempuan"
                                <?= $data['jenis_kelamin']=='Perempuan' ? 'selected' : '' ?>>
                                Perempuan
                            </option>

                        </select>

                    </div>

                    <!-- HP -->
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
                                value="<?= $data['no_hp'] ?>"
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
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    ><?= $data['alamat'] ?></textarea>

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
                                value="<?= $data['tgl_masuk'] ?>"
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

                            <?php while($k=mysqli_fetch_assoc($kamar)) { ?>

                                <option
                                    value="<?= $k['id_kamar'] ?>"
                                    <?= $data['id_kamar']==$k['id_kamar'] ? 'selected' : '' ?>
                                >

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

                <i class="fa-solid fa-user-pen text-2xl"></i>

            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Informasi Edit
            </h2>

            <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                Jika kamar diubah, maka status kamar lama otomatis menjadi kosong dan kamar baru menjadi terisi.
            </p>

            <!-- ALERT -->
            <div class="mt-6 p-4 rounded-2xl bg-blue-50 border border-blue-100">

                <div class="flex items-start gap-3">

                    <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>

                    <div>

                        <h3 class="font-semibold text-blue-700 text-sm">
                            Update Data
                        </h3>

                        <p class="text-xs text-blue-600 mt-1 leading-relaxed">
                            Pastikan data penghuni dan kamar sudah benar sebelum menyimpan perubahan.
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

<?php include '../../includes/footer.php'; ?>