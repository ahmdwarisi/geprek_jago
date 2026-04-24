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
                        <button class="btn-add" <?= ($row['stok'] <= 0) ? 'disabled title="Stok Habis"' : '' ?>>
                            <span class="material-symbols-outlined">add_shopping_cart</span>
                        </button>
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

<?php include '../includes/footer.php'; ?>