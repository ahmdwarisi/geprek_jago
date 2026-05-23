<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="section section-bg-surface" style="min-height: calc(100vh - 200px);">
    <div class="container mx-auto px-3 sm:px-4">
        <div class="section-header center">
            <h2>Semua Ulasan Jagoan</h2>
            <p>Apa kata mereka yang sudah mencoba kelezatan Geprek Jago?</p>
            <div class="divider"></div>
        </div>
        
        <div class="testi-wrap" style="gap: 2rem;">
          <?php
          $query_reviews = mysqli_query($conn, "SELECT * FROM review ORDER BY created_at DESC");
          if ($query_reviews && mysqli_num_rows($query_reviews) > 0) {
              while ($review = mysqli_fetch_assoc($query_reviews)) {
                  $rating = (int) round($review['rating']);
          ?>
            <div class="testi-card" style="width: 100%; max-width: 400px;">
              <div class="testi-stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $i <= $rating ? 1 : 0 ?>;">star</span>
                <?php endfor; ?>
              </div>
              <p class="testi-text">"<?= htmlspecialchars($review['komentar'] ?: 'Pelanggan tidak meninggalkan komentar.') ?>"</p>
              <div class="testi-user">
                <div>
                  <div class="testi-name"><?= htmlspecialchars($review['nama'] ?: 'Pelanggan') ?></div>
                  <div class="testi-role"><?= date('d M Y', strtotime($review['created_at'] ?? 'now')) ?></div>
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
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="index.php" class="btn-outline" style="color: var(--primary); border-color: var(--primary);">Kembali ke Beranda</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>