<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="container section" style="max-width: 800px;">
    <div class="section-header center">
        <h2>Riwayat Pesanan</h2>
        <p>Pantau status pesananmu di sini.</p>
        <div class="divider"></div>
    </div>

    <div>
        <?php
        // Cek apakah ada session my_orders dari transaksi checkout sebelumnya
        if (!empty($_SESSION['my_orders'])) {
            $order_ids = implode(',', $_SESSION['my_orders']);
            $query_orders = mysqli_query($conn, "SELECT * FROM orders WHERE id_order IN ($order_ids) ORDER BY created_at DESC");
            
            if (mysqli_num_rows($query_orders) > 0) {
                while ($order = mysqli_fetch_assoc($query_orders)) {
                    // Ambil detail menu untuk pesanan ini
                    $id_order = $order['id_order'];
                    $query_detail = mysqli_query($conn, "SELECT od.jumlah, m.nama_menu FROM order_detail od JOIN menu m ON od.id_menu = m.id_menu WHERE od.id_order = $id_order");
                    $item_names = [];
                    while ($detail = mysqli_fetch_assoc($query_detail)) {
                        $item_names[] = $detail['jumlah'] . 'x ' . $detail['nama_menu'];
                    }
                    $menu_list = implode(', ', $item_names);
        ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <p class="order-id-label">ID Pesanan</p>
                            <p class="order-id-value">#GJ-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></p>
                        </div>
                        <span class="badge-warning" <?= ($order['status'] == 'selesai') ? 'style="background: #d1fae5; color: #059669;"' : '' ?>>
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <div class="order-body">
                        <div class="order-items">
                            <p class="order-items-text"><?= htmlspecialchars($menu_list) ?></p>
                            <p class="order-date"><?= date('d M Y • H:i', strtotime($order['created_at'])) ?> WIB</p>
                        </div>
                        <div class="order-total-wrap">
                            <p class="order-total-label">Total Bayar</p>
                            <p class="order-total-value">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
        <?php
                }
            }
        } else {
            echo '<div style="text-align: center; padding: 3rem; background: white; border-radius: 1rem; border: 1px solid var(--surface-border);"><p style="color: var(--text-muted); margin-bottom: 1rem;">Kamu belum memiliki riwayat pesanan.</p><a href="menu.php" class="btn-primary">Pesan Sekarang</a></div>';
        }
        ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>