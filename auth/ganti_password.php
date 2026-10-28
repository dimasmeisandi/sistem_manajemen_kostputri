<?php
session_start();
require_once '../config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$activePage = '';

include '../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>

<div class="ml-72 min-h-screen bg-slate-50">

<!-- =========================
     TOPBAR
========================= -->
<div class="fixed top-0 left-72 right-0 bg-white/80 backdrop-blur border-b border-slate-200 px-8 py-5 z-40">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Ganti Password
            </h1>

            <p class="text-sm text-slate-500">
                Perbarui password akun administrator
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

    <!-- ALERT -->
    <?php if(isset($_GET['status'])){ ?>

        <?php if($_GET['status'] == 'success'){ ?>

            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <span class="font-semibold text-sm">
                    Password berhasil diperbarui.
                </span>
            </div>

        <?php } ?>

        <?php if($_GET['status'] == 'wrong'){ ?>

            <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark"></i>
                <span class="font-semibold text-sm">
                    Password lama tidak sesuai.
                </span>
            </div>

        <?php } ?>

        <?php if($_GET['status'] == 'notmatch'){ ?>

            <div class="mb-6 bg-amber-50 border border-amber-100 text-amber-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="font-semibold text-sm">
                    Konfirmasi password baru tidak sama.
                </span>
            </div>

        <?php } ?>

        <?php if($_GET['status'] == 'empty'){ ?>

            <div class="mb-6 bg-amber-50 border border-amber-100 text-amber-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="font-semibold text-sm">
                    Semua field wajib diisi.
                </span>
            </div>

        <?php } ?>

        <?php if($_GET['status'] == 'short'){ ?>

            <div class="mb-6 bg-amber-50 border border-amber-100 text-amber-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="font-semibold text-sm">
                    Password baru minimal 6 karakter.
                </span>
            </div>

        <?php } ?>

        <?php if($_GET['status'] == 'failed'){ ?>

            <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-5 py-4 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark"></i>
                <span class="font-semibold text-sm">
                    Password gagal diperbarui.
                </span>
            </div>

        <?php } ?>

    <?php } ?>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- FORM CARD -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- HEADER -->
            <div class="px-6 py-5 border-b border-slate-100">

                <h2 class="font-bold text-slate-800 text-lg">
                    Form Ganti Password
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Masukkan password lama dan password baru kamu.
                </p>

            </div>

            <!-- FORM -->
            <form action="proses_ganti_password.php" method="POST" class="p-6 space-y-5">

                <!-- PASSWORD LAMA -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Lama
                    </label>

                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="password"
                            name="password_lama"
                            id="password_lama"
                            placeholder="Masukkan password lama"
                            class="w-full pl-11 pr-12 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                        <button
                            type="button"
                            onclick="togglePassword('password_lama', this)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- PASSWORD BARU -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Baru
                    </label>

                    <div class="relative">
                        <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="password"
                            name="password_baru"
                            id="password_baru"
                            placeholder="Minimal 6 karakter"
                            class="w-full pl-11 pr-12 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                        <button
                            type="button"
                            onclick="togglePassword('password_baru', this)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- KONFIRMASI -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Konfirmasi Password Baru
                    </label>

                    <div class="relative">
                        <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="password"
                            name="konfirmasi_password"
                            id="konfirmasi_password"
                            placeholder="Ulangi password baru"
                            class="w-full pl-11 pr-12 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                        <button
                            type="button"
                            onclick="togglePassword('konfirmasi_password', this)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">

                    <button
                        type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2">

                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Password

                    </button>

                    <a href="../dashboard.php"
                       class="px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-semibold text-sm flex items-center justify-center gap-2">

                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali

                    </a>

                </div>

            </form>

        </div>

        <!-- INFO CARD -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 h-fit">

            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>

            <h3 class="font-bold text-slate-800 text-lg">
                Tips Keamanan
            </h3>

            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Gunakan password yang mudah diingat tetapi sulit ditebak.
            </p>

            <div class="mt-5 space-y-3">

                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mt-0.5">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">
                        Minimal 6 karakter.
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mt-0.5">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">
                        Kombinasikan huruf dan angka.
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mt-0.5">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">
                        Jangan gunakan password yang sama dengan akun lain.
                    </p>
                </div>

            </div>

        </div>

    </div>

</main>

</div>

<script>
function togglePassword(id, btn){
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');

    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }else{
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include '../includes/footer.php'; ?>