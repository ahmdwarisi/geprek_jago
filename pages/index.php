<?php require_once '../config/database.php'; ?>
<?php include '../includes/header.php'; ?>
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-bg">
        <img alt="Hero Geprek" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8N3_WQZYnVKGR6e7gUqHHsi2Tnm4twL1_9EHPKeFemKZ5JDxC7kLnzyVbOw3IhjhBz3EzXQBj_VTooCYX3HKAeFNf1o2KgR0Ttf5ONklmUsOfkVbzsqui40Tjiw2LdB6xs9gNrhtefaVA5uqzjU3rnZL6B50W3_75csGGgat-WqyJynCYNGLn_8hDczAqkAOELEZRcUTrJCQnObyr2onUxPHXipNqpWfe8epbglWsuN7thjW0GDZO2Y20JHezVD7s8eRiZYFku5g">
      </div>
      <div class="container hero-content grid-2 items-center">
        <div>
          <h1>Geprek Jago - <br><span>Pedasnya Juara!</span></h1>
          <p>
            Ayam geprek kualitas juara dengan bumbu rahasia nusantara yang bikin ketagihan. Dibuat dengan ayam pilihan dan sambal yang diulek segar setiap hari.
          </p>
          <div class="hero-actions">
            <button class="btn-secondary">
              Pesan Sekarang
              <span class="material-symbols-outlined">shopping_cart</span>
            </button>
            <button class="btn-outline">
              Lihat Menu
            </button>
          </div>
        </div>
        <div class="hero-img-wrap">
          <img alt="Sajian Ayam Geprek" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCZyLbrNq-tb4fu7IT38x1c6VgRemG283UcRK4oJcVfJ2b05qyNkyp4r_d6SjpYWAzHNgSOLm7zck9kohsBqJf3Jq-yUTEacXKelvEwP7LAEWLqvHJW20VivnRG8Y4kD58NMoRXmPGtR47qZ3fsZirxu6E_7at3WIJBNYXWnHRGb1qFkmA-sy2BsrThrg_TfpxoZYgnXo3jC5PS1NG9ptthDSKPkjS2KNmex9vokwdG0G3ttY8WzaTV6JAahwZj66LYuCDNFPNHHDU">
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
          $query = mysqli_query($conn, "SELECT * FROM menu LIMIT 3");
          if ($query && mysqli_num_rows($query) > 0) {
              while ($row = mysqli_fetch_assoc($query)) {
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
                    <span>4.8</span>
                  </div>
                </div>
                <p class="menu-card-desc"><?= htmlspecialchars($row['deskripsi']) ?></p>
                <div class="menu-card-footer">
                  <span class="menu-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                  <button class="btn-add" <?= ($row['stok'] <= 0) ? 'disabled title="Stok Habis"' : '' ?>>
                    <span class="material-symbols-outlined">add</span>
                  </button>
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
          <!-- Testimonial 1 -->
          <div class="testi-card">
            <div class="testi-stars">
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <p class="testi-text">"Pedasnya beneran nagih! Sambal koreknya fresh banget diulek dadakan. Ayamnya juga juicy dalemnya tapi garing diluar."</p>
            <div class="testi-user">
              <img alt="Siti Aminah" class="testi-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXRBj9qK_iKytsqMqMnLi30Ob6ZCJ2U3XO8UOTznD051D_W1AHJng_ANANqd-BDR44-WJc5lkjliU55_uyVl6hZTq8G9MKoq0fMaL5Y3Q31g73EkaBeNMIO1mutNe54M461rTviUHpLr_arwjWTg4omI_P37_cjkgseTcMH8IuXLs6C6L4vpEWzTZTV_z_0-gTym5K5OQZ4GCO8hYiKc7r-TtGkmppjD4qqbNBTYXiUV2cHAfTXu10rn4itE1BNcL6HDD5TLFBkR4">
              <div>
                <div class="testi-name">Siti Aminah</div>
                <div class="testi-role">Food Vlogger</div>
              </div>
            </div>
          </div>
          <!-- Testimonial 2 -->
          <div class="testi-card">
            <div class="testi-stars">
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <p class="testi-text">"Paling suka Geprek Mozzarella-nya. Keju-nya melimpah dan pas banget dimakan pas masih panas. Pelayanannya cepet banget buat take-away."</p>
            <div class="testi-user">
              <img alt="Budi Santoso" class="testi-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzNkK00nWQJJ0TjXcxg38Sn60t4LtkoY4c7vBCrreLtr9giUnTxynjzZGG7mUWJAVud43rYIFVp8aKN27OgU-97Qk3OV-SNm44BOMQB7XXkyNVB-PGoa8SYc-G32lJFfmRKyfakhl-bZkFHibqLfWvj8JVix5_lHxAQzJlIdNFUGiFmu1_Ohj2cSw0H1eUkS8UxxM1G4dybZrPIaUklj18_LPqB0y6NbYV7oDoRNM3mrQTdEzwapqsPhKcA8NKgAr2gWTHWzftdaY">
              <div>
                <div class="testi-name">Budi Santoso</div>
                <div class="testi-role">Mahasiswa</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php include '../includes/footer.php'; ?>