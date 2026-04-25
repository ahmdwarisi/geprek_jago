<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_menu = intval($_POST['id_menu'] ?? 0);

    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Ambil stok terbaru dari database
    $stok_tersedia = 0;
    if ($id_menu > 0) {
        $query = mysqli_query($conn, "SELECT stok FROM menu WHERE id_menu = $id_menu");
        if ($row = mysqli_fetch_assoc($query)) {
            $stok_tersedia = $row['stok'];
        }
    }

    // Logika Tambah Item
    if ($action === 'add' && $id_menu > 0) {
        $current_qty = isset($_SESSION['cart'][$id_menu]) ? $_SESSION['cart'][$id_menu] : 0;
        
        if ($current_qty < $stok_tersedia) {
            $_SESSION['cart'][$id_menu] = $current_qty + 1;
        } else {
            if (isset($_POST['ajax'])) {
                if (ob_get_length()) ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Maaf, stok menu ini tersisa ' . $stok_tersedia . ' porsi.']);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Maaf, stok menu ini tersisa ' . $stok_tersedia . ' porsi.';
            }
        }
    }
    
    // Logika Kurangi Item
    if ($action === 'decrement' && $id_menu > 0) {
        if (isset($_SESSION['cart'][$id_menu])) {
            if ($_SESSION['cart'][$id_menu] > 1) {
                $_SESSION['cart'][$id_menu] -= 1;
            } else {
                unset($_SESSION['cart'][$id_menu]);
            }
        }
    }

    // Logika Hapus Item
    if ($action === 'remove' && $id_menu > 0) {
        if (isset($_SESSION['cart'][$id_menu])) {
            unset($_SESSION['cart'][$id_menu]);
        }
    }

    // Jika request melalui AJAX (tanpa reload), kembalikan data JSON
    if (isset($_POST['ajax'])) {
        if (ob_get_length()) ob_clean(); // Bersihkan output error/spasi nyasar
        header('Content-Type: application/json'); // Paksa browser membaca sebagai JSON
        $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
        echo json_encode(['status' => 'success', 'cart_count' => $cart_count]);
        exit;
    }

    // Redirect kembali ke halaman sebelumnya (referrer)
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? '../pages/menu.php';
    header("Location: $redirect_url");
    exit;
}