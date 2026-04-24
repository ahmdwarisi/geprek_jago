<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="container section">
    <div class="section-header">
        <h2>Keranjang Belanja</h2>
    </div>

    <div class="cart-layout">
        <div class="cart-items">
            <!-- Item 1 (Contoh Data Statis) -->
            <div class="cart-item">
                <img src="../assets/img/ayam3.jpg" alt="Menu" class="cart-item-img">
                <div class="cart-item-details">
                    <h3 class="cart-item-title">Geprek Mozzarella</h3>
                    <p class="cart-item-desc">Ayam geprek + mozzarella</p>
                    <div class="cart-item-qty">
                        <button class="qty-btn">-</button>
                        <span style="font-weight: 700;">1</span>
                        <button class="qty-btn">+</button>
                    </div>
                </div>
                <div class="cart-item-price-wrap">
                    <p class="cart-item-price">Rp 20.000</p>
                    <button class="btn-remove">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem;">delete</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="cart-summary">
            <h3 class="summary-title">Ringkasan Pesanan</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp 20.000</span>
            </div>
            <div class="summary-row">
                <span>Ongkos Kirim</span>
                <span>Rp 10.000</span>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span>Rp 30.000</span>
            </div>
            <button class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem;">
                Checkout Sekarang
            </button>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>