<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="container mx-auto px-3 sm:px-4 section">
    <div class="section-header center">
        <h2>Riwayat Pesanan</h2>
        <p>Pantau status pesananmu di sini.</p>
        <div class="divider"></div>
    </div>

    <div>
        <?php
        $review_status = $_GET['review'] ?? '';
        if ($review_status === 'success') {
            echo '<div class="alert-success" style="margin-bottom:1.5rem; padding:1rem; border-radius:1rem; background:#ddf6e5; color:#064c1d;">Terima kasih! Reviewmu sudah dikirim dan akan muncul di halaman Home.</div>';
        } elseif ($review_status === 'error') {
            echo '<div class="alert-error" style="margin-bottom:1.5rem; padding:1rem; border-radius:1rem; background:#fee2e2; color:#991b1b;">Gagal mengirim review. Silakan coba lagi.</div>';
        }

        // Cek apakah ada session my_orders dari transaksi checkout sebelumnya
        $has_completed_order = false;
        $review_customer_name = '';
        $modals_html = '';
        if (!empty($_SESSION['my_orders'])) {
            $order_ids = implode(',', array_map('intval', $_SESSION['my_orders']));
            $query_orders = mysqli_query($conn, "SELECT * FROM orders WHERE id_order IN ($order_ids) ORDER BY created_at DESC");
            
            if (mysqli_num_rows($query_orders) > 0) {
                while ($order = mysqli_fetch_assoc($query_orders)) {
                    if ($order['status'] === 'selesai') {
                        $has_completed_order = true;
                        if (empty($review_customer_name)) {
                            $review_customer_name = $order['nama_pelanggan'];
                        }
                    }

                    // Ambil detail menu untuk pesanan ini
                    $id_order = $order['id_order'];
                    $query_detail = mysqli_query($conn, "SELECT od.*, m.nama_menu FROM order_detail od JOIN menu m ON od.id_menu = m.id_menu WHERE od.id_order = $id_order");
                    $item_names = [];
                    $detail_items_html = '';
                    $subtotal_items = 0;
                    while ($detail = mysqli_fetch_assoc($query_detail)) {
                        $item_names[] = $detail['jumlah'] . 'x ' . $detail['nama_menu'];
                        $detail_items_html .= '
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <div><span style="font-weight: 700;">'.$detail['jumlah'].'x</span> '.htmlspecialchars($detail['nama_menu']).'</div>
                            <div style="font-weight: 600;">Rp '.number_format($detail['subtotal'], 0, ',', '.').'</div>
                        </div>';
                        $subtotal_items += $detail['subtotal'];
                    }
                    $menu_list = implode(', ', $item_names);
                    
                    $ongkir = $order['total_harga'] - $subtotal_items;
                    $metode_pengiriman = $order['metode_pengiriman'] == 'delivery' ? 'Take Away' : 'Dine In';
                    $metode_pembayaran = strtoupper($order['metode_pembayaran']);

                    $label_lokasi = $order['metode_pengiriman'] == 'delivery' ? 'Catatan Pesanan' : 'Nomor Meja / Catatan';
                    $alamat_pesanan = htmlspecialchars($order['alamat'] ?: '-');

                    $modals_html .= '
                    <div id="modalOrderDetail'.$id_order.'" class="modal-overlay" role="dialog" aria-modal="true">
                        <div class="modal-content" style="max-width: 500px;">
                            <div class="modal-header">
                                <h3>Detail Pesanan #GJ-'.str_pad($id_order, 4, '0', STR_PAD_LEFT).'</h3>
                                <button type="button" class="btn-icon" onclick="document.getElementById(\'modalOrderDetail'.$id_order.'\').classList.remove(\'active\')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">×</button>
                            </div>
                            <div class="modal-body">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                    <div>
                                        <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Metode Makan</p>
                                        <div style="font-weight: 600; font-size: 0.875rem;">'.$metode_pengiriman.'</div>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Pembayaran</p>
                                        <div style="font-weight: 600; font-size: 0.875rem;">'.$metode_pembayaran.'</div>
                                    </div>
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">'.$label_lokasi.'</p>
                                    <div style="font-weight: 600; font-size: 0.875rem; line-height: 1.4;">'.$alamat_pesanan.'</div>
                                </div>
                                <div>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Daftar Pesanan</p>
                                    '.$detail_items_html;
                                    
                    if ($ongkir > 0) {
                        $modals_html .= '
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                                        <div><span style="font-weight: 700;">Ongkos Kirim</span></div>
                                        <div style="font-weight: 600;">Rp '.number_format($ongkir, 0, ',', '.').'</div>
                                    </div>';
                    }
                
                    $modals_html .= '
                                    <div style="border-top: 1px dashed var(--surface-border); margin-top: 1rem; padding-top: 1rem; display: flex; justify-content: space-between; font-weight: 800; color: var(--primary); font-size: 1.125rem;">
                                        <span>Total Harga</span>
                                        <span>Rp '.number_format($order['total_harga'], 0, ',', '.').'</span>
                                    </div>';
                                    
                    $modals_html .= '
                                </div>
                            </div>
                        </div>
                    </div>';
        ?>
                <div class="order-card" onclick="document.getElementById('modalOrderDetail<?= $order['id_order'] ?>').classList.add('active')" style="cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 4px 12px rgba(13,68,41,0.1)';" onmouseout="this.style.borderColor=''; this.style.boxShadow='';">
                    <div class="order-header">
                        <div>
                            <p class="order-id-label">ID Pesanan</p>
                            <p class="order-id-value">#GJ-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></p>
                        </div>
                        <span class="badge-warning" <?php if($order['status'] == 'selesai') echo 'style="background: #d1fae5; color: #059669;"'; elseif($order['status'] == 'batal') echo 'style="background: #fee2e2; color: #dc2626;"'; elseif($order['status'] == 'diproses') echo 'style="background: #dbeafe; color: #2563eb;"'; ?>>
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <div class="order-body">
                        <div class="order-items">
                            <p class="order-items-text"><?= htmlspecialchars($menu_list) ?></p>
                            <p class="order-date"><?= date('d M Y • H:i', strtotime($order['created_at'])) ?> WIB</p>
                        </div>
                        <div class="order-total-wrap" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--surface-border); margin-top: 1rem; padding-top: 1rem;">
                            <div>
                                <p class="order-total-label" style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;">Total Bayar</p>
                                <p class="order-total-value" style="font-size: 1.125rem; font-weight: 800; color: var(--primary);">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
                            </div>
                            <span class="material-symbols-outlined" style="color: var(--text-muted);">chevron_right</span>
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

        <?php if ($has_completed_order): ?>
            <div style="margin-top:2rem; text-align:center;">
                <button id="openReviewBtn" class="btn-primary" style="width:auto;">Beri Rating & Ulasan</button>
            </div>
        <?php elseif (!empty($_SESSION['my_orders'])): ?>
            <div style="margin-top:2rem; text-align:center; color: var(--text-muted);">
                <p>Untuk memberi rating, pastikan pesanan berstatus selesai terlebih dahulu.</p>
            </div>
        <?php endif; ?>
    </div>

    <?= $modals_html ?? '' ?>

    <div id="reviewModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="reviewModalTitle">Beri Rating & Ulasan</h3>
                <button type="button" id="closeReviewModal" class="btn-icon" aria-label="Tutup" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">×</button>
            </div>
            <form id="reviewForm" action="../process/review_action.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-input" value="<?= htmlspecialchars($review_customer_name) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating Bintang</label>
                        <div id="ratingStars" style="display:flex; gap:0.25rem;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" class="star-btn" data-value="<?= $i ?>" style="border:none; background:transparent; cursor:pointer; font-size:2rem; color:#d1d5db;">★</button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Komentar (opsional)</label>
                        <textarea name="komentar" class="form-input" rows="4" placeholder="Tulis pengalamanmu... (tidak wajib)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelReviewBtn" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Kirim Review</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('openReviewBtn');
        const reviewModal = document.getElementById('reviewModal');
        const closeBtn = document.getElementById('closeReviewModal');
        const cancelBtn = document.getElementById('cancelReviewBtn');
        const ratingStars = document.querySelectorAll('#ratingStars .star-btn');
        const ratingInput = document.getElementById('ratingInput');

        function setRating(value) {
            ratingInput.value = value;
            ratingStars.forEach((btn) => {
                btn.style.color = (parseInt(btn.dataset.value, 10) <= value) ? '#f59e0b' : '#d1d5db';
            });
        }

        if (openBtn) {
            openBtn.addEventListener('click', function() {
                reviewModal.classList.add('active');
                setRating(5);
            });
        }

        [closeBtn, cancelBtn].forEach((button) => {
            button?.addEventListener('click', function() {
                reviewModal.classList.remove('active');
            });
        });

        ratingStars.forEach((star) => {
            star.addEventListener('click', function() {
                setRating(parseInt(this.dataset.value, 10));
            });
        });

    // Menutup semua modal jika mengklik di luar area modal content
    document.querySelectorAll('.modal-overlay').forEach(function(modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>