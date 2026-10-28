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

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   DATA KAMAR
========================= */
$qKamar = mysqli_query($conn,"
    SELECT * FROM kamar
    WHERE id_kamar='$id'
");

if(!$qKamar){
    die("Query Error : " . mysqli_error($conn));
}

if(mysqli_num_rows($qKamar) == 0){
    header("Location: index.php");
    exit();
}

$k = mysqli_fetch_assoc($qKamar);

/* =========================
   DATA SEMUA PENGHUNI DI KAMAR INI
========================= */
$qPenghuni = mysqli_query($conn,"
    SELECT * FROM penghuni
    WHERE id_kamar='$id'
    ORDER BY tgl_masuk ASC
");

if(!$qPenghuni){
    die("Query Error : " . mysqli_error($conn));
}

$listPenghuni = [];
while($p = mysqli_fetch_assoc($qPenghuni)){
    $listPenghuni[] = $p;
}

$jumlahPenghuni = count($listPenghuni);

/* =========================
   FASILITAS OTOMATIS
========================= */
$nomor = $k['nomor_kamar'];

$fasilitas = [];

if(
    preg_match('/A([1-9]|10)$/', $nomor) ||
    preg_match('/B([1-9]|10)$/', $nomor)
){
    $fasilitas = [
        'AC',
        'Kamar Mandi Dalam',
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
                Detail Kamar
            </h1>

            <p class="text-sm text-slate-500">
                Informasi lengkap kamar kos
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

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- =========================
         CONTENT
    ========================== -->
    <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 p-8 text-white">

            <div class="flex items-center gap-5">

                <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur flex items-center justify-center">

                    <i class="fa-solid fa-door-open text-3xl"></i>

                </div>

                <div>

                    <p class="text-indigo-100 text-sm">
                        Nomor Kamar
                    </p>

                    <h1 class="text-4xl font-bold mt-1">
                        <?= $k['nomor_kamar'] ?>
                    </h1>

                    <p class="text-indigo-100 text-sm mt-1">
                        <?= $jumlahPenghuni ?> Penghuni
                    </p>

                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- HARGA -->
                <div class="border border-slate-200 rounded-2xl p-5">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">

                            <i class="fa-solid fa-money-bill-wave"></i>

                        </div>

                        <div>

                            <p class="text-sm text-slate-400">
                                Harga Kamar
                            </p>

                            <h3 class="font-bold text-slate-800 text-lg">
                                Rp <?= number_format($k['harga_kamar'],0,',','.') ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <!-- STATUS -->
                <div class="border border-slate-200 rounded-2xl p-5">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">

                            <i class="fa-solid fa-circle-info"></i>

                        </div>

                        <div>

                            <p class="text-sm text-slate-400">
                                Status Kamar
                            </p>

                            <?php if($k['status_kamar']=='Kosong'){ ?>

                                <span class="mt-1 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold">

                                    <i class="fa-solid fa-circle text-[8px]"></i>
                                    Kosong

                                </span>

                            <?php } else { ?>

                                <span class="mt-1 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-sm font-semibold">

                                    <i class="fa-solid fa-circle text-[8px]"></i>
                                    Terisi

                                </span>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            </div>

            <!-- =========================
                 DAFTAR PENGHUNI
            ========================== -->
            <div class="mt-8">

                <div class="flex items-center justify-between mb-5">

                    <h2 class="text-lg font-bold text-slate-800">
                        Penghuni Kamar
                    </h2>

                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-indigo-50 text-indigo-600">
                        <?= $jumlahPenghuni ?> Orang
                    </span>

                </div>

                <?php if($jumlahPenghuni > 0){ ?>

                    <div class="space-y-4">

                        <?php foreach($listPenghuni as $index => $p){ ?>

                            <div class="border border-slate-200 rounded-2xl p-5">

                                <!-- HEADER PENGHUNI -->
                                <div class="flex items-center gap-3 mb-4">

                                    <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg">
                                        <?= $index + 1 ?>
                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Penghuni #<?= $index + 1 ?>
                                        </p>

                                        <h3 class="font-bold text-slate-800 text-base">
                                            <?= htmlspecialchars($p['nama_penghuni'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        </h3>

                                    </div>

                                </div>

                                <!-- DETAIL PENGHUNI -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                    <!-- HP -->
                                    <div class="bg-slate-50 rounded-xl p-4">

                                        <p class="text-xs text-slate-400 mb-1">
                                            Nomor HP
                                        </p>

                                        <h4 class="font-semibold text-slate-700">
                                            <?= htmlspecialchars($p['no_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        </h4>

                                    </div>

                                    <!-- GENDER -->
                                    <div class="bg-slate-50 rounded-xl p-4">

                                        <p class="text-xs text-slate-400 mb-1">
                                            Jenis Kelamin
                                        </p>

                                        <h4 class="font-semibold text-slate-700">
                                            <?= htmlspecialchars($p['jenis_kelamin'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        </h4>

                                    </div>

                                    <!-- ALAMAT -->
                                    <div class="bg-slate-50 rounded-xl p-4 md:col-span-2">

                                        <p class="text-xs text-slate-400 mb-1">
                                            Alamat
                                        </p>

                                        <h4 class="font-semibold text-slate-700">
                                            <?= htmlspecialchars($p['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        </h4>

                                    </div>

                                    <!-- TANGGAL MASUK -->
                                    <div class="bg-slate-50 rounded-xl p-4 md:col-span-2">

                                        <p class="text-xs text-slate-400 mb-1">
                                            Tanggal Masuk
                                        </p>

                                        <h4 class="font-semibold text-slate-700">
                                            <?= date('d F Y', strtotime($p['tgl_masuk'])) ?>
                                        </h4>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                <?php } else { ?>

                    <div class="border border-dashed border-slate-200 rounded-2xl p-8 text-center">

                        <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-user-slash text-xl"></i>
                        </div>

                        <p class="text-slate-400 font-medium">
                            Belum Ada Penghuni
                        </p>

                        <p class="text-slate-300 text-sm mt-1">
                            Kamar ini masih kosong
                        </p>

                    </div>

                <?php } ?>

            </div>

            <!-- =========================
                 FASILITAS
            ========================== -->
            <div class="mt-8">

                <h2 class="text-lg font-bold text-slate-800 mb-5">
                    Fasilitas Kamar
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    <?php foreach($fasilitas as $f){ ?>

                        <?php
                            $bg = 'bg-slate-50 text-slate-700';
                            $icon = 'fa-check';

                            if($f == 'AC'){
                                $bg = 'bg-cyan-50 text-cyan-600';
                                $icon = 'fa-snowflake';
                            }

                            if($f == 'Kipas'){
                                $bg = 'bg-amber-50 text-amber-600';
                                $icon = 'fa-fan';
                            }

                            if($f == 'WiFi'){
                                $bg = 'bg-indigo-50 text-indigo-600';
                                $icon = 'fa-wifi';
                            }

                            if($f == 'Kasur'){
                                $bg = 'bg-rose-50 text-rose-600';
                                $icon = 'fa-bed';
                            }

                            if($f == 'Lemari'){
                                $bg = 'bg-violet-50 text-violet-600';
                                $icon = 'fa-box';
                            }

                            if($f == 'Kamar Mandi Dalam'){
                                $bg = 'bg-emerald-50 text-emerald-600';
                                $icon = 'fa-bath';
                            }
                        ?>

                        <div class="rounded-2xl p-4 <?= $bg ?> flex items-center gap-3">

                            <div class="w-11 h-11 rounded-xl bg-white/70 flex items-center justify-center">

                                <i class="fa-solid <?= $icon ?>"></i>

                            </div>

                            <div>

                                <p class="font-semibold">
                                    <?= $f ?>
                                </p>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
         SIDEBAR
    ========================== -->
    <div class="space-y-6">

        <!-- ACTION -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <h2 class="font-bold text-slate-800 mb-5">
                Quick Action
            </h2>

            <div class="space-y-3">

                <a href="edit.php?id=<?= $k['id_kamar'] ?>"
                   class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white rounded-2xl px-5 py-4 flex items-center justify-center gap-3 font-semibold">

                    <i class="fa-solid fa-pen"></i>
                    Edit Kamar

                </a>

                <a href="hapus.php?id=<?= $k['id_kamar'] ?>"
                   onclick="return confirm('Hapus kamar ini?')"
                   class="w-full bg-rose-500 hover:bg-rose-600 transition text-white rounded-2xl px-5 py-4 flex items-center justify-center gap-3 font-semibold">

                    <i class="fa-solid fa-trash"></i>
                    Hapus Kamar

                </a>

            </div>

        </div>

        <!-- INFO -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <h2 class="font-bold text-slate-800 mb-5">
                Informasi Kamar
            </h2>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">

                    <span class="text-slate-400">
                        ID Kamar
                    </span>

                    <span class="font-semibold text-slate-700">
                        #<?= $k['id_kamar'] ?>
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-slate-400">
                        Nomor
                    </span>

                    <span class="font-semibold text-slate-700">
                        <?= $k['nomor_kamar'] ?>
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-slate-400">
                        Status
                    </span>

                    <span class="font-semibold text-slate-700">
                        <?= $k['status_kamar'] ?>
                    </span>

                </div>

                <div class="flex justify-between">

                    <span class="text-slate-400">
                        Total Penghuni
                    </span>

                    <span class="font-semibold text-slate-700">
                        <?= $jumlahPenghuni ?> Orang
                    </span>

                </div>

            </div>

        </div>

        <!-- DAFTAR NAMA PENGHUNI (RINGKAS) -->
        <?php if($jumlahPenghuni > 0){ ?>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <h2 class="font-bold text-slate-800 mb-5">
                Daftar Penghuni
            </h2>

            <div class="space-y-3">

                <?php foreach($listPenghuni as $index => $p){ ?>

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            <?= $index + 1 ?>
                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-slate-700 text-sm truncate">
                                <?= htmlspecialchars($p['nama_penghuni'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <p class="text-xs text-slate-400 truncate">
                                <?= htmlspecialchars($p['no_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </p>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</main>

</div>

<?php include '../../includes/footer.php'; ?>