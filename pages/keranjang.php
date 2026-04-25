<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="section">
    <div class="container">
        <?php
    $subtotal = 0;
    $cart_items_data = []; // Menyimpan data menu yang ada di keranjang
    if (!empty($_SESSION['cart'])) {
        $cart_ids = implode(',', array_keys($_SESSION['cart']));
        $query = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu IN ($cart_ids)");
        
        while ($row = mysqli_fetch_assoc($query)) {
            $id_menu = $row['id_menu'];
            $qty = $_SESSION['cart'][$id_menu];
            $total_harga_item = $row['harga'] * $qty;
            $subtotal += $total_harga_item;
            $cart_items_data[] = ['row' => $row, 'qty' => $qty, 'total' => $total_harga_item];
        }
    }
        ?>
        <div class="section-header">
        <h2>Keranjang Belanja</h2>
    </div>

    <div class="cart-layout">
        <div class="cart-items">
            <?php
            if (!empty($cart_items_data)) {
                foreach ($cart_items_data as $item) {
                    $row = $item['row'];
                    $id_menu = $row['id_menu'];
                    $qty = $item['qty'];
                    $total_harga_item = $item['total'];
            ?>
                <div class="cart-item">
                    <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_menu']) ?>" class="cart-item-img">
                    <div class="cart-item-details">
                        <h3 class="cart-item-title"><?= htmlspecialchars($row['nama_menu']) ?></h3>
                        <p class="cart-item-desc"><?= htmlspecialchars($row['kategori']) ?></p>
                    </div>
                    
                    <div class="cart-item-actions" style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem;">
                        <div class="cart-item-qty" style="box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-radius: 8px; background: white; padding: 0.25rem;">
                            <form action="../process/cart_action.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="decrement">
                                <input type="hidden" name="id_menu" value="<?= $id_menu ?>">
                                <button type="submit" class="qty-btn">-</button>
                            </form>
                            <span style="font-weight: 700; width: 1.5rem; text-align: center;"><?= $qty ?></span>
                            <form action="../process/cart_action.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id_menu" value="<?= $id_menu ?>">
                                <button type="submit" class="qty-btn">+</button>
                            </form>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="text-align: right;">
                                <div style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600;">Subtotal</div>
                                <div style="color: var(--primary); font-weight: 800; font-size: 1rem;">Rp <?= number_format($total_harga_item, 0, ',', '.') ?></div>
                            </div>
                            <form action="../process/cart_action.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="id_menu" value="<?= $id_menu ?>">
                                <button type="submit" class="btn-remove" title="Hapus dari keranjang">
                                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<div style="text-align: center; padding: 3rem; background: white; border-radius: 1rem; border: 1px solid var(--surface-border);"><p style="color: var(--text-muted); margin-bottom: 1rem;">Keranjang belanja Anda masih kosong.</p><a href="menu.php" class="btn-primary">Mulai Belanja</a></div>';
            }
            ?>
        </div>

        <div class="cart-summary">
            <h3 class="summary-title" style="color: var(--primary);">Ringkasan Pesanan</h3>
            
            <?php if (!empty($cart_items_data)): ?>
                <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #ccc; color: #666;">
                    <?php foreach ($cart_items_data as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <span style="color: var(--text-main); font-weight: 500;"><?= $item['qty'] > 1 ? $item['qty'] . 'x ' : '' ?><?= htmlspecialchars($item['row']['nama_menu']) ?></span>
                        <span style="font-weight: 600;">Rp <?= number_format($item['total'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="summary-total">
                <span>Total</span>
                <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>
            <?php if (!empty($_SESSION['cart'])): ?>
                <a href="checkout.php" class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem; text-align: center; display: block; box-sizing: border-box; border-radius: 8px;">
                    Lanjut ke Pembayaran
                </a>
            <?php else: ?>
                <button class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem; opacity: 0.5; cursor: not-allowed; border-radius: 8px;" disabled>
                    Lanjut ke Pembayaran
                </button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php if (isset($_SESSION['error_msg'])): ?>
<script>
    alert("<?= htmlspecialchars($_SESSION['error_msg']) ?>");
</script>
<?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>