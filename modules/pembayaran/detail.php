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
   QUERY DETAIL
========================= */
$q = mysqli_query($conn,"
    SELECT 
        pembayaran.*,
        penghuni.nama_penghuni,
        penghuni.no_hp,
        penghuni.jenis_kelamin,
        kamar.nomor_kamar,
        kamar.harga_kamar,
        kamar.status_kamar
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

$r = mysqli_fetch_assoc($q);

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
                Detail Pembayaran
            </h1>

            <p class="text-sm text-slate-500">
                Informasi lengkap transaksi pembayaran
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT CONTENT -->
    <div class="lg:col-span-2 space-y-6">

        <!-- PAYMENT SUMMARY -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100 flex justify-between items-start">

                <div class="flex items-center gap-4">

                    <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-2xl"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-slate-800">
                            Transaksi #<?= $r['id_pembayaran'] ?>
                        </h2>

                        <p class="text-sm text-slate-400 mt-1">
                            Data pembayaran kos
                        </p>
                    </div>

                </div>

                <?php if(($r['status_pembayaran'] ?? '') == 'Lunas'){ ?>

                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold">
                        <i class="fa-solid fa-circle-check"></i>
                        Lunas
                    </span>

                <?php } else { ?>

                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-50 text-rose-600 text-sm font-semibold">
                        <i class="fa-solid fa-clock"></i>
                        Belum Lunas
                    </span>

                <?php } ?>

            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- TANGGAL -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Tanggal Bayar
                        </p>
                    </div>

                    <h3 class="font-bold text-slate-800">
                        <?= !empty($r['tanggal_bayar']) ? date('d F Y', strtotime($r['tanggal_bayar'])) : '-' ?>
                    </h3>

                </div>

                <!-- BULAN -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-calendar"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Bulan Bayar
                        </p>
                    </div>

                    <h3 class="font-bold text-slate-800">
                        <?= $r['bulan_bayar'] ?? '-' ?>
                    </h3>

                </div>

                <!-- JUMLAH -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 md:col-span-2">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>

                        <p class="text-sm text-slate-400">
                            Jumlah Pembayaran
                        </p>
                    </div>

                    <h3 class="text-3xl font-bold text-rose-600">
                        Rp <?= number_format($r['jumlah_bayar'] ?? 0,0,',','.') ?>
                    </h3>

                </div>

            </div>

        </div>

        <!-- PENGHUNI INFO -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100">

                <h2 class="font-bold text-slate-800 text-lg">
                    Informasi Penghuni
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Data penghuni yang melakukan pembayaran
                </p>

            </div>

            <div class="p-6">

                <div class="flex items-center gap-5">

                    <div class="w-16 h-16 rounded-3xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-2xl">
                        <?= !empty($r['nama_penghuni']) ? strtoupper(substr($r['nama_penghuni'],0,1)) : '?' ?>
                    </div>

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">
                            <?= $r['nama_penghuni'] ?? '-' ?>
                        </h3>

                        <p class="text-sm text-slate-400 mt-1">
                            <?= $r['no_hp'] ?? '-' ?>
                        </p>

                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">

                    <!-- GENDER -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                        <p class="text-sm text-slate-400 mb-2">
                            Jenis Kelamin
                        </p>

                        <?php if(($r['jenis_kelamin'] ?? '') == 'Perempuan'){ ?>

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-pink-50 text-pink-600 text-sm font-semibold">
                                <i class="fa-solid fa-venus"></i>
                                Perempuan
                            </span>

                        <?php } else if(($r['jenis_kelamin'] ?? '') == 'Laki-laki'){ ?>

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold">
                                <i class="fa-solid fa-mars"></i>
                                Laki-laki
                            </span>

                        <?php } else { ?>

                            <span class="text-slate-500">-</span>

                        <?php } ?>

                    </div>

                    <!-- KAMAR -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">

                        <p class="text-sm text-slate-400 mb-2">
                            Kamar
                        </p>

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold">
                            <i class="fa-solid fa-door-open"></i>
                            <?= $r['nomor_kamar'] ?? '-' ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="space-y-6">

        <!-- KAMAR CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <i class="fa-solid fa-door-open text-2xl"></i>
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                Kamar <?= $r['nomor_kamar'] ?? '-' ?>
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Informasi kamar penghuni
            </p>

            <div class="mt-6 space-y-4">

                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-sm text-slate-400">Harga Kamar</span>
                    <span class="font-bold text-slate-800">
                        Rp <?= number_format($r['harga_kamar'] ?? 0,0,',','.') ?>
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-400">Status Kamar</span>

                    <?php if(($r['status_kamar'] ?? '') == 'Kosong'){ ?>

                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                            Kosong
                        </span>

                    <?php } else { ?>

                        <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold">
                            Terisi
                        </span>

                    <?php } ?>

                </div>

            </div>

        </div>

        <!-- ACTION CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <h2 class="text-lg font-bold text-slate-800">
                Aksi Pembayaran
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Kelola data transaksi ini
            </p>

            <div class="mt-6 space-y-3">

                <a href="edit.php?id=<?= $r['id_pembayaran'] ?>"
                   class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-2xl font-semibold flex items-center justify-center gap-2">

                    <i class="fa-solid fa-pen"></i>
                    Edit Pembayaran

                </a>

                <a href="hapus.php?id=<?= $r['id_pembayaran'] ?>"
                   onclick="return confirm('Hapus data pembayaran ini?')"
                   class="w-full bg-rose-50 hover:bg-rose-100 transition text-rose-600 py-3 rounded-2xl font-semibold flex items-center justify-center gap-2">

                    <i class="fa-solid fa-trash"></i>
                    Hapus Pembayaran

                </a>

            </div>

        </div>

    </div>

</div>

</main>

</div>

<?php include '../../includes/footer.php'; ?>