<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan halaman admin tidak disimpan dalam cache browser
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login_admin.php");
    exit();
}

// Waktu tidak aktif maksimal dalam detik (3 jam = 10800 detik)
$timeout_duration = 10800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login_admin.php?error=timeout");
    exit();
}

// Perbarui waktu aktivitas terakhir untuk setiap request
$_SESSION['last_activity'] = time();
