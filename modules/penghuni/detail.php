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
   QUERY DETAIL PENGHUNI
========================= */
$query = mysqli_query($conn,"
    SELECT 
        penghuni.*,
        kamar.nomor_kamar,
        kamar.harga_kamar,
        kamar.status_kamar
    FROM penghuni
    LEFT JOIN kamar 
        ON penghuni.id_kamar = kamar.id_kamar
    WHERE penghuni.id_penghuni = '$id'
");

if(mysqli_num_rows($query) == 0){
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($query);

/* =========================
   FASILITAS KAMAR
========================= */
$fasilitas = [];

$nomor = $data['nomor_kamar'];

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
                Detail Penghuni
            </h1>

            <p class="text-sm text-slate-500">
                Informasi lengkap penghuni kos
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- =========================
         PROFILE
    ========================= -->
    <div class="lg:col-span-1">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

            <!-- AVATAR -->
            <div class="flex flex-col items-center text-center">

                <div class="w-28 h-28 rounded-3xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-4xl font-bold">

                    <?= strtoupper(substr($data['nama_penghuni'],0,1)); ?>

                </div>

                <h2 class="text-2xl font-bold text-slate-800 mt-5">

                    <?= $data['nama_penghuni'] ?>

                </h2>

                <p class="text-slate-400 text-sm mt-1">
                    Penghuni Kos
                </p>

            </div>

            <!-- INFO -->
            <div class="mt-8 space-y-5">

                <!-- HP -->
                <div class="flex items-start gap-4">

                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div>

                        <p class="text-xs text-slate-400">
                            Nomor HP
                        </p>

                        <h3 class="font-semibold text-slate-700 mt-1">
                            <?= $data['no_hp'] ?>
                        </h3>

                    </div>

                </div>

                <!-- GENDER -->
                <div class="flex items-start gap-4">

                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-venus-mars"></i>
                    </div>

                    <div>

                        <p class="text-xs text-slate-400">
                            Jenis Kelamin
                        </p>

                        <h3 class="font-semibold text-slate-700 mt-1">
                            <?= $data['jenis_kelamin'] ?>
                        </h3>

                    </div>

                </div>

                <!-- ALAMAT -->
                <div class="flex items-start gap-4">

                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>

                        <p class="text-xs text-slate-400">
                            Alamat
                        </p>

                        <h3 class="font-semibold text-slate-700 mt-1 leading-relaxed">
                            <?= $data['alamat'] ?>
                        </h3>

                    </div>

                </div>

                <!-- TANGGAL MASUK -->
                <div class="flex items-start gap-4">

                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar"></i>
                    </div>

                    <div>

                        <p class="text-xs text-slate-400">
                            Tanggal Masuk
                        </p>

                        <h3 class="font-semibold text-slate-700 mt-1">
                            <?php
                            if(!empty($data['tgl_masuk'])){
                              echo date('d F Y', strtotime($data['tgl_masuk']));
                              }else{
                                echo '-';
                                }
                                ?>
                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
         DETAIL KAMAR
    ========================= -->
    <div class="lg:col-span-2">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">

                <div>

                    <h2 class="text-xl font-bold text-slate-800">
                        Informasi Kamar
                    </h2>

                    <p class="text-sm text-slate-400 mt-1">
                        Detail kamar penghuni
                    </p>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">

                    <i class="fa-solid fa-door-open text-xl"></i>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="p-6">

                <!-- ROOM -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

                    <!-- NOMOR -->
                    <div class="bg-slate-50 rounded-2xl p-5">

                        <p class="text-xs text-slate-400 mb-2">
                            Nomor Kamar
                        </p>

                        <h3 class="text-2xl font-bold text-slate-800">
                            <?= $data['nomor_kamar'] ?>
                        </h3>

                    </div>

                    <!-- STATUS -->
                    <div class="bg-slate-50 rounded-2xl p-5">

                        <p class="text-xs text-slate-400 mb-2">
                            Status
                        </p>

                        <?php if($data['status_kamar']=='Terisi'){ ?>

                            <span class="px-4 py-2 rounded-full bg-rose-50 text-rose-600 text-sm font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-circle text-[8px]"></i>
                                Terisi

                            </span>

                        <?php } else { ?>

                            <span class="px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-circle text-[8px]"></i>
                                Kosong

                            </span>

                        <?php } ?>

                    </div>

                    <!-- HARGA -->
                    <div class="bg-slate-50 rounded-2xl p-5">

                        <p class="text-xs text-slate-400 mb-2">
                            Harga Kamar
                        </p>

                        <h3 class="text-xl font-bold text-indigo-600">
                            Rp <?= number_format($data['harga_kamar'],0,',','.') ?>
                        </h3>

                    </div>

                </div>

                <!-- FASILITAS -->
                <div>

                    <h3 class="font-bold text-slate-800 mb-4">
                        Fasilitas Kamar
                    </h3>

                    <div class="flex flex-wrap gap-3">

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

                                if($f == 'Kamar Mandi Dalam'){
                                    $color = 'bg-emerald-50 text-emerald-600';
                                }
                            ?>

                            <span class="px-4 py-2 rounded-xl text-sm font-semibold <?= $color ?>">

                                <?= $f ?>

                            </span>

                        <?php } ?>

                    </div>

                </div>

                <!-- ACTION -->
                <div class="mt-8 flex gap-3">

                    <a href="edit.php?id=<?= $data['id_penghuni'] ?>"
                       class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 transition text-white font-semibold text-sm flex items-center gap-2">

                        <i class="fa-solid fa-pen"></i>
                        Edit Penghuni

                    </a>

                    <a href="hapus.php?id=<?= $data['id_penghuni'] ?>"
                       onclick="return confirm('Hapus penghuni ini?')"
                       class="px-5 py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 transition text-rose-600 font-semibold text-sm flex items-center gap-2">

                        <i class="fa-solid fa-trash"></i>
                        Hapus

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</main>

</div>

<?php include '../../includes/footer.php'; ?>