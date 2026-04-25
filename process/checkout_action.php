<?php
session_start();
require_once '../config/database.php';

// Pastikan request adalah POST dan keranjang tidak kosong
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    
    // 1. Tangkap & bersihkan input form
    $nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan'] ?? '');
    $no_hp          = mysqli_real_escape_string($conn, $_POST['no_hp'] ?? '');
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $jarak_km       = floatval($_POST['jarak_km'] ?? 0);
    
    // Sesuaikan nilai form HTML dengan nilai ENUM di database
    $order_method = $_POST['order_method'] ?? 'dine_in';
    $db_metode_pengiriman = ($order_method === 'delivery') ? 'delivery' : 'makan_di_tempat';
    
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $db_metode_pembayaran = ($payment_method === 'bank_transfer') ? 'transfer' : $payment_method;

    // 2. Hitung ulang total harga (keamanan backend)
    $subtotal = 0;
    $cart_ids = implode(',', array_keys($_SESSION['cart']));
    $query_menu = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu IN ($cart_ids)");
    $menu_data = [];
    
    while ($row = mysqli_fetch_assoc($query_menu)) {
        $qty = $_SESSION['cart'][$row['id_menu']];
        $subtotal += $row['harga'] * $qty;
        $menu_data[$row['id_menu']] = $row;
    }

    $ongkir = ($db_metode_pengiriman === 'delivery') ? ($jarak_km * 3000) : 0;
    $total_harga = $subtotal + $ongkir;

    // 3. Simpan ke tabel 'orders'
    $query_order = "INSERT INTO orders (nama_pelanggan, no_hp, alamat, metode_pengiriman, metode_pembayaran, total_harga, status) 
                    VALUES ('$nama_pelanggan', '$no_hp', '$alamat', '$db_metode_pengiriman', '$db_metode_pembayaran', '$total_harga', 'pending')";
    
    if (mysqli_query($conn, $query_order)) {
        $id_order = mysqli_insert_id($conn); // Ambil ID pesanan yang baru saja dibuat

        // Simpan ID ini ke session sementara agar pembeli (guest) bisa melihat riwayat pesanannya di halaman pesanan.php
        if (!isset($_SESSION['my_orders'])) { $_SESSION['my_orders'] = []; }
        $_SESSION['my_orders'][] = $id_order;

        // 4. Simpan item ke tabel 'order_detail' dan kurangi stok
        foreach ($_SESSION['cart'] as $id_menu => $qty) {
            $harga_satuan = $menu_data[$id_menu]['harga'];
            $subtotal_item = $harga_satuan * $qty;
            
            mysqli_query($conn, "INSERT INTO order_detail (id_order, id_menu, jumlah, harga, subtotal) 
                                 VALUES ('$id_order', '$id_menu', '$qty', '$harga_satuan', '$subtotal_item')");
                                 
            // Kurangi stok menu di database sesuai jumlah yang dipesan
            mysqli_query($conn, "UPDATE menu SET stok = GREATEST(0, stok - $qty) WHERE id_menu = '$id_menu'");
        }

        // 5. Kosongkan keranjang & Arahkan ke halaman riwayat pesanan
        unset($_SESSION['cart']);
        header("Location: ../pages/pesanan.php?status=success");
        exit;
    } else {
        die("Terjadi kesalahan pada database: " . mysqli_error($conn));
    }
} else {
    header("Location: ../pages/menu.php");
}