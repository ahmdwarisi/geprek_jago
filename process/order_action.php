<?php
session_start();
require_once '../config/database.php';

// Proteksi: Hanya admin yang bisa mengubah status pesanan
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/login_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_order = intval($_POST['id_order']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if ($id_order > 0 && in_array($status, ['pending', 'diproses', 'selesai'])) {
        // Cek status saat ini di database
        $query = mysqli_query($conn, "SELECT status FROM orders WHERE id_order = $id_order");
        if ($row = mysqli_fetch_assoc($query)) {
            $current_status = $row['status'];
            
            // Aturan: Tidak bisa mundur dari 'selesai', dan tidak bisa mundur dari 'diproses' ke 'pending'
            $allow_update = true;
            if ($current_status === 'selesai') $allow_update = false;
            if ($current_status === 'diproses' && $status === 'pending') $allow_update = false;
            
            if ($allow_update) {
                mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id_order = $id_order");
            }
        }
    }
}

// Redirect kembali ke halaman sebelumnya dengan mempertahankan filter (referrer)
$redirect_url = $_SERVER['HTTP_REFERER'] ?? '../admin/kelola_pesanan.php';
header("Location: $redirect_url");
exit();