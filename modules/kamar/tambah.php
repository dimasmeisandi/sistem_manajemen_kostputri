<!-- =========================================
     tambah.php - SAAS STYLE
========================================= -->

<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

$activePage = 'kamar';
include '../../includes/header.php';
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="ml-72 min-h-screen bg-slate-50">

<!-- TOPBAR -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Kamar
            </h1>

            <p class="text-sm text-slate-500">
                Tambahkan data kamar baru
            </p>
        </div>

        <a href="index.php"
           class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center gap-2">

            <i class="fa-solid fa-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

<!-- CONTENT -->
<div class="pt-32 px-8 pb-10 w-full">


<!-- CARD -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- HEADER -->
    <div class="px-6 py-5 border-b border-slate-100">

        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-bed"></i>
            </div>

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Informasi Kamar
                </h2>

                <p class="text-sm text-slate-500">
                    Isi data kamar dengan lengkap
                </p>
            </div>

        </div>

    </div>

    <!-- FORM -->
    <form action="proses_tambah.php" method="POST" class="p-8 space-y-7">

        <!-- NOMOR -->
        <div>

            <label class="text-sm font-bold text-slate-700 block mb-3">
                Nomor Kamar
            </label>

            <div class="relative">

                <i class="fa-solid fa-door-open absolute left-4 top-4 text-slate-400"></i>

                <input type="text"
                       name="nomor_kamar"
                       required
                       placeholder="Contoh: A1"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition">

            </div>

        </div>

        <!-- HARGA -->
        <div>

            <label class="text-sm font-bold text-slate-700 block mb-3">
                Harga Kamar
            </label>

            <div class="relative">

                <span class="absolute left-4 top-4 text-slate-400 font-semibold">
                    Rp
                </span>

                <input type="number"
                       name="harga_kamar"
                       required
                       placeholder="750000"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition">

            </div>

        </div>

        <!-- STATUS -->
        <div>

            <label class="text-sm font-bold text-slate-700 block mb-3">
                Status Kamar
            </label>

            <div class="relative">

                <i class="fa-solid fa-circle-check absolute left-4 top-4 text-slate-400"></i>

                <select name="status_kamar"
                        class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none appearance-none">

                    <option value="Kosong">Kosong</option>
                    <option value="Terisi">Terisi</option>

                </select>

            </div>

        </div>

        <!-- AUTO FASILITAS -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">

            <div class="flex items-start gap-3">

                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div>

                    <h3 class="font-bold text-slate-700 mb-2">
                        Fasilitas Otomatis
                    </h3>

                    <p class="text-sm text-slate-500 leading-relaxed">
                        Fasilitas kamar akan otomatis menyesuaikan berdasarkan nomor kamar.
                        Contoh:
                    </p>

                    <ul class="mt-3 space-y-2 text-sm text-slate-600">

                        <li>
                            <span class="font-semibold text-indigo-600">
                                A1-A10
                            </span>
                            → AC, KM Dalam, WiFi, Kasur, Lemari
                        </li>

                        <li>
                            <span class="font-semibold text-indigo-600">
                                A11-A20
                            </span>
                            → Kipas, WiFi, Kasur, Lemari
                        </li>

                        <li>
                            <span class="font-semibold text-indigo-600">
                                B1-B10
                            </span>
                            → AC, KM Bersama, WiFi, Kasur, Lemari
                        </li>

                        <li>
                            <span class="font-semibold text-indigo-600">
                                B11-B20
                            </span>
                            → Kipas, KM Bersama, WiFi, Kasur, Lemari
                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-4 pt-4">

            <a href="index.php"
               class="px-6 py-4 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold">

                Batal

            </a>

            <button type="submit"
                    class="px-6 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition shadow-lg shadow-indigo-200 flex items-center gap-2">

                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Kamar

            </button>

        </div>

    </form>

</div>

</div>
</div>

</div>

<?php include '../../includes/footer.php'; ?>