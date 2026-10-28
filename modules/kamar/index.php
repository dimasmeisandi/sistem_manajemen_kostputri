<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

/* =========================
   FILTER
========================= */
$search = $_GET['search'] ?? '';
$filter = $_GET['status'] ?? '';

$where = "WHERE 1=1";

if($search != ''){
    $where .= " AND nomor_kamar LIKE '%$search%'";
}

if($filter != ''){
    $where .= " AND status_kamar='$filter'";
}

/* =========================
   DATA
========================= */
$q = mysqli_query($conn,"
    SELECT * FROM kamar
    $where
    ORDER BY id_kamar DESC
");

/* =========================
   STATISTIK
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t FROM kamar
"))['t'];

$kosong = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM kamar 
    WHERE status_kamar='Kosong'
"))['t'];

$terisi = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM kamar 
    WHERE status_kamar='Terisi'
"))['t'];

$occupancy = $total > 0 ? round(($terisi/$total)*100) : 0;

$activePage = 'kamar';

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
                Data Kamar
            </h1>

            <p class="text-sm text-slate-500">
                Sistem Manajemen Kos
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

<!-- =========================
     MAIN
========================= -->
<main class="pt-28 px-8 pb-10">

<!-- =========================
     STATS
========================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

    <!-- TOTAL -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Kamar
                </p>

                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    <?= $total ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-door-open text-xl"></i>
            </div>

        </div>

    </div>

    <!-- KOSONG -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Kamar Kosong
                </p>

                <h2 class="text-3xl font-bold text-emerald-500 mt-2">
                    <?= $kosong ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>

        </div>

    </div>

    <!-- TERISI -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Kamar Terisi
                </p>

                <h2 class="text-3xl font-bold text-rose-500 mt-2">
                    <?= $terisi ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-users text-xl"></i>
            </div>

        </div>

    </div>

    <!-- TERPAKAI -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <p class="text-sm text-slate-400 mb-2">
            Kamar Terpakai
        </p>

        <div class="flex items-end justify-between">

            <h2 class="text-3xl font-bold text-indigo-600">
                <?= $occupancy ?>%
            </h2>

            <i class="fa-solid fa-chart-pie text-indigo-500 text-xl"></i>

        </div>

        <div class="w-full h-2 bg-slate-100 rounded-full mt-4 overflow-hidden">
            <div class="bg-indigo-600 h-2 rounded-full"
                 style="width:<?= $occupancy ?>%">
            </div>
        </div>

    </div>

</div>

<!-- =========================
     FILTER
========================= -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">

    <div class="flex flex-col lg:flex-row justify-between gap-4">

        <!-- FILTER -->
        <form method="GET"
              id="filterForm"
              class="flex flex-wrap gap-3 items-center">

            <!-- SEARCH -->
            <div class="relative">

                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>

                <!-- Spinner icon (hidden by default) -->
                <i class="fa-solid fa-circle-notch fa-spin absolute right-4 top-1/2 -translate-y-1/2 text-indigo-400 text-sm hidden" id="searchSpinner"></i>

                <input
                    type="text"
                    name="search"
                    id="searchInput"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Cari nomor kamar..."
                    autocomplete="off"
                    class="pl-11 pr-10 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                >

            </div>

            <!-- STATUS -->
            <select
                name="status"
                id="statusFilter"
                class="px-4 py-3 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
            >

                <option value="" <?= $filter=='' ? 'selected' : '' ?>>
                    Semua Status
                </option>

                <option value="Kosong" <?= $filter=='Kosong' ? 'selected' : '' ?>>
                    Kosong
                </option>

                <option value="Terisi" <?= $filter=='Terisi' ? 'selected' : '' ?>>
                    Terisi
                </option>

            </select>

        </form>

        <!-- BUTTON -->
        <a href="tambah.php"
           class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-3 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">

            <i class="fa-solid fa-plus"></i>
            Tambah Kamar

        </a>

    </div>

</div>

<!-- =========================
     TABLE
========================= -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <!-- TABLE HEADER -->
    <div class="px-6 py-5 border-b border-slate-100">

        <h2 class="font-bold text-slate-800 text-lg">
            Daftar Kamar
        </h2>

        <p class="text-sm text-slate-400 mt-1">
            Total <?= mysqli_num_rows($q) ?> kamar
        </p>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 text-slate-400 uppercase text-xs">

                <tr>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-door-open mr-2"></i>
                        Kamar
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-money-bill-wave mr-2"></i>
                        Harga
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-circle-info mr-2"></i>
                        Status
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-bed mr-2"></i>
                        Fasilitas
                    </th>

                    <th class="px-6 py-4 text-center">
                        <i class="fa-solid fa-gear mr-2"></i>
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php while($r=mysqli_fetch_assoc($q)) { ?>

                <?php

                $nomor = $r['nomor_kamar'];
                $fasilitas = [];

                if(
                    preg_match('/A([1-9]|10)$/', $nomor) ||
                    preg_match('/B([1-9]|10)$/', $nomor)
                ){
                    $fasilitas = [
                        'AC',
                        'KM Dalam',
                        'WiFi',
                        'Kasur',
                        'Lemari'
                    ];
                }
                else{
                    $fasilitas = [
                        'Kipas',
                        'WiFi',
                        'Kasur',
                        'Lemari'
                    ];
                }

                ?>

                <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                    <!-- NOMOR -->
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-door-open"></i>
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    <?= $r['nomor_kamar'] ?>
                                </h3>

                                <p class="text-xs text-slate-400">
                                    Kamar Kos
                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- HARGA -->
                    <td class="px-6 py-5 text-slate-700 font-medium">

                        Rp <?= number_format($r['harga_kamar'],0,',','.') ?>

                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-5">

                        <?php if($r['status_kamar']=='Kosong'){ ?>

                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-circle text-[8px]"></i>
                                Kosong

                            </span>

                        <?php } else { ?>

                            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-circle text-[8px]"></i>
                                Terisi

                            </span>

                        <?php } ?>

                    </td>

                    <!-- FASILITAS -->
                    <td class="px-6 py-5">

                        <div class="flex flex-wrap gap-2">

                            <?php foreach($fasilitas as $f) { ?>

                                <?php
                                    $color = 'bg-slate-100 text-slate-600';

                                    if($f == 'AC'){
                                        $color = 'bg-cyan-50 text-cyan-600';
                                    }

                                    if($f == 'Kipas'){
                                        $color = 'bg-amber-50 text-amber-600';
                                    }

                                    if($f == 'WiFi'){
                                        $color = 'bg-indigo-50 text-indigo-600';
                                    }

                                    if($f == 'KM Dalam'){
                                        $color = 'bg-emerald-50 text-emerald-600';
                                    }
                                ?>

                                <span class="px-2 py-1 rounded-lg text-xs font-medium <?= $color ?>">

                                    <?= $f ?>

                                </span>

                            <?php } ?>

                        </div>

                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">

                        <div class="flex items-center justify-center gap-2">

                            <!-- DETAIL -->
                            <a href="detail.php?id=<?= $r['id_kamar'] ?>"
                            class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-100 transition flex items-center justify-center"
                            title="Detail Kamar">

                                <i class="fa-solid fa-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="edit.php?id=<?= $r['id_kamar'] ?>"
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition flex items-center justify-center"
                            title="Edit Kamar">

                                <i class="fa-solid fa-pen"></i>

                            </a>

                            <!-- DELETE -->
                            <a href="hapus.php?id=<?= $r['id_kamar'] ?>"
                            onclick="return confirm('Hapus kamar ini?')"
                            class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition flex items-center justify-center"
                            title="Hapus Kamar">

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

<!-- =========================
     JS FILTER
========================= -->
<script>

const form         = document.getElementById('filterForm');
const statusFilter = document.getElementById('statusFilter');
const searchInput  = document.getElementById('searchInput');
const spinner      = document.getElementById('searchSpinner');

let debounceTimer = null;

/* -------------------------------------------------------
   Live search — submit otomatis setelah berhenti mengetik
   300ms debounce supaya tidak terlalu banyak request
------------------------------------------------------- */
searchInput.addEventListener('input', function () {

    // Tampilkan spinner
    spinner.classList.remove('hidden');

    // Batalkan timer sebelumnya jika masih mengetik
    clearTimeout(debounceTimer);

    // Set timer baru
    debounceTimer = setTimeout(function () {
        form.submit();
    }, 300);

});

/* -------------------------------------------------------
   Auto submit saat filter status berubah
------------------------------------------------------- */
statusFilter.addEventListener('change', function () {
    form.submit();
});

/* -------------------------------------------------------
   Tetap support Enter (supaya tidak ada perilaku aneh)
------------------------------------------------------- */
searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        clearTimeout(debounceTimer);
        form.submit();
    }
});

</script>

<?php include '../../includes/footer.php'; ?>