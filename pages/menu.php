<?php require_once '../config/database.php'; ?>
<?php include '../includes/header.php'; ?> 

<main class="container section">
    <div class="section-header" style="align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h2>Menu Pilihan Juara</h2>
            <p>Pilih tingkat pedasmu dan nikmati kelezatan ayam geprek autentik kami.</p>
        </div>
        <div style="position: relative; width: 100%; max-width: 300px;">
            <span class="material-symbols-outlined" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">search</span>
            <input type="text" placeholder="Cari menu..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 1px solid var(--surface-border); border-radius: 9999px; background-color: var(--surface); outline: none; font-family: inherit;">
        </div>
    </div>

    <div class="grid-4">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM menu");
        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
        ?>
            <div class="menu-card">
                <div class="menu-card-img" style="position: relative;">
                    <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_menu']) ?>">
                    <?php if($row['stok'] <= 0): ?>
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center;">
                            <span style="background: var(--red-badge); color: white; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Habis</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="menu-card-body">
                    <div class="menu-card-title-row">
                        <h3><?= htmlspecialchars($row['nama_menu']) ?></h3>
                    </div>
                    <p class="menu-card-desc"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    
                    <div class="menu-card-footer">
                        <span class="menu-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                        <form action="../process/cart_action.php" method="POST" class="form-add-to-cart" style="margin: 0;">
                            <input type="hidden" name="id_menu" value="<?= $row['id_menu'] ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn-add" title="Tambah ke Keranjang">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo '<div style="grid-column: 1 / -1; text-align: center;"><p>Belum ada menu yang tersedia.</p></div>';
        }
        ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartForms = document.querySelectorAll('.form-add-to-cart');
        const cartBadge = document.querySelector('.cart-badge');

        cartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Tahan pengiriman form (Mencegah reload halaman)
                
                const formData = new FormData(this);
                formData.append('ajax', '1'); // Penanda bahwa ini adalah request AJAX
                
                // Animasi muter pada tombol cart saat proses penambahan
                const btn = this.querySelector('button');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined" style="animation: spin 1s linear infinite;">sync</span>';
                
                fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // Baca sebagai teks dulu agar tidak langsung crash
                .then(text => {
                    try {
                        const data = JSON.parse(text); // Coba terjemahkan teks menjadi JSON
                        if (data.status === 'success') {
                            cartBadge.textContent = data.cart_count; // Update notifikasi angka keranjang di Navbar
                            
                            // Animasi tombol berubah menjadi centang hijau sebentar
                            btn.innerHTML = '<span class="material-symbols-outlined">check</span>';
                            btn.style.backgroundColor = '#059669'; 
                            
                            setTimeout(() => {
                                btn.innerHTML = originalContent; // Kembalikan tombol ke semula
                                btn.style.backgroundColor = ''; 
                            }, 1000);
                        } else if (data.status === 'error') {
                            alert(data.message); // Tampilkan pesan stok habis
                            btn.innerHTML = originalContent;
                            btn.style.backgroundColor = ''; 
                        }
                    } catch (e) {
                        // Jika gagal diterjemahkan (ada error PHP), lempar error ke catch
                        console.error('Balasan server bermasalah:', text);
                        throw new Error('Format balasan server tidak valid (Bukan JSON).');
                    }
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    btn.innerHTML = originalContent; // Hentikan muter dan kembalikan tombol jika error
                    alert('Gagal menambahkan ke keranjang. Terjadi kesalahan pada server.');
                });
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>