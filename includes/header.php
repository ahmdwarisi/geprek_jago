<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Geprek Jago - Selera Nusantara, Kualitas Juara</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="index.php" class="navbar-logo">Geprek Jago</a>
            
            <div class="nav-links">
                <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
                <a href="menu.php" class="nav-link <?= ($current_page == 'menu.php') ? 'active' : '' ?>">Menu</a>
                <a href="pesanan.php" class="nav-link <?= ($current_page == 'pesanan.php') ? 'active' : '' ?>">Pesanan</a>
                <a href="kontak.php" class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : '' ?>">Kontak</a>
            </div>

            <div class="nav-actions">
                <button class="nav-toggle" id="navToggle" type="button" aria-label="Buka menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <a href="keranjang.php" class="cart-btn">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span class="cart-badge"><?= $cart_count ?></span>
                </a>
            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('navToggle');
            const navLinks = document.querySelector('.nav-links');
            navToggle?.addEventListener('click', function() {
                navLinks?.classList.toggle('mobile-open');
            });
        });
    </script>