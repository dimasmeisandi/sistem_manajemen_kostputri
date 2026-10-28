<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($conn, trim($_POST['username']));
    $pass_input = trim($_POST['password']);

    /*
        Ambil data admin berdasarkan username saja.
        Password dicek setelah data ditemukan.
    */
    $query = "
        SELECT *
        FROM admin
        WHERE username = '$user_input'
        LIMIT 1
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);
            $password_db = $row['password'];

            $loginValid = false;

            /*
                Support password hash:
                untuk password yang disimpan pakai password_hash()
            */
            if (password_verify($pass_input, $password_db)) {
                $loginValid = true;
            }

            /*
                Support password plain text:
                kalau ada data lama yang belum hash
            */
            if ($pass_input === $password_db) {
                $loginValid = true;
            }

            if ($loginValid) {
                $_SESSION['admin_id'] = $row['id_admin']; 
                $_SESSION['admin_nama'] = $row['nama_admin'];
                $_SESSION['admin_username'] = $row['username'];

                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = 'Username atau password salah!';
            }

        } else {
            $error = 'Username atau password salah!';
        }

    } else {
        die("Query Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Kos Telang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 bg-indigo-600 rounded-2xl items-center justify-center shadow-xl shadow-indigo-200 mb-4">
                <span class="text-white font-black text-2xl">MK</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h1>
            <p class="text-gray-500 mt-1 text-sm">Masuk untuk mengelola Kos Telang UTM</p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
            <?php if ($error): ?>
                <div class="bg-rose-50 text-rose-600 px-4 py-3 rounded-xl text-sm font-bold mb-6 border border-rose-100 text-center">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-2 ml-1">Username</label>
                    <input type="text" name="username" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 outline-none transition-all text-sm font-medium" 
                        placeholder="Masukkan username">
                </div>

                <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 outline-none transition-all text-sm font-medium" 
                        placeholder="Masukkan Password">
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" 
                            class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition-all cursor-pointer">
                        <label for="remember" class="ml-2 text-xs font-semibold text-slate-500 cursor-pointer select-none">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="lupa_password.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Lupa Password?
                    </a>
                </div>

                <div class="pt-2">
                    <button type="submit" name="login"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-[0.98]">
                        Login
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-400 text-sm mt-8 font-medium">
            &copy; 2026 Kelompok 1 - Sistem Informasi UTM
        </p>
    </div>

</body>
</html>