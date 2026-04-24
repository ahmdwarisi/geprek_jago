<?php
session_start();
require_once '../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

// Mengambil data statistik menu
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM menu");
$total_menu = $query_total ? mysqli_fetch_assoc($query_total)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Geprek Jago</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Admin -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                Geprek Jago
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Admin Panel</div>
            </div>
            <nav class="admin-nav">
                <a href="dashboard_admin.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="kelola_menu.php" class="admin-nav-link active">
                    <span class="material-symbols-outlined">restaurant_menu</span> Kelola Menu
                </a>
                <a href="kelola_pesanan.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">receipt_long</span> Pesanan
                </a>
                <a href="../process/auth.php?logout=true" class="admin-nav-link" style="margin-top: auto; color: var(--red-badge);">
                    <span class="material-symbols-outlined">logout</span> Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-content">
            <!-- Topbar -->
            <header class="admin-topbar">
                <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">Geprek Jago</h2>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span class="material-symbols-outlined" style="color: var(--text-muted); cursor: pointer;">notifications</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem;">
                        <span class="material-symbols-outlined" style="background: var(--surface-border); padding: 0.5rem; border-radius: 50%; color: var(--primary);">person</span>
                        <?= isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin' ?>
                    </div>
                </div>
            </header>

            <!-- Content Canvas -->
            <main class="admin-main">
                <!-- Notifikasi Status Aksi -->
                <?php if(isset($_GET['msg'])): ?>
                    <?php if($_GET['msg'] == 'success'): ?>
                        <div class="alert alert-success"><span class="material-symbols-outlined">check_circle</span> Menu berhasil ditambahkan!</div>
                    <?php elseif($_GET['msg'] == 'deleted'): ?>
                        <div class="alert alert-success"><span class="material-symbols-outlined">check_circle</span> Menu berhasil dihapus!</div>
                    <?php elseif($_GET['msg'] == 'updated'): ?>
                        <div class="alert alert-success"><span class="material-symbols-outlined">check_circle</span> Menu berhasil diperbarui!</div>
                    <?php elseif($_GET['msg'] == 'error_db' || $_GET['msg'] == 'error_upload'): ?>
                        <div class="alert alert-error"><span class="material-symbols-outlined">error</span> Terjadi kesalahan sistem atau gagal unggah gambar.</div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="admin-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h1>Kelola Menu</h1>
                        <p>Total: <?= $total_menu ?> Item Menu</p>
                    </div>
                    <button class="btn-primary" onclick="openModal('modalAddMenu')">
                        <span class="material-symbols-outlined">add</span> Tambah Menu
                    </button>
                </div>

                <div class="admin-table-card">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($conn, "SELECT * FROM menu ORDER BY id_menu DESC");
                                if ($query && mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)) :
                                        $isAvail = ($row['stok'] > 0);
                                ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="Menu" style="width: 3rem; height: 3rem; border-radius: 0.5rem; object-fit: cover;">
                                            <span style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($row['nama_menu']) ?></span>
                                        </div>
                                    </td>
                                    <td style="color: var(--text-muted);"><?= htmlspecialchars($row['kategori']) ?></td>
                                    <td style="font-weight: 700; color: var(--primary);">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($row['stok']) ?></td>
                                    <td style="text-align: center;">
                                        <span class="badge-warning" style="<?= $isAvail ? 'background: #d1fae5; color: #059669;' : 'background: #fee2e2; color: #dc2626;' ?>">
                                            <?= $isAvail ? 'Tersedia' : 'Habis' ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button onclick="openEditModal(this)" 
                                                data-id="<?= $row['id_menu'] ?>" 
                                                data-nama="<?= htmlspecialchars($row['nama_menu']) ?>" 
                                                data-harga="<?= $row['harga'] ?>" 
                                                data-stok="<?= $row['stok'] ?>" 
                                                data-kategori="<?= htmlspecialchars($row['kategori']) ?>" 
                                                data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>" 
                                                data-gambar="<?= htmlspecialchars($row['gambar']) ?>" 
                                                style="color: #3b82f6; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#eff6ff'" onmouseout="this.style.backgroundColor='transparent'">
                                            <span class="material-symbols-outlined" style="font-size: 1.25rem;">edit</span>
                                        </button>
                                        <a href="../process/menu_action.php?delete_id=<?= $row['id_menu'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" style="color: #ef4444; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s; display: inline-block; vertical-align: middle;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='transparent'">
                                            <span class="material-symbols-outlined" style="font-size: 1.25rem;">delete</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                } else {
                                    echo '<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada data menu.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Tambah Menu -->
    <div id="modalAddMenu" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Menu Baru</h3>
                <button class="btn-close" onclick="closeModal('modalAddMenu')"><span class="material-symbols-outlined">close</span></button>
            </div>
            <!-- Form terhubung ke menu_action.php dengan encrypt type khusus untuk upload file -->
            <form action="../process/menu_action.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-input" placeholder="Cth: Paket Geprek Jumbo" required>
                    </div>
                    <div class="grid-2" style="gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-input" placeholder="25000" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stok" class="form-input" placeholder="50" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-input" required>
                            <option value="Makanan">Makanan Utama</option>
                            <option value="Minuman">Minuman Segar</option>
                            <option value="Cemilan">Cemilan / Ekstra</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Menu</label>
                        <textarea name="deskripsi" rows="3" class="form-input" placeholder="Deskripsikan kelezatan menu ini..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gambar Menu</label>
                        <input type="file" name="gambar" class="form-input" accept="image/*" required style="padding: 0.5rem;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" style="color: var(--text-main); border-color: var(--surface-border);" onclick="closeModal('modalAddMenu')">Batal</button>
                    <button type="submit" name="add_menu" class="btn-primary">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Menu -->
    <div id="modalEditMenu" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Menu</h3>
                <button class="btn-close" onclick="closeModal('modalEditMenu')"><span class="material-symbols-outlined">close</span></button>
            </div>
            <!-- Form terhubung ke menu_action.php dengan encrypt type khusus untuk upload file -->
            <form action="../process/menu_action.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_menu" id="edit_id_menu">
                    <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                    
                    <div class="form-group">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" id="edit_nama_menu" class="form-input" required>
                    </div>
                    <div class="grid-2" style="gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" id="edit_harga" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" id="edit_stok" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" id="edit_kategori" class="form-input" required>
                            <option value="Makanan">Makanan Utama</option>
                            <option value="Minuman">Minuman Segar</option>
                            <option value="Cemilan">Cemilan / Ekstra</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Menu</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="form-input" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ganti Gambar Menu (Opsional)</label>
                        <input type="file" name="gambar" class="form-input" accept="image/*" style="padding: 0.5rem;">
                        <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.5rem; display: block;">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" style="color: var(--text-main); border-color: var(--surface-border);" onclick="closeModal('modalEditMenu')">Batal</button>
                    <button type="submit" name="edit_menu" class="btn-primary">Perbarui Menu</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        function openEditModal(button) {
            document.getElementById('edit_id_menu').value = button.getAttribute('data-id');
            document.getElementById('edit_nama_menu').value = button.getAttribute('data-nama');
            document.getElementById('edit_harga').value = button.getAttribute('data-harga');
            document.getElementById('edit_stok').value = button.getAttribute('data-stok');
            document.getElementById('edit_kategori').value = button.getAttribute('data-kategori');
            document.getElementById('edit_deskripsi').value = button.getAttribute('data-deskripsi');
            document.getElementById('edit_gambar_lama').value = button.getAttribute('data-gambar');
            
            openModal('modalEditMenu');
        }
    </script>
</body>
</html>