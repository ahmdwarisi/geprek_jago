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
