<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catatan = trim($_POST['catatan'] ?? '');

    if (!empty($catatan)) {
        $_SESSION['cart_deskripsi'] = $catatan;
    } else {
        unset($_SESSION['cart_deskripsi']);
    }
}

header('Location: ../pages/keranjang.php');
exit;
