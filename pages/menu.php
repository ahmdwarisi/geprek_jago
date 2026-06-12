<?php require_once '../config/database.php'; ?>
<?php include '../includes/header.php'; ?> 

<main class="container mx-auto px-3 sm:px-4 section">
    <div class="section-header" style="align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h2>Menu Pilihan Juara</h2>
            <p>Pilih tingkat pedasmu dan nikmati kelezatan ayam geprek autentik kami.</p>
        </div>
        <div style="position: relative; width: 100%; max-width: 300px;">
            <span class="material-symbols-outlined" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">search</span>
            <input type="text" id="searchInput" placeholder="Cari menu..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 1px solid var(--surface-border); border-radius: 9999px; background-color: var(--surface); outline: none; font-family: inherit;">
        </div>
    </div>

    <?php
    $kategori_list = ['Paket Super Jago', 'Paket Hemat Jago', 'Paket Mie Jago', 'Ala Carte'];
    $menu_ada = false;

    foreach ($kategori_list as $kategori) {
        $query = mysqli_query($conn, "SELECT m.*, COALESCE(AVG(r.rating), 0) AS avg_rating FROM menu m LEFT JOIN review r ON r.id_menu = m.id_menu WHERE m.kategori = '$kategori' GROUP BY m.id_menu");
        
        if(mysqli_num_rows($query) > 0) {
            $menu_ada = true;
            ?>
            <div class="menu-category-wrapper">
            <div style="margin-top: 2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin: 0;"><?= $kategori ?></h3>
                <div style="flex: 1; height: 2px; background: var(--surface-border); border-radius: 999px;"></div>
            </div>
            <div class="grid-4">
            <?php
            while($row = mysqli_fetch_assoc($query)) {
                $avgRating = number_format((float) $row['avg_rating'], 1);
            ?>
            <div class="menu-card">
                <a href="detail_menu.php?id=<?= $row['id_menu'] ?>" style="display: block; position: relative;">
                    <div class="menu-card-img">
                        <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_menu']) ?>">
                    </div>
                    <?php if($row['stok'] <= 0): ?>
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 10;">
                            <span style="background: var(--red-badge); color: white; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Habis</span>
                        </div>
                    <?php endif; ?>
                </a>
                
                <div class="menu-card-body">
                    <div class="menu-card-title-row">
                        <a href="detail_menu.php?id=<?= $row['id_menu'] ?>" style="text-decoration: none; color: inherit;">
                            <h3 style="transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'"><?= htmlspecialchars($row['nama_menu']) ?></h3>
                        </a>
                        <?php if ($avgRating > 0): ?>
                        <div class="menu-rating">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span><?= $avgRating ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="menu-card-desc"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    
                    <div class="menu-card-footer">
                        <span class="menu-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                        <form action="../process/cart_action.php" method="POST" class="form-add-to-cart" style="margin: 0;">
                            <input type="hidden" name="id_menu" value="<?= $row['id_menu'] ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn-add" title="Tambah ke Keranjang" <?= $row['stok'] <= 0 ? 'disabled' : '' ?>>
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
            }
            ?>
            </div>
            </div>
            <?php
        }
    }
    
    if (!$menu_ada) { echo '<div style="text-align: center; padding: 3rem 0;"><p>Belum ada menu yang tersedia.</p></div>'; }
    ?>
    <div id="noResultMsg" style="display: none; text-align: center; padding: 3rem 0; color: var(--text-muted);"><p>Pencarian menu tidak ditemukan.</p></div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const categoryWrappers = document.querySelectorAll('.menu-category-wrapper');
        const noResultMsg = document.getElementById('noResultMsg');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let totalVisible = 0;

                categoryWrappers.forEach(wrapper => {
                    const menuCards = wrapper.querySelectorAll('.menu-card');
                    let hasVisibleCard = false;

                    menuCards.forEach(card => {
                        const menuName = card.querySelector('h3').textContent.toLowerCase();
                        const menuDesc = card.querySelector('.menu-card-desc').textContent.toLowerCase();

                        if (menuName.includes(searchTerm) || menuDesc.includes(searchTerm)) { card.style.display = 'block'; hasVisibleCard = true; } 
                        else { card.style.display = 'none'; }
                    });

                    wrapper.style.display = hasVisibleCard ? 'block' : 'none';
                    if (hasVisibleCard) totalVisible++;
                });
                
                noResultMsg.style.display = totalVisible === 0 && searchTerm !== '' ? 'block' : 'none';
            });
        }

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