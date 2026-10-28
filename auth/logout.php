<?php
session_start();

/* =========================
   CEK SESSION AKTIF
========================= */
if(isset($_SESSION['admin_id'])){

    /* =========================
       KOSONGKAN SESSION
    ========================= */
    $_SESSION = [];

    /* =========================
       HAPUS COOKIE SESSION
    ========================= */
    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );

    }

    /* =========================
       HANCURKAN SESSION
    ========================= */
    session_destroy();
}

/* =========================
   REDIRECT KE LOGIN
========================= */
header("Location: login.php");
exit();
?>