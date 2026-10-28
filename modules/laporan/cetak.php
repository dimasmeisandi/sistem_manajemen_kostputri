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
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$tanggal_mulai = mysqli_real_escape_string($conn, $tanggal_mulai);
$tanggal_selesai = mysqli_real_escape_string($conn, $tanggal_selesai);

/* =========================
   DETEKSI KOLOM PENGELUARAN
========================= */
$columns = [];

$colQuery = mysqli_query($conn, "SHOW COLUMNS FROM pengeluaran");

if(!$colQuery){
    die("Tabel pengeluaran tidak ditemukan.");
}

while($col = mysqli_fetch_assoc($colQuery)){
    $columns[] = $col['Field'];
}

function pickColumn($columns, $candidates){
    foreach($candidates as $c){
        if(in_array($c, $columns)){
            return $c;
        }
    }
    return null;
}

$tanggalPengeluaranCol = pickColumn($columns, [
    'tanggal_pengeluaran',
    'tgl_pengeluaran',
    'tanggal',
    'tgl',
    'tanggal_transaksi',
    'tgl_transaksi'
]);

$jumlahPengeluaranCol = pickColumn($columns, [
    'jumlah_pengeluaran',
    'nominal_pengeluaran',
    'total_pengeluaran',
    'biaya_pengeluaran',
    'jumlah',
    'nominal',
    'total',
    'biaya'
]);

$keteranganPengeluaranCol = pickColumn($columns, [
    'keterangan',
    'deskripsi',
    'catatan',
    'detail',
    'nama_pengeluaran',
    'keperluan',
    'uraian'
]);

