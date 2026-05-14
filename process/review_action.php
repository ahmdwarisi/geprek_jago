<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['my_orders'])) {
    header('Location: ../pages/pesanan.php?review=error');
    exit;
}

$id_menu = intval($_POST['id_menu'] ?? 0);
$nama = trim($_POST['nama'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$komentar = trim($_POST['komentar'] ?? '');

if ($rating < 1 || $rating > 5) {
    header('Location: ../pages/pesanan.php?review=error');
    exit;
}

$order_ids = implode(',', array_map('intval', $_SESSION['my_orders']));
$valid_query = mysqli_query($conn, "SELECT nama_pelanggan FROM orders WHERE id_order IN ($order_ids) AND status = 'selesai' ORDER BY created_at DESC LIMIT 1");

if (! $valid_query) {
    header('Location: ../pages/pesanan.php?review=error');
    exit;
}

$valid_data = mysqli_fetch_assoc($valid_query);
if (empty($valid_data['nama_pelanggan'])) {
    header('Location: ../pages/pesanan.php?review=error');
    exit;
}

$nama_db = mysqli_real_escape_string($conn, $valid_data['nama_pelanggan']);
$komentar_db = mysqli_real_escape_string($conn, $komentar);

$insert = mysqli_query($conn, "INSERT INTO review (id_menu, nama, rating, komentar) VALUES (NULL, '$nama_db', $rating, '$komentar_db')");

if ($insert) {
    header('Location: ../pages/pesanan.php?review=success');
    exit;
}

header('Location: ../pages/pesanan.php?review=error');
exit;
