<?php
require_once '../config/database.php';

// Logika keranjang mandiri khusus halaman detail agar bisa tambah kuantitas (> 1) langsung
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_detail_cart'])) {
    $id_menu_post = intval($_POST['id_menu']);
    $qty_post = max(1, intval($_POST['qty']));
    $is_buy_now = isset($_POST['buy_now']);

    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    
    if (isset($_SESSION['cart'][$id_menu_post])) {
        $_SESSION['cart'][$id_menu_post] += $qty_post;
    } else {
        $_SESSION['cart'][$id_menu_post] = $qty_post;
    }

    if ($is_buy_now) {
        header("Location: checkout.php");
    } else {
        header("Location: keranjang.php");
    }
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: menu.php");
    exit;
}

$id_menu = intval($_GET['id']);

// Mengambil data menu sekaligus rata-rata ratingnya
$query = mysqli_query($conn, "SELECT m.*, COALESCE(AVG(r.rating), 5.0) AS avg_rating, COUNT(r.id_review) AS review_count FROM menu m LEFT JOIN review r ON r.id_menu = m.id_menu WHERE m.id_menu = $id_menu GROUP BY m.id_menu");
$menu = mysqli_fetch_assoc($query);

if (!$menu) {
    header("Location: menu.php");
    exit;
}

$avgRating = number_format((float) $menu['avg_rating'], 1);
$reviewCount = $menu['review_count'] > 0 ? $menu['review_count'] . " Penilaian" : "Belum ada penilaian";

include '../includes/header.php'; 
?>