if(!$tanggalPengeluaranCol || !$jumlahPengeluaranCol || !$keteranganPengeluaranCol){
    die("
        <div style='font-family:Arial;padding:30px'>
            <h2>Struktur tabel pengeluaran belum sesuai</h2>
            <p>Kolom tersedia:</p>
            <pre>".implode(", ", $columns)."</pre>
        </div>
    ");
}

/* =========================
   FILTER QUERY
========================= */
$filterPembayaran = "1=1";
$filterPengeluaran = "1=1";

if($tanggal_mulai != '' && $tanggal_selesai != ''){

    $filterPembayaran = "
        tanggal_bayar BETWEEN '$tanggal_mulai'
        AND '$tanggal_selesai'
    ";

    $filterPengeluaran = "
        $tanggalPengeluaranCol BETWEEN '$tanggal_mulai'
        AND '$tanggal_selesai'
    ";
}

/* =========================
   LABEL PERIODE
========================= */
if($tanggal_mulai != '' && $tanggal_selesai != ''){

    $labelPeriode =
        date('d F Y', strtotime($tanggal_mulai))
        . ' - ' .
        date('d F Y', strtotime($tanggal_selesai));

}else{

    $labelPeriode = 'Semua Periode';
}

/* =========================
   TOTAL PEMASUKAN
========================= */
$totalPemasukan = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(jumlah_bayar) as total
    FROM pembayaran
    WHERE $filterPembayaran
    AND status_pembayaran = 'Lunas'
"))['total'] ?? 0;

/* =========================
   TOTAL PENGELUARAN
========================= */
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM($jumlahPengeluaranCol) as total
    FROM pengeluaran
    WHERE $filterPengeluaran
"))['total'] ?? 0;

$saldoBersih = ($totalPemasukan ?? 0) - ($totalPengeluaran ?? 0);

/* =========================
   DATA PEMBAYARAN
========================= */
$qPembayaran = mysqli_query($conn,"
    SELECT 
        pembayaran.tanggal_bayar as tanggal,
        pembayaran.jumlah_bayar as nominal,
        pembayaran.status_pembayaran as status,
        penghuni.nama_penghuni,
        kamar.nomor_kamar
    FROM pembayaran
    LEFT JOIN penghuni 
        ON pembayaran.id_penghuni = penghuni.id_penghuni
    LEFT JOIN kamar 
        ON pembayaran.id_kamar = kamar.id_kamar
    WHERE $filterPembayaran
");

/* =========================
   DATA PENGELUARAN
========================= */
$qPengeluaran = mysqli_query($conn,"
    SELECT 
        $tanggalPengeluaranCol as tanggal,
        $jumlahPengeluaranCol as nominal,
        $keteranganPengeluaranCol as keterangan
    FROM pengeluaran
    WHERE $filterPengeluaran
");

/* =========================
   GABUNG TRANSAKSI
========================= */
$transaksi = [];

while($p = mysqli_fetch_assoc($qPembayaran)){
    $transaksi[] = [
        'tanggal' => $p['tanggal'],
        'jenis' => 'Pemasukan',
        'keterangan' => 'Pembayaran kos ' . ($p['nama_penghuni'] ?? '-') . ' - Kamar ' . ($p['nomor_kamar'] ?? '-'),
        'pemasukan' => $p['status'] == 'Lunas' ? $p['nominal'] : 0,
        'pengeluaran' => 0,
        'status' => $p['status']
    ];
}

while($k = mysqli_fetch_assoc($qPengeluaran)){
    $transaksi[] = [
        'tanggal' => $k['tanggal'],
        'jenis' => 'Pengeluaran',
        'keterangan' => $k['keterangan'],
        'pemasukan' => 0,
        'pengeluaran' => $k['nominal'],
        'status' => '-'
    ];
}

usort($transaksi, function($a, $b){
    return strtotime($a['tanggal']) - strtotime($b['tanggal']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Kos</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #e5e7eb;
            color: #111827;
            margin: 0;
            padding: 24px;
            font-size: 12px;
        }

        .toolbar {
            max-width: 900px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }

        .toolbar-title {
            font-size: 14px;
            font-weight: bold;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            text-decoration: none;
            border: none;
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-back {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-print {
            background: #4f46e5;
            color: #ffffff;
        }

        .paper {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
            margin: 0 auto;
            padding: 18mm;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .kop {
            text-align: center;
            border-bottom: 3px double #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .kop h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #374151;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h2 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .judul p {
            margin: 6px 0 0 0;
            font-size: 12px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 16px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 120px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .report-table th {
            border: 1px solid #111827;
            padding: 7px 6px;
            text-align: center;
            background: #f3f4f6;
            font-weight: bold;
        }

        .report-table td {
            border: 1px solid #111827;
            padding: 6px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .summary {
            width: 45%;
            margin-left: auto;
            margin-top: 18px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #111827;
            padding: 7px;
        }

        .summary .label {
            font-weight: bold;
            background: #f3f4f6;
        }

        .signature {
            width: 100%;
            margin-top: 48px;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 72px;
        }

        .footer-note {
            margin-top: 18px;
            font-size: 11px;
            color: #4b5563;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .toolbar {
                display: none;
            }

            .paper {
                width: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            @page {
                size: A4 portrait;
                margin: 18mm;
            }
        }
    </style>
</head>

<body>

<!-- TOOLBAR -->
<div class="toolbar">
    <div>
        <div class="toolbar-title">Preview Cetak Laporan</div>
        <div>Periode <?= $labelPeriode ?></div>
    </div>

    <div class="toolbar-actions">
        <a href="index.php?tanggal_mulai=<?= $tanggal_mulai ?>&tanggal_selesai=<?= $tanggal_selesai ?>" class="btn btn-back">
            Kembali
        </a>

        <button onclick="window.print()" class="btn btn-print">
            Cetak
        </button>
    </div>
</div>

<!-- PAPER -->
<div class="paper">

    <!-- KOP -->
    <div class="kop">
        <h1>Sistem Manajemen Kos</h1>
        <p>Laporan Keuangan Pemasukan dan Pengeluaran</p>
        <p>Dicetak melalui aplikasi manajemen kos</p>
    </div>

    <!-- JUDUL -->
    <div class="judul">
        <h2>Laporan Keuangan</h2>
        <p>Periode: <?= $labelPeriode ?></p>
    </div>

    <!-- INFO -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Laporan</td>
            <td>: Laporan Keuangan Kos</td>
        </tr>
        <tr>
            <td class="label">Periode</td>
            <td>: <?= $labelPeriode ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td>: <?= date('d F Y') ?></td>
        </tr>
        <tr>
            <td class="label">Dicetak Oleh</td>
            <td>: <?= $_SESSION['admin_nama'] ?></td>
        </tr>
    </table>

    <!-- TABLE -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 90px;">Tanggal</th>
                <th style="width: 90px;">Jenis</th>
                <th>Keterangan</th>
                <th style="width: 120px;">Pemasukan</th>
                <th style="width: 120px;">Pengeluaran</th>
                <th style="width: 90px;">Status</th>
            </tr>
        </thead>

        <tbody>
            <?php if(count($transaksi) > 0){ ?>

                <?php $no = 1; ?>
                <?php foreach($transaksi as $t){ ?>

                    <tr>
                        <td class="text-center"><?= $no++ ?></td>

                        <td class="text-center">
                            <?= !empty($t['tanggal']) ? date('d/m/Y', strtotime($t['tanggal'])) : '-' ?>
                        </td>

                        <td class="text-center">
                            <?= $t['jenis'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($t['keterangan']) ?>
                        </td>

                        <td class="text-right">
                            <?= $t['pemasukan'] > 0 ? 'Rp ' . number_format($t['pemasukan'],0,',','.') : '-' ?>
                        </td>

                        <td class="text-right">
                            <?= $t['pengeluaran'] > 0 ? 'Rp ' . number_format($t['pengeluaran'],0,',','.') : '-' ?>
                        </td>

                        <td class="text-center">
                            <?= $t['status'] ?>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

    <!-- SUMMARY -->
    <table class="summary">
        <tr>
            <td class="label">Total Pemasukan</td>
            <td class="text-right">
                Rp <?= number_format($totalPemasukan ?? 0,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td class="label">Total Pengeluaran</td>
            <td class="text-right">
                Rp <?= number_format($totalPengeluaran ?? 0,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td class="label">Saldo Bersih</td>
            <td class="text-right font-bold">
                Rp <?= number_format($saldoBersih ?? 0,0,',','.') ?>
            </td>
        </tr>
    </table>

    <!-- NOTE -->
    <div class="footer-note">
        Catatan: Total pemasukan dihitung dari pembayaran dengan status lunas.
    </div>

    <!-- SIGNATURE -->
    <table class="signature">
        <tr>
            <td></td>
            <td>
                <?= date('d F Y') ?><br>
                Administrator,
                <div class="signature-space"></div>
                <strong><?= $_SESSION['admin_nama'] ?></strong>
            </td>
        </tr>
    </table>

</div>

<script>
/*
Kalau ingin otomatis muncul dialog print saat halaman dibuka,
aktifkan kode di bawah ini.
*/

// window.onload = function(){
//     window.print();
// }
</script>

</body>
</html>