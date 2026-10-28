<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   SEARCH
========================= */
$search = $_GET['search'] ?? '';

$where = "";

if($search != ''){
    $where = "WHERE 
        penghuni.nama_penghuni LIKE '%$search%'
        OR kamar.nomor_kamar LIKE '%$search%'
        OR pembayaran.bulan_bayar LIKE '%$search%'
    ";
}

/* =========================
   QUERY PEMBAYARAN
========================= */
$q = mysqli_query($conn,"
    SELECT 
        pembayaran.*,
        penghuni.nama_penghuni,
        kamar.nomor_kamar
    FROM pembayaran
    LEFT JOIN penghuni 
        ON pembayaran.id_penghuni = penghuni.id_penghuni
    LEFT JOIN kamar
        ON pembayaran.id_kamar = kamar.id_kamar
    $where
    ORDER BY pembayaran.id_pembayaran DESC
");

/* =========================
   STATISTIK
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t FROM pembayaran
"))['t'];

$lunas = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM pembayaran 
    WHERE status_pembayaran='Lunas'
"))['t'];

$belum = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM pembayaran 
    WHERE status_pembayaran!='Lunas'
"))['t'];

$total_uang = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(jumlah_bayar) as total 
    FROM pembayaran
"))['total'];

$activePage = 'pembayaran';

include '../../includes/header.php';
?>

<div class="flex min-h-screen bg-slate-100">

<?php include '../../includes/sidebar.php'; ?>

<!-- MAIN -->
<div class="flex-1 ml-72 bg-slate-50 min-h-screen">

<!-- TOPBAR -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Data Pembayaran
            </h1>

            <p class="text-sm text-slate-500">
                Kelola transaksi pembayaran kos
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

<!-- CONTENT -->
<main class="pt-28 px-8 pb-10">

<!-- =========================
     STATS
========================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

    <!-- TOTAL -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Pembayaran
                </p>

                <h2 class="text-4xl font-bold text-slate-800 mt-2">
                    <?= $total ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-money-bill-wave text-xl"></i>
            </div>

        </div>

    </div>

    <!-- LUNAS -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Pembayaran Lunas
                </p>

                <h2 class="text-4xl font-bold text-emerald-600 mt-2">
                    <?= $lunas ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>

        </div>

    </div>

    <!-- BELUM -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Belum Lunas
                </p>

                <h2 class="text-4xl font-bold text-rose-600 mt-2">
                    <?= $belum ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-clock text-xl"></i>
            </div>

        </div>

    </div>

    <!-- TOTAL UANG -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Pendapatan
                </p>

                <h2 class="text-2xl font-bold text-indigo-600 mt-2">
                    Rp <?= number_format($total_uang,0,',','.') ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-wallet text-xl"></i>
            </div>

        </div>

    </div>

</div>

<!-- =========================
     FILTER
========================= -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">

    <div class="flex flex-col md:flex-row gap-4 justify-between">

        <!-- SEARCH -->
        <form method="GET" class="relative w-full md:w-[350px]">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

            <input
                type="text"
                name="search"
                value="<?= $search ?>"
                placeholder="Cari penghuni atau kamar..."
                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
            >

        </form>

        <!-- BUTTON -->
        <a href="tambah.php"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2 transition">

            <i class="fa-solid fa-plus"></i>
            Tambah Pembayaran

        </a>

    </div>

</div>

<!-- =========================
     TABLE
========================= -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

    <!-- HEADER -->
    <div class="px-6 py-5 border-b border-slate-100">

        <h2 class="font-bold text-slate-800 text-lg">
            Riwayat Pembayaran
        </h2>

        <p class="text-sm text-slate-400 mt-1">
            Total <?= mysqli_num_rows($q) ?> transaksi
        </p>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr class="text-slate-400 text-sm">

                    <th class="px-6 py-4 text-left">
                        Penghuni
                    </th>

                    <th class="px-6 py-4 text-left">
                        Kamar
                    </th>

                    <th class="px-6 py-4 text-left">
                        Periode
                    </th>

                    <th class="px-6 py-4 text-left">
                        Jumlah
                    </th>

                    <th class="px-6 py-4 text-left">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php while($r=mysqli_fetch_assoc($q)) { ?>

                <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                    <!-- PENGHUNI -->
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                <?= strtoupper(substr($r['nama_penghuni'],0,1)); ?>
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    <?= $r['nama_penghuni'] ?>
                                </h3>

                                <p class="text-sm text-slate-400">
                                    <?= date('d F Y', strtotime($r['tanggal_bayar'])) ?>
                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- KAMAR -->
                    <td class="px-6 py-5">

                        <span class="px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold">
                            <?= $r['nomor_kamar'] ?>
                        </span>

                    </td>

                    <!-- PERIODE -->
                    <td class="px-6 py-5">

                        <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-semibold">

                            <?= $r['bulan_bayar'] ?? '-' ?>

                        </span>

                    </td>

                    <!-- JUMLAH -->
                    <td class="px-6 py-5 font-semibold text-slate-700">

                        Rp <?= number_format($r['jumlah_bayar'],0,',','.') ?>

                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5">

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

                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-5">

                        <div class="flex items-center justify-center gap-3">

                            <!-- DETAIL -->
                            <a href="detail.php?id=<?= $r['id_pembayaran'] ?>"
                               class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">

                                <i class="fa-solid fa-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="edit.php?id=<?= $r['id_pembayaran'] ?>"
                               class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition">

                                <i class="fa-solid fa-pen"></i>

                            </a>

                            <!-- DELETE -->
                            <a href="hapus.php?id=<?= $r['id_pembayaran'] ?>"
                               onclick="return confirm('Hapus pembayaran ini?')"
                               class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">

                                <i class="fa-solid fa-trash"></i>

                            </a>

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