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
    ";
}

/* =========================
   QUERY
========================= */
$q = mysqli_query($conn,"
    SELECT 
        penghuni.*,
        kamar.nomor_kamar
    FROM penghuni
    LEFT JOIN kamar 
        ON penghuni.id_kamar = kamar.id_kamar
    $where
    ORDER BY penghuni.id_penghuni DESC
");

/* =========================
   STATISTIK
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t FROM penghuni
"))['t'];

$laki = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM penghuni 
    WHERE jenis_kelamin='Laki-laki'
"))['t'];

$perempuan = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as t 
    FROM penghuni 
    WHERE jenis_kelamin='Perempuan'
"))['t'];

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
                Data Penghuni
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
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <!-- TOTAL -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Total Penghuni
                </p>

                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    <?= $total ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-users text-xl"></i>
            </div>

        </div>

    </div>

    <!-- LAKI -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Laki-laki
                </p>

                <h2 class="text-3xl font-bold text-blue-600 mt-2">
                    <?= $laki ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-mars text-xl"></i>
            </div>

        </div>

    </div>

    <!-- PEREMPUAN -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-slate-400">
                    Perempuan
                </p>

                <h2 class="text-3xl font-bold text-pink-600 mt-2">
                    <?= $perempuan ?>
                </h2>
            </div>

            <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center">
                <i class="fa-solid fa-venus text-xl"></i>
            </div>

        </div>

    </div>

</div>

<!-- =========================
     FILTER
========================= -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">

    <div class="flex flex-col lg:flex-row justify-between gap-4">

        <!-- SEARCH -->
        <form method="GET"
              class="relative w-full md:w-[350px]">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>

            <input
                type="text"
                name="search"
                value="<?= $search ?>"
                placeholder="Cari penghuni atau kamar..."
                class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
            >

        </form>

        <!-- BUTTON -->
        <a href="tambah.php"
           class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-3 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">

            <i class="fa-solid fa-plus"></i>
            Tambah Penghuni

        </a>

    </div>

</div>

<!-- =========================
     TABLE
========================= -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <!-- HEADER -->
    <div class="px-6 py-5 border-b border-slate-100">

        <h2 class="font-bold text-slate-800 text-lg">
            Daftar Penghuni
        </h2>

        <p class="text-sm text-slate-400 mt-1">
            Total <?= mysqli_num_rows($q) ?> penghuni
        </p>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 text-slate-400 uppercase text-xs">

                <tr>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-user mr-2"></i>
                        Nama
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-phone mr-2"></i>
                        No HP
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-venus-mars mr-2"></i>
                        Jenis Kelamin
                    </th>

                    <th class="px-6 py-4 text-left">
                        <i class="fa-solid fa-door-open mr-2"></i>
                        Kamar
                    </th>

                    <th class="px-6 py-4 text-center">
                        <i class="fa-solid fa-gear mr-2"></i>
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php while($r=mysqli_fetch_assoc($q)) { ?>

                <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                    <!-- NAMA -->
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <?= strtoupper(substr($r['nama_penghuni'],0,1)); ?>
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    <?= $r['nama_penghuni'] ?>
                                </h3>

                                <p class="text-xs text-slate-400">
                                    Penghuni Kos
                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- HP -->
                    <td class="px-6 py-5 text-slate-600">

                        <?= $r['no_hp'] ?>

                    </td>

                    <!-- GENDER -->
                    <td class="px-6 py-5">

                        <?php if($r['jenis_kelamin']=='Perempuan'){ ?>

                            <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 text-xs font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-venus"></i>
                                Perempuan

                            </span>

                        <?php } else { ?>

                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-mars"></i>
                                Laki-laki

                            </span>

                        <?php } ?>

                    </td>

                    <!-- KAMAR -->
                    <td class="px-6 py-5">

                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">

                            <?= $r['nomor_kamar'] ?? '-' ?>

                        </span>

                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-5">

                        <div class="flex items-center justify-center gap-2">

                            <!-- DETAIL -->
                            <a href="detail.php?id=<?= $r['id_penghuni'] ?>"
                               class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-100 transition flex items-center justify-center">

                                <i class="fa-solid fa-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="edit.php?id=<?= $r['id_penghuni'] ?>"
                               class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition flex items-center justify-center">

                                <i class="fa-solid fa-pen"></i>

                            </a>

                            <!-- DELETE -->
                            <a href="hapus.php?id=<?= $r['id_penghuni'] ?>"
                               onclick="return confirm('Hapus penghuni ini?')"
                               class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition flex items-center justify-center">

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