<?php require_once '../config/database.php'; ?>
<?php include '../includes/header.php'; ?>
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-bg">
        <img alt="Hero Geprek" src="../assets/img/foto-sampul-baru.jpg">
      </div>
      <div class="container hero-content grid-2 items-center">
        <div>
          <h1>Geprek Jago - <br><span>Pedasnya Juara!</span></h1>
          <p>
            Ayam geprek kualitas juara dengan bumbu rahasia nusantara yang bikin ketagihan. Dibuat dengan ayam pilihan dan sambal yang diulek segar setiap hari.
          </p>
          <div class="hero-actions">
            <a href="keranjang.php" class="btn-secondary">
              Pesan Sekarang
              <span class="material-symbols-outlined">shopping_cart</span>
            </a>
            <a href="menu.php" class="btn-outline">
              Lihat Menu
            </a>
          </div>
        </div>
        <div class="hero-img-wrap">
          <img alt="Sajian Ayam Geprek" src="../assets/img/logo.jpg">
        </div>
      </div>
    </section>

    <!-- Best Seller Section -->
    <section class="section section-bg-surface">
      <div class="container">
        <div class="section-header">
          <div>
            <h2>Menu Terlaris</h2>
            <p>Pilihan favorit para Jagoan Kuliner.</p>
          </div>
          <a href="menu.php" class="nav-link" style="color: var(--primary); display: flex; align-items: center;">
            Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>
        <div class="grid-3">
          <?php
          $query = mysqli_query($conn, "SELECT m.*, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id_review) AS review_count FROM menu m LEFT JOIN review r ON r.id_menu = m.id_menu GROUP BY m.id_menu LIMIT 3");
          if ($query && mysqli_num_rows($query) > 0) {
              while ($row = mysqli_fetch_assoc($query)) {
                  $avgRating = number_format((float) $row['avg_rating'], 1);
          ?>
            <div class="menu-card">
              <div class="menu-card-img">
                <img alt="<?= htmlspecialchars($row['nama_menu']) ?>" src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>">
              </div>
              <div class="menu-card-body">
                <div class="menu-card-title-row">
                  <h3><?= htmlspecialchars($row['nama_menu']) ?></h3>
                  <div class="menu-rating">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span><?= $avgRating ?></span>
                  </div>
                </div>
                <p class="menu-card-desc"><?= htmlspecialchars($row['deskripsi']) ?></p>
                <div class="menu-card-footer">
                  <span class="menu-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                  <form action="../process/cart_action.php" method="POST" class="form-add-to-cart">
                      <input type="hidden" name="id_menu" value="<?= $row['id_menu'] ?>">
                      <input type="hidden" name="action" value="add">
                      <button type="submit" class="btn-add" title="Tambah ke Keranjang">
                        <span class="material-symbols-outlined">add</span>
                      </button>
                  </form>
                </div>
              </div>
            </div>
          <?php
              }
          } else {
              echo '<div style="grid-column: 1 / -1; text-align: center;"><p>Belum ada menu terlaris.</p></div>';
          }
          ?>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="section section-bg-primary" id="keunggulan">
      <div class="container">
        <div class="section-header center">
          <h2>Mengapa Geprek Jago?</h2>
          <div class="divider"></div>
        </div>
        <div class="grid-3">
          <div class="feature-card">
            <div class="feature-icon">
              <span class="material-symbols-outlined">payments</span>
            </div>
            <h4>Harga Terjangkau</h4>
            <p>Kenikmatan juara yang ramah di kantong mahasiswa maupun pekerja.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <span class="material-symbols-outlined">bolt</span>
            </div>
            <h4>Pelayanan Cepat</h4>
            <p>Pesanan disiapkan secepat kilat agar perut lapar segera teratasi.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <span class="material-symbols-outlined">eco</span>
            </div>
            <h4>Bahan Higienis</h4>
            <p>Bahan baku segar setiap hari dengan standar kebersihan tertinggi.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="section section-bg-surface" id="testimoni">
      <div class="container">
        <div class="section-header center">
          <h2>Apa Kata Jagoan?</h2>
          <div class="divider"></div>
        </div>
        <div class="testi-wrap">
          <?php
          $query_reviews = mysqli_query($conn, "SELECT * FROM review ORDER BY created_at DESC LIMIT 3");
          if ($query_reviews && mysqli_num_rows($query_reviews) > 0) {
              while ($review = mysqli_fetch_assoc($query_reviews)) {
                  $rating = (int) round($review['rating']);
          ?>
            <div class="testi-card">
              <div class="testi-stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $i <= $rating ? 1 : 0 ?>;">star</span>
                <?php endfor; ?>
              </div>
              <p class="testi-text">"<?= htmlspecialchars($review['komentar'] ?: 'Pelanggan tidak meninggalkan komentar.') ?>"</p>
              <div class="testi-user">
                <div>
                  <div class="testi-name"><?= htmlspecialchars($review['nama'] ?: 'Pelanggan') ?></div>
                  <div class="testi-role">Ulasan Pesanan</div>
                </div>
              </div>
            </div>
          <?php
              }
          } else {
              echo '<div style="grid-column: 1 / -1; text-align: center;"><p style="color: var(--text-muted);">Belum ada rating pelanggan. Ayo jadi yang pertama memberi ulasan!</p></div>';
          }
          ?>
        </div>
        <div style="text-align: center; margin-top: 2.5rem;">
          <a href="semua_review.php" class="btn-primary" style="background-color: white; color: var(--primary); border: 1px solid var(--surface-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            Review Lainnya
          </a>
        </div>
      </div>
    </section>
  </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartForms = document.querySelectorAll('.form-add-to-cart');
        const cartBadge = document.querySelector('.cart-badge');

        cartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const formData = new FormData(this);
                formData.append('ajax', '1');
                
                // Animasi muter pada tombol cart saat proses penambahan
                const btn = this.querySelector('button');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined" style="animation: spin 1s linear infinite;">sync</span>';
                
                fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) 
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.status === 'success') {
                            cartBadge.textContent = data.cart_count;
                            
                            // Animasi tombol berubah menjadi centang hijau sebentar
                            btn.innerHTML = '<span class="material-symbols-outlined">check</span>';
                            btn.style.backgroundColor = '#059669'; 
                            
                            setTimeout(() => {
                                btn.innerHTML = originalContent; 
                                btn.style.backgroundColor = ''; 
                            }, 1000);
                        } else if (data.status === 'error') {
                            alert(data.message);
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