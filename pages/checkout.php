<?php 
require_once '../config/database.php';
include '../includes/header.php'; 

// Redirect kembali ke menu jika keranjang kosong
if (empty($_SESSION['cart'])) {
    echo "<script>window.location='menu.php';</script>";
    exit;
}

// Menghitung subtotal dan menyiapkan data keranjang
$subtotal = 0;
$cart_ids = implode(',', array_keys($_SESSION['cart']));
$query = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu IN ($cart_ids)");
$cart_items = [];

while ($row = mysqli_fetch_assoc($query)) {
    $qty = $_SESSION['cart'][$row['id_menu']];
    $total_harga_item = $row['harga'] * $qty;
    $subtotal += $total_harga_item;
    $cart_items[] = ['row' => $row, 'qty' => $qty, 'total' => $total_harga_item];
}

$tarif_per_km = 3000; // Mengatur harga per kilometer
?>

<main class="container section">

    <!-- Loading Overlay (Simulated Integration) -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Memproses Pesanan...</div>
        <div class="loading-subtext">Menghubungkan ke sistem, mohon tunggu sebentar.</div>
    </div>

    <!-- Dynamic Payment Modal (Simulated Integration) -->
    <div id="paymentModal" class="loading-overlay">
        <div class="modal-content" style="max-width: 350px; text-align: center; padding: 2rem;">
            <div id="paymentStatePending">
                <h3 id="paymentTitle" style="font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">Pembayaran</h3>
                <p id="paymentDesc" style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">Selesaikan pembayaran Anda.</p>
                
                <div id="paymentDisplayArea" style="background: white; padding: 1rem; border: 2px dashed var(--surface-border); border-radius: 1rem; margin-bottom: 1.5rem; display: inline-block; min-width: 200px; min-height: 100px; align-content: center;">
                </div>
                
                <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary); margin-bottom: 1.5rem;" id="paymentTotalDisplay">Rp 0</div>
                <button type="button" id="btnCancelPayment" class="btn-outline" style="width: 100%; border-color: var(--red-badge); color: var(--red-badge); padding: 0.75rem;">Batalkan Pembayaran</button>
            </div>
            <div id="paymentStateSuccess" style="display: none; padding: 2rem 0;">
                <div class="stat-icon emerald material-symbols-outlined" style="font-size: 4rem; width: 6rem; height: 6rem; display: inline-flex; margin-bottom: 1rem; border-radius: 50%; justify-content: center; align-items: center;">check_circle</div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #059669;">Pembayaran Berhasil!</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">Meneruskan pesanan Anda ke dapur...</p>
            </div>
        </div>
    </div>

    <form id="checkoutForm" action="../process/checkout_action.php" method="POST" class="checkout-layout">
        <!-- Left Column: Forms -->
        <div class="checkout-forms">
            
            <!-- Delivery Method Selection -->
            <section class="checkout-section">
                <h2 class="checkout-section-title">Metode Makan</h2>
                <div class="radio-group radio-group-2">
                    <label class="radio-card center">
                        <input type="radio" name="order_method" value="dine_in" id="method_dine_in" checked>
                        <div class="radio-content">
                            <span class="material-symbols-outlined">restaurant</span> Makan di Tempat
                        </div>
                    </label>
                    <label class="radio-card center">
                        <input type="radio" name="order_method" value="delivery" id="method_delivery">
                        <div class="radio-content">
                            <span class="material-symbols-outlined">delivery_dining</span> Delivery
                        </div>
                    </label>
                </div>
            </section>

            <!-- Customer Details Form -->
            <section class="checkout-section">
                <h2 class="checkout-section-title">Data Pemesan</h2>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_pelanggan" class="form-input" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="form-group" id="hp_group" style="display: none;">
                    <label class="form-label">Nomor HP</label>
                    <div class="input-group">
                        <span class="input-prefix">+62</span>
                        <input type="tel" name="no_hp" id="input_hp" class="form-input" placeholder="812xxxx">
                    </div>
                </div>

                <!-- Input Jarak Pengiriman (Disembunyikan default) -->
                <div class="form-group" id="area_group" style="display: none;">
                    <label class="form-label">Jarak Pengiriman (KM)</label>
                    <input type="number" name="jarak_km" id="input_jarak" class="form-input" placeholder="Contoh: 3.5" min="0.1" step="0.1">
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; font-style: italic;">* Tarif ongkos kirim: Rp <?= number_format($tarif_per_km, 0, ',', '.') ?> / KM</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Pengiriman / Catatan Meja</label>
                    <textarea class="form-input" name="alamat" rows="3" placeholder="Contoh: Meja 12 (Makan di Tempat) atau Jl. Melati No. 5 (Delivery)" required></textarea>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; font-style: italic;">* Mohon isi nomor meja jika makan di tempat, atau alamat lengkap untuk pengiriman.</p>
                </div>
            </section>

            <!-- Payment Method -->
            <section class="checkout-section">
                <h2 class="checkout-section-title">Metode Pembayaran</h2>
                <div class="radio-group radio-group-2">
                    <label class="radio-card">
                        <input type="radio" name="payment_method" value="qris" checked>
                        <div class="radio-content">
                            <div class="payment-icon qris">
                                <span class="material-symbols-outlined">qr_code_2</span>
                            </div>
                            <span>QRIS</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="payment_method" value="ewallet">
                        <div class="radio-content">
                            <div class="payment-icon ewallet">
                                <span class="material-symbols-outlined">account_balance_wallet</span>
                            </div>
                            <span>E-wallet</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="payment_method" value="bank_transfer">
                        <div class="radio-content">
                            <div class="payment-icon bank">
                                <span class="material-symbols-outlined">account_balance</span>
                            </div>
                            <span>Transfer Bank</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="payment_method" value="cash">
                        <div class="radio-content">
                            <div class="payment-icon cash">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                            <span>Cash</span>
                        </div>
                    </label>
                </div>
            </section>
        </div>

        <!-- Right Column: Order Summary Sidebar -->
        <div>
            <div class="checkout-summary">
                <h2 class="checkout-section-title">Ringkasan Pesanan</h2>
                
                <div style="margin-bottom: 1.5rem;">
                    <!-- Render Items Dinamis -->
                    <?php foreach ($cart_items as $item): ?>
                        <div class="checkout-item">
                            <img src="../assets/img/<?= htmlspecialchars($item['row']['gambar']) ?>" class="checkout-item-img" alt="<?= htmlspecialchars($item['row']['nama_menu']) ?>">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <h3 style="font-weight: 700; font-size: 0.875rem;"><?= htmlspecialchars($item['row']['nama_menu']) ?></h3>
                                    <span style="font-weight: 700; font-size: 0.875rem;">Rp <?= number_format($item['total'], 0, ',', '.') ?></span>
                                </div>
                                <p style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($item['row']['kategori']) ?></p>
                                <p style="font-size: 0.75rem; font-weight: 700; color: var(--primary); margin-top: 0.25rem;">x<?= $item['qty'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="border-top: 1px dashed var(--surface-border); padding-top: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span id="ongkir-display" style="color: #059669; font-weight: 600;">Gratis</span>
                    </div>
                    <div class="summary-total">
                        <span>Total Harga</span>
                        <span id="total-display">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                </div>
                
                <button type="submit" name="checkout" class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem; font-size: 1rem;">
                    <span class="material-symbols-outlined">bolt</span> Pesan Sekarang
                </button>
                
                <p style="text-align: center; font-size: 0.625rem; color: var(--text-muted); margin-top: 1rem;">
                    Dengan menekan tombol di atas, Anda menyetujui <a href="#" style="text-decoration: underline;">Syarat & Ketentuan</a> Geprek Jago.
                </p>
            </div>
        </div>
    </form>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const methodRadios = document.querySelectorAll('input[name="order_method"]');
        const areaGroup = document.getElementById('area_group');
        const inputJarak = document.getElementById('input_jarak');
        const hpGroup = document.getElementById('hp_group');
        const inputHp = document.getElementById('input_hp');
        const ongkirDisplay = document.getElementById('ongkir-display');
        const totalDisplay = document.getElementById('total-display');
        
        // Variabel PHP dikonversi ke JS
        const subtotal = <?= $subtotal ?>;
        const tarifPerKm = <?= $tarif_per_km ?>;

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function calculateTotal() {
            let ongkir = 0;
            const selectedMethod = document.querySelector('input[name="order_method"]:checked').value;
            
            if (selectedMethod === 'delivery') {
                areaGroup.style.display = 'block'; // Tampilkan input jarak
                hpGroup.style.display = 'block'; // Tampilkan input HP
                inputHp.required = true; // Wajib isi HP
                
                const jarak = parseFloat(inputJarak.value) || 0; // Ambil nilai jarak dari input
                ongkir = jarak * tarifPerKm; // Hitung: jarak x tarif per KM
                
                ongkirDisplay.textContent = formatRupiah(ongkir);
                ongkirDisplay.style.color = 'var(--text-main)';
                ongkirDisplay.style.fontWeight = 'normal';
            } else {
                areaGroup.style.display = 'none'; // Sembunyikan input jarak
                hpGroup.style.display = 'none'; // Sembunyikan input HP
                inputHp.required = false; // Tidak wajib isi HP
                
                ongkir = 0;
                ongkirDisplay.textContent = 'Gratis';
                ongkirDisplay.style.color = '#059669';
                ongkirDisplay.style.fontWeight = '600';
            }

            const total = subtotal + ongkir;
            totalDisplay.textContent = formatRupiah(total);
        }

        methodRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
        
        // Update harga secara real-time saat user mengetik jarak
        inputJarak.addEventListener('input', calculateTotal);
        
        calculateTotal(); // Hitung saat halaman pertama kali dimuat

        // Simulated Integration Logic
        const checkoutForm = document.getElementById('checkoutForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        
        // Payment Modal Elements
        const paymentModal = document.getElementById('paymentModal');
        const btnCancelPayment = document.getElementById('btnCancelPayment');
        const paymentStatePending = document.getElementById('paymentStatePending');
        const paymentStateSuccess = document.getElementById('paymentStateSuccess');
        const paymentTotalDisplay = document.getElementById('paymentTotalDisplay');
        const paymentTitle = document.getElementById('paymentTitle');
        const paymentDesc = document.getElementById('paymentDesc');
        const paymentDisplayArea = document.getElementById('paymentDisplayArea');
        let paymentTimer;
        
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Tahan pengiriman form sementara
            
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

            // Tampilkan Modal Pembayaran untuk selain Cash
            if (selectedPayment !== 'cash') {
                paymentTotalDisplay.textContent = totalDisplay.textContent; // Salin total harga
                
                // Konfigurasi konten berdasarkan metode
                if (selectedPayment === 'qris') {
                    paymentTitle.textContent = 'Pembayaran QRIS';
                    paymentDesc.textContent = 'Scan barcode di bawah ini menggunakan aplikasi E-Wallet atau M-Banking Anda.';
                    paymentDisplayArea.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=GeprekJagoPaymentSimulation" alt="QR Code QRIS" style="width: 200px; height: 200px; object-fit: contain; margin: 0 auto;">';
                } else if (selectedPayment === 'ewallet') {
                    paymentTitle.textContent = 'Pembayaran E-Wallet';
                    paymentDesc.textContent = 'Silakan transfer ke nomor GoPay / OVO / DANA berikut:';
                    paymentDisplayArea.innerHTML = '<div style="font-size: 1.5rem; font-weight: 900; letter-spacing: 2px; color: var(--text-main); padding: 0.5rem 0;">0812-3456-7890</div><p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">A.N. GEPREK JAGO</p>';
                } else if (selectedPayment === 'bank_transfer') {
                    paymentTitle.textContent = 'Transfer Bank (VA)';
                    paymentDesc.textContent = 'Transfer sesuai nominal ke nomor Virtual Account (VA) berikut:';
                    paymentDisplayArea.innerHTML = '<div style="font-size: 1.5rem; font-weight: 900; letter-spacing: 2px; color: var(--text-main); padding: 0.5rem 0;">8899 0812 3456</div><p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">BCA / Mandiri / BRI</p>';
                }

                paymentStatePending.style.display = 'block';
                paymentStateSuccess.style.display = 'none';
                paymentModal.classList.add('active');

                // Simulasi menunggu pengguna bayar (5 detik)
                paymentTimer = setTimeout(() => {
                    paymentStatePending.style.display = 'none';
                    paymentStateSuccess.style.display = 'block'; // Tampilkan layar sukses
                    
                    // Jeda 1.5 detik untuk membaca pesan sukses, lalu submit form
                    setTimeout(() => { this.submit(); }, 1500);
                }, 5000);
            } else {
                // Loading biasa jika menggunakan metode Cash
                loadingOverlay.classList.add('active'); 
                setTimeout(() => { this.submit(); }, 2500);
            }
        });

        // Aksi jika tombol "Batalkan" ditekan
        btnCancelPayment.addEventListener('click', function() {
            clearTimeout(paymentTimer); // Hentikan hitung mundur sukses otomatis
            paymentModal.classList.remove('active'); // Tutup modal
        });
    });
</script>

<?php include '../includes/footer.php'; ?>