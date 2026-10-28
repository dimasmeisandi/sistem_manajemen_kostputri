<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: auth/login.php");
    exit();
}

$activePage = 'dashboard';
/* =========================
   DATA DASAR
========================= */
$q_kosong = mysqli_query($conn,"SELECT COUNT(*) as total FROM kamar WHERE status_kamar='Kosong'");
$d_kosong = mysqli_fetch_assoc($q_kosong);

$q_penghuni = mysqli_query($conn,"SELECT COUNT(*) as total FROM penghuni");
$d_penghuni = mysqli_fetch_assoc($q_penghuni);

$q_kamar = mysqli_query($conn,"SELECT COUNT(*) as total FROM kamar");
$d_kamar = mysqli_fetch_assoc($q_kamar);

$q_lunas = mysqli_query($conn,"SELECT COUNT(*) as total FROM pembayaran WHERE status_pembayaran='Lunas'");
$d_lunas = mysqli_fetch_assoc($q_lunas);

$q_pengeluaran = mysqli_query($conn,"SELECT SUM(jumlah) as total FROM pengeluaran");
$d_pengeluaran = mysqli_fetch_assoc($q_pengeluaran);

$q_pendapatan = mysqli_query($conn,"SELECT SUM(jumlah_bayar) as total FROM pembayaran WHERE status_pembayaran='Lunas'");
$d_pendapatan = mysqli_fetch_assoc($q_pendapatan);

/* =========================
   SAFE DATA
========================= */
$total_kamar = $d_kamar['total'] ?? 0;
$kamar_kosong = $d_kosong['total'] ?? 0;
$penghuni = $d_penghuni['total'] ?? 0;
$lunas = $d_lunas['total'] ?? 0;
$pengeluaran = $d_pengeluaran['total'] ?? 0;
$pendapatan = $d_pendapatan['total'] ?? 0;

$terisi = $total_kamar - $kamar_kosong;

/* =========================
   PENDAPATAN BULANAN
========================= */
$bulanList = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$dataPendapatan = array_fill(0,12,0);

$bulanList = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$dataPendapatan = array_fill(0,12,0);