<main>
    <!-- Bagian Detail Utama -->
    <section class="section" style="padding-top: 2rem;">
        <div class="container mx-auto px-3 sm:px-4">
            <!-- Breadcrumb -->
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">
                <a href="menu.php" style="transition: color 0.2s; display: flex; align-items: center; gap: 0.25rem;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
                    <span class="material-symbols-outlined" style="font-size: 1.125rem;">arrow_back</span>
                    Kembali
                </a>
                <div style="width: 1px; height: 1rem; background: var(--surface-border); margin: 0 0.5rem;"></div>
                <span><?= htmlspecialchars($menu['kategori']) ?></span>
                <span class="material-symbols-outlined" style="font-size: 1rem;">chevron_right</span>
                <span style="color: var(--primary); font-weight: 800;"><?= htmlspecialchars($menu['nama_menu']) ?></span>
            </div>

            <div class="grid-2" style="align-items: start;">
                <!-- Kiri: Gambar Produk -->
                <div style="position: relative; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); background: white;">
                    <img src="../assets/img/<?= htmlspecialchars($menu['gambar']) ?>" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" style="width: 100%; height: auto; aspect-ratio: 1/1; object-fit: cover; display: block;">
                <?php if ($avgRating >= 4.5): ?>
                <div style="position: absolute; top: 1.5rem; left: 1.5rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); padding: 0.5rem 1rem; border-radius: 9999px; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 8px 15px -3px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.5);">
                    <span class="material-symbols-outlined" style="color: #fb923c; font-size: 1.25rem; font-variation-settings: 'FILL' 1;">local_fire_department</span>
                    <span style="font-weight: 800; color: #ea580c; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Terlaris</span>
                </div>
                <?php endif; ?>
            </div>

                <!-- Kanan: Detail Produk -->
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; line-height: 1.2;"><?= htmlspecialchars($menu['nama_menu']) ?></h1>
                    
                    <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--surface-border);">
                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                            <span class="material-symbols-outlined" style="color: var(--orange-star); font-variation-settings: 'FILL' 1;">star</span>
                            <span style="font-weight: 800; font-size: 1.125rem; color: var(--text-main);"><?= $avgRating ?></span>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">(<?= $reviewCount ?>)</span>
                        <div style="width: 4px; height: 4px; border-radius: 50%; background: var(--text-muted); opacity: 0.3;"></div>
                        <span style="color: var(--primary); font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;">
                            <span class="material-symbols-outlined" style="font-size: 1.25rem;">verified</span> Pasti Nagih
                        </span>
                        <div style="width: 4px; height: 4px; border-radius: 50%; background: var(--text-muted); opacity: 0.3;"></div>
                        <span style="color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Sisa Stok: <?= $menu['stok'] ?></span>
                    </div>

                    <div style="font-size: 2rem; font-weight: 800; color: var(--primary); margin-bottom: 1.5rem;">
                        Rp <?= number_format($menu['harga'], 0, ',', '.') ?>
                    </div>

                    <p style="font-size: 1.125rem; color: var(--text-muted); margin-bottom: 2.5rem;">
                        <?= nl2br(htmlspecialchars($menu['deskripsi'])) ?>
                    </p>

                    <form action="" method="POST" style="background: white; border: 1px solid var(--surface-border); border-radius: 1.25rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 1.5rem;">
                        <input type="hidden" name="process_detail_cart" value="1">
                        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--surface); padding: 0.25rem; border-radius: 0.75rem; border: 1px solid var(--surface-border);">
                                <button type="button" id="btnMin" style="width: 2rem; height: 2rem; background: white; border: 1px solid var(--surface-border); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; color: var(--text-main);">-</button>
                                <input type="number" name="qty" id="inputQty" value="1" min="1" max="<?= $menu['stok'] > 0 ? $menu['stok'] : 1 ?>" style="width: 3rem; text-align: center; border: none; font-weight: 700; font-size: 1rem; outline: none; background: transparent; color: var(--primary);" readonly>
                                <button type="button" id="btnPlus" style="width: 2rem; height: 2rem; background: var(--primary); border: none; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; color: white;">+</button>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Bayar</div>
                                <div id="displayTotal" style="font-size: 1.25rem; font-weight: 800; color: var(--primary);">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <button type="submit" name="add_cart" class="btn-outline" style="flex: 1; border-color: var(--primary); color: var(--primary); display: flex; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; white-space: nowrap; border-radius: 9999px; transition: background 0.2s;" onmouseover="this.style.backgroundColor='var(--surface)'" onmouseout="this.style.backgroundColor='transparent'" <?= $menu['stok'] <= 0 ? 'disabled' : '' ?>>
                                <span class="material-symbols-outlined">add_shopping_cart</span> Keranjang
                            </button>
                            <button type="submit" name="buy_now" class="btn-primary" style="flex: 1; justify-content: center; white-space: nowrap;" <?= $menu['stok'] <= 0 ? 'disabled' : '' ?>>
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">bolt</span> Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Bagian Rekomendasi "Lengkapi Hidanganmu" -->
    <section class="section section-bg-surface">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="section-header" style="align-items: center;">
                <div>
                    <h2>Lengkapi Hidanganmu</h2>
                    <p>Menu pelengkap yang cocok dengan <?= htmlspecialchars($menu['nama_menu']) ?></p>
                </div>
                <a href="menu.php" class="nav-link" style="color: var(--primary); display: flex; align-items: center; font-weight: 700;">
                    Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            
            <div class="grid-4">
                <?php
                // Mengambil 4 menu acak selain menu yang sedang dibuka
                $q_rek = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu != $id_menu ORDER BY RAND() LIMIT 4");
                while($rek = mysqli_fetch_assoc($q_rek)):
                ?>
                <a href="detail_menu.php?id=<?= $rek['id_menu'] ?>" class="menu-card" style="display: block; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="menu-card-img" style="border-radius: 1rem 1rem 0 0;">
                        <img src="../assets/img/<?= htmlspecialchars($rek['gambar']) ?>" alt="<?= htmlspecialchars($rek['nama_menu']) ?>">
                    </div>
                    <div class="menu-card-body" style="padding: 1rem;">
                        <h3 style="font-size: 1rem; color: var(--text-main); font-weight: 700; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($rek['nama_menu']) ?></h3>
                        <div style="font-weight: 800; color: var(--primary);">Rp <?= number_format($rek['harga'], 0, ',', '.') ?></div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnMin = document.getElementById('btnMin');
    const btnPlus = document.getElementById('btnPlus');
    const inputQty = document.getElementById('inputQty');
    const displayTotal = document.getElementById('displayTotal');
    
    const maxStok = <?= $menu['stok'] ?>;
    const hargaSatuan = <?= $menu['harga'] ?>;

    function updateTotal() {
        let qty = parseInt(inputQty.value) || 1;
        let total = qty * hargaSatuan;
        displayTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    btnMin.addEventListener('click', () => { if(inputQty.value > 1) { inputQty.value--; updateTotal(); } });
    btnPlus.addEventListener('click', () => { if(inputQty.value < maxStok) { inputQty.value++; updateTotal(); } });
});
</script>

<?php include '../includes/footer.php'; ?>