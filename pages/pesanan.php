<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="container section" style="max-width: 800px;">
    <div class="section-header center">
        <h2>Riwayat Pesanan</h2>
        <p>Pantau status ayam geprek favoritmu di sini.</p>
        <div class="divider"></div>
    </div>

    <div>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <p class="order-id-label">ID Pesanan</p>
                    <p class="order-id-value">#GJ-100293</p>
                </div>
                <span class="badge-warning">Sedang Disiapkan</span>
            </div>
            <div class="order-body">
                <div class="order-items">
                    <p class="order-items-text">1x Geprek Original, 1x Es Teh Manis</p>
                    <p class="order-date">23 April 2026 • 19:45 WIB</p>
                </div>
                <div class="order-total-wrap">
                    <p class="order-total-label">Total Bayar</p>
                    <p class="order-total-value">Rp 28.000</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>