$q1 = mysqli_query($conn,"
    SELECT bulan_bayar, SUM(jumlah_bayar) as total
    FROM pembayaran
    WHERE status_pembayaran='Lunas'
    GROUP BY bulan_bayar
");

while($r = mysqli_fetch_assoc($q1)){

    $bulan = strtolower($r['bulan_bayar']);

    $map = [
        'januari'=>0,'jan'=>0,
        'februari'=>1,'feb'=>1,
        'maret'=>2,'mar'=>2,
        'april'=>3,'apr'=>3,
        'mei'=>4,
        'juni'=>5,'jun'=>5,
        'juli'=>6,'jul'=>6,
        'agustus'=>7,'agu'=>7,
        'september'=>8,'sep'=>8,
        'oktober'=>9,'okt'=>9,
        'november'=>10,'nov'=>10,
        'desember'=>11,'des'=>11
    ];

    if(isset($map[$bulan])){
        $dataPendapatan[$map[$bulan]] = (int)$r['total'];
    }
}

/* =========================
   PENGELUARAN
========================= */
$q2 = mysqli_query($conn,"
    SELECT keterangan, SUM(jumlah) as total
    FROM pengeluaran
    GROUP BY keterangan
");

$kategoriPengeluaran = [];
$dataPengeluaran = [];

while($r = mysqli_fetch_assoc($q2)){
    $kategoriPengeluaran[] = $r['keterangan'];
    $dataPengeluaran[] = (int)$r['total'];
}

include 'includes/header.php';
?>

<style>
.card-anim{
    animation: fadeUp .4s ease;
}
@keyframes fadeUp{
    from{opacity:0; transform:translateY(15px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

<div class="flex h-screen bg-slate-100 overflow-hidden">

<?php include 'includes/sidebar.php'; ?>

<main class="flex-1 ml-72 overflow-y-auto">

<!-- HEADER (TETAP ASLI) -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b px-8 py-5 z-40">
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500">Sistem Manajemen Kos</p>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <h3 class="font-semibold text-slate-800">
                    <?= $_SESSION['admin_nama'] ?>
                </h3>
                <p class="text-xs text-slate-400">Administrator</p>
            </div>

            <div class="w-11 h-11 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold">
                <?= strtoupper(substr($_SESSION['admin_nama'],0,2)); ?>
            </div>
        </div>

    </div>
</div>

<!-- CONTENT -->
<div class="p-8 pt-28">

<!-- =========================
     KPI + HERO
========================= -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- HERO PENDAPATAN -->
    <div class="xl:col-span-2 bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-8 rounded-2xl shadow-lg card-anim">

        <div class="flex items-center gap-3 mb-2">
            <i class="fa-solid fa-sack-dollar text-2xl"></i>
            <p class="text-indigo-100">Total Pendapatan</p>
        </div>

        <h1 class="text-4xl font-bold">
            Rp <?= number_format($pendapatan,0,',','.') ?>
        </h1>

        <p class="text-sm mt-3 text-indigo-100">
            Akumulasi seluruh pembayaran kos
        </p>
    </div>

    <!-- KPI -->
    <div class="grid grid-cols-2 gap-4">

        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400">Penghuni</p>
                <h2 class="text-2xl font-bold"><?= $penghuni ?></h2>
            </div>
            <i class="fa-solid fa-users text-emerald-500 text-xl"></i>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400">Kamar Kosong</p>
                <h2 class="text-2xl font-bold text-indigo-600"><?= $kamar_kosong ?></h2>
            </div>
            <i class="fa-solid fa-bed text-indigo-500 text-xl"></i>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm col-span-2 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400">Pembayaran Lunas</p>
                <h2 class="text-2xl font-bold"><?= $lunas ?></h2>
            </div>
            <i class="fa-solid fa-wallet text-cyan-500 text-xl"></i>
        </div>

    </div>

</div>

<!-- =========================
     CHARTS
========================= -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- PENDAPATAN -->
    <div class="bg-white p-5 rounded-2xl shadow-sm xl:col-span-2">
        <h3 class="font-bold mb-4">Pendapatan Bulanan</h3>
        <canvas id="chartPendapatan"></canvas>
    </div>

    <!-- OKUPANSI -->
    <div class="bg-white p-5 rounded-2xl shadow-sm">
        <h3 class="font-bold mb-4">Status Kamar</h3>
        <canvas id="chartOkupansi"></canvas>
        <p class="text-center text-sm mt-3 text-slate-500">
            <?= $terisi ?> terisi / <?= $total_kamar ?> kamar
        </p>
    </div>

</div>

<!-- PENGELUARAN -->
<div class="mt-6 bg-white p-5 rounded-2xl shadow-sm">
    <h3 class="font-bold mb-2">Pengeluaran</h3>
    <p class="text-red-500 font-bold mb-4">
        Rp <?= number_format($pengeluaran,0,',','.') ?>
    </p>

    <canvas id="chartPengeluaran"></canvas>
</div>

</div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* =========================
   PENDAPATAN (LINE AREA)
========================= */
new Chart(document.getElementById('chartPendapatan'), {
    type:'line',
    data:{
        labels: <?= json_encode($bulanList) ?>,
        datasets:[{
            label:'Pendapatan',
            data: <?= json_encode($dataPendapatan) ?>,
            borderColor:'#4f46e5',
            backgroundColor:'rgba(79,70,229,0.2)',
            fill:true,
            tension:0.4
        }]
    }
});

/* =========================
   OKUPANSI (DONUT)
========================= */
new Chart(document.getElementById('chartOkupansi'), {
    type:'doughnut',
    data:{
        labels:['Terisi','Kosong'],
        datasets:[{
            data:[<?= $terisi ?>, <?= $kamar_kosong ?>],
            backgroundColor:['#10b981','#f59e0b']
        }]
    },
    options:{
        cutout:'70%'
    }
});

/* =========================
   PENGELUARAN (HORIZONTAL BAR)
========================= */
new Chart(document.getElementById('chartPengeluaran'), {
    type:'bar',
    data:{
        labels: <?= json_encode($kategoriPengeluaran) ?>,
        datasets:[{
            label:'Pengeluaran',
            data: <?= json_encode($dataPengeluaran) ?>,
            backgroundColor:'#ef4444'
        }]
    },
    options:{
        indexAxis:'y'
    }
});

</script>

<?php include 'includes/footer.php'; ?>