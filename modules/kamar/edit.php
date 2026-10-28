<?php
session_start();
require_once '../../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: ../../auth/login.php");
    exit();
}

$id = $_GET['id'];

$query = mysqli_query($conn,"
    SELECT * FROM kamar
    WHERE id_kamar='$id'
");

$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location: index.php");
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
                Edit Kamar
            </h1>

            <p class="text-sm text-slate-500">
                Ubah data kamar kos
            </p>
        </div>

        <a href="index.php"
           class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center gap-2">

            <i class="fa-solid fa-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

<!-- CONTENT -->
<div class="pt-28 px-8 pb-10">

<form action="proses_edit.php" method="POST">

<input type="hidden" name="id_kamar" value="<?= $data['id_kamar'] ?>">

<!-- CARD -->
<div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">

    <!-- HEADER -->
    <div class="px-6 py-5 border-b border-slate-100">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-door-open"></i>
            </div>

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Form Edit Kamar
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Edit informasi kamar kos
                </p>
            </div>

        </div>

    </div>

    <!-- BODY -->
    <div class="p-6 space-y-6">

        <!-- NOMOR -->
        <div>

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Nomor Kamar
            </label>

            <div class="relative">

                <i class="fa-solid fa-door-closed absolute left-4 top-3.5 text-slate-400"></i>

                <input type="text"
                       name="nomor_kamar"
                       id="nomorKamar"
                       required
                       value="<?= $data['nomor_kamar'] ?>"
                       class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition">

            </div>

        </div>

        <!-- HARGA -->
        <div>

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Harga Kamar
            </label>

            <div class="relative">

                <span class="absolute left-4 top-3 text-slate-400 font-semibold">
                    Rp
                </span>

                <input type="number"
                       name="harga_kamar"
                       required
                       value="<?= $data['harga_kamar'] ?>"
                       class="w-full rounded-2xl border border-slate-200 pl-12 pr-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition">

            </div>

        </div>

        <!-- STATUS -->
        <div>

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Status Kamar
            </label>

            <div class="grid grid-cols-2 gap-4">

                <!-- KOSONG -->
                <label class="cursor-pointer">

                    <input type="radio"
                           name="status_kamar"
                           value="Kosong"
                           class="hidden peer"
                           <?= ($data['status_kamar']=='Kosong') ? 'checked' : '' ?>>

                    <div class="border border-slate-200 rounded-2xl p-4 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                            <div>
                                <h3 class="font-semibold text-slate-800 text-sm">
                                    Kosong
                                </h3>

                                <p class="text-xs text-slate-500">
                                    Tersedia
                                </p>
                            </div>

                        </div>

                    </div>

                </label>

                <!-- TERISI -->
                <label class="cursor-pointer">

                    <input type="radio"
                           name="status_kamar"
                           value="Terisi"
                           class="hidden peer"
                           <?= ($data['status_kamar']=='Terisi') ? 'checked' : '' ?>>

                    <div class="border border-slate-200 rounded-2xl p-4 peer-checked:border-rose-500 peer-checked:bg-rose-50 transition">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                                <i class="fa-solid fa-users"></i>
                            </div>

                            <div>
                                <h3 class="font-semibold text-slate-800 text-sm">
                                    Terisi
                                </h3>

                                <p class="text-xs text-slate-500">
                                    Digunakan
                                </p>
                            </div>

                        </div>

                    </div>

                </label>

            </div>

        </div>

        <!-- FASILITAS -->
        <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6">

            <div class="flex items-start gap-4">

                <div class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-wifi"></i>
                </div>

                <div class="flex-1">

                    <h3 class="text-lg font-bold text-slate-800 mb-4">
                        Fasilitas Kamar
                    </h3>

                    <!-- LIST -->
                    <div id="fasilitasContainer" class="flex flex-wrap gap-3"></div>

                    <p class="text-xs text-slate-400 mt-5">
                        Fasilitas otomatis berdasarkan nomor kamar.
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">

        <a href="index.php"
           class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white transition font-semibold text-sm">

            Batal

        </a>

        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-lg shadow-indigo-200 transition text-sm flex items-center gap-2">

            <i class="fa-solid fa-floppy-disk"></i>
            Simpan

        </button>

    </div>

</div>

</form>

</div>

</div>

<!-- JS FASILITAS -->
<script>

const nomorInput = document.getElementById('nomorKamar');
const fasilitasContainer = document.getElementById('fasilitasContainer');

function renderFasilitas(){

    const nomor = nomorInput.value.toUpperCase();

    let fasilitas = [];

    /* A1 - A10 */
    if(/^A([1-9]|10)$/.test(nomor)){

        fasilitas = [
            'AC',
            'Kamar Mandi Dalam',
            'WiFi',
            'Kasur',
            'Lemari'
        ];
    }

    /* A11 - A20 */
    else if(/^A(1[1-9]|20)$/.test(nomor)){

        fasilitas = [
            'Kipas',
            'WiFi',
            'Kasur',
            'Lemari'
        ];
    }

    /* B1 - B10 */
    else if(/^B([1-9]|10)$/.test(nomor)){

        fasilitas = [
            'AC',
            'Kamar Mandi Dalam',
            'WiFi',
            'Kasur',
            'Lemari'
        ];
    }

    /* B11 - B20 */
    else if(/^B(1[1-9]|20)$/.test(nomor)){

        fasilitas = [
            'Kipas',
            'WiFi',
            'Kasur',
            'Lemari'
        ];
    }

    fasilitasContainer.innerHTML = '';

    fasilitas.forEach(item => {

        fasilitasContainer.innerHTML += `
            <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm text-sm text-slate-700 font-medium">
                ${item}
            </div>
        `;
    });
}

/* realtime */
nomorInput.addEventListener('input', renderFasilitas);

/* first load */
renderFasilitas();

</script>

<?php include '../../includes/footer.php'; ?>